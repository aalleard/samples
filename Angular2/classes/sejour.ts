/************************************************************************************************************************************/
/*																																  */
/*	sejour.ts
/*	Auteur : Antoine Alleard
/*	Date : 02/02/2017 22:49:04
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } 				from './mainObject';
import { Adresse } 					from './adresse';
import { CustUnite } 				from './custUnite';
import { CustDevise } 				from './custDevise';
import { CustTypeAnnulation } 		from './custTypeAnnulation';
import { Media } 					from './media';
import { SejourAvis } 				from './sejourAvis';
import { Dispo } 					from './dispo';
import { SejourDispo } 				from './sejourDispo';
import { Service } 					from './service';
import { SejourService } 			from './sejourService';
import { SejourT } 					from './sejourT';
import { SejourMedia } 				from './sejourMedia';
import { Recherche } 				from './recherche';
import { RechercheSejour } 			from './rechercheSejour';

export class Sejour extends MainObject {

	/**
		Code specifique
	*/

	statut: number = 1;
	prix: 			number = 0;
	prixPersonne:	number = 0;

	defineStatut() {
		if (this.suppression) {
			this.setStatut(3);
		} else {
			for (var i = 0; i < this.aService.length; ++i) {
				if(this.aService[i].type == 1) {
					// On a une service hébergement
					return;	
				}
			}
			this.setStatut(2);
		}
	}

	setPrix(prix){
		this.prix = parseInt(prix);
	}

	setPrixPersonne(prixPersonne){
		this.prixPersonne = parseInt(prixPersonne);
	}

	setStatut(statut) {
		this.statut = parseInt(statut);
	}

	/**
		Fin du code specifique
	*/

	/* Identifiant du sejour */
	uid: number = 0;		/* Primary key */
	/* Identifiant utilisateur */
	userId: number = 0;
	/* Date de creation du sejour */
	creaDate: Date;
	/* Sejour supprime ? */
	suppression: boolean = false;
	/* Date de suppression */
	dateSuppr: Date;
	/* Adresse du sejour */
	adresse: number = 0;
	/* Capacite minimale */
	capaMini: number = 0;
	/* Capacite maximale */
	capaMaxi: number = 0;
	/* Duree minimale */
	dureeMini: number = 0;
	/* Duree maximale */
	dureeMaxi: number = 0;
	/* Unite de duree */
	dureeUnite: string = '';
	/* Delai minimale de reservation */
	delai: number = 0;
	/* Unite du delai */
	delaiUnite: string = '';
	/* Intervalle de temps entre 2 sejours */
	intervalle: number = 0;
	/* Unite de l'intervalle */
	intervalleUnite: string = '';
	/* Possibilite d'hebergement autonome */
	hebergAutonome: boolean = false;
	/* Repas autonome ? */
	repasAutonome: boolean = false;
	/* Provisions matin ? */
	repasProvMatin: boolean = false;
	/* Provision midi ? */
	repasProvMidi: boolean = false;
	/* Provision soir ? */
	repasProvSoir: boolean = false;
	/* Matin prepare ? */
	repasCuisMatin: boolean = false;
	/* Dejeune prepare ? */
	repasCuisMidi: boolean = false;
	/* Diner prepare ? */
	repasCuisSoir: boolean = false;
	/* Prix provision matin pour un adulte */
	prixProvMatinAdulte: number = 0;
	/* Prix provision midi pour un adulte */
	prixProvMidiAdulte: number = 0;
	/* Prix provision soir pour un adulte */
	prixProvSoirAdulte: number = 0;
	/* Prix repas matin pour un adulte */
	prixRepasMatinAdulte: number = 0;
	/* Prix repas midi pour un adulte */
	prixRepasMidiAdulte: number = 0;
	/* Prix repas soir pour un adulte */
	prixRepasSoirAdulte: number = 0;
	/* Prix provision matin pour un enfant */
	prixProvMatinEnfant: number = 0;
	/* Prix provision midi pour un enfant */
	prixProvMidiEnfant: number = 0;
	/* Prix provision soir pour un enfant */
	prixProvSoirEnfant: number = 0;
	/* Prix repas matin pour un enfant */
	prixRepasMatinEnfant: number = 0;
	/* Prix repas midi pour un enfant */
	prixRepasMidiEnfant: number = 0;
	/* Prix repas soir pour un enfant */
	prixRepasSoirEnfant: number = 0;
	/* Devise */
	devise: string = '';
	/* Conditions d'annulation */
	annulation: number = 0;
	/* Photo de couverture */
	cover: number = 0;
	/* Photo de la section decouvrir */
	decouvrir: number = 0;
	/* Photo de la section manger */
	manger: number = 0;
	/* Photo de la section dormir */
	dormir: number = 0;
	/* Photo de la section bouger */
	bouger: number = 0;

	/* Depending object, class Adresse */
	oAdresse: Adresse = new Adresse({});
	/* Depending object, class Cust_unite */
	oUniteDuree: CustUnite = new CustUnite({});
	/* Depending object, class Cust_unite */
	oUniteDelai: CustUnite = new CustUnite({});
	/* Depending object, class Cust_unite */
	oUniteIntervalle: CustUnite = new CustUnite({});
	/* Depending object, class Cust_devise */
	oDevise: CustDevise = new CustDevise({});
	/* Depending object, class Cust_type_annulation */
	oTypeAnnulation: CustTypeAnnulation = new CustTypeAnnulation({});
	/* Depending object, class Media */
	oCover: Media = new Media({});
	/* Depending object, class Media */
	oDecouvrir: Media = new Media({});
	/* Depending object, class Media */
	oManger: Media = new Media({});
	/* Depending object, class Media */
	oDormir: Media = new Media({});
	/* Depending object, class Media */
	oBouger: Media = new Media({});
	/* Depending object, class Sejour_avis */
	aAvis: SejourAvis[] = [];
	/* Depending object, class Dispo */
	aDispo: Dispo[] = [];
	aSejourDispo: SejourDispo[] = []; /* Table pivot de la relation */
	/* Depending object, class Service */
	aService: Service[] = [];
	aSejourService: SejourService[] = []; /* Table pivot de la relation */
	/* Depending object, class Sejour_t */
	aTexte: SejourT[] = [];
	/* Depending object, class Media */
	aMedia: Media[] = [];
	aSejourMedia: SejourMedia[] = []; /* Table pivot de la relation */
	/* Depending object, class Recherche */
	aRecherche: Recherche[] = [];
	aRechercheSejour: RechercheSejour[] = []; /* Table pivot de la relation */

	constructor(oData) {
		super(oData);
		this.hydrate(oData);
		/**
			Debut: Appel de la definition du statut
		*/
		this.defineStatut();
		/**
			Fin: Appel de la definition du statut
		*/
	}

	hydrate(oData) {
		for (var key in oData){
			var method = "set"+key.charAt(0).toUpperCase() + key.slice(1);
			/* Setters dynamic call */
			if(this[method])this[method](oData[key]);
		}
	}

	/* Identifiant du sejour */
	setUid(uid) {
		this.uid = parseInt(uid);
	}
	/* Identifiant utilisateur */
	setUserId(userId) {
		this.userId = parseInt(userId);
	}
	/* Date de creation du sejour */
	setCreaDate(creaDate) {
		this.creaDate = this.toDateObject(creaDate);
	}
	/* Sejour supprime ? */
	setSuppression(suppression) {
		this.suppression = this.toBoolean(suppression);
	}
	/* Date de suppression */
	setDateSuppr(dateSuppr) {
		this.dateSuppr = this.toDateObject(dateSuppr);
	}
	/* Adresse du sejour */
	setAdresse(adresse) {
		this.adresse = parseInt(adresse);
	}
	/* Capacite minimale */
	setCapaMini(capaMini) {
		this.capaMini = parseInt(capaMini);
	}
	/* Capacite maximale */
	setCapaMaxi(capaMaxi) {
		this.capaMaxi = parseInt(capaMaxi);
	}
	/* Duree minimale */
	setDureeMini(dureeMini) {
		this.dureeMini = parseInt(dureeMini);
	}
	/* Duree maximale */
	setDureeMaxi(dureeMaxi) {
		this.dureeMaxi = parseInt(dureeMaxi);
	}
	/* Unite de duree */
	setDureeUnite(dureeUnite) {
		this.dureeUnite = dureeUnite;
	}
	/* Delai minimale de reservation */
	setDelai(delai) {
		this.delai = parseInt(delai);
	}
	/* Unite du delai */
	setDelaiUnite(delaiUnite) {
		this.delaiUnite = delaiUnite;
	}
	/* Intervalle de temps entre 2 sejours */
	setIntervalle(intervalle) {
		this.intervalle = parseInt(intervalle);
	}
	/* Unite de l'intervalle */
	setIntervalleUnite(intervalleUnite) {
		this.intervalleUnite = intervalleUnite;
	}
	/* Possibilite d'hebergement autonome */
	setHebergAutonome(hebergAutonome) {
		this.hebergAutonome = this.toBoolean(hebergAutonome);
	}
	/* Repas autonome ? */
	setRepasAutonome(repasAutonome) {
		this.repasAutonome = this.toBoolean(repasAutonome);
	}
	/* Provisions matin ? */
	setRepasProvMatin(repasProvMatin) {
		this.repasProvMatin = this.toBoolean(repasProvMatin);
	}
	/* Provision midi ? */
	setRepasProvMidi(repasProvMidi) {
		this.repasProvMidi = this.toBoolean(repasProvMidi);
	}
	/* Provision soir ? */
	setRepasProvSoir(repasProvSoir) {
		this.repasProvSoir = this.toBoolean(repasProvSoir);
	}
	/* Matin prepare ? */
	setRepasCuisMatin(repasCuisMatin) {
		this.repasCuisMatin = this.toBoolean(repasCuisMatin);
	}
	/* Dejeune prepare ? */
	setRepasCuisMidi(repasCuisMidi) {
		this.repasCuisMidi = this.toBoolean(repasCuisMidi);
	}
	/* Diner prepare ? */
	setRepasCuisSoir(repasCuisSoir) {
		this.repasCuisSoir = this.toBoolean(repasCuisSoir);
	}
	/* Prix provision matin pour un adulte */
	setPrixProvMatinAdulte(prixProvMatinAdulte) {
		this.prixProvMatinAdulte = parseFloat(prixProvMatinAdulte);
	}
	/* Prix provision midi pour un adulte */
	setPrixProvMidiAdulte(prixProvMidiAdulte) {
		this.prixProvMidiAdulte = parseFloat(prixProvMidiAdulte);
	}
	/* Prix provision soir pour un adulte */
	setPrixProvSoirAdulte(prixProvSoirAdulte) {
		this.prixProvSoirAdulte = parseFloat(prixProvSoirAdulte);
	}
	/* Prix repas matin pour un adulte */
	setPrixRepasMatinAdulte(prixRepasMatinAdulte) {
		this.prixRepasMatinAdulte = parseFloat(prixRepasMatinAdulte);
	}
	/* Prix repas midi pour un adulte */
	setPrixRepasMidiAdulte(prixRepasMidiAdulte) {
		this.prixRepasMidiAdulte = parseFloat(prixRepasMidiAdulte);
	}
	/* Prix repas soir pour un adulte */
	setPrixRepasSoirAdulte(prixRepasSoirAdulte) {
		this.prixRepasSoirAdulte = parseFloat(prixRepasSoirAdulte);
	}
	/* Prix provision matin pour un enfant */
	setPrixProvMatinEnfant(prixProvMatinEnfant) {
		this.prixProvMatinEnfant = parseFloat(prixProvMatinEnfant);
	}
	/* Prix provision midi pour un enfant */
	setPrixProvMidiEnfant(prixProvMidiEnfant) {
		this.prixProvMidiEnfant = parseFloat(prixProvMidiEnfant);
	}
	/* Prix provision soir pour un enfant */
	setPrixProvSoirEnfant(prixProvSoirEnfant) {
		this.prixProvSoirEnfant = parseFloat(prixProvSoirEnfant);
	}
	/* Prix repas matin pour un enfant */
	setPrixRepasMatinEnfant(prixRepasMatinEnfant) {
		this.prixRepasMatinEnfant = parseFloat(prixRepasMatinEnfant);
	}
	/* Prix repas midi pour un enfant */
	setPrixRepasMidiEnfant(prixRepasMidiEnfant) {
		this.prixRepasMidiEnfant = parseFloat(prixRepasMidiEnfant);
	}
	/* Prix repas soir pour un enfant */
	setPrixRepasSoirEnfant(prixRepasSoirEnfant) {
		this.prixRepasSoirEnfant = parseFloat(prixRepasSoirEnfant);
	}
	/* Devise */
	setDevise(devise) {
		this.devise = devise;
	}
	/* Conditions d'annulation */
	setAnnulation(annulation) {
		this.annulation = parseInt(annulation);
	}
	/* Photo de couverture */
	setCover(cover) {
		this.cover = parseInt(cover);
	}
	/* Photo de la section decouvrir */
	setDecouvrir(decouvrir) {
		this.decouvrir = parseInt(decouvrir);
	}
	/* Photo de la section manger */
	setManger(manger) {
		this.manger = parseInt(manger);
	}
	/* Photo de la section dormir */
	setDormir(dormir) {
		this.dormir = parseInt(dormir);
	}
	/* Photo de la section bouger */
	setBouger(bouger) {
		this.bouger = parseInt(bouger);
	}

	/* Depending object, class Adresse */
	setOAdresse(oAdresse) {
		if (this.oAdresse) {
			this.oAdresse.hydrate(oAdresse);
		} else {
			this.oAdresse = new Adresse(oAdresse);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteDuree(oUniteDuree) {
		if (this.oUniteDuree) {
			this.oUniteDuree.hydrate(oUniteDuree);
		} else {
			this.oUniteDuree = new CustUnite(oUniteDuree);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteDelai(oUniteDelai) {
		if (this.oUniteDelai) {
			this.oUniteDelai.hydrate(oUniteDelai);
		} else {
			this.oUniteDelai = new CustUnite(oUniteDelai);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteIntervalle(oUniteIntervalle) {
		if (this.oUniteIntervalle) {
			this.oUniteIntervalle.hydrate(oUniteIntervalle);
		} else {
			this.oUniteIntervalle = new CustUnite(oUniteIntervalle);
		}
	}

	/* Depending object, class Cust_devise */
	setODevise(oDevise) {
		if (this.oDevise) {
			this.oDevise.hydrate(oDevise);
		} else {
			this.oDevise = new CustDevise(oDevise);
		}
	}

	/* Depending object, class Cust_type_annulation */
	setOTypeAnnulation(oTypeAnnulation) {
		if (this.oTypeAnnulation) {
			this.oTypeAnnulation.hydrate(oTypeAnnulation);
		} else {
			this.oTypeAnnulation = new CustTypeAnnulation(oTypeAnnulation);
		}
	}

	/* Depending object, class Media */
	setOCover(oCover) {
		if (this.oCover) {
			this.oCover.hydrate(oCover);
		} else {
			this.oCover = new Media(oCover);
		}
	}

	/* Depending object, class Media */
	setODecouvrir(oDecouvrir) {
		if (this.oDecouvrir) {
			this.oDecouvrir.hydrate(oDecouvrir);
		} else {
			this.oDecouvrir = new Media(oDecouvrir);
		}
	}

	/* Depending object, class Media */
	setOManger(oManger) {
		if (this.oManger) {
			this.oManger.hydrate(oManger);
		} else {
			this.oManger = new Media(oManger);
		}
	}

	/* Depending object, class Media */
	setODormir(oDormir) {
		if (this.oDormir) {
			this.oDormir.hydrate(oDormir);
		} else {
			this.oDormir = new Media(oDormir);
		}
	}

	/* Depending object, class Media */
	setOBouger(oBouger) {
		if (this.oBouger) {
			this.oBouger.hydrate(oBouger);
		} else {
			this.oBouger = new Media(oBouger);
		}
	}

	/* Depending object, class Sejour_avis */
	setAAvis(aAvis) {
		this.aAvis = [];
		for (var i in aAvis) {
			this.setOAvis(aAvis[i]);
		}
	}

	setOAvis(oAvis) {
		var loAvis = new SejourAvis(oAvis);
		this.aAvis.push(loAvis);
	}

	/* Depending object, class Dispo */
	setADispo(aDispo) {
		this.aDispo = [];
		for (var i in aDispo) {
			this.setODispo(aDispo[i]);
		}
	}

	setODispo(oDispo) {
		var loDispo = new Dispo(oDispo);
		this.aDispo.push(loDispo);
	}

	setASejourDispo(aSejourDispo) {
		this.aSejourDispo = [];
		for (var i = 0; i < aSejourDispo.length; i++) {
			this.setOSejourDispo(aSejourDispo[i]);
		}
	}

	setOSejourDispo(oSejourDispo) {
		var loSejourDispo = new SejourDispo(oSejourDispo);
		this.aSejourDispo.push(loSejourDispo);
	}

	/* Depending object, class Service */
	setAService(aService) {
		this.aService = [];
		for (var i in aService) {
			this.setOService(aService[i]);
		}
	}

	setOService(oService) {
		var loService = new Service(oService);
		this.aService.push(loService);
	}

	setASejourService(aSejourService) {
		this.aSejourService = [];
		for (var i = 0; i < aSejourService.length; i++) {
			this.setOSejourService(aSejourService[i]);
		}
	}

	setOSejourService(oSejourService) {
		var loSejourService = new SejourService(oSejourService);
		this.aSejourService.push(loSejourService);
	}

	/* Depending object, class Sejour_t */
	setATexte(aTexte) {
		this.aTexte = [];
		for (var i in aTexte) {
			this.setOTexte(aTexte[i]);
		}
	}

	setOTexte(oTexte) {
		var loTexte = new SejourT(oTexte);
		this.aTexte[loTexte.langue] = loTexte;
	}

	/* Depending object, class Media */
	setAMedia(aMedia) {
		this.aMedia = [];
		for (var i in aMedia) {
			this.setOMedia(aMedia[i]);
		}
	}

	setOMedia(oMedia) {
		var loMedia = new Media(oMedia);
		this.aMedia.push(loMedia);
	}

	setASejourMedia(aSejourMedia) {
		this.aSejourMedia = [];
		for (var i = 0; i < aSejourMedia.length; i++) {
			this.setOSejourMedia(aSejourMedia[i]);
		}
	}

	setOSejourMedia(oSejourMedia) {
		var loSejourMedia = new SejourMedia(oSejourMedia);
		this.aSejourMedia.push(loSejourMedia);
	}

	/* Depending object, class Recherche */
	setARecherche(aRecherche) {
		this.aRecherche = [];
		for (var i in aRecherche) {
			this.setORecherche(aRecherche[i]);
		}
	}

	setORecherche(oRecherche) {
		var loRecherche = new Recherche(oRecherche);
		this.aRecherche.push(loRecherche);
	}

	setARechercheSejour(aRechercheSejour) {
		this.aRechercheSejour = [];
		for (var i = 0; i < aRechercheSejour.length; i++) {
			this.setORechercheSejour(aRechercheSejour[i]);
		}
	}

	setORechercheSejour(oRechercheSejour) {
		var loRechercheSejour = new RechercheSejour(oRechercheSejour);
		this.aRechercheSejour.push(loRechercheSejour);
	}

}
