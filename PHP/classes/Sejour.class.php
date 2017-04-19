<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour.class.php
/*	Auteur : Antoine Alleard
/*	Date : 21/02/2017 10:55:44
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	require_once(PHP_PATH.'classes/managers/SejourManager.class.php');
	require_once(PHP_PATH.'classes/Adresse.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_unite.class.php');
	require_once(PHP_PATH.'classes/Cust_devise.class.php');
	require_once(PHP_PATH.'classes/Cust_type_annulation.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Sejour_avis.class.php');
	require_once(PHP_PATH.'classes/Dispo.class.php');
	require_once(PHP_PATH.'classes/Sejour_dispo.class.php');
	require_once(PHP_PATH.'classes/Service.class.php');
	require_once(PHP_PATH.'classes/Sejour_service.class.php');
	require_once(PHP_PATH.'classes/Sejour_t.class.php');
	require_once(PHP_PATH.'classes/Media.class.php');
	require_once(PHP_PATH.'classes/Sejour_media.class.php');
	require_once(PHP_PATH.'classes/Recherche.class.php');
	require_once(PHP_PATH.'classes/Recherche_sejour.class.php');

	class Sejour extends MainObject {

		/**
			ATTRIBUTES
		*/

		/* Identifiant du sejour */
		private $uid = 0;		/* Primary key */
		/* Identifiant utilisateur */
		private $user_id = 0;
		/* Date de creation du sejour */
		private $crea_date = '';
		/* Sejour supprime ? */
		private $suppression = '';
		/* Date de suppression */
		private $date_suppr = '';
		/* Adresse du sejour */
		private $adresse = 0;
		/* Capacite minimale */
		private $capa_mini = 0;
		/* Capacite maximale */
		private $capa_maxi = 0;
		/* Duree minimale */
		private $duree_mini = 0;
		/* Duree maximale */
		private $duree_maxi = 0;
		/* Unite de duree */
		private $duree_unite = '';
		/* Delai minimale de reservation */
		private $delai = 0;
		/* Unite du delai */
		private $delai_unite = '';
		/* Intervalle de temps entre 2 sejours */
		private $intervalle = 0;
		/* Unite de l'intervalle */
		private $intervalle_unite = '';
		/* Possibilite d'hebergement autonome */
		private $heberg_autonome = '';
		/* Repas autonome ? */
		private $repas_autonome = '';
		/* Provisions matin ? */
		private $repas_prov_matin = '';
		/* Provision midi ? */
		private $repas_prov_midi = '';
		/* Provision soir ? */
		private $repas_prov_soir = '';
		/* Matin prepare ? */
		private $repas_cuis_matin = '';
		/* Dejeune prepare ? */
		private $repas_cuis_midi = '';
		/* Diner prepare ? */
		private $repas_cuis_soir = '';
		/* Prix provision matin pour un adulte */
		private $prix_prov_matin_adulte = '';
		/* Prix provision midi pour un adulte */
		private $prix_prov_midi_adulte = '';
		/* Prix provision soir pour un adulte */
		private $prix_prov_soir_adulte = '';
		/* Prix repas matin pour un adulte */
		private $prix_repas_matin_adulte = '';
		/* Prix repas midi pour un adulte */
		private $prix_repas_midi_adulte = '';
		/* Prix repas soir pour un adulte */
		private $prix_repas_soir_adulte = '';
		/* Prix provision matin pour un enfant */
		private $prix_prov_matin_enfant = '';
		/* Prix provision midi pour un enfant */
		private $prix_prov_midi_enfant = '';
		/* Prix provision soir pour un enfant */
		private $prix_prov_soir_enfant = '';
		/* Prix repas matin pour un enfant */
		private $prix_repas_matin_enfant = '';
		/* Prix repas midi pour un enfant */
		private $prix_repas_midi_enfant = '';
		/* Prix repas soir pour un enfant */
		private $prix_repas_soir_enfant = '';
		/* Devise */
		private $devise = '';
		/* Conditions d'annulation */
		private $annulation = 0;
		/* Photo de couverture */
		private $cover = 0;
		/* Photo de la section decouvrir */
		private $decouvrir = 0;
		/* Photo de la section manger */
		private $manger = 0;
		/* Photo de la section dormir */
		private $dormir = 0;
		/* Photo de la section bouger */
		private $bouger = 0;

		/* Linked object, class Adresse */
		private $o_adresse;
		/* Linked object, class Cust_unite */
		private $o_unite_duree;
		/* Linked object, class Cust_unite */
		private $o_unite_delai;
		/* Linked object, class Cust_unite */
		private $o_unite_intervalle;
		/* Linked object, class Cust_devise */
		private $o_devise;
		/* Linked object, class Cust_type_annulation */
		private $o_type_annulation;
		/* Linked object, class Media */
		private $o_cover;
		/* Linked object, class Media */
		private $o_decouvrir;
		/* Linked object, class Media */
		private $o_manger;
		/* Linked object, class Media */
		private $o_dormir;
		/* Linked object, class Media */
		private $o_bouger;
		/* Linked object, class Sejour_avis */
		private $t_avis = [];
		/* Linked object, class Dispo */
		private $t_dispo = [];
		private $t_sejour_dispo = []; /* Relation's pivot table */
		/* Linked object, class Service */
		private $t_service = [];
		private $t_sejour_service = []; /* Relation's pivot table */
		/* Linked object, class Sejour_t */
		private $t_texte = [];
		/* Linked object, class Media */
		private $t_media = [];
		private $t_sejour_media = []; /* Relation's pivot table */
		/* Linked object, class Recherche */
		private $t_recherche = [];
		private $t_recherche_sejour = []; /* Relation's pivot table */

		/* Object buffer */
		private static $T_BUFFER;

		/************************************************************************************************************************************/
		/* FUNCTIONAL METHODS - INSERT CODE HERE :)
		/************************************************************************************************************************************/

		// Champs calcules
		private $prix 			= 0;
		private $prix_personne  = 0;
		private $prix_services	= 0;
		private $frais_resa 	= 0;
		private $frais_dossier 	= 0;
		private $taxe_sejour	= 0;
		// Services prereserves
		private $t_prebooked_service = [];

		private function _calcBookingPrice(){

			// Lecture du parametrage des frais de reservation
			$lt_parameters = array(
				'type' => 1
			);
			$lt_frais = Cust_type_fraisManager::get($lt_parameters);
			$this->frais_resa = $this->prix_services * $lt_frais[0]->getVariable() / 100 + $lt_frais[0]->getFixe();

		}

		private function _calcBreakfastPriceFromSearch(Recherche $io_recherche) {
			// Calcul du prix du petit dejeuner en fonction des criteres de recherche
			$lv_adultes = $io_recherche->getAdultes();
			$lv_enfants = $io_recherche->getEnfants();

			if ($this->repas_prov_matin == 0 && $this->repas_cuis_matin == 0 || $io_recherche->getRepas_matin() == 0) {
				# Le petit dejeuner n'est pas disponible pour ce sejour ou n'est pas demande
				return 0;
			}

			// On aura le meme comportement si dans la recherche on demande a la fois cuisine / provisions, ou l'inverse
			if ($io_recherche->getRepas_provision() == $io_recherche->getRepas_cuisine()) {
				# Dans ce cas, la priorite sera faite sur le mode "provisions" car plus avantageux pour le voyageur

				if ($this->repas_prov_matin == 1) {
					return $lv_adultes * $this->prix_prov_matin_adulte + $lv_enfants * $this->prix_prov_matin_enfant;
				} else {
					return $lv_adultes * $this->prix_repas_matin_adulte + $lv_enfants * $this->prix_repas_matin_enfant;
				}

			} elseif ($io_recherche->getRepas_provision() == 1 && $this->repas_prov_matin == 1) {
				# Le voyageur souhaite avoir les provisions, pas les repas cuisines, et les provisions sont dispos pour le sejour
					
				return $lv_adultes * $this->prix_prov_matin_adulte + $lv_enfants * $this->prix_prov_matin_enfant;

			} elseif ($this->repas_cuis_matin == 1) {
				# Le voyageur souhaite avoir les repas cuisines, pas les provisions, et les repas sont dispos pour le sejour

				return $lv_adultes * $this->prix_repas_matin_adulte + $lv_enfants * $this->prix_repas_matin_enfant;

			}

			# Valeur de retour par defaut
			return 0;

		}

		private function _calcDinerPriceFromSearch(Recherche $io_recherche) {
			// Calcul du prix du diner en fonction des criteres de recherche
			$lv_adultes = $io_recherche->getAdultes();
			$lv_enfants = $io_recherche->getEnfants();

			if ($this->repas_prov_soir == 0 && $this->repas_cuis_soir == 0 || $io_recherche->getRepas_soir() == 0) {
				# Le petit dejeuner n'est pas disponible pour ce sejour ou n'est pas demande
				return 0;
			}

			// On aura le meme comportement si dans la recherche on demande a la fois cuisine / provisions, ou l'inverse
			if ($io_recherche->getRepas_provision() == $io_recherche->getRepas_cuisine()) {
				# Dans ce cas, la priorite sera faite sur le mode "provisions" car plus avantageux pour le voyageur

				if ($this->repas_prov_soir == 1) {
					return $lv_adultes * $this->prix_prov_soir_adulte + $lv_enfants * $this->prix_prov_soir_enfant;
				} else {
					return $lv_adultes * $this->prix_repas_soir_adulte + $lv_enfants * $this->prix_repas_soir_enfant;
				}

			} elseif ($io_recherche->getRepas_provision() == 1 && $this->repas_prov_soir == 1) {
				# Le voyageur souhaite avoir les provisions, pas les repas cuisines, et les provisions sont dispos pour le sejour
					
				return $lv_adultes * $this->prix_prov_soir_adulte + $lv_enfants * $this->prix_prov_soir_enfant;

			} elseif ($this->repas_cuis_soir == 1) {
				# Le voyageur souhaite avoir les repas cuisines, pas les provisions, et les repas sont dispos pour le sejour

				return $lv_adultes * $this->prix_repas_soir_adulte + $lv_enfants * $this->prix_repas_soir_enfant;

			}

			# Valeur de retour par defaut
			return 0;
		}

		private function _calcLunchPriceFromSearch(Recherche $io_recherche) {
			// Calcul du prix du dejeuner en fonction des criteres de recherche
			$lv_adultes = $io_recherche->getAdultes();
			$lv_enfants = $io_recherche->getEnfants();

			if ($this->repas_prov_midi == 0 && $this->repas_cuis_midi == 0 || $io_recherche->getRepas_midi() == 0) {
				# Le petit dejeuner n'est pas disponible pour ce sejour ou n'est pas demande
				return 0;
			}

			// On aura le meme comportement si dans la recherche on demande a la fois cuisine / provisions, ou l'inverse
			if ($io_recherche->getRepas_provision() == $io_recherche->getRepas_cuisine()) {
				# Dans ce cas, la priorite sera faite sur le mode "provisions" car plus avantageux pour le voyageur

				if ($this->repas_prov_midi == 1) {
					return $lv_adultes * $this->prix_prov_midi_adulte + $lv_enfants * $this->prix_prov_midi_enfant;
				} else {
					return $lv_adultes * $this->prix_repas_midi_adulte + $lv_enfants * $this->prix_repas_midi_enfant;
				}

			} elseif ($io_recherche->getRepas_provision() == 1 && $this->repas_prov_midi == 1) {
				# Le voyageur souhaite avoir les provisions, pas les repas cuisines, et les provisions sont dispos pour le sejour
					
				return $lv_adultes * $this->prix_prov_midi_adulte + $lv_enfants * $this->prix_prov_midi_enfant;

			} elseif ($this->repas_cuis_midi == 1) {
				# Le voyageur souhaite avoir les repas cuisines, pas les provisions, et les repas sont dispos pour le sejour

				return $lv_adultes * $this->prix_repas_midi_adulte + $lv_enfants * $this->prix_repas_midi_enfant;

			}

			# Valeur de retour par defaut
			return 0;
		}

		private function _calcMealPriceFromSearch(Recherche $io_recherche) {
			// Calcul du prix des repas en fonction des criteres de recherche
			$lv_prix  = 0;
			$lv_duree = $io_recherche->getDuree();

			$lv_prix += $this->_calcBreakfastPriceFromSearch($io_recherche);
			$lv_prix += $this->_calcLunchPriceFromSearch($io_recherche);
			$lv_prix += $this->_calcDinerPriceFromSearch($io_recherche);

			// Multiplication par la duree du sejour
			return $lv_prix * $lv_duree;

		}

		private function _calcSojournTax(){

			// Lecture du parametrage de la taxe de sejour

			$this->taxe_sejour = 0;
		}

		public function calculatePriceFromSearch(Recherche $io_recherche){
			/*
				Calcul du prix du sejour a partir d'une recherche.
				Le calcul se compose :
					- calcul du prix minimum des repas
					- calcul du prix des services prereserves
			*/
			$this->prix = 0;

			// Calcul du prix des services
			$this->_calcServicesPrice($io_recherche);

			// Calcul des frais de reservation
			$this->_calcBookingPrice();

			// Calcul des frais de dossier
			$this->_calcSojournTax();
 
			$this->prix = $this->prix_services +
						  $this->frais_resa	   +
						  $this->taxe_sejour;

			$this->prix_personne = $this->prix / ($io_recherche->getAdultes() + $io_recherche->getEnfants());

		}

		private function _calcServicesPrice(Recherche $io_recherche){
			// Calcul du prix des repas
			$this->prix_services += $this->_calcMealPriceFromSearch($io_recherche);
			
			// Calcul du prix des services prereserves
			$this->_getPrebookedServicesFromSearch($io_recherche);
			foreach ($this->t_prebooked_service as $lo_service) {
				$this->prix_services += $lo_service->calculatePriceFromSearch($io_recherche);
			}
		}

		private function _checkHebergCapacity(Recherche $io_recherche) {

			$lv_persons = $io_recherche->getAdultes() + $io_recherche->getEnfants();

			$lv_capacity = 0;
			foreach ($this->t_service as $lo_service) {
				// Addition des capacites des logements
				if ($lo_service->getType() == 1) {
					$lv_capacity += $lo_service->getCapa_max();
				}
			}
			if ($lv_capacity < $lv_persons) {
				return false;
			} else {
				return true;
			}
		}

		private function _checkSearchMealOptions(Recherche $io_recherche){

			// Demande de repas autonomes ?
			if($this->repas_autonome == 0 && $io_recherche->getRepas_autonome() == 1) return false;

			// Provisions de maniere generale
			// Le matin
			if($io_recherche->getRepas_matin() 				== 1 ){  // On souhaite le petit dejeuner
				if( ( $this->repas_prov_matin 		        == 0 &&
				      $this->repas_cuis_matin     	        == 0 )   // Aucune option petit dejeuner n'est proposee dans le sejour
					 ||
					( $io_recherche->getRepas_provision() == 1 && // On souhaite les provisions pour le petit dej seulement
					  $io_recherche->getRepas_cuisine()   == 0 &&
					  $this->repas_prov_matin              == 0 )  // Pas de provisions pour le petit dejeuner
					 ||
					( $io_recherche->getRepas_provision() == 0 && // On souhaite le petit dej cuisine seulement
					  $io_recherche->getRepas_cuisine()   == 1 &&
					  $this->repas_cuis_matin              == 0 )  // L'hote ne cuisine pas le petit dejeuner
				     ) return false;
			}

			// Le midi
			if($io_recherche->getRepas_midi() 			== 1 ){  // On souhaite le dejeuner

				if( ( $this->repas_prov_midi 		    == 0 &&
				      $this->repas_cuis_midi     	    == 0 )   // Aucune option dejeuner n'est proposee dans le sejour
					 ||
					( $io_recherche->getRepas_provision() == 1 && // On souhaite les provisions pour le dej seulement
					  $io_recherche->getRepas_cuisine()   == 0 &&
					  $this->repas_prov_midi            == 0 )  // Pas de provisions pour le dejeuner
					 ||
					( $io_recherche->getRepas_provision() == 0 && // On souhaite le dej cuisine seulement
					  $io_recherche->getRepas_cuisine()   == 1 &&
					  $this->repas_cuis_midi            == 0 )  // L'hote ne cuisine pas le dejeuner
				     ) return false;
			}

			// Le soir
			if($io_recherche->getRepas_soir() 			== 1 ){  // On souhaite le diner

				if( ( $this->repas_prov_soir 		    == 0 &&
				      $this->repas_cuis_soir     	    == 0 )   // Aucune option diner n'est proposee dans le sejour
					 ||
					( $io_recherche->getRepas_provision() == 1 && // On souhaite les provisions pour le diner seulement
					  $io_recherche->getRepas_cuisine()   == 0 &&
					  $this->repas_prov_soir            == 0 )  // Pas de provisions pour le diner
					 ||
					( $io_recherche->getRepas_provision() == 0 && // On souhaite le diner cuisine seulement
					  $io_recherche->getRepas_cuisine()   == 1 &&
					  $this->repas_cuis_soir            == 0 )  // L'hote ne cuisine pas le diner
				     ) return false;
			}

			// Aucun filtre n'exclut ce sejour des resultats
			return true;
		}

		private function _checkSearchServices(Recherche $io_recherche){
			// Chargement des services du sejour
			$this->loadService();
			// Si aucun element, on ne prend pas en compte le sejour
			if(count($this->t_service) == 0)return false;

			// On va verifier que les services correspondent aux criteres de recherche
			$lt_service_tmp = [];
			foreach ($this->t_service as $lo_service) {
				if ($lo_service->isSearchResult($io_recherche)) {
					$lt_service_tmp[] = $lo_service;
				}
			}

			// Verification du nombre de services restants
			if(count($lt_service_tmp) == 0)return false;
			// On ne garde que les services qui sont des resultats valables
			$this->t_service = $lt_service_tmp;

			// On verifie qu'il y a assez pour loger tout le monde
			if(!$this->_checkHebergCapacity($io_recherche)) return false;

			return true;
		}

		private function _create(&$cv_success=true) {
			# Insert your business rules here for object creation

			$this->crea_date = new DateTime();

			return $this->_save('I', $cv_success);
		}

		private function _getPrebookedHebergFromSearch(Recherche $io_recherche){
			$et_services = [];

			// Nombre de participants
			$lv_persons = $io_recherche->getAdultes() + $io_recherche->getEnfants();

			$lv_capacity = $lv_persons;
			foreach ($this->t_service as $lo_service) {
				if ($lo_service->getType() == 1) {

					$et_services[] = $lo_service;
					if ($lo_service->getCapa_max() < $lv_capacity) {
						// La taille du logement est inferieure a la demande,
						// On place autant de personne que l'on peut et on cherche un autre logement
						$lv_capacity -= $lo_service->getCapa_max();
					} else {
						// On a assez de place, sortie de boucle
						break;
					}
				}
			}

			return $et_services;
		}

		private function _getPrebookedOtherServicesFromSearch(Recherche $io_recherche) {
			$et_services = [];
			
			foreach ($this->t_service as $lo_service) {
				if ($lo_service->getType() == 3 || $lo_service->getType() == 4) {
					// Definition de la date par defaut dans la periode de recherche
					$lt_dates = DateFunctions::getDayDate($lo_service->getJour_defaut(), $io_recherche->getDate_from(), $io_recherche->getDate_to());
					foreach ($lt_dates as $lo_date) {
						# Il y a une date existante dans la periode voulue
						# Verification que le service est disponible a la dite date
						if ($lo_service->isDispo($lo_date)) {
							# Ajout du service aux services prereserves
							$et_services[] = $lo_service;
							break;
						}
					}
				}
			}

			return $et_services;
		}

		private function _getPrebookedServicesFromSearch(Recherche $io_recherche) {
			$this->t_prebooked_service = [];
			
			// S'il n'est pas possible de ne pas choisir de solution d'hebergement, on en selectionne de maniere optimale
			$lt_services = $this->_getPrebookedHebergFromSearch($io_recherche);
			foreach ($lt_services as $lo_service) {
				// On ajoute les hebergements preselectionnes
				$this->t_prebooked_service[] = $lo_service;
			}

			// Recherche des activites a prereserver
			$lt_services = $this->_getPrebookedOtherServicesFromSearch($io_recherche);
			foreach ($lt_services as $lo_service) {
				// On ajoute les hebergements preselectionnes
				$this->t_prebooked_service[] = $lo_service;
			}
		}

		public function getFrais_dossier(){
			return $this->frais_dossier;
		}

		public function getFrais_resa(){
			return $this->frais_resa;
		}

		public function getPrix(){
			return $this->prix;
		}

		public function getPrix_services(){
			return $this->prix_services;
		}

		public function getTaxe_sejour(){
			return $this->taxe_sejour;
		}

		private function _hasRequiredServices(Recherche $io_recherche){
			// Le but est de verifier qu'on repond au moins une fois a toutes les exigences de la recherche

			// Criteres sur les services demandes
			$lt_categories = $io_recherche->getT_categories();
			if (count($lt_categories) != 0) {
				$lv_heberg_ok = false;
				$lv_autres_ok = false;
				// On commence par separer les hÈbergements du reste
				$lt_hebergement = [];
				$lt_autres = [];
				foreach ($lt_categories as $lo_categorie) {
					if ($lo_categorie->getType_service() == 1) {
						$lt_hebergement[] = $lo_categorie;
					} else {
						$lt_autres[] = $lo_categorie;
					}
				}

				// Hebergements
				if (count($lt_hebergement) != 0) {
					foreach ($lt_hebergement as $lo_categorie) {
						if($this->_hasService(
							$lo_categorie->getType_service(), 
							$lo_categorie->getCategorie(), 
							$lo_categorie->getSous_cat())) {
							$lv_heberg_ok = true;
							break;
						}
					}
				} else {
					$lv_heberg_ok = true;
				}

				// Autres
				if (count($lt_autres) != 0) {
					foreach ($lt_autres as $lo_categorie) {
						if($this->_hasService(
							$lo_categorie->getType_service(), 
							$lo_categorie->getCategorie(), 
							$lo_categorie->getSous_cat())) {
							$lv_autres_ok = true;
							break;
						}
					}
				} else {
					$lv_autres_ok = true;
				}

				if ($lv_heberg_ok && $lv_autres_ok) {
					return true;
				} else {
					return false;
				}
			} else {
				// Aucun critere n'a ete specifie
				return true;	
			}

		}

		private function _hasService($iv_type, $iv_cat, $iv_sous_cat){
			// On va boucler sur les services, jusqu'a en trouver un qui correspond.
			// Si on ne trouve pas on renvoit faux

			foreach ($this->t_service as $lo_service) {
				if ($lo_service->getType() 			== $iv_type 	&&
					$lo_service->getCategorie() 	== $iv_cat 		&&
					( $lo_service->getSous_cat() 	== $iv_sous_cat || $iv_sous_cat == 0 ))
					return true;
			}

			// On n'a rien trouve, le sejour ne correspondra pas aux criteres de recherche
			return false;
		}

		public function isSearchResult(Recherche $io_recherche) {
			// Si le voyageur souhaite pouvoir ne pas reserver d'hebergement, mais que le sejour ne l'autorise pas
			if ($this->heberg_autonome == 0 && $io_recherche->getHeberg_autonome() == 1) return false;
			// Verifie les options de repas
			if (!$this->_checkSearchMealOptions($io_recherche)) return false;

			// Verifie les services attaches au sejour
			if (!$this->_checkSearchServices($io_recherche)) return false;

			// On verifie qu'il y a toujours un service qui repond aux criteres specifiques de la recherche
			if (!$this->_hasRequiredServices($io_recherche)) return false;

			return true;
		}

		private function _update(Sejour $io_sejour_old, &$cv_success=true) {
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
		public function getUid() {
			return $this->uid;
		}
		/* Identifiant utilisateur */
		public function getUser_id() {
			return $this->user_id;
		}
		/* Date de creation du sejour */
		public function getCrea_date() {
			return $this->crea_date;
		}
		/* Sejour supprime ? */
		public function getSuppression() {
			return $this->suppression;
		}
		/* Date de suppression */
		public function getDate_suppr() {
			return $this->date_suppr;
		}
		/* Adresse du sejour */
		public function getAdresse() {
			return $this->adresse;
		}
		/* Capacite minimale */
		public function getCapa_mini() {
			return $this->capa_mini;
		}
		/* Capacite maximale */
		public function getCapa_maxi() {
			return $this->capa_maxi;
		}
		/* Duree minimale */
		public function getDuree_mini() {
			return $this->duree_mini;
		}
		/* Duree maximale */
		public function getDuree_maxi() {
			return $this->duree_maxi;
		}
		/* Unite de duree */
		public function getDuree_unite() {
			return $this->duree_unite;
		}
		/* Delai minimale de reservation */
		public function getDelai() {
			return $this->delai;
		}
		/* Unite du delai */
		public function getDelai_unite() {
			return $this->delai_unite;
		}
		/* Intervalle de temps entre 2 sejours */
		public function getIntervalle() {
			return $this->intervalle;
		}
		/* Unite de l'intervalle */
		public function getIntervalle_unite() {
			return $this->intervalle_unite;
		}
		/* Possibilite d'hebergement autonome */
		public function getHeberg_autonome() {
			return $this->heberg_autonome;
		}
		/* Repas autonome ? */
		public function getRepas_autonome() {
			return $this->repas_autonome;
		}
		/* Provisions matin ? */
		public function getRepas_prov_matin() {
			return $this->repas_prov_matin;
		}
		/* Provision midi ? */
		public function getRepas_prov_midi() {
			return $this->repas_prov_midi;
		}
		/* Provision soir ? */
		public function getRepas_prov_soir() {
			return $this->repas_prov_soir;
		}
		/* Matin prepare ? */
		public function getRepas_cuis_matin() {
			return $this->repas_cuis_matin;
		}
		/* Dejeune prepare ? */
		public function getRepas_cuis_midi() {
			return $this->repas_cuis_midi;
		}
		/* Diner prepare ? */
		public function getRepas_cuis_soir() {
			return $this->repas_cuis_soir;
		}
		/* Prix provision matin pour un adulte */
		public function getPrix_prov_matin_adulte() {
			return $this->prix_prov_matin_adulte;
		}
		/* Prix provision midi pour un adulte */
		public function getPrix_prov_midi_adulte() {
			return $this->prix_prov_midi_adulte;
		}
		/* Prix provision soir pour un adulte */
		public function getPrix_prov_soir_adulte() {
			return $this->prix_prov_soir_adulte;
		}
		/* Prix repas matin pour un adulte */
		public function getPrix_repas_matin_adulte() {
			return $this->prix_repas_matin_adulte;
		}
		/* Prix repas midi pour un adulte */
		public function getPrix_repas_midi_adulte() {
			return $this->prix_repas_midi_adulte;
		}
		/* Prix repas soir pour un adulte */
		public function getPrix_repas_soir_adulte() {
			return $this->prix_repas_soir_adulte;
		}
		/* Prix provision matin pour un enfant */
		public function getPrix_prov_matin_enfant() {
			return $this->prix_prov_matin_enfant;
		}
		/* Prix provision midi pour un enfant */
		public function getPrix_prov_midi_enfant() {
			return $this->prix_prov_midi_enfant;
		}
		/* Prix provision soir pour un enfant */
		public function getPrix_prov_soir_enfant() {
			return $this->prix_prov_soir_enfant;
		}
		/* Prix repas matin pour un enfant */
		public function getPrix_repas_matin_enfant() {
			return $this->prix_repas_matin_enfant;
		}
		/* Prix repas midi pour un enfant */
		public function getPrix_repas_midi_enfant() {
			return $this->prix_repas_midi_enfant;
		}
		/* Prix repas soir pour un enfant */
		public function getPrix_repas_soir_enfant() {
			return $this->prix_repas_soir_enfant;
		}
		/* Devise */
		public function getDevise() {
			return $this->devise;
		}
		/* Conditions d'annulation */
		public function getAnnulation() {
			return $this->annulation;
		}
		/* Photo de couverture */
		public function getCover() {
			return $this->cover;
		}
		/* Photo de la section decouvrir */
		public function getDecouvrir() {
			return $this->decouvrir;
		}
		/* Photo de la section manger */
		public function getManger() {
			return $this->manger;
		}
		/* Photo de la section dormir */
		public function getDormir() {
			return $this->dormir;
		}
		/* Photo de la section bouger */
		public function getBouger() {
			return $this->bouger;
		}
		/* Static buffer for object of this class */
		public static function getBuffer() {
			return self::$T_BUFFER;
		}
		/* Depending object, class Adresse */
		public function getO_adresse() {
			return $this->o_adresse;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_duree() {
			return $this->o_unite_duree;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_delai() {
			return $this->o_unite_delai;
		}
		/* Depending object, class Cust_unite */
		public function getO_unite_intervalle() {
			return $this->o_unite_intervalle;
		}
		/* Depending object, class Cust_devise */
		public function getO_devise() {
			return $this->o_devise;
		}
		/* Depending object, class Cust_type_annulation */
		public function getO_type_annulation() {
			return $this->o_type_annulation;
		}
		/* Depending object, class Media */
		public function getO_cover() {
			return $this->o_cover;
		}
		/* Depending object, class Media */
		public function getO_decouvrir() {
			return $this->o_decouvrir;
		}
		/* Depending object, class Media */
		public function getO_manger() {
			return $this->o_manger;
		}
		/* Depending object, class Media */
		public function getO_dormir() {
			return $this->o_dormir;
		}
		/* Depending object, class Media */
		public function getO_bouger() {
			return $this->o_bouger;
		}
		/* Depending object, class Sejour_avis */
		public function getT_avis() {
			return $this->t_avis;
		}
		/* Depending object, class Dispo */
		public function getT_dispo() {
			return $this->t_dispo;
		}
		/* Depending object, class Service */
		public function getT_service() {
			return $this->t_service;
		}
		/* Depending object, class Sejour_t */
		public function getT_texte() {
			return $this->t_texte;
		}
		/* Depending object, class Media */
		public function getT_media() {
			return $this->t_media;
		}
		/* Depending object, class Recherche */
		public function getT_recherche() {
			return $this->t_recherche;
		}

		/**
			SETTERS
		*/

		protected function setUri() {
			$lv_id = $this->uid;
			parent::setFullUri($lv_id);
		}

		/* Identifiant du sejour */
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
		/* Identifiant utilisateur */
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
		/* Date de creation du sejour */
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
		/* Sejour supprime ? */
		public function setSuppression($iv_suppression) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->suppression = Securite::checkDataFormat($iv_suppression, $lv_datatype);
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
		/* Adresse du sejour */
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
		public function setCapa_mini($iv_capa_mini) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->capa_mini = Securite::checkDataFormat($iv_capa_mini, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Capacite maximale */
		public function setCapa_maxi($iv_capa_maxi) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->capa_maxi = Securite::checkDataFormat($iv_capa_maxi, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Duree minimale */
		public function setDuree_mini($iv_duree_mini) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->duree_mini = Securite::checkDataFormat($iv_duree_mini, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Duree maximale */
		public function setDuree_maxi($iv_duree_maxi) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->duree_maxi = Securite::checkDataFormat($iv_duree_maxi, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite de duree */
		public function setDuree_unite($iv_duree_unite) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->duree_unite = Securite::checkDataFormat($iv_duree_unite, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Delai minimale de reservation */
		public function setDelai($iv_delai) {
			/* Database attributes control */
			$lv_datatype = 'smallint(2) unsigned';
			try {
				$this->delai = Securite::checkDataFormat($iv_delai, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Unite du delai */
		public function setDelai_unite($iv_delai_unite) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->delai_unite = Securite::checkDataFormat($iv_delai_unite, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Intervalle de temps entre 2 sejours */
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
		/* Unite de l'intervalle */
		public function setIntervalle_unite($iv_intervalle_unite) {
			/* Database attributes control */
			$lv_datatype = 'varchar(3)';
			try {
				$this->intervalle_unite = Securite::checkDataFormat($iv_intervalle_unite, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Possibilite d'hebergement autonome */
		public function setHeberg_autonome($iv_heberg_autonome) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->heberg_autonome = Securite::checkDataFormat($iv_heberg_autonome, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Repas autonome ? */
		public function setRepas_autonome($iv_repas_autonome) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_autonome = Securite::checkDataFormat($iv_repas_autonome, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Provisions matin ? */
		public function setRepas_prov_matin($iv_repas_prov_matin) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_prov_matin = Securite::checkDataFormat($iv_repas_prov_matin, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Provision midi ? */
		public function setRepas_prov_midi($iv_repas_prov_midi) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_prov_midi = Securite::checkDataFormat($iv_repas_prov_midi, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Provision soir ? */
		public function setRepas_prov_soir($iv_repas_prov_soir) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_prov_soir = Securite::checkDataFormat($iv_repas_prov_soir, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Matin prepare ? */
		public function setRepas_cuis_matin($iv_repas_cuis_matin) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_cuis_matin = Securite::checkDataFormat($iv_repas_cuis_matin, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Dejeune prepare ? */
		public function setRepas_cuis_midi($iv_repas_cuis_midi) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_cuis_midi = Securite::checkDataFormat($iv_repas_cuis_midi, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Diner prepare ? */
		public function setRepas_cuis_soir($iv_repas_cuis_soir) {
			/* Database attributes control */
			$lv_datatype = 'tinyint(1)';
			try {
				$this->repas_cuis_soir = Securite::checkDataFormat($iv_repas_cuis_soir, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision matin pour un adulte */
		public function setPrix_prov_matin_adulte($iv_prix_prov_matin_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_matin_adulte = Securite::checkDataFormat($iv_prix_prov_matin_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision midi pour un adulte */
		public function setPrix_prov_midi_adulte($iv_prix_prov_midi_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_midi_adulte = Securite::checkDataFormat($iv_prix_prov_midi_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision soir pour un adulte */
		public function setPrix_prov_soir_adulte($iv_prix_prov_soir_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_soir_adulte = Securite::checkDataFormat($iv_prix_prov_soir_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas matin pour un adulte */
		public function setPrix_repas_matin_adulte($iv_prix_repas_matin_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_matin_adulte = Securite::checkDataFormat($iv_prix_repas_matin_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas midi pour un adulte */
		public function setPrix_repas_midi_adulte($iv_prix_repas_midi_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_midi_adulte = Securite::checkDataFormat($iv_prix_repas_midi_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas soir pour un adulte */
		public function setPrix_repas_soir_adulte($iv_prix_repas_soir_adulte) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_soir_adulte = Securite::checkDataFormat($iv_prix_repas_soir_adulte, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision matin pour un enfant */
		public function setPrix_prov_matin_enfant($iv_prix_prov_matin_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_matin_enfant = Securite::checkDataFormat($iv_prix_prov_matin_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision midi pour un enfant */
		public function setPrix_prov_midi_enfant($iv_prix_prov_midi_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_midi_enfant = Securite::checkDataFormat($iv_prix_prov_midi_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix provision soir pour un enfant */
		public function setPrix_prov_soir_enfant($iv_prix_prov_soir_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_prov_soir_enfant = Securite::checkDataFormat($iv_prix_prov_soir_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas matin pour un enfant */
		public function setPrix_repas_matin_enfant($iv_prix_repas_matin_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_matin_enfant = Securite::checkDataFormat($iv_prix_repas_matin_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas midi pour un enfant */
		public function setPrix_repas_midi_enfant($iv_prix_repas_midi_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_midi_enfant = Securite::checkDataFormat($iv_prix_repas_midi_enfant, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Prix repas soir pour un enfant */
		public function setPrix_repas_soir_enfant($iv_prix_repas_soir_enfant) {
			/* Database attributes control */
			$lv_datatype = 'decimal(10,2)';
			try {
				$this->prix_repas_soir_enfant = Securite::checkDataFormat($iv_prix_repas_soir_enfant, $lv_datatype);
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
		/* Conditions d'annulation */
		public function setAnnulation($iv_annulation) {
			/* Database attributes control */
			$lv_datatype = 'smallint(3) unsigned';
			try {
				$this->annulation = Securite::checkDataFormat($iv_annulation, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Photo de couverture */
		public function setCover($iv_cover) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->cover = Securite::checkDataFormat($iv_cover, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Photo de la section decouvrir */
		public function setDecouvrir($iv_decouvrir) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->decouvrir = Securite::checkDataFormat($iv_decouvrir, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Photo de la section manger */
		public function setManger($iv_manger) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->manger = Securite::checkDataFormat($iv_manger, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Photo de la section dormir */
		public function setDormir($iv_dormir) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->dormir = Securite::checkDataFormat($iv_dormir, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
			}
		}
		/* Photo de la section bouger */
		public function setBouger($iv_bouger) {
			/* Database attributes control */
			$lv_datatype = 'int(10) unsigned';
			try {
				$this->bouger = Securite::checkDataFormat($iv_bouger, $lv_datatype);
			} catch (Message $lo_message) {
				$this->addMessage($lo_message);
				$this->v_has_error = true;
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

		/* Depending object, class Unite_duree */
		public function setO_unite_duree($io_unite_duree) {
			if(!is_object($io_unite_duree)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_duree'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_duree) != 'Cust_unite' && get_class($io_unite_duree) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_duree)));
			} elseif (get_class($io_unite_duree) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_duree = $io_unite_duree;
				$this->o_unite_duree->setUnite($this->getDuree_unite());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_duree as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_duree = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Unite_delai */
		public function setO_unite_delai($io_unite_delai) {
			if(!is_object($io_unite_delai)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_delai'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_delai) != 'Cust_unite' && get_class($io_unite_delai) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_delai)));
			} elseif (get_class($io_unite_delai) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_delai = $io_unite_delai;
				$this->o_unite_delai->setUnite($this->getDelai_unite());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_delai as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_delai = new Cust_unite($lt_data);
			}
		}

		/* Depending object, class Unite_intervalle */
		public function setO_unite_intervalle($io_unite_intervalle) {
			if(!is_object($io_unite_intervalle)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_unite_intervalle'));
				return;
			}

			/* Check class */
			if(get_class($io_unite_intervalle) != 'Cust_unite' && get_class($io_unite_intervalle) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_unite', get_class($io_unite_intervalle)));
			} elseif (get_class($io_unite_intervalle) == 'Cust_unite') {
				/* Object is Cust_unite, direct assignment */
				$this->o_unite_intervalle = $io_unite_intervalle;
				$this->o_unite_intervalle->setUnite($this->getIntervalle_unite());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_unite_intervalle as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_unite_intervalle = new Cust_unite($lt_data);
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

		/* Depending object, class Type_annulation */
		public function setO_type_annulation($io_type_annulation) {
			if(!is_object($io_type_annulation)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_type_annulation'));
				return;
			}

			/* Check class */
			if(get_class($io_type_annulation) != 'Cust_type_annulation' && get_class($io_type_annulation) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Cust_type_annulation', get_class($io_type_annulation)));
			} elseif (get_class($io_type_annulation) == 'Cust_type_annulation') {
				/* Object is Cust_type_annulation, direct assignment */
				$this->o_type_annulation = $io_type_annulation;
				$this->o_type_annulation->setAnnulation($this->getAnnulation());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_type_annulation as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_type_annulation = new Cust_type_annulation($lt_data);
			}
		}

		/* Depending object, class Cover */
		public function setO_cover($io_cover) {
			if(!is_object($io_cover)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_cover'));
				return;
			}

			/* Check class */
			if(get_class($io_cover) != 'Media' && get_class($io_cover) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_cover)));
			} elseif (get_class($io_cover) == 'Media') {
				/* Object is Media, direct assignment */
				$this->o_cover = $io_cover;
				$this->o_cover->setUid($this->getCover());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_cover as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_cover = new Media($lt_data);
			}
		}

		/* Depending object, class Decouvrir */
		public function setO_decouvrir($io_decouvrir) {
			if(!is_object($io_decouvrir)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_decouvrir'));
				return;
			}

			/* Check class */
			if(get_class($io_decouvrir) != 'Media' && get_class($io_decouvrir) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_decouvrir)));
			} elseif (get_class($io_decouvrir) == 'Media') {
				/* Object is Media, direct assignment */
				$this->o_decouvrir = $io_decouvrir;
				$this->o_decouvrir->setUid($this->getDecouvrir());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_decouvrir as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_decouvrir = new Media($lt_data);
			}
		}

		/* Depending object, class Manger */
		public function setO_manger($io_manger) {
			if(!is_object($io_manger)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_manger'));
				return;
			}

			/* Check class */
			if(get_class($io_manger) != 'Media' && get_class($io_manger) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_manger)));
			} elseif (get_class($io_manger) == 'Media') {
				/* Object is Media, direct assignment */
				$this->o_manger = $io_manger;
				$this->o_manger->setUid($this->getManger());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_manger as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_manger = new Media($lt_data);
			}
		}

		/* Depending object, class Dormir */
		public function setO_dormir($io_dormir) {
			if(!is_object($io_dormir)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_dormir'));
				return;
			}

			/* Check class */
			if(get_class($io_dormir) != 'Media' && get_class($io_dormir) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_dormir)));
			} elseif (get_class($io_dormir) == 'Media') {
				/* Object is Media, direct assignment */
				$this->o_dormir = $io_dormir;
				$this->o_dormir->setUid($this->getDormir());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_dormir as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_dormir = new Media($lt_data);
			}
		}

		/* Depending object, class Bouger */
		public function setO_bouger($io_bouger) {
			if(!is_object($io_bouger)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_bouger'));
				return;
			}

			/* Check class */
			if(get_class($io_bouger) != 'Media' && get_class($io_bouger) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Media', get_class($io_bouger)));
			} elseif (get_class($io_bouger) == 'Media') {
				/* Object is Media, direct assignment */
				$this->o_bouger = $io_bouger;
				$this->o_bouger->setUid($this->getBouger());
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_bouger as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->o_bouger = new Media($lt_data);
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
			if(get_class($io_avis) != 'Sejour_avis' && get_class($io_avis) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour_avis', get_class($io_avis)));
			} elseif (get_class($io_avis) == 'Sejour_avis') {
				/* Object is Sejour_avis, direct assignment */
				$this->t_avis[] = $io_avis;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_avis as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_avis[] = new Sejour_avis($lt_data);
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

		public function setT_sejour_dispo(Array $it_sejour_dispo) {
			$this->t_sejour_dispo = [];
			foreach ($it_sejour_dispo as $io_sejour_dispo) {
				$this->setO_sejour_dispo($io_sejour_dispo);
			}
		}

		public function setO_sejour_dispo($io_sejour_dispo) {
			if(!is_object($io_sejour_dispo)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_sejour_dispo'));
			}

			/* Check class */
			if(get_class($io_sejour_dispo) != 'Sejour_dispo' && get_class($io_sejour_dispo) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour_dispo', get_class($io_sejour_dispo)));
			} elseif (get_class($io_sejour_dispo) == 'Sejour_dispo') {
				/* Object is Sejour_dispo, direct assignment */
				$this->t_sejour_dispo[] = $io_sejour_dispo;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_sejour_dispo as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_sejour_dispo[] = new Sejour_dispo($lt_data);
			}
		}

		/* Depending object, class Service */
		public function setT_service(Array $it_service) {
			$this->t_service = [];
			foreach ($it_service as $io_service) {
				$this->setO_service($io_service);
			}
		}

		public function setO_service($io_service) {
			if(!is_object($io_service)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_service'));
				return;
			}

			/* Check class */
			if(get_class($io_service) != 'Service' && get_class($io_service) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Service', get_class($io_service)));
			} elseif (get_class($io_service) == 'Service') {
				/* Object is Service, direct assignment */
				$this->t_service[] = $io_service;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_service as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_service[] = new Service($lt_data);
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
			if(get_class($io_texte) != 'Sejour_t' && get_class($io_texte) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour_t', get_class($io_texte)));
			} elseif (get_class($io_texte) == 'Sejour_t') {
				/* Object is Sejour_t, direct assignment */
				$this->t_texte[] = $io_texte;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_texte as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_texte[] = new Sejour_t($lt_data);
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

		public function setT_sejour_media(Array $it_sejour_media) {
			$this->t_sejour_media = [];
			foreach ($it_sejour_media as $io_sejour_media) {
				$this->setO_sejour_media($io_sejour_media);
			}
		}

		public function setO_sejour_media($io_sejour_media) {
			if(!is_object($io_sejour_media)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_sejour_media'));
			}

			/* Check class */
			if(get_class($io_sejour_media) != 'Sejour_media' && get_class($io_sejour_media) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Sejour_media', get_class($io_sejour_media)));
			} elseif (get_class($io_sejour_media) == 'Sejour_media') {
				/* Object is Sejour_media, direct assignment */
				$this->t_sejour_media[] = $io_sejour_media;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_sejour_media as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_sejour_media[] = new Sejour_media($lt_data);
			}
		}

		/* Depending object, class Recherche */
		public function setT_recherche(Array $it_recherche) {
			$this->t_recherche = [];
			foreach ($it_recherche as $io_recherche) {
				$this->setO_recherche($io_recherche);
			}
		}

		public function setO_recherche($io_recherche) {
			if(!is_object($io_recherche)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_recherche'));
				return;
			}

			/* Check class */
			if(get_class($io_recherche) != 'Recherche' && get_class($io_recherche) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Recherche', get_class($io_recherche)));
			} elseif (get_class($io_recherche) == 'Recherche') {
				/* Object is Recherche, direct assignment */
				$this->t_recherche[] = $io_recherche;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_recherche as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_recherche[] = new Recherche($lt_data);
			}
		}

		public function setT_recherche_sejour(Array $it_recherche_sejour) {
			$this->t_recherche_sejour = [];
			foreach ($it_recherche_sejour as $io_recherche_sejour) {
				$this->setO_recherche_sejour($io_recherche_sejour);
			}
		}

		public function setO_recherche_sejour($io_recherche_sejour) {
			if(!is_object($io_recherche_sejour)) {
				Message::bufferMessage(new Message('all', 3, 'x', 'setO_recherche_sejour'));
			}

			/* Check class */
			if(get_class($io_recherche_sejour) != 'Recherche_sejour' && get_class($io_recherche_sejour) != 'stdClass') {
				Message::bufferMessage(new Message('all', 2, 'x', 'Recherche_sejour', get_class($io_recherche_sejour)));
			} elseif (get_class($io_recherche_sejour) == 'Recherche_sejour') {
				/* Object is Recherche_sejour, direct assignment */
				$this->t_recherche_sejour[] = $io_recherche_sejour;
			} else {
				/* Object is stdClass, convert it to desired class */
				$lt_data = [];
				foreach ($io_recherche_sejour as $lv_key => $lv_value) {
					$lv_key = Utilities::convertJsToPhpVar($lv_key);
					$lt_data[$lv_key] = $lv_value;
				}
				$this->t_recherche_sejour[] = new Recherche_sejour($lt_data);
			}
		}


		/**
			LOAD RELATED OBJECTS
		*/

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

		public function loadUnite_duree(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->duree_unite;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_duree($lt_results[0]);
			} else {
				$this->setO_unite_duree(new Cust_unite($lt_parameters));
			}
		}

		public function loadUnite_delai(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->delai_unite;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_delai($lt_results[0]);
			} else {
				$this->setO_unite_delai(new Cust_unite($lt_parameters));
			}
		}

		public function loadUnite_intervalle(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['unite'] = $this->intervalle_unite;
			/* Load data */
			$lt_results = Cust_uniteManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_unite_intervalle($lt_results[0]);
			} else {
				$this->setO_unite_intervalle(new Cust_unite($lt_parameters));
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

		public function loadType_annulation(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['annulation'] = $this->annulation;
			/* Load data */
			$lt_results = Cust_type_annulationManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_type_annulation($lt_results[0]);
			} else {
				$this->setO_type_annulation(new Cust_type_annulation($lt_parameters));
			}
		}

		public function loadCover(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->cover;
			/* Load data */
			$lt_results = MediaManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_cover($lt_results[0]);
			} else {
				$this->setO_cover(new Media($lt_parameters));
			}
		}

		public function loadDecouvrir(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->decouvrir;
			/* Load data */
			$lt_results = MediaManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_decouvrir($lt_results[0]);
			} else {
				$this->setO_decouvrir(new Media($lt_parameters));
			}
		}

		public function loadManger(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->manger;
			/* Load data */
			$lt_results = MediaManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_manger($lt_results[0]);
			} else {
				$this->setO_manger(new Media($lt_parameters));
			}
		}

		public function loadDormir(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->dormir;
			/* Load data */
			$lt_results = MediaManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_dormir($lt_results[0]);
			} else {
				$this->setO_dormir(new Media($lt_parameters));
			}
		}

		public function loadBouger(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['uid'] = $this->bouger;
			/* Load data */
			$lt_results = MediaManager::get($lt_parameters, [], $it_expand);

			if (isset($lt_results[0])) {
				$this->setO_bouger($lt_results[0]);
			} else {
				$this->setO_bouger(new Media($lt_parameters));
			}
		}

		public function loadAvis(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['sejour_id'] = $this->uid;
			/* Load data */
			$lt_results = Sejour_avisManager::get($lt_parameters, [], $it_expand);

			$this->setT_avis($lt_results);
		}

		public function loadDispo(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['sejour_id'] = $this->uid;
			/* Load data */
			$this->setT_sejour_dispo(Sejour_dispoManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_sejour_dispo as $lo_sejour_dispo) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_sejour_dispo->getDispo();
				/* Load data from linked table */
				$lt_results_tmp = DispoManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_dispo($lt_results);
		}

		public function loadService(Array $it_expand = []) {
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
			$lt_parameters['sejour'] = $this->uid;
			/* Load data */
			$this->setT_sejour_service(Sejour_serviceManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_sejour_service as $lo_sejour_service) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_sejour_service->getService();
				/* Load data from linked table */
				$lt_results_tmp = ServiceManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_service($lt_results);
		}

		public function loadTexte(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['sejour_id'] = $this->uid;
			/* Load data */
			$lt_results = Sejour_tManager::get($lt_parameters, [], $it_expand);

			$this->setT_texte($lt_results);
		}

		public function loadMedia(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['sejour_uid'] = $this->uid;
			/* Load data */
			$this->setT_sejour_media(Sejour_mediaManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_sejour_media as $lo_sejour_media) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_sejour_media->getMedia_uid();
				/* Load data from linked table */
				$lt_results_tmp = MediaManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_media($lt_results);
		}

		public function loadRecherche(Array $it_expand = []) {
			$lt_results = [];
			/* Init search parameters */
			$lt_parameters = [];
			/* Fill search parameters */
			$lt_parameters['sejour_uid'] = $this->uid;
			/* Load data */
			$this->setT_recherche_sejour(Recherche_sejourManager::get($lt_parameters, [], $it_expand));

			foreach ($this->t_recherche_sejour as $lo_recherche_sejour) {
				/* Search results 1 by 1 */
				$lt_parameters = [];
				$lt_parameters['uid'] = $lo_recherche_sejour->getRecherche_uid();
				/* Load data from linked table */
				$lt_results_tmp = RechercheManager::get($lt_parameters, [], $it_expand);
				foreach ($lt_results_tmp as $lo_result_tmp) {
					$lt_results[] = $lo_result_tmp;
				}
			}
			$this->setT_recherche($lt_results);
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
				$lt_results = SejourManager::get($lt_parameters);
				if (count($lt_results) == 0) {
					/* No record, create a new one */
					$lo_sejour = $this->_create($cv_success);
					if (!$cv_success) {
						return $this;
					}
				} else {
					/* Update record */
					$lo_sejour = $this->_update($lt_results[0], $cv_success);
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
			$this->saveCover($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveDecouvrir($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveManger($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveDormir($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveBouger($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveDispo($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveService($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveMedia($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveRecherche($cv_success);
			if (!$cv_success) {
				return $this;
			}
			if ($iv_mode == 'I') {
				/* No record, create a new one */
				$lo_sejour = SejourManager::add($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
				$this->setUid($lo_sejour->getUid());
				$this->setUri();
			} else {
				/* Update record */
				$lo_sejour = SejourManager::update($this, $cv_success);
				if (!$cv_success) {
					return $this;
				}
			}

			/* Save depending objects */
			$this->saveAvis($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveSejour_dispo($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveSejour_service($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveTexte($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveSejour_media($cv_success);
			if (!$cv_success) {
				return $this;
			}
			$this->saveRecherche_sejour($cv_success);
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

		public function saveCover(&$cv_success) {
			/* Save linked objects of type Media */
			if ($this->o_cover != null) {
				$this->o_cover->setUid($this->getCover());
				$lo_cover_new = $this->o_cover->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setCover($lo_cover_new->getUid());
				$this->setO_cover($lo_cover_new);
			}
		}

		public function saveDecouvrir(&$cv_success) {
			/* Save linked objects of type Media */
			if ($this->o_decouvrir != null) {
				$this->o_decouvrir->setUid($this->getDecouvrir());
				$lo_decouvrir_new = $this->o_decouvrir->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setDecouvrir($lo_decouvrir_new->getUid());
				$this->setO_decouvrir($lo_decouvrir_new);
			}
		}

		public function saveManger(&$cv_success) {
			/* Save linked objects of type Media */
			if ($this->o_manger != null) {
				$this->o_manger->setUid($this->getManger());
				$lo_manger_new = $this->o_manger->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setManger($lo_manger_new->getUid());
				$this->setO_manger($lo_manger_new);
			}
		}

		public function saveDormir(&$cv_success) {
			/* Save linked objects of type Media */
			if ($this->o_dormir != null) {
				$this->o_dormir->setUid($this->getDormir());
				$lo_dormir_new = $this->o_dormir->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setDormir($lo_dormir_new->getUid());
				$this->setO_dormir($lo_dormir_new);
			}
		}

		public function saveBouger(&$cv_success) {
			/* Save linked objects of type Media */
			if ($this->o_bouger != null) {
				$this->o_bouger->setUid($this->getBouger());
				$lo_bouger_new = $this->o_bouger->save($cv_success);
				if (!$cv_success) {
					return;
				}
				$this->setBouger($lo_bouger_new->getUid());
				$this->setO_bouger($lo_bouger_new);
			}
		}

		public function saveAvis(&$cv_success) {
			/* Save linked objects of type Sejour_avis */
			$lt_avis = [];
			foreach ($this->t_avis as $lo_avis) {
				/* Define link between object values */
				$lo_avis->setSejour_id($this->uid);
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
				$lt_parameters['sejour_id'] = $this->uid;
				$lt_results = Sejour_dispoManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_sejour_dispo[] = new Sejour_dispo($lt_parameters);
				}
			}
		}

		public function saveService(&$cv_success) {
			/* Save linked objects of type Service */
			foreach ($this->t_service as $lo_service) {
				$lo_service_new = $lo_service->save($cv_success);
				/* Search link within pivot table */
				$lt_parameters = [];
				$lt_parameters['service'] = $lo_service_new->getUid();
				$lt_parameters['sejour'] = $this->uid;
				$lt_results = Sejour_serviceManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_sejour_service[] = new Sejour_service($lt_parameters);
				}
			}
		}

		public function saveTexte(&$cv_success) {
			/* Save linked objects of type Sejour_t */
			$lt_texte = [];
			foreach ($this->t_texte as $lo_texte) {
				/* Define link between object values */
				$lo_texte->setSejour_id($this->uid);
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
				$lt_parameters['sejour_uid'] = $this->uid;
				$lt_results = Sejour_mediaManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_sejour_media[] = new Sejour_media($lt_parameters);
				}
			}
		}

		public function saveRecherche(&$cv_success) {
			/* Save linked objects of type Recherche */
			foreach ($this->t_recherche as $lo_recherche) {
				$lo_recherche_new = $lo_recherche->save($cv_success);
				/* Search link within pivot table */
				$lt_parameters = [];
				$lt_parameters['recherche_uid'] = $lo_recherche_new->getUid();
				$lt_parameters['sejour_uid'] = $this->uid;
				$lt_results = Recherche_sejourManager::get($lt_parameters);
				if (!isset($lt_results[0])) {
					/* Create link */
					$this->t_recherche_sejour[] = new Recherche_sejour($lt_parameters);
				}
			}
		}

		public function saveSejour_dispo(&$cv_success) {
			$lt_sejour_dispo = [];
			foreach ($this->t_sejour_dispo as $lo_sejour_dispo) {
				$lo_sejour_dispo->setSejour_id($this->uid);
				$lt_sejour_dispo[] = $lo_sejour_dispo->save($cv_success);
			}
			$this->setT_sejour_dispo($lt_sejour_dispo);
		}

		public function saveSejour_service(&$cv_success) {
			$lt_sejour_service = [];
			foreach ($this->t_sejour_service as $lo_sejour_service) {
				$lo_sejour_service->setSejour($this->uid);
				$lt_sejour_service[] = $lo_sejour_service->save($cv_success);
			}
			$this->setT_sejour_service($lt_sejour_service);
		}

		public function saveSejour_media(&$cv_success) {
			$lt_sejour_media = [];
			foreach ($this->t_sejour_media as $lo_sejour_media) {
				$lo_sejour_media->setSejour_uid($this->uid);
				$lt_sejour_media[] = $lo_sejour_media->save($cv_success);
			}
			$this->setT_sejour_media($lt_sejour_media);
		}

		public function saveRecherche_sejour(&$cv_success) {
			$lt_recherche_sejour = [];
			foreach ($this->t_recherche_sejour as $lo_recherche_sejour) {
				$lo_recherche_sejour->setSejour_uid($this->uid);
				$lt_recherche_sejour[] = $lo_recherche_sejour->save($cv_success);
			}
			$this->setT_recherche_sejour($lt_recherche_sejour);
		}


		/**
			OBJECTS DELETION
		*/

		public function delete(&$cv_success=true) {

			/* Delete linked objects */
			$this->deleteAvis($cv_success);
			$this->deleteSejour_dispo($cv_success);
			$this->deleteSejour_service($cv_success);
			$this->deleteTexte($cv_success);
			$this->deleteSejour_media($cv_success);
			$this->deleteRecherche_sejour($cv_success);

			/* Delete this object */
			SejourManager::delete($this, $cv_success);
		}

		public function deleteAvis(&$cv_success) {
			$this->loadAvis();
			foreach ($this->t_avis as $lo_avis) {
				$lo_avis->delete($cv_success);
			}
		}

		public function deleteSejour_dispo(&$cv_success) {
			$this->loadDispo();
			foreach ($this->t_sejour_dispo as $lo_sejour_dispo) {
				$lo_sejour_dispo->delete($cv_success);
			}
		}

		public function deleteSejour_service(&$cv_success) {
			$this->loadService();
			foreach ($this->t_sejour_service as $lo_sejour_service) {
				$lo_sejour_service->delete($cv_success);
			}
		}

		public function deleteTexte(&$cv_success) {
			$this->loadTexte();
			foreach ($this->t_texte as $lo_texte) {
				$lo_texte->delete($cv_success);
			}
		}

		public function deleteSejour_media(&$cv_success) {
			$this->loadMedia();
			foreach ($this->t_sejour_media as $lo_sejour_media) {
				$lo_sejour_media->delete($cv_success);
			}
		}

		public function deleteRecherche_sejour(&$cv_success) {
			$this->loadRecherche();
			foreach ($this->t_recherche_sejour as $lo_recherche_sejour) {
				$lo_recherche_sejour->delete($cv_success);
			}
		}


		/**
			OBJECTS BUFFERING
		*/

		public static function bufferize(Sejour $io_sejour) {
			self::$T_BUFFER[] = $io_sejour;
		}

		/**
			EXPORT JSON
		*/

		public function json($iv_prefix=''){
			$ev_json = '';
			if ($iv_prefix != '') $ev_json .= '"sejour":';
			$lt_tab   = get_object_vars($this);
			$ev_json .= JSON::jsonFormat($lt_tab);

			return $ev_json;
		}

		public static function jsonBuffer(){
			$ev_json = '{ "sejour" : [ ';
			foreach (self::$T_BUFFER as $lo_sejour) {
				$ev_json.= $lo_sejour->json();
				if ($lo_sejour !== end(self::$T_BUFFER)) {
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