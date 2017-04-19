<?php

/************************************************************************************************************************************/
/*																																  */
/*	SejourManager.class.php
/*	Auteur : Antoine Alleard
/*	Date : 02/02/2017 20:33:59
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	class SejourManager {

		/**
			Requete specifiques
		*/
		public static function getInitialPerimeter(Recherche $io_recherche){

			$et_sejour = [];
			// Durée demandée
			$lo_interval = $io_recherche->getDate_to()->diff($io_recherche->getDate_from());
			$lv_duree    = $lo_interval->days;

			// Dates
			$lv_date_from = $io_recherche->getDate_from()->format('Y-m-d');
			$lv_date_to   = $io_recherche->getDate_to()->format('Y-m-d');

			// Nombre de personnes
			$lv_people = $io_recherche->getAdultes() + $io_recherche->getEnfants();

			if($io_recherche->getDestination() != 0){
				$io_recherche->loadVille();
				// Recherche des villes dans le périmètre
				// Chargement de la ville sélectionnée
				$lo_ville = $io_recherche->getO_ville();
				$lt_villes = $lo_ville->getWithinRadius(40);

				// Création du range de villes
				$lv_villes = Securite::createRange($lt_villes);

				// Recherche des séjours correspondants au nombre de personnes, dispo aux dates indiquées
				$lo_sql = new Requete("SELECT A.* FROM sejour 		    AS A 
											 INNER JOIN adresse 		AS C
											 		 ON A.adresse 		=  C.uid
												  WHERE A.suppression   =  0
												  	AND A.capa_mini     <= $lv_people 
												    AND ( A.capa_maxi   >= $lv_people OR A.capa_maxi = 0 )
												    AND A.duree_mini    <= $lv_duree
												    AND ( A.duree_maxi  >= $lv_duree  OR A.duree_maxi = 0 )
												    AND NOT EXISTS ( SELECT sejour_id FROM sejour_dispo AS B 
												    							INNER JOIN dispo        AS D
												    									ON B.dispo     = D.uid
												    								 WHERE B.sejour_id = A.uid
												    								   AND D.date      BETWEEN '$lv_date_from' AND '$lv_date_to'
												    								   AND D.type    <> 1)
													AND C.ville         IN $lv_villes");
			} else {
				// Recherche des séjours correspondants au nombre de personnes, dispo aux dates indiquées
				$lo_sql = new Requete("SELECT A.*  FROM sejour 		    AS A 
												  WHERE A.suppression   =  0
												  	AND A.capa_mini     <= $lv_people 
												    AND ( A.capa_maxi   >= $lv_people OR A.capa_maxi = 0 )
												    AND A.duree_mini    <= $lv_duree
												    AND ( A.duree_maxi  >= $lv_duree  OR A.duree_maxi = 0 )
												    AND NOT EXISTS ( SELECT sejour_id FROM sejour_dispo AS B 
												    							INNER JOIN dispo        AS D
												    									ON B.dispo     = D.uid
												    								 WHERE B.sejour_id = A.uid
												    								   AND D.date      BETWEEN '$lv_date_from' AND '$lv_date_to'
												    								   AND D.type    <> 1)");
			}
			
			$lo_statement = $lo_sql->getStatement();

			while ($ls_donnees = $lo_statement->fetch(PDO::FETCH_ASSOC)){
				$et_sejour[] = self::buildObject($ls_donnees);
			}
			return $et_sejour;
		}

		/**
			Requetes standard
		*/

		private static $T_BUFFER = [];

		public static function add(Sejour $io_sejour, &$cv_success=true){
			$lv_requete = "INSERT INTO sejour SET 
									   user_id = '".Securite::bdd($io_sejour->getUser_id())."',
									   crea_date = '".Securite::bdd($io_sejour->getCrea_date())."',
									   suppression = '".Securite::bdd($io_sejour->getSuppression())."',
									   date_suppr = '".Securite::bdd($io_sejour->getDate_suppr())."',
									   adresse = '".Securite::bdd($io_sejour->getAdresse())."',
									   capa_mini = '".Securite::bdd($io_sejour->getCapa_mini())."',
									   capa_maxi = '".Securite::bdd($io_sejour->getCapa_maxi())."',
									   duree_mini = '".Securite::bdd($io_sejour->getDuree_mini())."',
									   duree_maxi = '".Securite::bdd($io_sejour->getDuree_maxi())."',
									   duree_unite = '".Securite::bdd($io_sejour->getDuree_unite())."',
									   delai = '".Securite::bdd($io_sejour->getDelai())."',
									   delai_unite = '".Securite::bdd($io_sejour->getDelai_unite())."',
									   intervalle = '".Securite::bdd($io_sejour->getIntervalle())."',
									   intervalle_unite = '".Securite::bdd($io_sejour->getIntervalle_unite())."',
									   heberg_autonome = '".Securite::bdd($io_sejour->getHeberg_autonome())."',
									   repas_autonome = '".Securite::bdd($io_sejour->getRepas_autonome())."',
									   repas_prov_matin = '".Securite::bdd($io_sejour->getRepas_prov_matin())."',
									   repas_prov_midi = '".Securite::bdd($io_sejour->getRepas_prov_midi())."',
									   repas_prov_soir = '".Securite::bdd($io_sejour->getRepas_prov_soir())."',
									   repas_cuis_matin = '".Securite::bdd($io_sejour->getRepas_cuis_matin())."',
									   repas_cuis_midi = '".Securite::bdd($io_sejour->getRepas_cuis_midi())."',
									   repas_cuis_soir = '".Securite::bdd($io_sejour->getRepas_cuis_soir())."',
									   prix_prov_matin_adulte = '".Securite::bdd($io_sejour->getPrix_prov_matin_adulte())."',
									   prix_prov_midi_adulte = '".Securite::bdd($io_sejour->getPrix_prov_midi_adulte())."',
									   prix_prov_soir_adulte = '".Securite::bdd($io_sejour->getPrix_prov_soir_adulte())."',
									   prix_repas_matin_adulte = '".Securite::bdd($io_sejour->getPrix_repas_matin_adulte())."',
									   prix_repas_midi_adulte = '".Securite::bdd($io_sejour->getPrix_repas_midi_adulte())."',
									   prix_repas_soir_adulte = '".Securite::bdd($io_sejour->getPrix_repas_soir_adulte())."',
									   prix_prov_matin_enfant = '".Securite::bdd($io_sejour->getPrix_prov_matin_enfant())."',
									   prix_prov_midi_enfant = '".Securite::bdd($io_sejour->getPrix_prov_midi_enfant())."',
									   prix_prov_soir_enfant = '".Securite::bdd($io_sejour->getPrix_prov_soir_enfant())."',
									   prix_repas_matin_enfant = '".Securite::bdd($io_sejour->getPrix_repas_matin_enfant())."',
									   prix_repas_midi_enfant = '".Securite::bdd($io_sejour->getPrix_repas_midi_enfant())."',
									   prix_repas_soir_enfant = '".Securite::bdd($io_sejour->getPrix_repas_soir_enfant())."',
									   devise = '".Securite::bdd($io_sejour->getDevise())."',
									   annulation = '".Securite::bdd($io_sejour->getAnnulation())."',
									   cover = '".Securite::bdd($io_sejour->getCover())."',
									   decouvrir = '".Securite::bdd($io_sejour->getDecouvrir())."',
									   manger = '".Securite::bdd($io_sejour->getManger())."',
									   dormir = '".Securite::bdd($io_sejour->getDormir())."',
									   bouger = '".Securite::bdd($io_sejour->getBouger())."'";
			if ($io_sejour->getUid() != null) {
				/* Si une valeur est définie pour l'auto incrément */
				$lv_requete .= ", uid = '".Securite::bdd($io_sejour->getUid())."'";
			}
			$lo_sql = new Requete($lv_requete);
			try {
				$lv_uid = $lo_sql->execute();
				$io_sejour->addMessage(new Message('sejour', 0, 's'));

				$lt_parameters = [];
				$lt_parameters['uid'] = $lv_uid;
				$lt_results = self::get($lt_parameters);
				return $lt_results[0];

			} catch (Message $lo_message) {
				$io_sejour->addMessage(new Message('sejour', 4, 'e'));
				$io_sejour->addMessage($lo_message);
				$cv_success = false;
			}

		}

		public static function buildObject(Array $is_donnees, Array $it_expand = []) {
			$lo_instance = new Sejour($is_donnees);

			/* Manage requested expands */
			foreach ($it_expand as $lv_expand) {
				if (!property_exists('Sejour', 'o_'.$lv_expand) && !property_exists('Sejour', 't_'.$lv_expand)) {
				/* Property does not exist for this class */
				continue;
				}
				/* Method's name dynamic definition */
				$lv_method = "load".ucfirst($lv_expand);
				if (method_exists($lo_instance, $lv_method)) {
					$lo_instance->$lv_method($it_expand);
				} else {
					$lo_instance->addMessage(new Message('expand', 0, 'x', 'Sejour', $lv_method));
					/* Method &1->&2() does not exist */
				}
			}
				/* Text loading */
				$lo_instance->loadTexte();
			return $lo_instance;
		}

		public static function delete(Sejour $io_sejour, &$cv_success=true) {
			$lo_sql = new Requete("DELETE FROM sejour
										 WHERE uid = '".Securite::bdd($io_sejour->getUid())."'");

			try {
				$lo_sql->execute();
				$io_sejour->addMessage(new Message('sejour', 2, 's'));
			} catch (Message $lo_message) {
				$io_sejour->addMessage(new Message('sejour', 6, 'e'));
				$io_sejour->addMessage($lo_message);
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
			$lo_sql = new Requete("SELECT * FROM sejour ".$lv_query.$lv_sorter.$lv_limit);

			$lo_statement = $lo_sql->getStatement();
			while ($ls_donnees = $lo_statement->fetch(PDO::FETCH_ASSOC)) {
				$et_results[] = self::buildObject($ls_donnees, $it_expand);
			}

			if (count($et_results) == 0) {
				Message::bufferMessage(new Message('sejour', 3, 'w'));
			}

			return $et_results;
		}

		public static function getBuffer() {
			return self::$T_BUFFER;
		}

		public static function update(Sejour $io_sejour, &$cv_success=true) {
			$sql = new Requete("UPDATE sejour SET
									   user_id = '".Securite::bdd($io_sejour->getUser_id())."',
									   crea_date = '".Securite::bdd($io_sejour->getCrea_date())."',
									   suppression = '".Securite::bdd($io_sejour->getSuppression())."',
									   date_suppr = '".Securite::bdd($io_sejour->getDate_suppr())."',
									   adresse = '".Securite::bdd($io_sejour->getAdresse())."',
									   capa_mini = '".Securite::bdd($io_sejour->getCapa_mini())."',
									   capa_maxi = '".Securite::bdd($io_sejour->getCapa_maxi())."',
									   duree_mini = '".Securite::bdd($io_sejour->getDuree_mini())."',
									   duree_maxi = '".Securite::bdd($io_sejour->getDuree_maxi())."',
									   duree_unite = '".Securite::bdd($io_sejour->getDuree_unite())."',
									   delai = '".Securite::bdd($io_sejour->getDelai())."',
									   delai_unite = '".Securite::bdd($io_sejour->getDelai_unite())."',
									   intervalle = '".Securite::bdd($io_sejour->getIntervalle())."',
									   intervalle_unite = '".Securite::bdd($io_sejour->getIntervalle_unite())."',
									   heberg_autonome = '".Securite::bdd($io_sejour->getHeberg_autonome())."',
									   repas_autonome = '".Securite::bdd($io_sejour->getRepas_autonome())."',
									   repas_prov_matin = '".Securite::bdd($io_sejour->getRepas_prov_matin())."',
									   repas_prov_midi = '".Securite::bdd($io_sejour->getRepas_prov_midi())."',
									   repas_prov_soir = '".Securite::bdd($io_sejour->getRepas_prov_soir())."',
									   repas_cuis_matin = '".Securite::bdd($io_sejour->getRepas_cuis_matin())."',
									   repas_cuis_midi = '".Securite::bdd($io_sejour->getRepas_cuis_midi())."',
									   repas_cuis_soir = '".Securite::bdd($io_sejour->getRepas_cuis_soir())."',
									   prix_prov_matin_adulte = '".Securite::bdd($io_sejour->getPrix_prov_matin_adulte())."',
									   prix_prov_midi_adulte = '".Securite::bdd($io_sejour->getPrix_prov_midi_adulte())."',
									   prix_prov_soir_adulte = '".Securite::bdd($io_sejour->getPrix_prov_soir_adulte())."',
									   prix_repas_matin_adulte = '".Securite::bdd($io_sejour->getPrix_repas_matin_adulte())."',
									   prix_repas_midi_adulte = '".Securite::bdd($io_sejour->getPrix_repas_midi_adulte())."',
									   prix_repas_soir_adulte = '".Securite::bdd($io_sejour->getPrix_repas_soir_adulte())."',
									   prix_prov_matin_enfant = '".Securite::bdd($io_sejour->getPrix_prov_matin_enfant())."',
									   prix_prov_midi_enfant = '".Securite::bdd($io_sejour->getPrix_prov_midi_enfant())."',
									   prix_prov_soir_enfant = '".Securite::bdd($io_sejour->getPrix_prov_soir_enfant())."',
									   prix_repas_matin_enfant = '".Securite::bdd($io_sejour->getPrix_repas_matin_enfant())."',
									   prix_repas_midi_enfant = '".Securite::bdd($io_sejour->getPrix_repas_midi_enfant())."',
									   prix_repas_soir_enfant = '".Securite::bdd($io_sejour->getPrix_repas_soir_enfant())."',
									   devise = '".Securite::bdd($io_sejour->getDevise())."',
									   annulation = '".Securite::bdd($io_sejour->getAnnulation())."',
									   cover = '".Securite::bdd($io_sejour->getCover())."',
									   decouvrir = '".Securite::bdd($io_sejour->getDecouvrir())."',
									   manger = '".Securite::bdd($io_sejour->getManger())."',
									   dormir = '".Securite::bdd($io_sejour->getDormir())."',
									   bouger = '".Securite::bdd($io_sejour->getBouger())."'
								 WHERE uid = '".Securite::bdd($io_sejour->getUid())."'
								");

			try {
				$sql->execute();
				$io_sejour->addMessage(new Message('sejour', 1, 's'));

				$lt_parameters = [];
				$lt_parameters['uid'] = $io_sejour->getUid();
				$lt_results = self::get($lt_parameters);
				return $lt_results[0];

			} catch (Message $lo_message) {
				$io_sejour->addMessage(new Message('sejour', 5, 'e'));
				$io_sejour->addMessage($lo_message);
				$cv_success = false;
			}

		}

	}
?>