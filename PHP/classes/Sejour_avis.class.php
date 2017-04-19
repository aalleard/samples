<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour_avis.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:44
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/Sejour_avisManager.class.php');

	class Sejour_avis extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Identifiant de l'avis */
		private $uid = 0;		/* Primary key */
		/* Identifiant du sejour */
		private $sejour_id = 0;
		/* Identifiant de l'utilisateur */
		private $user_id = 0;
		/* Date de creation */
		private $crea_date = '';
		/* Note de l'implication de l'hote */
		private $implication = 0;
		/* Note de proprete */
		private $proprete = 0;
		/* Note de la description */
		private $description = 0;
		/* Rapport qualite prix */
		private $qualite_prix = 0;
		/* Commentaire du voyageur */
		private $commentaire = '';


		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation



			return $this->_save('I', $cv_success);
		}

		private function _update(Sejour_avis $io_sejour_avis_old, &$cv_success=true) {
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

		/* Identifiant de l'avis */
		public function getUid() {
			return $this->uid;
		}
		/* Identifiant du sejour */
		public function getSejour_id() {
			return $this->sejour_id;
		}
		/* Identifiant de l'utilisateur */
		public function getUser_id() {
			return $this->user_id;
		}
		/* Date de creation */
		public function getCrea_date() {
			return $this->crea_date;
		}
		/* Note de l'implication de l'hote */
		public function getImplication() {
			return $this->implication;
		}
		/* Note de proprete */
		public function getProprete() {
			return $this->proprete;
		}
		/* Note de la description */
		public function getDescription() {
			return $this->description;
		}
		/* Rapport qualite prix */
		public function getQualite_prix() {
			return $this->qualite_prix;
		}
		/* Commentaire du voyageur */
		public function getCommentaire() {
			return $this->commentaire;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = $this->uid;
			parent::setFullUri($lv_id);
		}

		/* Identifiant de l'avis */
		public function setUid($iv_uid) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->uid = Securite::checkDataFormat($iv_uid, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
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
		/* Identifiant de l'utilisateur */
		public function setUser_id($iv_user_id) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->user_id = Securite::checkDataFormat($iv_user_id, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Date de creation */
		public function setCrea_date($iv_crea_date) {
			/* Database attributes control */
			$lv_datatype = 'datetime';
			try {
				$this->crea_date = Securite::checkDataFormat($iv_crea_date, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Note de l'implication de l'hote */
		public function setImplication($iv_implication) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->implication = Securite::checkDataFormat($iv_implication, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Note de proprete */
		public function setProprete($iv_proprete) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->proprete = Securite::checkDataFormat($iv_proprete, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Note de la description */
		public function setDescription($iv_description) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->description = Securite::checkDataFormat($iv_description, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Rapport qualite prix */
		public function setQualite_prix($iv_qualite_prix) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->qualite_prix = Securite::checkDataFormat($iv_qualite_prix, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Commentaire du voyageur */
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
				$lt_parameters['uid'] = $this->uid;
				$lt_results = Sejour_avisManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_sejour_avis = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_sejour_avis = $this->_update($lt_results[0], $cv_success);
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
				$lo_sejour_avis = Sejour_avisManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUid($lo_sejour_avis->getUid());
				$this->setUri();
			} else {
				/* Update record */
				$lo_sejour_avis = Sejour_avisManager::update($this, $cv_success);
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
			Sejour_avisManager::delete($this, $cv_success);
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Sejour_avis $io_sejour_avis) {
			self::$T_BUFFER[] = $io_sejour_avis;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"sejour_avis":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "sejour_avis" : [ ';
			foreach (self::$T_BUFFER as $lo_sejour_avis) {
				$ev_json.= $lo_sejour_avis->json();
				if ($lo_sejour_avis !== end(self::$T_BUFFER)) {
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