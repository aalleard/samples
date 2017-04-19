<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour_t.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:44
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/Sejour_tManager.class.php');
	require_once(PHP_PATH.'classes/Cust_langue.class.php');

	class Sejour_t extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Identifiant du sejour */
		private $sejour_id = 0;		/* Primary key */
		/* Langue des textes */
		private $langue = '';		/* Primary key */
		/* Titre du sejour */
		private $titre = '';
		/* Description longue */
		private $texte_long = '';
		/* Nature du sejour */
		private $nature = '';
		/* Commentaire sur la cible visee */
		private $commentaire = '';
		/* Presentation des repas */
		private $repas = '';

		/* Linked object, class Cust_langue */
		private $o_langue;

		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation



			return $this->_save('I', $cv_success);
		}

		private function _update(Sejour_t $io_sejour_t_old, &$cv_success=true) {
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
		public function getSejour_id() {
			return $this->sejour_id;
		}
		/* Langue des textes */
		public function getLangue() {
			return $this->langue;
		}
		/* Titre du sejour */
		public function getTitre() {
			return $this->titre;
		}
		/* Description longue */
		public function getTexte_long() {
			return $this->texte_long;
		}
		/* Nature du sejour */
		public function getNature() {
			return $this->nature;
		}
		/* Commentaire sur la cible visee */
		public function getCommentaire() {
			return $this->commentaire;
		}
		/* Presentation des repas */
		public function getRepas() {
			return $this->repas;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}
		/* Depending object, class Cust_langue */
		public function getO_langue() {
			return $this->o_langue;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = '?sejour_id='.$this->sejour_id.'&langue='.$this->langue;
			parent::setFullUri($lv_id);
		}

		/* Identifiant du sejour */
		public function setSejour_id($iv_sejour_id) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->sejour_id = Securite::checkDataFormat($iv_sejour_id, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Langue des textes */
		public function setLangue($iv_langue) {
			/* Database attributes control */
			$lv_datatype = 'varchar(2)';
			try {
				$this->langue = Securite::checkDataFormat($iv_langue, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Titre du sejour */
		public function setTitre($iv_titre) {
			/* Database attributes control */
			$lv_datatype = 'tinytext';
			try {
				$this->titre = Securite::checkDataFormat($iv_titre, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Description longue */
		public function setTexte_long($iv_texte_long) {
			/* Database attributes control */
			$lv_datatype = 'text';
			try {
				$this->texte_long = Securite::checkDataFormat($iv_texte_long, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Nature du sejour */
		public function setNature($iv_nature) {
			/* Database attributes control */
			$lv_datatype = 'text';
			try {
				$this->nature = Securite::checkDataFormat($iv_nature, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Commentaire sur la cible visee */
		public function setCommentaire($iv_commentaire) {
			/* Database attributes control */
			$lv_datatype = 'text';
			try {
				$this->commentaire = Securite::checkDataFormat($iv_commentaire, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Presentation des repas */
		public function setRepas($iv_repas) {
			/* Database attributes control */
			$lv_datatype = 'text';
			try {
				$this->repas = Securite::checkDataFormat($iv_repas, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}

		/* Depending object, class Langue */
		public function setO_langue($io_langue) {
			if(!is_object($io_langue)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_langue'));
				return;
			}

			/* Check class */
			if(get_class($io_langue) != 'Cust_langue' && get_class($io_langue) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_langue', get_class($io_langue)));
			} elseif (get_class($io_langue) == 'Cust_langue') {
				/* Object is Cust_langue, direct assignment */
				$this->o_langue = $io_langue;
				$this->o_langue->setLangue($this->getLangue());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_langue as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_langue = new Cust_langue($lt_data);
			}
		}


		/**
			LOAD RELATED OBJECTS
		*/

		public function loadLangue(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['langue'] = $this->langue;
			/* Load data */
			$lt_results = Cust_langueManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_langue($lt_results[0]);
			} else {
				$this->setO_langue(new Cust_langue($lt_parameters));
			}
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
				$lt_parameters['sejour_id'] = $this->sejour_id;
				$lt_parameters['langue'] = $this->langue;
				$lt_results = Sejour_tManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_sejour_t = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_sejour_t = $this->_update($lt_results[0], $cv_success);
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
				$lo_sejour_t = Sejour_tManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUri();
			} else {
				/* Update record */
				$lo_sejour_t = Sejour_tManager::update($this, $cv_success);
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
			Sejour_tManager::delete($this, $cv_success);
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Sejour_t $io_sejour_t) {
			self::$T_BUFFER[] = $io_sejour_t;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"sejour_t":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "sejour_t" : [ ';
			foreach (self::$T_BUFFER as $lo_sejour_t) {
				$ev_json.= $lo_sejour_t->json();
				if ($lo_sejour_t !== end(self::$T_BUFFER)) {
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