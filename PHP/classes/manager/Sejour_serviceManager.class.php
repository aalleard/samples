<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour_serviceManager.class.php
/*	Auteur : Antoine Alleard
/*	Date : 17/02/2017 19:00:45
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	class Sejour_serviceManager {

		private static $T_BUFFER = [];

		public static function add(Sejour_service $io_sejour_service, &$cv_success=true){
			$lv_requete = "INSERT INTO sejour_service SET 
									   sejour = '".Securite::bdd($io_sejour_service->getSejour())."',
									   service = '".Securite::bdd($io_sejour_service->getService())."'";
			$lo_sql = new Requete($lv_requete);
			try {
				$lo_sql->execute();
				$io_sejour_service->addMessage(new Message('sejour_service', 0, 's'));

				$lt_parameters = [];
				$lt_parameters['sejour'] = $io_sejour_service->getSejour();
				$lt_parameters['service'] = $io_sejour_service->getService();
				$lt_results = self::get($lt_parameters);
				return $lt_results[0];

			} catch (Message $lo_message) {
				$io_sejour_service->addMessage(new Message('sejour_service', 4, 'e'));
				$io_sejour_service->addMessage($lo_message);
				$cv_success = false;
			}

		}

		public static function buildObject(Array $is_donnees, Array $it_expand = []) {
			$lo_instance = new Sejour_service($is_donnees);

			/* Manage requested expands */
			foreach ($it_expand as $lv_expand) {
				if (!property_exists('Sejour_service', 'o_'.$lv_expand) && !property_exists('Sejour_service', 't_'.$lv_expand)) {
				/* Property does not exist for this class */
				continue;
				}
				/* Method's name dynamic definition */
				$lv_method = "load".ucfirst($lv_expand);
				if (method_exists($lo_instance, $lv_method)) {
					$lo_instance->$lv_method($it_expand);
				} else {
					$lo_instance->addMessage(new Message('expand', 0, 'x', 'Sejour_service', $lv_method));
					/* Method &1->&2() does not exist */
				}
			}
				/* Text loading */
			return $lo_instance;
		}

		public static function delete(Sejour_service $io_sejour_service, &$cv_success=true) {
			$lo_sql = new Requete("DELETE FROM sejour_service
										 WHERE sejour = '".Securite::bdd($io_sejour_service->getSejour())."'
										   AND service = '".Securite::bdd($io_sejour_service->getService())."'");

			try {
				$lo_sql->execute();
				$io_sejour_service->addMessage(new Message('sejour_service', 2, 's'));
			} catch (Message $lo_message) {
				$io_sejour_service->addMessage(new Message('sejour_service', 6, 'e'));
				$io_sejour_service->addMessage($lo_message);
				$cv_success = false;
			}

		}

		public static function get(Array $it_parameters = [], Array $it_sorters = [], Array $it_expand = [], $iv_limit = 0) {

			/* Variables initialisation */
			$lt_keys   = [];
			$lv_query  = "";
			$lv_sorter = "";
			$lv_limit  = "";
			/* Return values */
			$et_results = [];

			/* Use input parameters */
			reset($it_parameters);
			$lv_first = key($it_parameters);
			foreach ($it_parameters as $lv_key => $lv_value) {
				/* Define buffer keys */
				$lt_keys[] = $lv_value;
				/* Define search criterias */
				if ($lv_key === $lv_first) {
					/* First occurence, use keyword WHERE */
					$lv_query .= " WHERE ";
				} else {
					// Other occurences, keyword AND
					$lv_query .= " AND ";
				}
				$lv_query .= $lv_key." = '".Securite::bdd($lv_value)."'";
			}

			reset($it_sorters);
			$lv_first = key($it_sorters);
			foreach ($it_sorters as $lv_key => $lv_value) {
				if ($lv_key === $lv_first) {
					/* First occurence, use keyword ORDER BY */
					$lv_sorter .= " ORDER BY ";
				} else {
					// Other occurences, use a comma
					$lv_sorter .= ", ";
				}
				$lv_sorter .= $lv_value;
			}

			if ($iv_limit != 0) {
				$lv_limit = " LIMIT ".$iv_limit;
			}

			/* Database query */
			$lo_sql = new Requete("SELECT * FROM sejour_service ".$lv_query.$lv_sorter.$lv_limit);

			$lo_statement = $lo_sql->getStatement();
			while ($ls_donnees = $lo_statement->fetch(PDO::FETCH_ASSOC)) {
				$et_results[] = self::buildObject($ls_donnees, $it_expand);
			}

			if (count($et_results) == 0) {
				Message::bufferMessage(new Message('sejour_service', 3, 'w'));
			}

			return $et_results;
		}

		public static function getBuffer() {
			return self::$T_BUFFER;
		}

		public static function update(Sejour_service $io_sejour_service, &$cv_success=true) {
			return $io_sejour_service;
		}

	}
?>