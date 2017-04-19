<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour_service.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:44
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/Sejour_serviceManager.class.php');

	class Sejour_service extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Identifiant du sejour */
		private $sejour = 0;		/* Primary key */
		/* Identifiant du service */
		private $service = 0;		/* Primary key */


		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation



			return $this->_save('I', $cv_success);
		}

		private function _update(Sejour_service $io_sejour_service_old, &$cv_success=true) {
			# Insert your business rules here for object modification



			return $this->_save('U', $cv_success);
		}

		/************************************************************************************************************************************/
		/* TECHNICAL METHODS - BE CAREFULL WHEN MODIFYING!!!
		/************************************************************************************************************************************/

		/**
			CONSTRUCTOR
		*/

		public function __construct(Array $donnees) {
			parent::__construct();
			$this->hydrate($donnees);
			$this->setUri();
			$this->setV_has_error(false);
		}

		/**
			HYDRATATION
		*/

		public function hydrate(Array $donnees) {
			foreach ($donnees as $key => $value) {
				$method = 'set'.ucfirst($key);
				if (method_exists($this, $method)) {
					$this->$method($value);
				}
			}
		}

		/**
			GETTERS
		*/

		/* Identifiant du sejour */
		public function getSejour() {
			return $this->sejour;
		}
		/* Identifiant du service */
		public function getService() {
			return $this->service;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = '?sejour='.$this->sejour.'&service='.$this->service;
			parent::setFullUri($lv_id);
		}

		/* Identifiant du sejour */
		public function setSejour($iv_sejour) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->sejour = Securite::checkDataFormat($iv_sejour, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Identifiant du service */
		public function setService($iv_service) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->service = Securite::checkDataFormat($iv_service, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}


		/**
			LOAD RELATED OBJECTS
		*/


		/**
			OBJECT SAVING
		*/

		public function save(&$cv_success=true) {
			/* Save function for public calls */

			if ($this->v_delete) {
				$this->delete($cv_success);
				return null;
			} else {
				/* Search a record with the same primary key */
				$lt_parameters = [];
				$lt_parameters['sejour'] = $this->sejour;
				$lt_parameters['service'] = $this->service;
				$lt_results = Sejour_serviceManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_sejour_service = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_sejour_service = $this->_update($lt_results[0], $cv_success);
					if (!$cv_success) {
						return $this;
					}
				}
			}

			return $this;
		}

		private function _save($iv_mode, &$cv_success=true) {
			/* Save object and linked objects */

			/* Check that there is no error for this object */
			if ($this->v_has_error) {
				Message::bufferMessage(new Message('all', 4, 'e'));
				$cv_success = false;
				return $this;
			}

			/* Save objects whose our object is depending on */
			if ($iv_mode == 'I') {
				/* No record, create a new one */
				$lo_sejour_service = Sejour_serviceManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUri();
			} else {
				/* Update record */
				$lo_sejour_service = Sejour_serviceManager::update($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
			}

			/* Save depending objects */

			return $this;

		}


		/**
			OBJECTS DELETION
		*/

		public function delete(&$cv_success=true) {

			/* Delete linked objects */

			/* Delete this object */
			Sejour_serviceManager::delete($this, $cv_success);
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Sejour_service $io_sejour_service) {
			self::$T_BUFFER[] = $io_sejour_service;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"sejour_service":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "sejour_service" : [ ';
			foreach (self::$T_BUFFER as $lo_sejour_service) {
				$ev_json.= $lo_sejour_service->json();
				if ($lo_sejour_service !== end(self::$T_BUFFER)) {
					$ev_json .= ', ';
				}
			}
			$ev_json.= ' ]}';

			return $ev_json;
		}

		/**
			REPLACE TEXT PLACEHOLDERS
		*/

		public function fill_text_markup($iv_text){
			$ev_text = $iv_text;

			foreach ($this as $lv_key => $lv_value) {
				$lv_to_replace = '{{'.$lv_key.'}}';
				if (is_array($lv_value)) {
					continue;
				} elseif (gettype($lv_value) == 'object' && get_class($lv_value) == ('DateTime')) {
					$ev_text = str_replace($lv_to_replace, $lv_value->format('m/d/Y'), $ev_text);
				} elseif (gettype($lv_value) == 'object') {
					$ev_text = $lv_value->fill_text_markup($ev_text);
				} else {
					$ev_text = str_replace($lv_to_replace, $lv_value, $ev_text);
				}
			}

			return $ev_text;
		}

	}
?>