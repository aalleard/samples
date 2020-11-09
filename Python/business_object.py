from datetime import datetime, timedelta

import django.apps
from django.conf    			import settings
from django.contrib.auth.models import User
from django.db 					import models
from django.db.models 			import DEFERRED, Q
from django.http 				import Http404

from simple_history.models import HistoricalRecords

from authorization.submodels.authorization						import Authorization
from authorization.submodels.object_authorization				import ObjectAuthorization
from authorization.tasks										import background_authorizations
from core.classes.binary_search									import BinarySearch
from core.classes.conversions									import Conversions
from core.classes.factories.business_object_activity_status		import BusinessObjectActivityStatusFactory
from core.classes.factories.business_object_authorized_activity	import BusinessObjectAuthorizedActivityFactory
from core.classes.factories.business_object_authorized_status	import BusinessObjectAuthorizedStatusFactory
from core.classes.factories.business_object_status				import BusinessObjectStatusFactory
from core.classes.frontend_message 								import FrontendMessage
from core.classes.logger 										import Logger
from core.exceptions.business_object							import *
from core.exceptions.business_object_status						import *
from core.submodels.activity									import Activity
from core.submodels.field_visibility							import FieldVisibility
from core.submodels.status_setup								import StatusSetup
from user.submodels.user_authorization							import UserAuthorization


class BusinessObject(models.Model):
	# Exceptions
	AlreadyExists = BusinessObjectAlreadyExists
	DataInconsistency = BusinessObjectDataInconsistency
	InvalidActivity = BusinessObjectInvalidActivity
	InvalidData = BusinessObjectInvalidData
	InvalidUser = BusinessObjectInvalidUser

	# Common fields
	creation_date = models.DateTimeField(auto_now_add=True, editable=False, help_text='Creation date')
	creation_user = models.ForeignKey(User, models.SET_NULL, blank=True, null=True, related_name="created_%(app_label)s_%(class)s", help_text='Creation user')
	deleted		  = models.BooleanField(default=False, help_text="Deletion flag")
	deletion_date = models.DateTimeField(null=True, blank=True, help_text="Deletion date")
	deletion_user = models.ForeignKey(User, models.SET_NULL, null=True, blank=True, related_name="deleted_%(app_label)s_%(class)s",help_text='Deletion user')
	update_date	  = models.DateTimeField(auto_now=True, null=True, blank=True, help_text='Update date')
	update_user	  = models.ForeignKey(User, models.SET_NULL, blank=True, null=True, related_name="updated_%(app_label)s_%(class)s",help_text='Update user')
	
	# To store modifications
	history = HistoricalRecords(inherit=True, excluded_fields=['update_date', 'update_user'], cascade_delete_history=True)
	
	# Default values
	business_id_prefix	= None		
	message_domain 		= "object"	# Default message domain
	
	_old			= None			# Previous object's version
	_activity		= None			# Current business activity
	_api 			= False			# Process comes from API?
	_checked		= False			# Business data is checked?
	errors 			= {}			# Object's indexed errors
	_invalid 		= False			# Validity of object after data check
	_request_user 	= None			# User making the request
	_test 			= False			# Test mode? (no DB access)
	t_messages		= []			# Messages list
	
	class Meta:
		abstract = True
		ordering = ['id']

	def after_create(self):
		"""
		Method called after the object has been created in the database

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_create - '{}'".format(self))
		# Log action
		Logger.Success(self.message_domain, 202, self)

	def after_delete(self):
		"""
		Method called after the object has been deleted logically, but not from DB

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_delete - '{}'".format(self))
		# Log action
		Logger.Success(self.message_domain, 222, self)

	def after_hard_delete(self):
		"""
		Method called after the object has been deleted physicaly from the database

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_hard_delete - '{}'".format(self))
		# Log action
		Logger.Success(self.message_domain, 222, self)

	def after_read(self):
		"""
		Method called after the object has been read with an API call
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_read - '{}'".format(self))
		self._manage_status()

	def after_save(self):
		"""
		Method called after the object has been saved in the database

		It routes to the right method with business logic depending on
		activity
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_save - '{}'".format(self))
		if self.activity.code == Activity.CREATE:
			self.after_create()
		elif self.activity.code == Activity.DELETE:
			self.after_delete()
		elif self.activity.code == Activity.HARD_DELETE:
			self.after_hard_delete()
			return
		else:
			self.after_update()

		# Finally:
		# 	- manage object status (except if object deleted from DB)
		#	- calculate authorizations for the object
		self._manage_status()
		self.define_authorizations()

	def after_update(self):
		"""
		Method called after the object has been updated in the database

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.after_update - '{}'".format(self))
		# Log action
		Logger.Success(self.message_domain, 212, self)
		# Message for user 
		self.t_messages.append(FrontendMessage.get(self.message_domain, 301))

	def authorizations_check(self, io_user=None, io_activity=None):
		"""
		Generic method to check authorizations on an object
		1 - It will check that requested activity is authorized by the customizing
		2 - If so, it will check that user performing the request is authorized to perform the activity

		Parameters allow to deactivate the checks, must have a good reason to do so
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.authorizations_check - '{}'".format(self))
		
		if io_user is None:
			lo_user = self.request_user
		else:
			lo_user = io_user

		if io_activity is None:
			lo_activity = self.activity
		else:
			lo_activity = io_activity

		# Check authorization for activity
		BusinessObjectAuthorizedActivityFactory.get_class(self).authorization_check(lo_activity, self)

		# Check authorization for user
		Authorization.authorization_check(lo_user, lo_activity, self)
		
	def before_create(self):
		"""
		Method called before object creation in the database

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()

		Use this method to perform needed database access before object creation
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.before_create - '{}'".format(self))
		# Log action
		Logger.Info(self.message_domain, 201, self.request_user)
		# Default logic
		self.creation_user = self.request_user

	def before_delete(self):
		"""
		Method to handle deletion attributes by default, if they exist

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.before_delete - '{}'".format(self))
		# Log action
		Logger.Info(self.message_domain, 221, self.request_user, self)
		# Default logic
		self.deleted = True
		self.deletion_date = datetime.now()
		self.deletion_user = self.request_user

	def before_hard_delete(self):
		"""
		Method to handle hard deletion by default

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.before_hard_delete - '{}'".format(self))
		# Log action
		Logger.Info(self.message_domain, 221, self.request_user, self)
		# Default logic, remove all authorization entries
		self.delete_authorizations()

	def before_save(self):
		"""
		Method called before the database update is performed

		It routes to the right method with business logic depending on
		the creation and deletion flags
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.before_save - '{}'".format(self))

		# If no ID provided on object and no activity, consider we are in creation mode
		if self.id is None and self.activity is None:
			self.activity = Activity.CREATE
		# If ID provided but no activity, then consider this is a simple change operation
		elif self.id is not None and self.activity is None:
			self.activity = Activity.CHANGE
		# If ID provided and activity is CREATE, then change it with CHANGE
		elif self.id is not None and self.activity.code == Activity.CREATE:
			self.activity = Activity.CHANGE

		# Detect activity and perform relevant treatments
		if self.activity.code == Activity.CREATE:
			self.before_create()
		elif self.activity.code == Activity.DELETE:
			self.before_delete()
		elif self.activity.code == Activity.HARD_DELETE:
			self.before_hard_delete()
		else:
			self.before_update()

	def before_update(self):
		"""
		Method to handle update attributes by default, if they exist

		This method must be overridden to implement custom business logic,
		but it is advised to keep calling it using super()
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.before_update - '{}'".format(self))
		
		# Log action
		Logger.Info(self.message_domain, 211, self.request_user, self)
		# Default logic
		self.update_user = self.request_user

	def build_client_url(self):
		"""
		Method to build an URL for a client application
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.build_client_url - '{}'".format(self))
		ev_url = settings.CLIENT_APP_URLS.get(settings.SERVER_ENVIRONMENT) + self.service_url + "/" + str(self.id) + "/"
		return ev_url

	@property
	def business_id(self):
		"""	
		Define a human readable business id for the instance
		"""
		if self.business_id_prefix is not None:
			lv_length = 10 - len(self.business_id_prefix)
			return self.business_id_prefix + Conversions.fill_leading_zeros(self.id, lv_length)
		else:
			return None

	def check_data(self):
		"""
		Default method to implement data consistency check on an object

		This method must be overridden to implement custom business logic and it is strongly
		advised to keep calling it from children classes
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.check_data - '{}'".format(self))
		# Log action
		Logger.Debug("core", 4, self) # Checking data consistency

		# Init errors dictionary
		self.errors = {}
		self.invalid = False

	def _clear_nested_list(self, lv_name):
		"""
		Clear a nested list of the object

		This list must be called "t_<lv_name>"
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject._clear_nested_list - '{}'".format(self))
		
		lv_attribute = "t_" + lv_name
		lt_list = getattr(self, lv_attribute)
		lt_list.clear()

	def complete_data(self):
		"""
		Define default values for the object depending on context

		This method must be overridden to implement custom business logic and it is strongly
		advised to keep calling it from children classes
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.complete_data - '{}'".format(self))

		# If no ID provided on object and no activity, consider we are in creation mode
		if self.id is None and self.activity is None:
			self.activity = Activity.CREATE
		# If ID provided but no activity, then consider this is a simple change operation
		elif self.id is not None and self.activity is None:
			self.activity = Activity.CHANGE

	def _copy_from(self, io_object):
		"""	
		Method to copy all values from an object into another object of the same class
		"""
		lo_request_user = self.request_user
		for lv_key in io_object.__dict__.keys():
			setattr(self, lv_key, getattr(io_object, lv_key))
		self.request_user = lo_request_user

	@classmethod
	def define_all_authorizations(cls, iv_model=None):
		"""
		Define, for all objects of all BusinessObjects models, the authorizations
		"""
		lt_models = django.apps.apps.get_models()
		for lcl_model in lt_models:
			# Check with required model
			if iv_model is not None and lcl_model.__name__ != iv_model:
				continue

			# Check that model is a BusinessObject
			if issubclass(lcl_model, cls) and lcl_model.__name__ != "LegalEntity" and lcl_model.__name__ != "Material":
				Logger.Info("debug", 1, "Processing Model {}".format(lcl_model.__name__))
				Authorization.define_authorizations(lcl_model)

	def define_authorizations(self, ib_synchronous=False):
		"""
		Define, for this object, all authorized activities for owner entity persons
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.define_authorizations - '{}'".format(self))

		Authorization.define_authorizations(self.__class__, ib_synchronous=ib_synchronous, iv_pk=self.id)

	def define_status(self, i_status, ib_inactive=False, i_user=None):
		"""
		Generic method to apply a status

		Child class must have defined status management model and object attribute name on this model
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.define_status - '{}' - '{}'".format(self, i_status))

		# Get an instance of the model storing business object status
		lo_status = BusinessObjectStatusFactory.get(self)

		# This method is called only for objects having status management
		assert lo_status is not None

		# Set status
		lo_status.set_status(i_status)
		# Inactive indicator
		lo_status.set_inactive(ib_inactive)
		# If user is not provided, use request user
		if i_user is None:
			lo_status.set_user(self.request_user)
		else:
			lo_status.set_user(i_user)
		# Save the status
		Logger.Debug("debug", 1, "Set status '{}' for {} '{}', user '{}'".format(i_status, self.__class__.__name__, self, lo_status.user))
		try:
			lo_status.save()
		except BusinessObjectStatusInvalidUser as io_exception:
			Logger.Error(
				io_exception.message_domain, 
				io_exception.message_number,
				i_user
			)
		except BusinessObjectStatusException as io_exception:
			Logger.Error(
				io_exception.message_domain, 
				io_exception.message_number,
				i_status
			)

	def delete_authorizations(self):
		"""
		Delete, for this object, all authorizations
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.delete_authorizations - '{}'".format(self))

		# Get authorizations for this object
		ObjectAuthorization.objects.filter(
			model=self.__class__.__name__,
			object_id=self.id
		).delete()

	@classmethod
	def from_db(cls, db, field_names, values):
		# Override default method
		# https://docs.djangoproject.com/fr/3.0/ref/models/instances/#customizing-model-loading
		if len(values) != len(cls._meta.concrete_fields):
			values = list(values)
			values.reverse()
			values = [
				values.pop() if f.attname in field_names else DEFERRED
				for f in cls._meta.concrete_fields
			]
		# START - Change to use custom initialization
		instance = cls.instantiate(*values)
		instance.old = cls.instantiate(*values)
		# END - Change to use custom initialization
		instance._state.adding = False
		instance._state.db = db
		return instance

	@classmethod
	def get(cls, pk):
		"""
		Class method to query a single object entity,
		and return an instance if it exists, or a 404 error if not
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.get - '{}': {}".format(cls.__name__, pk))
		try:
			return cls.objects.get(pk=pk)
		except cls.DoesNotExist:
			raise Http404()

	def _get_authorizations_eligible_persons(self):
		"""
		Get a list of persons who could have an access to the object

		By default this method will return Persons assigned to the owner LegalEntity.

		Create a new implementation in sub-classes to handle specific needs
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject._get_authorizations_eligible_persons - '{}'".format(self))

		if self.owner is None:
			# Do not process instance with no owner
			Logger.Critical("debug", 1, "Instance {} of model {} has no owner".format(self, self.__class__.__name__))
			return self.__class__.objects.none()
		else:
			return self.owner.persons.all()

	def get_authorized_activities(self, io_user=None):
		"""
		Get list of user's authorized activities for the object

		The method will fecth all authorized activities for the object type form the customizing,
		then it will check each one of them
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.get_authorized_activities - '{}'".format(self))
		et_activities = []
		
		# Load activities authorized for object
		BusinessObjectAuthorizedActivityFactory.get_class(self).load_customizing()
		for lo_activity in BusinessObjectAuthorizedActivityFactory.get_class(self).gt_buffer:
			try:
				self.authorizations_check(io_user=io_user, io_activity=lo_activity.activity)
				# We reach this point if activity is authorized
				# Insert activity at correct index in activities list
				ld_result = BinarySearch.find(et_activities, lo_activity.activity, "code")
				if not ld_result.get("found"):
					et_activities.insert(ld_result.get("index"), lo_activity.activity)
			except:
				# No error processing here, as the result is given as an information
				pass

		return et_activities

	@classmethod
	def get_list(cls, io_user, io_filters=None, iv_activity=Activity.READ):
		"""
		Get the list of objects corresponding to given filters

		The system will first filter on authorized objects for the READ activity
		"""

		# Init queryset	
		et_queryset = cls.objects.all()
		
		# Unless user is batch user...
		if io_user.username != "batch":
			# ...filter queryset with user's authorized objects
			# 1. Get user authorizations for the required activity
			lt_authorizations = list(
				UserAuthorization.objects.all().filter(
					user=io_user,
					authorization__activities__code=iv_activity
				).values_list("authorization_id", "legal_entity_id").distinct("authorization_id", "legal_entity_id")
			)
			if len(lt_authorizations) == 0:
				# User has no authorization at all, return empty queryset
				return cls.objects.none()
			# 2. Build a filter with user, and its authorizations
			lo_filter = Q(
				Q(activity__code=iv_activity) &
				Q(model=cls.__name__) &
				Q(
					Q(user=io_user) |
					Q(user=None)
				)
			)
			lo_ids_filter = None
			for ltu_ids in lt_authorizations:
				if lo_ids_filter is None:
					lo_ids_filter = Q(authorization_id=ltu_ids[0], legal_entity_id=ltu_ids[1])
				else:
					lo_ids_filter = lo_ids_filter | Q(authorization_id=ltu_ids[0], legal_entity_id=ltu_ids[1])
			if lo_ids_filter is not None:
				lo_filter = lo_filter & lo_ids_filter
			# 3. Get the list of authorized objects
			lt_object_ids = list(
				ObjectAuthorization.objects.all().filter(lo_filter).values_list("object_id", flat=True).distinct("object_id")
			)
			et_queryset = et_queryset.filter(pk__in=lt_object_ids)
		
		# Use other filters
		if io_filters is not None:
			et_queryset = et_queryset.filter(cls.transform_filters(io_filters, io_user))

		return et_queryset

	def has_status(self, iv_status, io_user=None):
		"""
		Check if object has a status

		It will check if the status exists and is active

		It will also check for user dependant status that the status corresponds to the requested user,
		or to the request user if none provided
		"""

		# Check that method is authorized for object
		try:
			self._meta.get_field("status")
		except:
			Logger.Critical("status", 6, self.__class__.__name__)
			# This is programming error as this method must me called only for objects
			# with status management
			assert 1 == 0

		try:
			lcl_authorized_status_model = BusinessObjectAuthorizedStatusFactory.get_class(self)
			lcl_status_model = BusinessObjectStatusFactory.get_class(self)
			lo_status = lcl_authorized_status_model.objects.get(status__code=iv_status)
			if lo_status.user_related:
				if io_user is not None:
					lo_user = io_user
				else:
					lo_user = self.request_user
				Logger.Debug("debug", 1, "Check if {} '{}' has status '{}' for user '{}'".format(self.__class__.__name__, self, iv_status, lo_user))
				lcl_status_model.objects.get(object=self, status__code=iv_status, inactive=False, user=lo_user)
			else:
				Logger.Debug("debug", 1, "Check if {} '{}' has status '{}'".format(self.__class__.__name__, self, iv_status))
				lcl_status_model.objects.get(object=self, status__code=iv_status, inactive=False)
			Logger.DebugSuccess("debug", 1, "Status is active")
			return True
		except:
			Logger.DebugInfo("debug", 1, "Status is not active")
			return False

	@classmethod
	def instantiate(cls, *args, **kwargs):
		"""
		Redefinition of Django's method, to handle non persitant fields
		"""
		eo_instance = cls(*args, **kwargs)
		eo_instance.old 			= None
		eo_instance.activity 		= None
		eo_instance.api 			= False
		eo_instance.checked 		= False
		eo_instance.errors 			= {}
		eo_instance.invalid 		= False
		eo_instance.request_user 	= None
		eo_instance.test 			= False
		eo_instance.t_messages 		= []

		# Default activity
		# If no ID provided on object and no activity, consider we are in creation mode
		if eo_instance.id is None and eo_instance.activity is None:
			eo_instance.activity = Activity.CREATE
		# If ID provided but no activity, then consider this is a simple change operation
		elif eo_instance.id is not None and eo_instance.activity is None:
			eo_instance.activity = Activity.CHANGE

		return eo_instance

	def is_hidden(self, iv_field):
		"""
		Method to detect if a given field is hidden (ie not relevant), depending on type associated to the business object
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.is_hidden - '{}' - '{}'".format(self, iv_field))
		Logger.Debug("debug", 1, "Checking if '{}' is hidden for type '{}'".format(iv_field, self.type))
		Logger.Debug("debug", 1, "Customizing value: {}".format(getattr(self.type, iv_field).code))

		if self.type is not None and getattr(self.type, iv_field).code == FieldVisibility.HIDDEN:
			return True
		else:
			return False

	def is_required(self, iv_field):
		"""
		Method to detect if a given field is required, depending on type associated to the business object
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.is_required - '{}' - '{}'".format(self, iv_field))
		Logger.Debug("debug", 1, "Checking if '{}' is required for type '{}'".format(iv_field, self.type))

		if self.type is None:
			return False
			
		Logger.Debug("debug", 1, "Customizing value: {}".format(getattr(self.type, iv_field).code))
		if self.type is not None and getattr(self.type, iv_field).code == FieldVisibility.REQUIRED:
			return True
		else:
			return False

	def _manage_status(self):
		"""
		Method to manage object status once it has been saved
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject._manage_status - '{}'".format(self))
		# Check that status management is implemented for this object type
		try:
			self._meta.get_field("status")
		except:
			# No status management, so this is OK
			return

		# Load customizing
		lcl_activity_status_model = BusinessObjectActivityStatusFactory.get_class(self)
		lt_status_qs = lcl_activity_status_model.objects.filter(activity__activity=self.activity)
		for lo_status in lt_status_qs:
			lb_inactive = False
			# Check if there is an action to perform for this status
			if lo_status.setup.code == StatusSetup.NO_ACTION:
				continue
			# Check if this action is set or delete
			elif lo_status.setup.code == StatusSetup.DELETE:
				lb_inactive = True
			# Apply status
			self.define_status(lo_status.status.status, lb_inactive)

	def map_attribute(self, io_data, iv_name):
		"""
		Map incomming data from request dictionnary. Key must be provided
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_attribute - '{}'".format(self))
		# Dynamically define the setter associated
		lv_method = "set_" + iv_name
		lf_method = getattr(self, lv_method, None)
		if iv_name in io_data:
			Logger.Verbose("debug", 1, "Mapping value '{}' to attribute '{}'".format(io_data[iv_name], iv_name))
			if lf_method:
				# Explicit setters
				lf_method(io_data[iv_name])
			else:
				# Decorated setters
				setattr(self, iv_name, io_data[iv_name])

	def map_foreign_key(self, io_data, iv_name, icl_model):
		"""
		Method to check if a related object exists in the request payload

		If it exists it will get its instance from the database

		Use this method if it is not intended to modify nested object.

		Otherwise, use method 'map_nested_object'
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_foreign_key - '{}'".format(self))
		# Dynamically define the setter associated
		lv_method = "set_" + iv_name
		lf_method = getattr(self, lv_method)
		# Check that an object was given for the foreign key, and that this object has an ID
		if iv_name in io_data and io_data[iv_name] is not None and 'id' in io_data[iv_name]:
			# Call dynamically setter method to assign the object
			lo_object = icl_model.objects.get(pk=io_data[iv_name]['id']) 
			Logger.Verbose("debug", 1, "Mapping object '{}' to attribute '{}'".format(lo_object, iv_name))
			lf_method(lo_object)
		# Same check, but with a code, for custo objects
		elif iv_name in io_data and io_data[iv_name] is not None and 'code' in io_data[iv_name]:
			# Call dynamically setter method to assign the object
			lo_object = icl_model.objects.get(code=io_data[iv_name]['code']) 
			Logger.Verbose("debug", 1, "Mapping object '{}' to attribute '{}'".format(lo_object, iv_name))
			lf_method(lo_object)
		# If the object is given but null
		elif iv_name in io_data and (io_data[iv_name] is None or 'id' not in io_data[iv_name]):
			lf_method(None)

	def map_foreign_keys(self, io_data, iv_name, icl_model):
		"""
		Method to map a collection of nested objects.

		Use this method to map objects that can not be modified.

		To map instances that can be modified by API call,
		use method map_nested_list.

		Instance class must have an attribute "t_<name>" and
		instance class must have a method "append_<name>" where <name>
		is the attribute name for the array in the payload
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_foreign_keys - '{}'".format(self))
		self._clear_nested_list(iv_name)
		# Dynamically define the setter associated
		lv_method = "append_" + iv_name
		lf_method = getattr(self, lv_method)
		# Check that array is not empty
		if iv_name in io_data and len(io_data[iv_name]) > 0:
			Logger.Debug("debug", 1, "Mapping {} {} into {}".format(len(io_data[iv_name]), iv_name, self))
			# Loop on each item
			for ld_instance in io_data[iv_name]:
				lo_instance = icl_model.get(pk=ld_instance["id"])
				lo_instance.map_attribute(ld_instance, "deleted")
				# Push the instance into the nested array
				lf_method(lo_instance)

	def map_nested_list(self, io_data, iv_name, icl_model):
		"""
		Method to map a collection of nested objects.

		Use this method to map objects that can be modified.

		To map instances that shall not be modified by API call,
		use method map_foreign_keys.

		Calling class must have a method "append_<name>" where <name>
		is the attribute name for the array in the payload
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_nested_list - '{}'".format(self))
		self._clear_nested_list(iv_name)
		# Dynamically define the setter associated
		lv_method = "append_" + iv_name
		lf_method = getattr(self, lv_method)
		# Check that array is not empty
		if iv_name in io_data and len(io_data[iv_name]) > 0:
			Logger.Debug("debug", 1, "Mapping {} {} into {}".format(len(io_data[iv_name]), iv_name, self))
			# Loop on each item
			for ld_instance in io_data[iv_name]:
				# Check if this is an existing instance
				try:
					lo_instance = icl_model.get(pk=ld_instance['id'])
				except Exception:
					Logger.DebugInfo("debug", 1, "Object not found, create a new instance")
					lo_instance = icl_model.instantiate()
				# Map data from request payload
				lo_instance.map_request(ld_instance)
				# Push the instance into the nested array
				lf_method(lo_instance)

	def map_nested_object(self, io_data, iv_name, icl_model):
		"""
		Method to map a nested object.

		Use this method to map object that can be modified.

		To map instances that shall not be modified by API call,
		use method map_foreign_key
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_nested_object - '{}'".format(self))
		# Dynamically define the setter associated
		lv_method = "set_" + iv_name
		lf_method = getattr(self, lv_method)
		if iv_name in io_data and io_data[iv_name] is not None:
			# Check if this is an existing instance
			try:
				try:
					lo_instance = icl_model.get(pk=io_data[iv_name]['id'])
				except:
					lo_instance = icl_model.get(pk=getattr(self.old, iv_name, None).id)
			except Exception:
				Logger.Verbose("debug", 1, "Object not found, create a new instance")
				lo_instance = icl_model.instantiate()
			# Map data from request payload
			lo_instance.map_request(io_data[iv_name])
			# Push the instance into the nested array
			lf_method(lo_instance)
		# If the object is given but null
		elif iv_name in io_data and io_data[iv_name] is None:
			lf_method(None)

	def map_request(self, io_data):
		"""
		Default method to map a request from API. It will handle all common data
		such as New, Delete etc...
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.map_request - '{}'".format(self))
		# Log action
		Logger.Debug("core", 3, self) # Performing request mapping for {}

		# Attributes
		self.api = True
		self.map_attribute(io_data, "new")
		self.map_attribute(io_data, "deleted")
		self.map_attribute(io_data, "test")

		if self.activity.code == Activity.CREATE:
			# Remove 'id' from io_data
			try:
				del io_data["id"]
			except Exception:
				pass

	def merge_errors(self, io_object, iv_prefix=None):
		"""
		Merge errors from an object to an other
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.merge_errors - '{}'".format(self))
		for lv_key in io_object.errors:
			if iv_prefix is None:
				lv_new_key = lv_key
			else:
				lv_new_key = "_".join((iv_prefix, lv_key))
			self.errors[lv_new_key] = self.expense.errors[lv_key]

	def save(self, io_user=None, *args, **kwargs):
		"""
		Method called to save an object into the database.

		It will call methods to check data and perform custom
		business logics before and after database access
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject.save - '{}'".format(self))
		# Log action
		Logger.Debug("core", 5, self, self.__class__.__name__) # Saving object {}, class {}

		if io_user is not None:
			self.request_user = io_user
		elif self.request_user is None:
			self.request_user = User.objects.get(username="batch")

		# Before calling standard save() method, perform activity and check data consistency
		if not self.checked:
			# Perform business logic before save
			self.complete_data()
			# Check data consistency
			self.check_data()
		# Fail safe if invalid state was not catched
		if self.invalid:
			raise self.__class__.InvalidData

		# Perform DB actions that need to be done before this instance is saved
		self.before_save()

		# If in test mode, we don't go further
		if self.test:
			Logger.Debug("core", 6, self, type(self)) # Test mode, do not perform action
			return

		# Save data in the DB, even for deletion
		if self.activity.code == Activity.HARD_DELETE:
			self.delete()
		else:
			super().save(*args, **kwargs)

		# Perform DB actions that need to be done after this instance is saved
		self.after_save()

	def _save(self, *args, **kwargs):
		"""
		Direct call to save method without any check.

		Must be used very carefully, and with a documented good reason!
		"""
		Logger.Verbose("debug", 1, "START - BusinessObject._save - '{}'".format(self))
		super().save(*args, **kwargs)

	@classmethod
	def transform_filters(cls, io_filters, io_user):
		"""
		Transform API filters to fit with DB attributes

		This method is the default one and will handle common properties.

		Specific transformation can be applied in child classes, but it is advised to keep calling
		this method
		"""
		for lv_index, ltu_child in enumerate(io_filters.children):
			# Check if child is tuple or another Q object
			if isinstance(ltu_child, Q):
				io_filters.children[lv_index] =  cls.transform_filters(ltu_child, io_user)
			else:
				io_filters.children[lv_index] = cls._transform_filter(ltu_child, io_user)

		return io_filters

	@classmethod
	def _transform_filter(cls, itu_condition, io_user):
		"""
		Transform filters on common query attributes to match DB structure
		"""
		if itu_condition[0] == "status" or itu_condition[0] == "status__code":
			"""
			For status, we do not select directly on the attribute, as we need to check
			that the status is active, and also that it is relevant for the current user.
			So in fact we select all status that match the conditions, and use the related
			business objects ids
			"""
			# Query to get <business_object>Status 
			lo_query = Q(
				Q(status__code=itu_condition[1]) &
				Q(inactive=False)
			)
			# When querying with batch user, do not consider User field
			if io_user.username != "batch":
				lo_query = lo_query & Q(
					Q(user=io_user) |
					Q(user=None)
				)
			# Get the correct Model
			lcl_status_model = BusinessObjectStatusFactory.get_class(cls)
			# Build queryset
			lt_object_qs = lcl_status_model.objects.filter(lo_query).values_list("object_id", flat=True).distinct("object_id")
			# Build a condition on primary key
			return ("pk__in", lt_object_qs)
		elif "__date" in itu_condition[0]:
			# Check that field is a DateTime field. If Date only, remove suffix
			lt_parts = itu_condition[0].split("__")
			lv_index = lt_parts.index("date")
			lv_field = lt_parts[lv_index - 1]
			lo_meta = cls._meta.get_field(lv_field)
			if lo_meta.get_internal_type() == "DateField":
				del lt_parts[lv_index]
				return ("__".join(lt_parts), itu_condition[1])
			else:
				return itu_condition
		else:
			return itu_condition

	##########################################################################################################################
	# GETTERS / SETTERS
	##########################################################################################################################

	@property
	def activity(self):
		if self._activity is None:
			return None
		else:
			return self._activity

	@activity.setter
	def activity(self, i_activity):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_activity - '{}' - '{}'".format(self, i_activity))
		if i_activity is None:
			self._activity = None
		elif isinstance(i_activity, str):
			self._activity = Activity.objects.get(code=i_activity)
		elif isinstance(i_activity, int):
			self._activity = Activity.objects.get(pk=i_activity)
		elif isinstance(i_activity, Activity):
			self._activity = i_activity
		else:
			raise BusinessObject.InvalidActivity

	@property
	def api(self):
		return self._api

	@api.setter
	def api(self, ib_api):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_api - '{}' - '{}'".format(self, ib_api))
		self._api = ib_api

	@property
	def checked(self):
		return self._checked

	@checked.setter
	def checked(self, ib_checked):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_checked - '{}' - '{}'".format(self, ib_checked))
		self._checked = ib_checked

	@property
	def invalid(self):
		return self._invalid

	@invalid.setter
	def invalid(self, ib_invalid):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_invalid - '{}' - '{}'".format(self, ib_invalid))
		self._invalid = ib_invalid

	@property
	def old(self):
		return self._old

	@old.setter
	def old(self, io_old):
		self._old = io_old

	@property
	def request_user(self):
		return self._request_user

	@request_user.setter
	def request_user(self, i_user):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_request_user - '{}' - '{}'".format(self, i_user))
		if i_user is None:
			self._request_user = None
		elif isinstance(i_user, str):
			self._request_user = User.objects.get(username=i_user)
		elif isinstance(i_user, int):
			self._request_user = User.objects.get(pk=i_user)
		elif isinstance(i_user, User):
			self._request_user = i_user
		else:
			raise BusinessObject.InvalidUser

	def set_deleted(self, ib_deleted):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_deleted - '{}' - '{}'".format(self, ib_deleted))
		if ib_deleted:
			self.activity = Activity.DELETE

	@property
	def test(self):
		return self._test

	@test.setter
	def test(self, ib_test):
		Logger.Verbose("debug", 1, "START - BusinessObject.set_test - '{}' - '{}'".format(self, ib_test))
		self._test = ib_test
