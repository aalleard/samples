import json

from django.conf            	import settings
from django.db.models 			import Q
from rest_framework.response 	import Response
from rest_framework.views    	import status

from core.classes.logger 	import Logger


class OdataMiddleware(object):
	FILTER_ALIAS = "__FILTER__"
	COMPARISON_OPERATORS = {
		"eq": "",
		"ne": "!",
		"ge": "__gte",
		"gt": "__gt",
		"le": "__lte",
		"lt": "__lt",
		"substringof": "__icontains"
	}
	LOGICAL_OPERATORS = {
		"OR": " or ",
		"AND": " and ",
		"NOT": " not"
	}
	SORTING_OPERATORS = {
		"asc": "",
		"desc": "-"
	}
	
	"""
	Middleware class to handle query parameters.
	"""
	def __init__(self, get_response):
		self.aliases = {}
		self.get_response = get_response

	def __call__(self, io_request):
		return self.process_request(io_request)

	def _abstract_filter(self, iv_filters):
		"""
		Replace text enclosed in parentheses by an alias
		Stops after on replacement

		Parameters:
		iv_filters (str): The query string

		Returns:
		ev_filters (str): The query string with firts parentheses replaced by an alias
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._abstract_filter - '{}'".format(iv_filters))
		# Detect the first closing parenthese
		lt_groups = iv_filters.split(")")
		if len(lt_groups) == 1:
			return iv_filters
		# Use the part of the string before
		lv_start = lt_groups[0]

		# Detect the last opening parenthese
		lt_groups = lv_start.split("(")
		lv_filter = "(" + lt_groups[-1] + ")"

		# Replace the substring with an alias
		lv_alias = OdataMiddleware.FILTER_ALIAS + str(len(self.aliases))
		ev_filters = iv_filters.replace(lv_filter, lv_alias)
		self.aliases[lv_alias] = lv_filter[1:-1]

		return ev_filters

	def _abstract_filters(self, iv_filters):
		"""
		This method is used to replace parenthesis groups by aliases
		So the query build can be done with correct priority

		Parameters:
		iv_filters (str): The query string

		Returns:
		ev_filters (str): The query string with parentheses replaced by aliases
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._abstract_filters - '{}'".format(iv_filters))
		ev_filters = self._abstract_filter(iv_filters)
		if ev_filters != iv_filters:
			# A substitution has be done
			# Continue to substitute
			ev_filters = self._abstract_filters(ev_filters)

		return ev_filters

	def _build_filters(self, iv_filters):
		"""
		Build query filters from the $filter URL parameter string or substring

		Parameters:
		iv_filters (str): A string containing one or many conditions

		Returns:
		eo_filters (Q): The filters in Django accepted format
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._build_filters - '{}'".format(iv_filters))
		lv_filter = iv_filters.strip()

		# 1. Check if there is a "," in the filter
		lt_parts = lv_filter.split(",")
		if len(lt_parts) > 1:
			eo_filter = Q()
			for lv_part in lt_parts:
				eo_filter = eo_filter & self._build_filters(lv_part)
			return eo_filter
		
		# 2. Check if there is an "or" in the filter
		lt_parts = lv_filter.split(OdataMiddleware.LOGICAL_OPERATORS.get("OR"))
		if len(lt_parts) > 1:
			eo_filter = Q()
			for lv_part in lt_parts:
				eo_filter = eo_filter | self._build_filters(lv_part)
			return eo_filter

		# 3. Check if there is an "and" in the filter
		lt_parts = lv_filter.split(OdataMiddleware.LOGICAL_OPERATORS.get("AND"))
		if len(lt_parts) > 1:
			eo_filter = Q()
			for lv_part in lt_parts:
				eo_filter = eo_filter & self._build_filters(lv_part)
			return eo_filter

		# 4. Check if there is an "not" in the filter
		lt_parts = lv_filter.split(OdataMiddleware.LOGICAL_OPERATORS.get("NOT"))
		if len(lt_parts) > 1:
			eo_filter = ~Q(self._build_filters(lt_parts[1]))
			return eo_filter

		# 5. Build the filter
		# 5.a: filter is an Alias
		if self.aliases.get(lv_filter, None) is not None:
			eo_filter = self._build_filters(self.aliases.get(lv_filter))
			return eo_filter

		# 5.b: filter is a 'real' filter
		lv_property = self._build_filter_property(lv_filter)
		lv_value    = self._build_filter_value(lv_filter)
		if lv_property.endswith("!"):
			eo_filter = ~Q(**{lv_property[:-1]: lv_value})
		else:
			eo_filter = Q(**{lv_property: lv_value})

		# Return the filter
		return eo_filter

	def _build_filter_property(self, iv_filter):
		"""
		Build the filter property

		Parameters: 
		iv_filter (str): the filter string

		Returns:
		ev_property (str): the property to query
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._build_filter_property - '{}'".format(iv_filter))
		lv_filter = iv_filter.strip()
		lt_parts = lv_filter.split(" ")
		# Property will be defined using property and operator

		# Init property name
		ev_property = lt_parts[0].replace("/", "__")

		# Replace comparison operator
		lv_operator = lt_parts[1]
		ev_property += OdataMiddleware.COMPARISON_OPERATORS.get(lv_operator)

		return ev_property
		
	def _build_filter_value(self, iv_filter):
		"""
		Build the filter value

		Parameters:
		iv_filter (str): the filter string

		Returns:
		ev_value (any): The value to query
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._build_filter_value - '{}'".format(iv_filter))
		lv_filter = iv_filter.strip()
		lt_parts = lv_filter.split(" ")
		# Value will be the third part
		lv_value = lt_parts[2]
		lt_value_parts = lv_value.split("'")
		if len(lt_value_parts) > 1:
			# Value is string
			ev_value = lt_value_parts[1]
		elif lv_value == "true":
			ev_value = True
		elif lv_value == "false":
			ev_value = False
		elif lv_value == "null":
			ev_value = None
		elif '.' in lv_value:
			# Value is float
			ev_value = float(lv_value)
		else:
			# Value is integer
			ev_value = int(lv_value)

		return ev_value

	def _build_sorter(self, iv_sorter):
		"""
		Transform a single OData Orderby parameter into sorter

		Parameters:
		iv_sorter (str): A Orderby parameter

		Returns:
		ev_sorter (str): A sorter as expected by Django
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._build_sorter - '{}'".format(iv_sorter))
		
		# Split string to detect a sorting operator
		lt_parts = iv_sorter.split(" ")
		
		# Property name
		ev_sorter = lt_parts[0].replace("/", "__")

		# Sorting operator
		if len(lt_parts) > 1:
			lv_operator = lt_parts[1]
			ev_sorter = OdataMiddleware.SORTING_OPERATORS.get(lv_operator) + ev_sorter

		return ev_sorter

	def _build_sorters(self, iv_sorters):
		"""
		Build query sorters from the $orderby URL parameter string or substring

		Parameters:
		iv_sorters (str): A string containing one or many conditions

		Returns:
		et_sorters (str[]) A list of sorters
		"""
		Logger.Verbose("debug", 1, "START - OdataMiddleware._build_sorters - '{}'".format(iv_sorters))
		lv_sorter = iv_sorters.strip()

		et_sorters = []
		# 1. Check if there is a "," in the sorter
		lt_parts = lv_sorter.split(",")
		if len(lt_parts) > 1:
			for lv_part in lt_parts:
				et_sorters.append(self._build_sorter(lv_part))
		else:
			et_sorters.append(self._build_sorter(lv_sorter))	

		return et_sorters

	def process_request(self, io_request):
		"""
		Analyse request and transform URL parameters:
		- $filter: build a Q object for DB query
		- $orderby:

		Parameters:
		io_request (WSGIRequest): Django HTTP Request

		Returns:
		The Response 
		"""
		# Init
		io_request.filters = None

		# Count only
		if io_request.get_full_path().endswith("/$count"):
			io_request.count_request = True
		else:
			io_request.count_request = False

		# Sorters
		lv_sorters = io_request.GET.get("$orderby", None)
		if lv_sorters is not None:
			io_request.sorters = self._build_sorters(lv_sorters)
		else:
			io_request.sorters = None

		# Filters
		lv_filters = io_request.GET.get("$filter", None)
		if io_request.count_request:
			lv_filters = "/".join(lv_filters.split("/")[:-1])
		if lv_filters is not None:
			lv_filters = self._abstract_filters(lv_filters)
			io_request.filters = self._build_filters(lv_filters)
		else:
			io_request.filters = None

		# Pagination
		io_request.skip = int(io_request.GET.get("$skip", 0))
		io_request.top  = io_request.skip + int(io_request.GET.get("$top", int(settings.DRF_DEFAULT_PAGE_SIZE)))

		# Expand
		lv_expand = io_request.GET.get("$expand", None)
		if lv_expand is not None:
			io_request.expand = lv_expand.split(",")
		else:
			io_request.expand = None

		# Count
		io_request.count = json.loads(io_request.GET.get("$count", "false"))

		# Debugging
		lb_debug = json.loads(io_request.GET.get("$debug", "false"))
		lb_verbose = json.loads(io_request.GET.get("$debug-verbose", "false"))
		lb_trace = json.loads(io_request.GET.get("$debug-trace", "false"))
		if lb_debug:
			Logger.LOG_LEVEL = 10
		elif lb_verbose:
			Logger.LOG_LEVEL = 5
		elif lb_trace:
			Logger.LOG_LEVEL = 1
		else:
			Logger.LOG_LEVEL = int(settings.LOG_LEVEL)

		return self.get_response(io_request)
			