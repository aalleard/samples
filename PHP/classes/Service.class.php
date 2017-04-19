<?php

/************************************************************************************************************************************/
/*																																  */
/*	Service.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:44
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/ServiceManager.class.php');
	require_once(PHP_PATH.'classes/Cust_type_service.class.php');
	require_once(PHP_PATH.'classes/Cust_cat_service.class.php');
	require_once(PHP_PATH.'classes/Cust_sous_cat_service.class.php');
	require_once(PHP_PATH.'classes/Adresse.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_devise.class.php');
	require_once(PHP_PATH.'classes/Cust_jour.class.php');
	require_once(PHP_PATH.'classes/Service_avis.class.php');
	require_once(PHP_PATH.'classes/Dispo.class.php');
	require_once(PHP_PATH.'classes/Service_dispo.class.php');
	require_once(PHP_PATH.'classes/Service_t.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Service_media.class.php');
	require_once(PHP_PATH.'classes/Sejour.class.php');
	require_once(PHP_PATH.'classes/Sejour_service.class.php');
	require_once(PHP_PATH.'classes/Service_degressivite.class.php');
	require_once(PHP_PATH.'classes/Cust_delai_prevenance.class.php');
	require_once(PHP_PATH.'classes/Cust_intervalle_reservation.class.php');

	class Service extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Identifiant du service */
		private $uid = 0;		/* Primary key */
		/* Identifiant du l'utilisateur */
		private $user_id = 0;
		/* Date de creation */
		private $date_crea = '';
		/* Service supprime ? */
		private $suppr = '';
		/* Date de suppression */
		private $date_suppr = '';
		/* Type de service */
		private $type = 0;
		/* Categorie de service */
		private $categorie = 0;
		/* Sous-categorie de service */
		private $sous_cat = 0;
		/* Adresse du service */
		private $adresse = 0;
		/* Capacite minimale */
		private $capa_min = 0;
		/* Capacite maximale */
		private $capa_max = 0;
		/* Duree minimale */
		private $duree_min = 0;
		/* Unite de la duree minimale */
		private $duree_min_unit = '';
		/* Duree maximale */
		private $duree_max = 0;
		/* Unite de la duree maximale */
		private $duree_max_unit = '';
		/* Heure de debut minimale */
		private $heure_debut_min = '';
		/* Heure de debut maximale */
		private $heure_debut_max = '';
		/* Heure de fin minimale */
		private $heure_fin_min = '';
		/* Heure de fin maximale */
		private $heure_fin_max = '';
		/* Pause */
		private $pause = '';
		/* Heure de debut de la pause */
		private $pause_debut = '';
		/* Heure de fin de la pause */
		private $pause_fin = '';
		/* Quantite minmale */
		private $quantite_min = 0;
		/* Unite de quantite minimale */
		private $quantite_min_unit = '';
		/* Quantite maximale */
		private $quantite_max = 0;
		/* Unite de quantite maximale */
		private $quantite_max_unit = '';
		/* Nombre de reservations simultanees */
		private $simultane = 0;
		/* Acces aux personnes handicapees */
		private $handicap = '';
		/* Animaux acceptes */
		private $animaux = '';
		/* Fumeurs acceptes */
		private $fumeur = '';
		/* Condition d'age */
		private $age = '';
		/* Certificat medical requis */
		private $certif = '';
		/* Permis de conduire requis */
		private $permis = '';
		/* Linge de maison fourni */
		private $linge_maison = '';
		/* Serviettes de bain fournies */
		private $serviette = '';
		/* Lave linge */
		private $lave_linge = '';
		/* Lave vaisselle */
		private $lave_vaisselle = '';
		/* Seche cheveux */
		private $seche_cheveux = '';
		/* Fer a repasser */
		private $fer_repasser = '';
		/* Acces internet */
		private $internet = '';
		/* Lit bebe */
		private $lit_bebe = '';
		/* Table a langer */
		private $table_langer = '';
		/* Piscine */
		private $piscine = '';
		/* Parking */
		private $parking = '';
		/* Ascenseur */
		private $ascenseur = '';
		/* Materiel fourni */
		private $materiel = '';
		/* Repas fourni le matin */
		private $repas_matin = '';
		/* Repas fourni le midi */
		private $repas_midi = '';
		/* Repas fourni le soir */
		private $repas_soir = '';
		/* Prix */
		private $prix = 0;
		/* Prix enfant */
		private $prix_enfant = 0;
		/* Unite de prix */
		private $prix_unite = '';
		/* Devise */
		private $devise = '';
		/* Modalites de reservation. 1 = Ouvert a tous. 0 = par mes sejours */
		private $modalite_resa = '';
		/* Taux de commission */
		private $taux_commission = 0;
		/* Jour par defaut */
		private $jour_defaut = 0;
		/* Heure par defaut */
		private $heure_defaut = '';
		/* Delai de prevenance */
		private $prevenance = 0;
		/* Intervalle entre deux reservations */
		private $intervalle = 0;

		/* Linked object, class Cust_type_service */
		private $o_type_service;
		/* Linked object, class Cust_cat_service */
		private $o_cat_service;
		/* Linked object, class Cust_sous_cat_service */
		private $o_sous_cat_service;
		/* Linked object, class Adresse */
		private $o_adresse;
		/* Linked object, class Cust_unite */
		private $o_unite_duree_min;
		/* Linked object, class Cust_unite */
		private $o_unite_duree_max;
		/* Linked object, class Cust_unite */
		private $o_unite_quantite_min;
		/* Linked object, class Cust_unite */
		private $o_unite_quantite_max;
		/* Linked object, class Cust_devise */
		private $o_devise;
		/* Linked object, class Cust_jour */
		private $o_jour;
		/* Linked object, class Service_avis */
		private $t_avis = [];
		/* Linked object, class Dispo */
		private $t_dispo = [];
		private $t_service_dispo = []; /* Relation's pivot table */
		/* Linked object, class Service_t */
		private $t_texte = [];
		/* Linked object, class Media */
		private $t_media = [];
		private $t_service_media = []; /* Relation's pivot table */
		/* Linked object, class Sejour */
		private $t_sejour = [];
		private $t_sejour_service = []; /* Relation's pivot table */
		/* Linked object, class Service_degressivite */
		private $t_degressivites = [];
		/* Linked object, class Cust_delai_prevenance */
		private $o_delai_prevenance;
		/* Linked object, class Cust_intervalle_reservation */
		private $o_intervalle_reservation;

		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		private function _calculateActivityPrice($iv_adultes, $iv_enfants) {
			// Calcul du prix d'une activité

			switch ($this->prix_unite) {
				case '/H':
				case '/M':
				case '/J':
					# Prix à la durée, on se base sur le minimum
					return $this->duree_min * $this->prix;
					break;
				case '/P':
					# Prix par personne
					return $iv_adultes * $this->prix + $iv_enfants * $this->prix_enfant;
					break;
				case '/PH':
				case '/PM':
				case '/PJ':
					# Prix par personne et par unité de temps
					return ($iv_adultes * $this->prix + $iv_enfants * $this->prix_enfant) * $this->duree_min;
					return;
				default:
					# C'est un gros soucis, à voir comment gérer cela
					return 0;
					break;
			}
		}

		private function _calculateExtraPrice($iv_adultes, $iv_enfants) {
			// Calcul du prix d'un extra
			
			switch ($this->prix_unite) {
				case '/H':
				case '/M':
				case '/J':
					# Prix à la durée, on se base sur le minimum
					return $this->duree_min * $this->prix;
					break;
				case '/P':
					# Prix par personne
					return $iv_adultes * $this->prix + $iv_enfants * $this->prix_enfant;
					break;
				case '/PH':
				case '/PM':
				case '/PJ':
					# Prix par personne et par unité de temps
					return ($iv_adultes * $this->prix + $iv_enfants * $this->prix_enfant) * $this->duree_min;
					return;
				default:
					# C'est un gros soucis, à voir comment gérer cela
					return 0;
					break;
			}

		}

		private function _calculateHebergPrice($io_date_from, $io_date_to) {
			$ev_prix = 0;
			// Le prix de l'hébergement est à la nuité
			// Formule : prix = durée x prix par nuit
			// Durée du séjour
			$lv_duree     	  = DateFunctions::diff($io_date_from, $io_date_to)->d;
			$lv_prix_unitaire = $this->prix;
			// Prix de base sans réduction
			$ev_prix = $lv_prix_unitaire * $lv_duree;

			// On va aller chercher dans les dispos si des réductions sont applicables
			$lt_dispos = DispoManager::getBetween($this->uid, 'service', $io_date_from, $io_date_to);
			foreach ($lt_dispos as $lo_dispo) {
				# On ne prend pas en compte le dernier jour, car la dernière nuit sera celle de la veille
				if ($lo_dispo === end($lt_dispos)) {
					break;
				}
				# Pour chaque dispo, on va retirer la remise
				if($lo_dispo->getRemise() != 0) {
					$ev_prix -= $lv_prix_unitaire * $lo_dispo->getRemise() /100;
				}
			}

			return $ev_prix;
		}

		public function calculatePriceFromSearch(Recherche $io_recherche) {
			// Calcul du prix d'un service à partir des paramètres de recherche
			
			switch ($this->type) {
				case 1:
					# Hébergement
					return $this->_calculateHebergPrice($io_recherche->getDate_from(), $io_recherche->getDate_to());
					break;
				
				case 3:
					# Activité
					return $this->_calculateActivityPrice($io_recherche->getAdultes(), $io_recherche->getEnfants());
					break;
				
				case 4:
					# Extra
					return $this->_calculateExtraPrice($io_recherche->getAdultes(), $io_recherche->getEnfants());
					break;
			}
		}

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation

			# Date de creation
			$this->setDate_crea(new DateTime());

			return $this->_save('I', $cv_success);
		}

		public function isDispo(DateTime $io_date) {
			if (count($this->t_dispo) == 0) {
				# Aucun enregistrement, donc c'est libre
				return true;
			} else {
				foreach ($this->t_dispo as $lo_dispo) {
					if (DateFunctions::compare($lo_dispo->getDate(), $io_date) == 0) {
						# On va vérifier le type de dispo
						if ($lo_dispo->getStatut()->getStatut() == 2 || $lo_dispo->getStatut()->getStatut() == 3) {
							# Non disponible
							return false;
						}
					}
				}
				return true;
			}

		}

		public function isSearchResult(Recherche $io_recherche){
			$lt_dates = [];

			// Pas de règle de gestion implémentée pour le moment
			switch ($this->type) {
				case 1:
					# Hébergement
					// On va vérifier que l'hébergement est disponible
					$this->loadDispo();
					$lt_dates = DateFunctions::getDates($io_recherche->getDate_from(), $io_recherche->getDate_to());
					foreach ($lt_dates as $lv_date) {
						# Il y a une date existante dans la période voulue
						# Vérification que le service est disponible à la dite date
						if (!$this->isDispo($lv_date)) {
							return false;
						}
					}
					break;
				
				case 3:
					# Activité
					break;
				
				case 4:
					# Extra
					break;
			}

			return true;
		}

		private function _update(Service $io_service_old, &$cv_success=true) {
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

		/* Identifiant du service */
		public function getUid() {
			return $this->uid;
		}
		/* Identifiant du l'utilisateur */
		public function getUser_id() {
			return $this->user_id;
		}
		/* Date de creation */
		public function getDate_crea() {
			return $this->date_crea;
		}
		/* Service supprime ? */
		public function getSuppr() {
			return $this->suppr;
		}
		/* Date de suppression */
		public function getDate_suppr() {
			return $this->date_suppr;
		}
		/* Type de service */
		public function getType() {
			return $this->type;
		}
		/* Categorie de service */
		public function getCategorie() {
			return $this->categorie;
		}
		/* Sous-categorie de service */
		public function getSous_cat() {
			return $this->sous_cat;
		}
		/* Adresse du service */
		public function getAdresse() {
			return $this->adresse;
		}
		/* Capacite minimale */
		public function getCapa_min() {
			return $this->capa_min;
		}
		/* Capacite maximale */
		public function getCapa_max() {
			return $this->capa_max;
		}
		/* Duree minimale */
		public function getDuree_min() {
			return $this->duree_min;
		}
		/* Unite de la duree minimale */
		public function getDuree_min_unit() {
			return $this->duree_min_unit;
		}
		/* Duree maximale */
		public function getDuree_max() {
			return $this->duree_max;
		}
		/* Unite de la duree maximale */
		public function getDuree_max_unit() {
			return $this->duree_max_unit;
		}
		/* Heure de debut minimale */
		public function getHeure_debut_min() {
			return $this->heure_debut_min;
		}
		/* Heure de debut maximale */
		public function getHeure_debut_max() {
			return $this->heure_debut_max;
		}
		/* Heure de fin minimale */
		public function getHeure_fin_min() {
			return $this->heure_fin_min;
		}
		/* Heure de fin maximale */
		public function getHeure_fin_max() {
			return $this->heure_fin_max;
		}
		/* Pause */
		public function getPause() {
			return $this->pause;
		}
		/* Heure de debut de la pause */
		public function getPause_debut() {
			return $this->pause_debut;
		}
		/* Heure de fin de la pause */
		public function getPause_fin() {
			return $this->pause_fin;
		}
		/* Quantite minmale */
		public function getQuantite_min() {
			return $this->quantite_min;
		}
		/* Unite de quantite minimale */
		public function getQuantite_min_unit() {
			return $this->quantite_min_unit;
		}
		/* Quantite maximale */
		public function getQuantite_max() {
			return $this->quantite_max;
		}
		/* Unite de quantite maximale */
		public function getQuantite_max_unit() {
			return $this->quantite_max_unit;
		}
		/* Nombre de reservations simultanees */
		public function getSimultane() {
			return $this->simultane;
		}
		/* Acces aux personnes handicapees */
		public function getHandicap() {
			return $this->handicap;
		}
		/* Animaux acceptes */
		public function getAnimaux() {
			return $this->animaux;
		}
		/* Fumeurs acceptes */
		public function getFumeur() {
			return $this->fumeur;
		}
		/* Condition d'age */
		public function getAge() {
			return $this->age;
		}
		/* Certificat medical requis */
		public function getCertif() {
			return $this->certif;
		}
		/* Permis de conduire requis */
		public function getPermis() {
			return $this->permis;
		}
		/* Linge de maison fourni */
		public function getLinge_maison() {
			return $this->linge_maison;
		}
		/* Serviettes de bain fournies */
		public function getServiette() {
			return $this->serviette;
		}
		/* Lave linge */
		public function getLave_linge() {
			return $this->lave_linge;
		}
		/* Lave vaisselle */
		public function getLave_vaisselle() {
			return $this->lave_vaisselle;
		}
		/* Seche cheveux */
		public function getSeche_cheveux() {
			return $this->seche_cheveux;
		}
		/* Fer a repasser */
		public function getFer_repasser() {
			return $this->fer_repasser;
		}
		/* Acces internet */
		public function getInternet() {
			return $this->internet;
		}
		/* Lit bebe */
		public function getLit_bebe() {
			return $this->lit_bebe;
		}
		/* Table a langer */
		public function getTable_langer() {
			return $this->table_langer;
		}
		/* Piscine */
		public function getPiscine() {
			return $this->piscine;
		}
		/* Parking */
		public function getParking() {
			return $this->parking;
		}
		/* Ascenseur */
		public function getAscenseur() {
			return $this->ascenseur;
		}
		/* Materiel fourni */
		public function getMateriel() {
			return $this->materiel;
		}
		/* Repas fourni le matin */
		public function getRepas_matin() {
			return $this->repas_matin;
		}
		/* Repas fourni le midi */
		public function getRepas_midi() {
			return $this->repas_midi;
		}
		/* Repas fourni le soir */
		public function getRepas_soir() {
			return $this->repas_soir;
		}
		/* Prix */
		public function getPrix() {
			return $this->prix;
		}
		/* Prix enfant */
		public function getPrix_enfant() {
			return $this->prix_enfant;
		}
		/* Unite de prix */
		public function getPrix_unite() {
			return $this->prix_unite;
		}
		/* Devise */
		public function getDevise() {
			return $this->devise;
		}
		/* Modalites de reservation. 1 = Ouvert a tous. 0 = par mes sejours */
		public function getModalite_resa() {
			return $this->modalite_resa;
		}
		/* Taux de commission */
		public function getTaux_commission() {
			return $this->taux_commission;
		}
		/* Jour par defaut */
		public function getJour_defaut() {
			return $this->jour_defaut;
		}
		/* Heure par defaut */
		public function getHeure_defaut() {
			return $this->heure_defaut;
		}
		/* Delai de prevenance */
		public function getPrevenance() {
			return $this->prevenance;
		}
		/* Intervalle entre deux reservations */
		public function getIntervalle() {
			return $this->intervalle;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}
		/* Depending object, class Cust_type_service */
		public function getO_type_service() {
			return $this->o_type_service;
		}
		/* Depending object, class Cust_cat_service */
		public function getO_cat_service() {
			return $this->o_cat_service;
		}
		/* Depending object, class Cust_sous_cat_service */
		public function getO_sous_cat_service() {
			return $this->o_sous_cat_service;
		}
		/* Depending object, class Adresse */
		public function getO_adresse() {
			return $this->o_adresse;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_duree_min() {
			return $this->o_unite_duree_min;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_duree_max() {
			return $this->o_unite_duree_max;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_quantite_min() {
			return $this->o_unite_quantite_min;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_quantite_max() {
			return $this->o_unite_quantite_max;
		}
		/* Depending object, class Cust_devise */
		public function getO_devise() {
			return $this->o_devise;
		}
		/* Depending object, class Cust_jour */
		public function getO_jour() {
			return $this->o_jour;
		}
		/* Depending object, class Service_avis */
		public function getT_avis() {
			return $this->t_avis;
		}
		/* Depending object, class Dispo */
		public function getT_dispo() {
			return $this->t_dispo;
		}
		/* Depending object, class Service_t */
		public function getT_texte() {
			return $this->t_texte;
		}
		/* Depending object, class Media */
		public function getT_media() {
			return $this->t_media;
		}
		/* Depending object, class Sejour */
		public function getT_sejour() {
			return $this->t_sejour;
		}
		/* Depending object, class Service_degressivite */
		public function getT_degressivites() {
			return $this->t_degressivites;
		}
		/* Depending object, class Cust_delai_prevenance */
		public function getO_delai_prevenance() {
			return $this->o_delai_prevenance;
		}
		/* Depending object, class Cust_intervalle_reservation */
		public function getO_intervalle_reservation() {
			return $this->o_intervalle_reservation;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = $this->uid;
			parent::setFullUri($lv_id);
		}

		/* Identifiant du service */
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
		/* Identifiant du l'utilisateur */
		public function setUser_id($iv_user_id) {
			/* Database attributes control */
			$lv_datatype = 'int(10)';
			try {
				$this->user_id = Securite::checkDataFormat($iv_user_id, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Date de creation */
		public function setDate_crea($iv_date_crea) {
			/* Database attributes control */
			$lv_datatype = 'datetime';
			try {
				$this->date_crea = Securite::checkDataFormat($iv_date_crea, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Service supprime ? */
		public function setSuppr($iv_suppr) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->suppr = Securite::checkDataFormat($iv_suppr, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Date de suppression */
		public function setDate_suppr($iv_date_suppr) {
			/* Database attributes control */
			$lv_datatype = 'datetime';
			try {
				$this->date_suppr = Securite::checkDataFormat($iv_date_suppr, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Type de service */
		public function setType($iv_type) {
			/* Database attributes control */
			$lv_datatype = 'smallint(3) unsigned';
			try {
				$this->type = Securite::checkDataFormat($iv_type, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Categorie de service */
		public function setCategorie($iv_categorie) {
			/* Database attributes control */
			$lv_datatype = 'smallint(3) unsigned';
			try {
				$this->categorie = Securite::checkDataFormat($iv_categorie, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Sous-categorie de service */
		public function setSous_cat($iv_sous_cat) {
			/* Database attributes control */
			$lv_datatype = 'smallint(3) unsigned';
			try {
				$this->sous_cat = Securite::checkDataFormat($iv_sous_cat, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Adresse du service */
		public function setAdresse($iv_adresse) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->adresse = Securite::checkDataFormat($iv_adresse, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Capacite minimale */
		public function setCapa_min($iv_capa_min) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->capa_min = Securite::checkDataFormat($iv_capa_min, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Capacite maximale */
		public function setCapa_max($iv_capa_max) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->capa_max = Securite::checkDataFormat($iv_capa_max, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Duree minimale */
		public function setDuree_min($iv_duree_min) {
			/* Database attributes control */
			$lv_datatype = 'smallint(4) unsigned';
			try {
				$this->duree_min = Securite::checkDataFormat($iv_duree_min, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de la duree minimale */
		public function setDuree_min_unit($iv_duree_min_unit) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->duree_min_unit = Securite::checkDataFormat($iv_duree_min_unit, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Duree maximale */
		public function setDuree_max($iv_duree_max) {
			/* Database attributes control */
			$lv_datatype = 'smallint(4) unsigned';
			try {
				$this->duree_max = Securite::checkDataFormat($iv_duree_max, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de la duree maximale */
		public function setDuree_max_unit($iv_duree_max_unit) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->duree_max_unit = Securite::checkDataFormat($iv_duree_max_unit, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de debut minimale */
		public function setHeure_debut_min($iv_heure_debut_min) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->heure_debut_min = Securite::checkDataFormat($iv_heure_debut_min, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de debut maximale */
		public function setHeure_debut_max($iv_heure_debut_max) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->heure_debut_max = Securite::checkDataFormat($iv_heure_debut_max, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de fin minimale */
		public function setHeure_fin_min($iv_heure_fin_min) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->heure_fin_min = Securite::checkDataFormat($iv_heure_fin_min, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de fin maximale */
		public function setHeure_fin_max($iv_heure_fin_max) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->heure_fin_max = Securite::checkDataFormat($iv_heure_fin_max, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Pause */
		public function setPause($iv_pause) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->pause = Securite::checkDataFormat($iv_pause, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de debut de la pause */
		public function setPause_debut($iv_pause_debut) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->pause_debut = Securite::checkDataFormat($iv_pause_debut, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure de fin de la pause */
		public function setPause_fin($iv_pause_fin) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->pause_fin = Securite::checkDataFormat($iv_pause_fin, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Quantite minmale */
		public function setQuantite_min($iv_quantite_min) {
			/* Database attributes control */
			$lv_datatype = 'float';
			try {
				$this->quantite_min = Securite::checkDataFormat($iv_quantite_min, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de quantite minimale */
		public function setQuantite_min_unit($iv_quantite_min_unit) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->quantite_min_unit = Securite::checkDataFormat($iv_quantite_min_unit, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Quantite maximale */
		public function setQuantite_max($iv_quantite_max) {
			/* Database attributes control */
			$lv_datatype = 'float';
			try {
				$this->quantite_max = Securite::checkDataFormat($iv_quantite_max, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de quantite maximale */
		public function setQuantite_max_unit($iv_quantite_max_unit) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->quantite_max_unit = Securite::checkDataFormat($iv_quantite_max_unit, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Nombre de reservations simultanees */
		public function setSimultane($iv_simultane) {
			/* Database attributes control */
			$lv_datatype = 'smallint(4) unsigned';
			try {
				$this->simultane = Securite::checkDataFormat($iv_simultane, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Acces aux personnes handicapees */
		public function setHandicap($iv_handicap) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->handicap = Securite::checkDataFormat($iv_handicap, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Animaux acceptes */
		public function setAnimaux($iv_animaux) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->animaux = Securite::checkDataFormat($iv_animaux, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Fumeurs acceptes */
		public function setFumeur($iv_fumeur) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->fumeur = Securite::checkDataFormat($iv_fumeur, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Condition d'age */
		public function setAge($iv_age) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->age = Securite::checkDataFormat($iv_age, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Certificat medical requis */
		public function setCertif($iv_certif) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->certif = Securite::checkDataFormat($iv_certif, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Permis de conduire requis */
		public function setPermis($iv_permis) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->permis = Securite::checkDataFormat($iv_permis, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Linge de maison fourni */
		public function setLinge_maison($iv_linge_maison) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->linge_maison = Securite::checkDataFormat($iv_linge_maison, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Serviettes de bain fournies */
		public function setServiette($iv_serviette) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->serviette = Securite::checkDataFormat($iv_serviette, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Lave linge */
		public function setLave_linge($iv_lave_linge) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->lave_linge = Securite::checkDataFormat($iv_lave_linge, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Lave vaisselle */
		public function setLave_vaisselle($iv_lave_vaisselle) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->lave_vaisselle = Securite::checkDataFormat($iv_lave_vaisselle, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Seche cheveux */
		public function setSeche_cheveux($iv_seche_cheveux) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->seche_cheveux = Securite::checkDataFormat($iv_seche_cheveux, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Fer a repasser */
		public function setFer_repasser($iv_fer_repasser) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->fer_repasser = Securite::checkDataFormat($iv_fer_repasser, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Acces internet */
		public function setInternet($iv_internet) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->internet = Securite::checkDataFormat($iv_internet, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Lit bebe */
		public function setLit_bebe($iv_lit_bebe) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->lit_bebe = Securite::checkDataFormat($iv_lit_bebe, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Table a langer */
		public function setTable_langer($iv_table_langer) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->table_langer = Securite::checkDataFormat($iv_table_langer, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Piscine */
		public function setPiscine($iv_piscine) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->piscine = Securite::checkDataFormat($iv_piscine, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Parking */
		public function setParking($iv_parking) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->parking = Securite::checkDataFormat($iv_parking, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Ascenseur */
		public function setAscenseur($iv_ascenseur) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->ascenseur = Securite::checkDataFormat($iv_ascenseur, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Materiel fourni */
		public function setMateriel($iv_materiel) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->materiel = Securite::checkDataFormat($iv_materiel, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Repas fourni le matin */
		public function setRepas_matin($iv_repas_matin) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_matin = Securite::checkDataFormat($iv_repas_matin, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Repas fourni le midi */
		public function setRepas_midi($iv_repas_midi) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_midi = Securite::checkDataFormat($iv_repas_midi, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Repas fourni le soir */
		public function setRepas_soir($iv_repas_soir) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_soir = Securite::checkDataFormat($iv_repas_soir, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix */
		public function setPrix($iv_prix) {
			/* Database attributes control */
			$lv_datatype = 'float unsigned';
			try {
				$this->prix = Securite::checkDataFormat($iv_prix, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix enfant */
		public function setPrix_enfant($iv_prix_enfant) {
			/* Database attributes control */
			$lv_datatype = 'float unsigned';
			try {
				$this->prix_enfant = Securite::checkDataFormat($iv_prix_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de prix */
		public function setPrix_unite($iv_prix_unite) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->prix_unite = Securite::checkDataFormat($iv_prix_unite, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
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
		/* Modalites de reservation. 1 = Ouvert a tous. 0 = par mes sejours */
		public function setModalite_resa($iv_modalite_resa) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->modalite_resa = Securite::checkDataFormat($iv_modalite_resa, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Taux de commission */
		public function setTaux_commission($iv_taux_commission) {
			/* Database attributes control */
			$lv_datatype = 'float';
			try {
				$this->taux_commission = Securite::checkDataFormat($iv_taux_commission, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Jour par defaut */
		public function setJour_defaut($iv_jour_defaut) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->jour_defaut = Securite::checkDataFormat($iv_jour_defaut, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Heure par defaut */
		public function setHeure_defaut($iv_heure_defaut) {
			/* Database attributes control */
			$lv_datatype = 'time';
			try {
				$this->heure_defaut = Securite::checkDataFormat($iv_heure_defaut, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Delai de prevenance */
		public function setPrevenance($iv_prevenance) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->prevenance = Securite::checkDataFormat($iv_prevenance, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Intervalle entre deux reservations */
		public function setIntervalle($iv_intervalle) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->intervalle = Securite::checkDataFormat($iv_intervalle, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}

		/* Depending object, class Type_service */
		public function setO_type_service($io_type_service) {
			if(!is_object($io_type_service)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_type_service'));
				return;
			}

			/* Check class */
			if(get_class($io_type_service) != 'Cust_type_service' && get_class($io_type_service) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_type_service', get_class($io_type_service)));
			} elseif (get_class($io_type_service) == 'Cust_type_service') {
				/* Object is Cust_type_service, direct assignment */
				$this->o_type_service = $io_type_service;
				$this->o_type_service->setType($this->getType());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_type_service as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_type_service = new Cust_type_service($lt_data);
			}
		}

		/* Depending object, class Cat_service */
		public function setO_cat_service($io_cat_service) {
			if(!is_object($io_cat_service)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_cat_service'));
				return;
			}

			/* Check class */
			if(get_class($io_cat_service) != 'Cust_cat_service' && get_class($io_cat_service) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_cat_service', get_class($io_cat_service)));
			} elseif (get_class($io_cat_service) == 'Cust_cat_service') {
				/* Object is Cust_cat_service, direct assignment */
				$this->o_cat_service = $io_cat_service;
				$this->o_cat_service->setCategorie($this->getCategorie());
				$this->o_cat_service->setType_service($this->getType());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_cat_service as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_cat_service = new Cust_cat_service($lt_data);
			}
		}

		/* Depending object, class Sous_cat_service */
		public function setO_sous_cat_service($io_sous_cat_service) {
			if(!is_object($io_sous_cat_service)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_sous_cat_service'));
				return;
			}

			/* Check class */
			if(get_class($io_sous_cat_service) != 'Cust_sous_cat_service' && get_class($io_sous_cat_service) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_sous_cat_service', get_class($io_sous_cat_service)));
			} elseif (get_class($io_sous_cat_service) == 'Cust_sous_cat_service') {
				/* Object is Cust_sous_cat_service, direct assignment */
				$this->o_sous_cat_service = $io_sous_cat_service;
				$this->o_sous_cat_service->setCategorie($this->getCategorie());
				$this->o_sous_cat_service->setSous_cat($this->getSous_cat());
				$this->o_sous_cat_service->setType_service($this->getType());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_sous_cat_service as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_sous_cat_service = new Cust_sous_cat_service($lt_data);
			}
		}

		/* Depending object, class Adresse */
		public function setO_adresse($io_adresse) {
			if(!is_object($io_adresse)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_adresse'));
				return;
			}

			/* Check class */
			if(get_class($io_adresse) != 'Adresse' && get_class($io_adresse) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Adresse', get_class($io_adresse)));
			} elseif (get_class($io_adresse) == 'Adresse') {
				/* Object is Adresse, direct assignment */
				$this->o_adresse = $io_adresse;
				$this->o_adresse->setUid($this->getAdresse());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_adresse as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_adresse = new Adresse($lt_data);
			}
		}

		/* Depending object, class Unite_duree_min */
		public function setO_unite_duree_min($io_unite_duree_min) {
			if(!is_object($io_unite_duree_min)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_duree_min'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_duree_min) != 'Cust_unite' && get_class($io_unite_duree_min) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_duree_min)));
			} elseif (get_class($io_unite_duree_min) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_duree_min = $io_unite_duree_min;
				$this->o_unite_duree_min->setUnite($this->getDuree_min_unit());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_duree_min as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_duree_min = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Unite_duree_max */
		public function setO_unite_duree_max($io_unite_duree_max) {
			if(!is_object($io_unite_duree_max)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_duree_max'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_duree_max) != 'Cust_unite' && get_class($io_unite_duree_max) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_duree_max)));
			} elseif (get_class($io_unite_duree_max) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_duree_max = $io_unite_duree_max;
				$this->o_unite_duree_max->setUnite($this->getDuree_max_unit());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_duree_max as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_duree_max = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Unite_quantite_min */
		public function setO_unite_quantite_min($io_unite_quantite_min) {
			if(!is_object($io_unite_quantite_min)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_quantite_min'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_quantite_min) != 'Cust_unite' && get_class($io_unite_quantite_min) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_quantite_min)));
			} elseif (get_class($io_unite_quantite_min) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_quantite_min = $io_unite_quantite_min;
				$this->o_unite_quantite_min->setUnite($this->getQuantite_min_unit());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_quantite_min as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_quantite_min = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Unite_quantite_max */
		public function setO_unite_quantite_max($io_unite_quantite_max) {
			if(!is_object($io_unite_quantite_max)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_quantite_max'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_quantite_max) != 'Cust_unite' && get_class($io_unite_quantite_max) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_quantite_max)));
			} elseif (get_class($io_unite_quantite_max) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_quantite_max = $io_unite_quantite_max;
				$this->o_unite_quantite_max->setUnite($this->getQuantite_max_unit());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_quantite_max as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_quantite_max = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Devise */
		public function setO_devise($io_devise) {
			if(!is_object($io_devise)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_devise'));
				return;
			}

			/* Check class */
			if(get_class($io_devise) != 'Cust_devise' && get_class($io_devise) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_devise', get_class($io_devise)));
			} elseif (get_class($io_devise) == 'Cust_devise') {
				/* Object is Cust_devise, direct assignment */
				$this->o_devise = $io_devise;
				$this->o_devise->setDevise($this->getDevise());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_devise as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_devise = new Cust_devise($lt_data);
			}
		}

		/* Depending object, class Jour */
		public function setO_jour($io_jour) {
			if(!is_object($io_jour)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_jour'));
				return;
			}

			/* Check class */
			if(get_class($io_jour) != 'Cust_jour' && get_class($io_jour) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_jour', get_class($io_jour)));
			} elseif (get_class($io_jour) == 'Cust_jour') {
				/* Object is Cust_jour, direct assignment */
				$this->o_jour = $io_jour;
				$this->o_jour->setJour($this->getJour_defaut());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_jour as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_jour = new Cust_jour($lt_data);
			}
		}

		/* Depending object, class Avis */
		public function setT_avis(Array $it_avis) {
			$this->t_avis = [];
			foreach ($it_avis as $io_avis) {
				$this->setO_avis($io_avis);
			}
		}

		public function setO_avis($io_avis) {
			if(!is_object($io_avis)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_avis'));
				return;
			}

			/* Check class */
			if(get_class($io_avis) != 'Service_avis' && get_class($io_avis) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service_avis', get_class($io_avis)));
			} elseif (get_class($io_avis) == 'Service_avis') {
				/* Object is Service_avis, direct assignment */
				$this->t_avis[] = $io_avis;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_avis as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_avis[] = new Service_avis($lt_data);
			}
		}

		/* Depending object, class Dispo */
		public function setT_dispo(Array $it_dispo) {
			$this->t_dispo = [];
			foreach ($it_dispo as $io_dispo) {
				$this->setO_dispo($io_dispo);
			}
		}

		public function setO_dispo($io_dispo) {
			if(!is_object($io_dispo)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_dispo'));
				return;
			}

			/* Check class */
			if(get_class($io_dispo) != 'Dispo' && get_class($io_dispo) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Dispo', get_class($io_dispo)));
			} elseif (get_class($io_dispo) == 'Dispo') {
				/* Object is Dispo, direct assignment */
				$this->t_dispo[] = $io_dispo;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_dispo as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_dispo[] = new Dispo($lt_data);
			}
		}

		public function setT_service_dispo(Array $it_service_dispo) {
			$this->t_service_dispo = [];
			foreach ($it_service_dispo as $io_service_dispo) {
				$this->setO_service_dispo($io_service_dispo);
			}
		}

		public function setO_service_dispo($io_service_dispo) {
			if(!is_object($io_service_dispo)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_service_dispo'));
			}

			/* Check class */
			if(get_class($io_service_dispo) != 'Service_dispo' && get_class($io_service_dispo) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service_dispo', get_class($io_service_dispo)));
			} elseif (get_class($io_service_dispo) == 'Service_dispo') {
				/* Object is Service_dispo, direct assignment */
				$this->t_service_dispo[] = $io_service_dispo;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_service_dispo as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_service_dispo[] = new Service_dispo($lt_data);
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
			if(get_class($io_texte) != 'Service_t' && get_class($io_texte) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service_t', get_class($io_texte)));
			} elseif (get_class($io_texte) == 'Service_t') {
				/* Object is Service_t, direct assignment */
				$this->t_texte[] = $io_texte;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_texte as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_texte[] = new Service_t($lt_data);
			}
		}

		/* Depending object, class Media */
		public function setT_media(Array $it_media) {
			$this->t_media = [];
			foreach ($it_media as $io_media) {
				$this->setO_media($io_media);
			}
		}

		public function setO_media($io_media) {
			if(!is_object($io_media)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_media'));
				return;
			}

			/* Check class */
			if(get_class($io_media) != 'Media' && get_class($io_media) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_media)));
			} elseif (get_class($io_media) == 'Media') {
				/* Object is Media, direct assignment */
				$this->t_media[] = $io_media;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_media as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_media[] = new Media($lt_data);
			}
		}

		public function setT_service_media(Array $it_service_media) {
			$this->t_service_media = [];
			foreach ($it_service_media as $io_service_media) {
				$this->setO_service_media($io_service_media);
			}
		}

		public function setO_service_media($io_service_media) {
			if(!is_object($io_service_media)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_service_media'));
			}

			/* Check class */
			if(get_class($io_service_media) != 'Service_media' && get_class($io_service_media) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service_media', get_class($io_service_media)));
			} elseif (get_class($io_service_media) == 'Service_media') {
				/* Object is Service_media, direct assignment */
				$this->t_service_media[] = $io_service_media;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_service_media as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_service_media[] = new Service_media($lt_data);
			}
		}

		/* Depending object, class Sejour */
		public function setT_sejour(Array $it_sejour) {
			$this->t_sejour = [];
			foreach ($it_sejour as $io_sejour) {
				$this->setO_sejour($io_sejour);
			}
		}

		public function setO_sejour($io_sejour) {
			if(!is_object($io_sejour)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_sejour'));
				return;
			}

			/* Check class */
			if(get_class($io_sejour) != 'Sejour' && get_class($io_sejour) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour', get_class($io_sejour)));
			} elseif (get_class($io_sejour) == 'Sejour') {
				/* Object is Sejour, direct assignment */
				$this->t_sejour[] = $io_sejour;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_sejour as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_sejour[] = new Sejour($lt_data);
			}
		}

		public function setT_sejour_service(Array $it_sejour_service) {
			$this->t_sejour_service = [];
			foreach ($it_sejour_service as $io_sejour_service) {
				$this->setO_sejour_service($io_sejour_service);
			}
		}

		public function setO_sejour_service($io_sejour_service) {
			if(!is_object($io_sejour_service)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_sejour_service'));
			}

			/* Check class */
			if(get_class($io_sejour_service) != 'Sejour_service' && get_class($io_sejour_service) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour_service', get_class($io_sejour_service)));
			} elseif (get_class($io_sejour_service) == 'Sejour_service') {
				/* Object is Sejour_service, direct assignment */
				$this->t_sejour_service[] = $io_sejour_service;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_sejour_service as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_sejour_service[] = new Sejour_service($lt_data);
			}
		}

		/* Depending object, class Degressivites */
		public function setT_degressivites(Array $it_degressivites) {
			$this->t_degressivites = [];
			foreach ($it_degressivites as $io_degressivites) {
				$this->setO_degressivites($io_degressivites);
			}
		}

		public function setO_degressivites($io_degressivites) {
			if(!is_object($io_degressivites)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_degressivites'));
				return;
			}

			/* Check class */
			if(get_class($io_degressivites) != 'Service_degressivite' && get_class($io_degressivites) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service_degressivite', get_class($io_degressivites)));
			} elseif (get_class($io_degressivites) == 'Service_degressivite') {
				/* Object is Service_degressivite, direct assignment */
				$this->t_degressivites[] = $io_degressivites;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_degressivites as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_degressivites[] = new Service_degressivite($lt_data);
			}
		}

		/* Depending object, class Delai_prevenance */
		public function setO_delai_prevenance($io_delai_prevenance) {
			if(!is_object($io_delai_prevenance)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_delai_prevenance'));
				return;
			}

			/* Check class */
			if(get_class($io_delai_prevenance) != 'Cust_delai_prevenance' && get_class($io_delai_prevenance) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_delai_prevenance', get_class($io_delai_prevenance)));
			} elseif (get_class($io_delai_prevenance) == 'Cust_delai_prevenance') {
				/* Object is Cust_delai_prevenance, direct assignment */
				$this->o_delai_prevenance = $io_delai_prevenance;
				$this->o_delai_prevenance->setUid($this->getPrevenance());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_delai_prevenance as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_delai_prevenance = new Cust_delai_prevenance($lt_data);
			}
		}

		/* Depending object, class Intervalle_reservation */
		public function setO_intervalle_reservation($io_intervalle_reservation) {
			if(!is_object($io_intervalle_reservation)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_intervalle_reservation'));
				return;
			}

			/* Check class */
			if(get_class($io_intervalle_reservation) != 'Cust_intervalle_reservation' && get_class($io_intervalle_reservation) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_intervalle_reservation', get_class($io_intervalle_reservation)));
			} elseif (get_class($io_intervalle_reservation) == 'Cust_intervalle_reservation') {
				/* Object is Cust_intervalle_reservation, direct assignment */
				$this->o_intervalle_reservation = $io_intervalle_reservation;
				$this->o_intervalle_reservation->setUid($this->getIntervalle());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_intervalle_reservation as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_intervalle_reservation = new Cust_intervalle_reservation($lt_data);
			}
		}


		/**
			LOAD RELATED OBJECTS
		*/

		public function loadType_service(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['type'] = $this->type;
			/* Load data */
			$lt_results = Cust_type_serviceManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_type_service($lt_results[0]);
			} else {
				$this->setO_type_service(new Cust_type_service($lt_parameters));
			}
		}

		public function loadCat_service(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['categorie'] = $this->categorie;
			$lt_parameters['type_service'] = $this->type;
			/* Load data */
			$lt_results = Cust_cat_serviceManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_cat_service($lt_results[0]);
			} else {
				$this->setO_cat_service(new Cust_cat_service($lt_parameters));
			}
		}

		public function loadSous_cat_service(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['categorie'] = $this->categorie;
			$lt_parameters['sous_cat'] = $this->sous_cat;
			$lt_parameters['type_service'] = $this->type;
			/* Load data */
			$lt_results = Cust_sous_cat_serviceManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_sous_cat_service($lt_results[0]);
			} else {
				$this->setO_sous_cat_service(new Cust_sous_cat_service($lt_parameters));
			}
		}

		public function loadAdresse(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->adresse;
			/* Load data */
			$lt_results = AdresseManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_adresse($lt_results[0]);
			} else {
				$this->setO_adresse(new Adresse($lt_parameters));
			}
		}

		public function loadUnite_duree_min(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->duree_min_unit;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_duree_min($lt_results[0]);
			} else {
				$this->setO_unite_duree_min(new Cust_unite($lt_parameters));
			}
		}

		public function loadUnite_duree_max(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->duree_max_unit;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_duree_max($lt_results[0]);
			} else {
				$this->setO_unite_duree_max(new Cust_unite($lt_parameters));
			}
		}

		public function loadUnite_quantite_min(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->quantite_min_unit;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_quantite_min($lt_results[0]);
			} else {
				$this->setO_unite_quantite_min(new Cust_unite($lt_parameters));
			}
		}

		public function loadUnite_quantite_max(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->quantite_max_unit;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_quantite_max($lt_results[0]);
			} else {
				$this->setO_unite_quantite_max(new Cust_unite($lt_parameters));
			}
		}

		public function loadDevise(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['devise'] = $this->devise;
			/* Load data */
			$lt_results = Cust_deviseManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_devise($lt_results[0]);
			} else {
				$this->setO_devise(new Cust_devise($lt_parameters));
			}
		}

		public function loadJour(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['jour'] = $this->jour_defaut;
			/* Load data */
			$lt_results = Cust_jourManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_jour($lt_results[0]);
			} else {
				$this->setO_jour(new Cust_jour($lt_parameters));
			}
		}

		public function loadAvis(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service_id'] = $this->uid;
			/* Load data */
			$lt_results = Service_avisManager::get($lt_parameters, [], $it_expand);

			$this->setT_avis($lt_results);
		}

		public function loadDispo(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service'] = $this->uid;
			/* Load data */
			$this->setT_service_dispo(Service_dispoManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_service_dispo as $lo_service_dispo) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_service_dispo->getDispo();
				/* Load data from linked table */
				$lt_results_tmp = DispoManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_dispo($lt_results);
		}

		public function loadTexte(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service_id'] = $this->uid;
			/* Load data */
			$lt_results = Service_tManager::get($lt_parameters, [], $it_expand);

			$this->setT_texte($lt_results);
		}

		public function loadMedia(Array $it_expand = []) {
			/**
				Debut: suppression expand "service" pour eviter boucle infinie
			*/
				$lv_index = array_search('service', $it_expand);
				if (is_int($lv_index)) {
					unset($it_expand[$lv_index]);
				}
			/**
				Fin: suppression expand "service" pour eviter boucle infinie
			*/
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service_uid'] = $this->uid;
			/* Load data */
			$this->setT_service_media(Service_mediaManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_service_media as $lo_service_media) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_service_media->getMedia_uid();
				/* Load data from linked table */
				$lt_results_tmp = MediaManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_media($lt_results);
		}

		public function loadSejour(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service'] = $this->uid;
			/* Load data */
			$this->setT_sejour_service(Sejour_serviceManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_sejour_service as $lo_sejour_service) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_sejour_service->getSejour();
				/* Load data from linked table */
				$lt_results_tmp = SejourManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_sejour($lt_results);
		}

		public function loadDegressivites(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['service'] = $this->uid;
			/* Load data */
			$lt_results = Service_degressiviteManager::get($lt_parameters, [], $it_expand);

			$this->setT_degressivites($lt_results);
		}

		public function loadDelai_prevenance(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->prevenance;
			/* Load data */
			$lt_results = Cust_delai_prevenanceManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_delai_prevenance($lt_results[0]);
			} else {
				$this->setO_delai_prevenance(new Cust_delai_prevenance($lt_parameters));
			}
		}

		public function loadIntervalle_reservation(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->intervalle;
			/* Load data */
			$lt_results = Cust_intervalle_reservationManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_intervalle_reservation($lt_results[0]);
			} else {
				$this->setO_intervalle_reservation(new Cust_intervalle_reservation($lt_parameters));
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
				$lt_parameters['uid'] = $this->uid;
				$lt_results = ServiceManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_service = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_service = $this->_update($lt_results[0], $cv_success);
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
			$this->saveAdresse($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveDispo($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveMedia($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveSejour($cv_success);
			if (!$cv_success) {
				return $this;
			}
			if ($iv_mode == 'I') {
				/* No record, create a new one */
				$lo_service = ServiceManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUid($lo_service->getUid());
				$this->setUri();
			} else {
				/* Update record */
				$lo_service = ServiceManager::update($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
			}

			/* Save depending objects */
			$this->saveAvis($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveService_dispo($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveTexte($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveService_media($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveSejour_service($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveDegressivites($cv_success);
			if (!$cv_success) {
				return $this;
			}

			return $this;

		}

		public function saveAdresse(&$cv_success) {
			/* Save linked objects of type Adresse */
			if ($this->o_adresse != null) {
				$this->o_adresse->setUid($this->getAdresse());
				$lo_adresse_new = $this->o_adresse->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setAdresse($lo_adresse_new->getUid());
				$this->setO_adresse($lo_adresse_new);
			}
		}

		public function saveAvis(&$cv_success) {
			/* Save linked objects of type Service_avis */
			$lt_avis = [];
			foreach ($this->t_avis as $lo_avis) {
				/* Define link between object values */
				$lo_avis->setService_id($this->uid);
				$lt_avis[] = $lo_avis->save($cv_success);
			}
			$this->setT_avis($lt_avis);
		}

		public function saveDispo(&$cv_success) {
			/* Save linked objects of type Dispo */
			foreach ($this->t_dispo as $lo_dispo) {
				$lo_dispo_new = $lo_dispo->save($cv_success);
				/* Search link within pivot table */
				$lt_parameters = [];
				$lt_parameters['dispo'] = $lo_dispo_new->getUid();
				$lt_parameters['service'] = $this->uid;
				$lt_results = Service_dispoManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_service_dispo[] = new Service_dispo($lt_parameters);
				}
			}
		}

		public function saveTexte(&$cv_success) {
			/* Save linked objects of type Service_t */
			$lt_texte = [];
			foreach ($this->t_texte as $lo_texte) {
				/* Define link between object values */
				$lo_texte->setService_id($this->uid);
				$lt_texte[] = $lo_texte->save($cv_success);
			}
			$this->setT_texte($lt_texte);
		}

		public function saveMedia(&$cv_success) {
			/* Save linked objects of type Media */
			foreach ($this->t_media as $lo_media) {
				$lo_media_new = $lo_media->save($cv_success);
				/* Search link within pivot table */
				$lt_parameters = [];
				$lt_parameters['media_uid'] = $lo_media_new->getUid();
				$lt_parameters['service_uid'] = $this->uid;
				$lt_results = Service_mediaManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_service_media[] = new Service_media($lt_parameters);
				}
			}
		}

		public function saveSejour(&$cv_success) {
			/* Save linked objects of type Sejour */
			foreach ($this->t_sejour as $lo_sejour) {
				$lo_sejour_new = $lo_sejour->save($cv_success);
				/* Search link within pivot table */
				$lt_parameters = [];
				$lt_parameters['sejour'] = $lo_sejour_new->getUid();
				$lt_parameters['service'] = $this->uid;
				$lt_results = Sejour_serviceManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_sejour_service[] = new Sejour_service($lt_parameters);
				}
			}
		}

		public function saveDegressivites(&$cv_success) {
			/* Save linked objects of type Service_degressivite */
			$lt_degressivites = [];
			foreach ($this->t_degressivites as $lo_degressivites) {
				/* Define link between object values */
				$lo_degressivites->setService($this->uid);
				$lt_degressivites[] = $lo_degressivites->save($cv_success);
			}
			$this->setT_degressivites($lt_degressivites);
		}

		public function saveService_dispo(&$cv_success) {
			$lt_service_dispo = [];
			foreach ($this->t_service_dispo as $lo_service_dispo) {
				$lo_service_dispo->setService($this->uid);
				$lt_service_dispo[] = $lo_service_dispo->save($cv_success);
			}
			$this->setT_service_dispo($lt_service_dispo);
		}

		public function saveService_media(&$cv_success) {
			$lt_service_media = [];
			foreach ($this->t_service_media as $lo_service_media) {
				$lo_service_media->setService_uid($this->uid);
				$lt_service_media[] = $lo_service_media->save($cv_success);
			}
			$this->setT_service_media($lt_service_media);
		}

		public function saveSejour_service(&$cv_success) {
			$lt_sejour_service = [];
			foreach ($this->t_sejour_service as $lo_sejour_service) {
				$lo_sejour_service->setService($this->uid);
				$lt_sejour_service[] = $lo_sejour_service->save($cv_success);
			}
			$this->setT_sejour_service($lt_sejour_service);
		}


		/**
			OBJECTS DELETION
		*/

		public function delete(&$cv_success=true) {

			/* Delete linked objects */
			$this->deleteAvis($cv_success);
			$this->deleteService_dispo($cv_success);
			$this->deleteTexte($cv_success);
			$this->deleteService_media($cv_success);
			$this->deleteSejour_service($cv_success);
			$this->deleteDegressivites($cv_success);

			/* Delete this object */
			ServiceManager::delete($this, $cv_success);
		}

		public function deleteAvis(&$cv_success) {
			$this->loadAvis();
			foreach ($this->t_avis as $lo_avis) {
				$lo_avis->delete($cv_success);
			}
		}

		public function deleteService_dispo(&$cv_success) {
			$this->loadDispo();
			foreach ($this->t_service_dispo as $lo_service_dispo) {
				$lo_service_dispo->delete($cv_success);
			}
		}

		public function deleteTexte(&$cv_success) {
			$this->loadTexte();
			foreach ($this->t_texte as $lo_texte) {
				$lo_texte->delete($cv_success);
			}
		}

		public function deleteService_media(&$cv_success) {
			$this->loadMedia();
			foreach ($this->t_service_media as $lo_service_media) {
				$lo_service_media->delete($cv_success);
			}
		}

		public function deleteSejour_service(&$cv_success) {
			$this->loadSejour();
			foreach ($this->t_sejour_service as $lo_sejour_service) {
				$lo_sejour_service->delete($cv_success);
			}
		}

		public function deleteDegressivites(&$cv_success) {
			$this->loadDegressivites();
			foreach ($this->t_degressivites as $lo_degressivites) {
				$lo_degressivites->delete($cv_success);
			}
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Service $io_service) {
			self::$T_BUFFER[] = $io_service;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"service":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "service" : [ ';
			foreach (self::$T_BUFFER as $lo_service) {
				$ev_json.= $lo_service->json();
				if ($lo_service !== end(self::$T_BUFFER)) {
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