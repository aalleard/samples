<?php

/************************************************************************************************************************************/
/*																																  */
/*	Cust_devise.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:43
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/Cust_deviseManager.class.php');
	require_once(PHP_PATH.'classes/Cust_devise_t.class.php');

	class Cust_devise extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Devise */
		private $devise = '';		/* Primary key */

		/* Linked object, class Cust_devise_t */
		private $t_texte = [];

		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation



			return $this->_save('I', $cv_success);
		}

		private function _update(Cust_devise $io_cust_devise_old, &$cv_success=true) {
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

		/* Devise */
		public function getDevise() {
			return $this->devise;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}
		/* Depending object, class Cust_devise_t */
		public function getT_texte() {
			return $this->t_texte;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = $this->devise;
			parent::setFullUri($lv_id);
		}

		/* Devise */
		public function setDevise($iv_devise) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->devise = Securite::checkDataFormat($iv_devise, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}

		/* Depending object, class Texte */
		public function setT_texte(Array $it_texte) {
			$this->t_texte = [];
			foreach ($it_texte as $io_texte) {
				$this->setO_texte($io_texte);
			}
		}

		public function setO_texte($io_texte) {
			if(!is_object($io_texte)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_texte'));
				return;
			}

			/* Check class */
			if(get_class($io_texte) != 'Cust_devise_t' && get_class($io_texte) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_devise_t', get_class($io_texte)));
			} elseif (get_class($io_texte) == 'Cust_devise_t') {
				/* Object is Cust_devise_t, direct assignment */
				$this->t_texte[] = $io_texte;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_texte as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_texte[] = new Cust_devise_t($lt_data);
			}
		}


		/**
			LOAD RELATED OBJECTS
		*/

		public function loadTexte(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['devise'] = $this->devise;
			/* Load data */
			$lt_results = Cust_devise_tManager::get($lt_parameters, [], $it_expand);

			$this->setT_texte($lt_results);
		}


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
				$lt_parameters['devise'] = $this->devise;
				$lt_results = Cust_deviseManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_cust_devise = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_cust_devise = $this->_update($lt_results[0], $cv_success);
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
				$lo_cust_devise = Cust_deviseManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUri();
			} else {
				/* Update record */
				$lo_cust_devise = Cust_deviseManager::update($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
			}

			/* Save depending objects */
			$this->saveTexte($cv_success);
			if (!$cv_success) {
				return $this;
			}

			return $this;

		}

		public function saveTexte(&$cv_success) {
			/* Save linked objects of type Cust_devise_t */
			$lt_texte = [];
			foreach ($this->t_texte as $lo_texte) {
				/* Define link between object values */
				$lo_texte->setDevise($this->devise);
				$lt_texte[] = $lo_texte->save($cv_success);
			}
			$this->setT_texte($lt_texte);
		}


		/**
			OBJECTS DELETION
		*/

		public function delete(&$cv_success=true) {

			/* Delete linked objects */
			$this->deleteTexte($cv_success);

			/* Delete this object */
			Cust_deviseManager::delete($this, $cv_success);
		}

		public function deleteTexte(&$cv_success) {
			$this->loadTexte();
			foreach ($this->t_texte as $lo_texte) {
				$lo_texte->delete($cv_success);
			}
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Cust_devise $io_cust_devise) {
			self::$T_BUFFER[] = $io_cust_devise;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"cust_devise":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "cust_devise" : [ ';
			foreach (self::$T_BUFFER as $lo_cust_devise) {
				$ev_json.= $lo_cust_devise->json();
				if ($lo_cust_devise !== end(self::$T_BUFFER)) {
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