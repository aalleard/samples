import datetime

from django.contrib.auth.models import User
from django.db 					import models
from django.db.models 			import Q

from .case_type 								import CaseType
from core.classes.conversions					import Conversions
from core.classes.logger 						import Logger
from core.submodels.activity					import Activity
from core.submodels.abstract.business_object 	import BusinessObject
from legal_entity.submodels.legal_entity		import LegalEntity
from legal_entity.submodels.organization		import Organization
from legal_entity.submodels.person				import Person


class Case(BusinessObject):

	##########################################################################################################################
	# ATTRIBUTES
	##########################################################################################################################

	archived		= models.BooleanField(default=False, help_text="Archived case?")
	archiving_date  = models.DateTimeField(null=True, blank=True, help_text="Archiving date")
	archiving_user  = models.ForeignKey(User, models.SET_NULL, null=True, blank=True, related_name='case_archiving_user')
	assignee		= models.ForeignKey('legal_entity.Person', models.SET_NULL, null=True, blank=True, verbose_name='Assignee', related_name="assigned_cases")
	description     = models.TextField(blank=True, null=True)
	due_date    	= models.DateField(null=True, blank=True)
	merged_into		= models.ForeignKey('self', null=True, blank=True, on_delete=models.DO_NOTHING, help_text="Case into which this case was merged")
	name            = models.CharField(max_length=100)
	on_hold         = models.BooleanField(default=False, help_text="Is case on hold?")
	owner		    = models.ForeignKey('legal_entity.LegalEntity', models.SET_NULL, null=True, blank=True, verbose_name='Owner', related_name="cases")
	status			= models.ManyToManyField('core.Status', through="CaseStatus", through_fields=("object", "status"))
	type            = models.ForeignKey('CaseType', on_delete=models.DO_NOTHING, null=True, blank=True, related_name='cases', help_text='Case type')
	
	business_id_prefix	= "TSK"	# TASK
	message_domain  	= "case"

	_t_conversations			= []

	##########################################################################################################################
	# METHODS
	##########################################################################################################################

	def __str__(self):
		return "{} - {}".format(self.id, self.name)

	def after_create(self):
		"""
		Redefinition of method called after creation has been completed successfully

		After the case has been created, we attach a public conversation to it
		"""
		Logger.Verbose("debug", 1, "START - Case.after_create - '{}'".format(self))
		super().after_create()

		self._save_conversations()

	def after_update(self):
		"""
		Redefinition of method called after deletion has been completed successfully
		"""
		Logger.Verbose("debug", 1, "START - Case.after_update - '{}'".format(self))
		super().after_update()

		self._save_conversations()

	def append_conversations(self, io_conversation):
		"""
		Add a Conversation to the Case's conversations list
		"""
		Logger.Verbose("debug", 1, "START - Case.append_conversations - '{}'".format(self))
		self._t_conversations.append(io_conversation)

	def complete_data(self):
		"""
		Auto complete data from context before save
		"""
		Logger.Verbose("debug", 1, "START - Case.complete_data - '{}'".format(self))
		# Call parent method
		super().complete_data()

		# If no type defined, this is an Action
		if not hasattr(self, 'type') or self.type == None:
			# If a type is not provided at creation, set 'action'
			self.set_type(CaseType.objects.get(code=CaseType.ACTION))

		self._define_assignee()

	def _define_assignee(self):
		"""
		Define the default assignee if None is provided
		"""
		Logger.Verbose("debug", 1, "START - Case._define_assignee - '{}'".format(self))

		# If assignee is provided, no need to go further
		if self.assignee is not None:
			return

		# By default, assignee is owner entity accountant
		self.assignee = self.owner.get_accountant()

	def _init_public_conversation(self):
		"""
		Init a public conversation for the case
		"""
		from messaging.submodels.conversation	import Conversation
		Logger.Verbose("debug", 1, "START - Case._init_public_conversation - '{}'".format(self))

		# Add an empty public Conversation to the Case
		self.append_conversations(Conversation.instantiate())

	@classmethod
	def instantiate(cls, *args, **kwargs):
		"""
		Redefine Django default method to handle non persistant attributes
		"""
		lo_instance = super().instantiate(*args, **kwargs)
		lo_instance.save_purchase_requisition	= False
		lo_instance._t_conversations			= []
		return lo_instance

	def map_request(self, io_data):
		"""
		Method that will map data received from a legal entity application
		to a LegalEntity object
		"""
		from messaging.submodels.conversation import Conversation
		Logger.Verbose("debug", 1, "START - Case.map_request - '{}'".format(self))
		#  Fields
		self.map_attribute(io_data, "description")
		self.map_attribute(io_data, "due_date")
		self.map_attribute(io_data, "name")
		# Foreign keys
		self.map_foreign_key(io_data, 'assignee', Person)
		self.map_foreign_key(io_data, 'owner', LegalEntity)
		# Nested objects
		self.map_nested_list(io_data, "conversations", Conversation)

	def merge(self, io_source_case, io_user):
		"""
		Merge the given Case into current Case instance

		It will also merge Conversations
		"""
		Logger.Verbose("debug", 1, "START - Case.merge - Target:'{}' - Source: '{}'".format(self, io_source_case))
		# Log action
		Logger.Info(self.message_domain, 401, io_user.username, io_source_case, self) # {} merges case {} into case {}

		# Manage conversations
		self._merge_conversations(io_source_case, io_user)

		# We indicate in what case the source case has been merged
		io_source_case.merged_into = self

		# Put a delete flag on the source case
		io_source_case.activity = Activity.DELETE

		# Save the source case
		io_source_case.save(io_user)

	def _merge_conversations(self, io_source_case, io_user):
		"""
		Merge source case conversations into target case similar ones

		Public conversations will be merged, and private will be merged if they
		contains the same persons
		"""
		from messaging.submodels.conversation import Conversation
		Logger.Verbose("debug", 1, "START - Case.merge - Target:'{}' - Source: '{}'".format(self, io_source_case))
		lt_sources_qs = Conversation.objects.all().filter(case=io_source_case)
		lt_targets_qs = Conversation.objects.all().filter(case=self)

		# Compare conversations
		for lo_source in lt_sources_qs:
			lb_similar_found = False
			for lo_target in lt_targets_qs:
				if not lo_source.private and not lo_target.private:
					# Public conversations
					lo_target.merge(lo_source, io_user)
					lb_similar_found = True
					break
				elif lo_source.private and lo_target.private:
					# Private conversations
					# Must have same persons
					lt_source_persons_id = list(lo_source.persons.all().order_by('id').values_list('id', flat=True))
					lt_target_persons_id = list(lo_target.persons.all().order_by('id').values_list('id', flat=True))
					if lt_source_persons_id == lt_target_persons_id:
						lo_target.merge(lo_source, io_user)
						lb_similar_found = True
						break
			# If no similar conversation is found, reassign source conversation to target case
			if not lb_similar_found:
				lo_source.set_case(self)
				lo_source.save(io_user=self.request_user)

	def _save_conversations(self):
		"""
		Save related Conversations
		"""
		Logger.Verbose("debug", 1, "START - Case._save_conversations - '{}'".format(self))

		if len(self._t_conversations) == 0:
			# If no conversation was provided, create a public one
			self._init_public_conversation()

		# Save conversations
		for lo_conversation in self._t_conversations:
			lo_conversation.set_case(self)
			lo_conversation.save(io_user=self.request_user)

	@property
	def t_conversations(self):
		return self._t_conversations

	@t_conversations.setter
	def t_conversations(self, it_values):
		self._t_conversations = it_values

	##########################################################################################################################
	# SETTERS
	##########################################################################################################################

	def set_assignee(self, io_assignee):
		Logger.Debug("debug", 1, "Setting assignee: {}".format(io_assignee))
		self.assignee = io_assignee

	def set_description(self, lv_description):
		Logger.Debug("debug", 1, "Setting description: {}".format(lv_description))
		self.description = lv_description

	def set_due_date(self, lo_due_date):
		Logger.Debug("debug", 1, "Setting due date: {}".format(lo_due_date))
		self.due_date = Conversions.convert_to_date(lo_due_date)

	def set_name(self, lv_name):
		Logger.Debug("debug", 1, "Setting name: {}".format(lv_name))
		self.name = lv_name

	def set_on_hold(self, ib_on_hold):
		Logger.Debug("debug", 1, "Setting on hold flag: {}".format(ib_on_hold))
		self.on_hold = ib_on_hold

	def set_owner(self, lo_owner):
		Logger.Debug("debug", 1, "Setting owner: {}".format(lo_owner))
		self.owner = lo_owner

	def set_type(self, io_type):
		Logger.Debug("debug", 1, "Setting type: {}".format(io_type))
		self.type = io_type
