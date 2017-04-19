/************************************************************************************************************************************/
/*																																  */
/*	service.ts
/*	Auteur : Antoine Alleard
/*	Date : 09/02/2017 17:05:22
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } from './mainObject';
import { CustTypeService } from './custTypeService';
import { CustCatService } from './custCatService';
import { CustSousCatService } from './custSousCatService';
import { Adresse } from './adresse';
import { CustUnite } from './custUnite';
import { CustDevise } from './custDevise';
import { CustJour } from './custJour';
import { ServiceAvis } from './serviceAvis';
import { Dispo } from './dispo';
import { ServiceDispo } from './serviceDispo';
import { ServiceT } from './serviceT';
import { Media } from './media';
import { ServiceMedia } from './serviceMedia';
import { Sejour } from './sejour';
import { SejourService } from './sejourService';
import { ServiceDegressivite } from './serviceDegressivite';
import { CustDelaiPrevenance } from './custDelaiPrevenance';
import { CustIntervalleReservation } from './custIntervalleReservation';

export class Service extends MainObject {

	/* Identifiant du service */
	uid: number = 0;		/* Primary key */
	/* Identifiant du l'utilisateur */
	userId: number = 0;
	/* Date de creation */
	dateCrea: Date;
	/* Service supprime ? */
	suppr: boolean = false;
	/* Date de suppression */
	dateSuppr: Date;
	/* Type de service */
	type: number = 0;
	/* Categorie de service */
	categorie: number = 0;
	/* Sous-categorie de service */
	sousCat: number = 0;
	/* Adresse du service */
	adresse: number = 0;
	/* Capacite minimale */
	capaMin: number = 0;
	/* Capacite maximale */
	capaMax: number = 0;
	/* Duree minimale */
	dureeMin: number = 0;
	/* Unite de la duree minimale */
	dureeMinUnit: string = '';
	/* Duree maximale */
	dureeMax: number = 0;
	/* Unite de la duree maximale */
	dureeMaxUnit: string = '';
	/* Heure de debut minimale */
	heureDebutMin: string = '';
	/* Heure de debut maximale */
	heureDebutMax: string = '';
	/* Heure de fin minimale */
	heureFinMin: string = '';
	/* Heure de fin maximale */
	heureFinMax: string = '';
	/* Pause */
	pause: boolean = false;
	/* Heure de debut de la pause */
	pauseDebut: string = '';
	/* Heure de fin de la pause */
	pauseFin: string = '';
	/* Quantite minmale */
	quantiteMin: number = 0;
	/* Unite de quantite minimale */
	quantiteMinUnit: string = '';
	/* Quantite maximale */
	quantiteMax: number = 0;
	/* Unite de quantite maximale */
	quantiteMaxUnit: string = '';
	/* Nombre de reservations simultanees */
	simultane: number = 0;
	/* Acces aux personnes handicapees */
	handicap: boolean = false;
	/* Animaux acceptes */
	animaux: boolean = false;
	/* Fumeurs acceptes */
	fumeur: boolean = false;
	/* Condition d'age */
	age: boolean = false;
	/* Certificat medical requis */
	certif: boolean = false;
	/* Permis de conduire requis */
	permis: boolean = false;
	/* Linge de maison fourni */
	lingeMaison: boolean = false;
	/* Serviettes de bain fournies */
	serviette: boolean = false;
	/* Lave linge */
	laveLinge: boolean = false;
	/* Lave vaisselle */
	laveVaisselle: boolean = false;
	/* Seche cheveux */
	secheCheveux: boolean = false;
	/* Fer a repasser */
	ferRepasser: boolean = false;
	/* Acces internet */
	internet: boolean = false;
	/* Lit bebe */
	litBebe: boolean = false;
	/* Table a langer */
	tableLanger: boolean = false;
	/* Piscine */
	piscine: boolean = false;
	/* Parking */
	parking: boolean = false;
	/* Ascenseur */
	ascenseur: boolean = false;
	/* Materiel fourni */
	materiel: boolean = false;
	/* Repas fourni le matin */
	repasMatin: boolean = false;
	/* Repas fourni le midi */
	repasMidi: boolean = false;
	/* Repas fourni le soir */
	repasSoir: boolean = false;
	/* Prix */
	prix: number = 0;
	/* Prix enfant */
	prixEnfant: number = 0;
	/* Unite de prix */
	prixUnite: string = '';
	/* Devise */
	devise: string = '';
	/* Modalites de reservation. 1 = Ouvert a tous. 0 = par mes sejours */
	modaliteResa: boolean = false;
	/* Taux de commission */
	tauxCommission: number = 0;
	/* Jour par defaut */
	jourDefaut: number = 0;
	/* Heure par defaut */
	heureDefaut: string = '';
	/* Delai de prevenance */
	prevenance: number = 0;
	/* Intervalle entre deux reservations */
	intervalle: number = 0;

	/* Depending object, class Cust_type_service */
	oTypeService: CustTypeService = new CustTypeService({});
	/* Depending object, class Cust_cat_service */
	oCatService: CustCatService = new CustCatService({});
	/* Depending object, class Cust_sous_cat_service */
	oSousCatService: CustSousCatService = new CustSousCatService({});
	/* Depending object, class Adresse */
	oAdresse: Adresse = new Adresse({});
	/* Depending object, class Cust_unite */
	oUniteDureeMin: CustUnite = new CustUnite({});
	/* Depending object, class Cust_unite */
	oUniteDureeMax: CustUnite = new CustUnite({});
	/* Depending object, class Cust_unite */
	oUniteQuantiteMin: CustUnite = new CustUnite({});
	/* Depending object, class Cust_unite */
	oUniteQuantiteMax: CustUnite = new CustUnite({});
	/* Depending object, class Cust_devise */
	oDevise: CustDevise = new CustDevise({});
	/* Depending object, class Cust_jour */
	oJour: CustJour = new CustJour({});
	/* Depending object, class Service_avis */
	aAvis: ServiceAvis[] = [];
	/* Depending object, class Dispo */
	aDispo: Dispo[] = [];
	aServiceDispo: ServiceDispo[] = []; /* Table pivot de la relation */
	/* Depending object, class Service_t */
	aTexte: ServiceT[] = [];
	/* Depending object, class Media */
	aMedia: Media[] = [];
	aServiceMedia: ServiceMedia[] = []; /* Table pivot de la relation */
	/* Depending object, class Sejour */
	aSejour: Sejour[] = [];
	aSejourService: SejourService[] = []; /* Table pivot de la relation */
	/* Depending object, class Service_degressivite */
	aDegressivites: ServiceDegressivite[] = [];
	/* Depending object, class Cust_delai_prevenance */
	oDelaiPrevenance: CustDelaiPrevenance = new CustDelaiPrevenance({});
	/* Depending object, class Cust_intervalle_reservation */
	oIntervalleReservation: CustIntervalleReservation = new CustIntervalleReservation({});

	constructor(oData) {
		super(oData);
		this.hydrate(oData);
	}

	hydrate(oData) {
		for (var key in oData){
			var method = "set"+key.charAt(0).toUpperCase() + key.slice(1);
			/* Setters dynamic call */
			if(this[method])this[method](oData[key]);
		}
	}

	/* Identifiant du service */
	setUid(uid) {
		this.uid = parseInt(uid);
	}
	/* Identifiant du l'utilisateur */
	setUserId(userId) {
		this.userId = parseInt(userId);
	}
	/* Date de creation */
	setDateCrea(dateCrea) {
		this.dateCrea = this.toDateObject(dateCrea);
	}
	/* Service supprime ? */
	setSuppr(suppr) {
		this.suppr = this.toBoolean(suppr);
	}
	/* Date de suppression */
	setDateSuppr(dateSuppr) {
		this.dateSuppr = this.toDateObject(dateSuppr);
	}
	/* Type de service */
	setType(type) {
		this.type = parseInt(type);
	}
	/* Categorie de service */
	setCategorie(categorie) {
		this.categorie = parseInt(categorie);
	}
	/* Sous-categorie de service */
	setSousCat(sousCat) {
		this.sousCat = parseInt(sousCat);
	}
	/* Adresse du service */
	setAdresse(adresse) {
		this.adresse = parseInt(adresse);
	}
	/* Capacite minimale */
	setCapaMin(capaMin) {
		this.capaMin = parseInt(capaMin);
	}
	/* Capacite maximale */
	setCapaMax(capaMax) {
		this.capaMax = parseInt(capaMax);
	}
	/* Duree minimale */
	setDureeMin(dureeMin) {
		this.dureeMin = parseInt(dureeMin);
	}
	/* Unite de la duree minimale */
	setDureeMinUnit(dureeMinUnit) {
		this.dureeMinUnit = dureeMinUnit;
	}
	/* Duree maximale */
	setDureeMax(dureeMax) {
		this.dureeMax = parseInt(dureeMax);
	}
	/* Unite de la duree maximale */
	setDureeMaxUnit(dureeMaxUnit) {
		this.dureeMaxUnit = dureeMaxUnit;
	}
	/* Heure de debut minimale */
	setHeureDebutMin(heureDebutMin) {
		this.heureDebutMin = heureDebutMin;
	}
	/* Heure de debut maximale */
	setHeureDebutMax(heureDebutMax) {
		this.heureDebutMax = heureDebutMax;
	}
	/* Heure de fin minimale */
	setHeureFinMin(heureFinMin) {
		this.heureFinMin = heureFinMin;
	}
	/* Heure de fin maximale */
	setHeureFinMax(heureFinMax) {
		this.heureFinMax = heureFinMax;
	}
	/* Pause */
	setPause(pause) {
		this.pause = this.toBoolean(pause);
	}
	/* Heure de debut de la pause */
	setPauseDebut(pauseDebut) {
		this.pauseDebut = pauseDebut;
	}
	/* Heure de fin de la pause */
	setPauseFin(pauseFin) {
		this.pauseFin = pauseFin;
	}
	/* Quantite minmale */
	setQuantiteMin(quantiteMin) {
		this.quantiteMin = parseFloat(quantiteMin);
	}
	/* Unite de quantite minimale */
	setQuantiteMinUnit(quantiteMinUnit) {
		this.quantiteMinUnit = quantiteMinUnit;
	}
	/* Quantite maximale */
	setQuantiteMax(quantiteMax) {
		this.quantiteMax = parseFloat(quantiteMax);
	}
	/* Unite de quantite maximale */
	setQuantiteMaxUnit(quantiteMaxUnit) {
		this.quantiteMaxUnit = quantiteMaxUnit;
	}
	/* Nombre de reservations simultanees */
	setSimultane(simultane) {
		this.simultane = parseInt(simultane);
	}
	/* Acces aux personnes handicapees */
	setHandicap(handicap) {
		this.handicap = this.toBoolean(handicap);
	}
	/* Animaux acceptes */
	setAnimaux(animaux) {
		this.animaux = this.toBoolean(animaux);
	}
	/* Fumeurs acceptes */
	setFumeur(fumeur) {
		this.fumeur = this.toBoolean(fumeur);
	}
	/* Condition d'age */
	setAge(age) {
		this.age = this.toBoolean(age);
	}
	/* Certificat medical requis */
	setCertif(certif) {
		this.certif = this.toBoolean(certif);
	}
	/* Permis de conduire requis */
	setPermis(permis) {
		this.permis = this.toBoolean(permis);
	}
	/* Linge de maison fourni */
	setLingeMaison(lingeMaison) {
		this.lingeMaison = this.toBoolean(lingeMaison);
	}
	/* Serviettes de bain fournies */
	setServiette(serviette) {
		this.serviette = this.toBoolean(serviette);
	}
	/* Lave linge */
	setLaveLinge(laveLinge) {
		this.laveLinge = this.toBoolean(laveLinge);
	}
	/* Lave vaisselle */
	setLaveVaisselle(laveVaisselle) {
		this.laveVaisselle = this.toBoolean(laveVaisselle);
	}
	/* Seche cheveux */
	setSecheCheveux(secheCheveux) {
		this.secheCheveux = this.toBoolean(secheCheveux);
	}
	/* Fer a repasser */
	setFerRepasser(ferRepasser) {
		this.ferRepasser = this.toBoolean(ferRepasser);
	}
	/* Acces internet */
	setInternet(internet) {
		this.internet = this.toBoolean(internet);
	}
	/* Lit bebe */
	setLitBebe(litBebe) {
		this.litBebe = this.toBoolean(litBebe);
	}
	/* Table a langer */
	setTableLanger(tableLanger) {
		this.tableLanger = this.toBoolean(tableLanger);
	}
	/* Piscine */
	setPiscine(piscine) {
		this.piscine = this.toBoolean(piscine);
	}
	/* Parking */
	setParking(parking) {
		this.parking = this.toBoolean(parking);
	}
	/* Ascenseur */
	setAscenseur(ascenseur) {
		this.ascenseur = this.toBoolean(ascenseur);
	}
	/* Materiel fourni */
	setMateriel(materiel) {
		this.materiel = this.toBoolean(materiel);
	}
	/* Repas fourni le matin */
	setRepasMatin(repasMatin) {
		this.repasMatin = this.toBoolean(repasMatin);
	}
	/* Repas fourni le midi */
	setRepasMidi(repasMidi) {
		this.repasMidi = this.toBoolean(repasMidi);
	}
	/* Repas fourni le soir */
	setRepasSoir(repasSoir) {
		this.repasSoir = this.toBoolean(repasSoir);
	}
	/* Prix */
	setPrix(prix) {
		this.prix = parseFloat(prix);
	}
	/* Prix enfant */
	setPrixEnfant(prixEnfant) {
		this.prixEnfant = parseFloat(prixEnfant);
	}
	/* Unite de prix */
	setPrixUnite(prixUnite) {
		this.prixUnite = prixUnite;
	}
	/* Devise */
	setDevise(devise) {
		this.devise = devise;
	}
	/* Modalites de reservation. 1 = Ouvert a tous. 0 = par mes sejours */
	setModaliteResa(modaliteResa) {
		this.modaliteResa = this.toBoolean(modaliteResa);
	}
	/* Taux de commission */
	setTauxCommission(tauxCommission) {
		this.tauxCommission = parseFloat(tauxCommission);
	}
	/* Jour par defaut */
	setJourDefaut(jourDefaut) {
		this.jourDefaut = parseInt(jourDefaut);
	}
	/* Heure par defaut */
	setHeureDefaut(heureDefaut) {
		this.heureDefaut = heureDefaut;
	}
	/* Delai de prevenance */
	setPrevenance(prevenance) {
		this.prevenance = parseInt(prevenance);
	}
	/* Intervalle entre deux reservations */
	setIntervalle(intervalle) {
		this.intervalle = parseInt(intervalle);
	}

	/* Depending object, class Cust_type_service */
	setOTypeService(oTypeService) {
		if (this.oTypeService) {
			this.oTypeService.hydrate(oTypeService);
		} else {
			this.oTypeService = new CustTypeService(oTypeService);
		}
	}

	/* Depending object, class Cust_cat_service */
	setOCatService(oCatService) {
		if (this.oCatService) {
			this.oCatService.hydrate(oCatService);
		} else {
			this.oCatService = new CustCatService(oCatService);
		}
	}

	/* Depending object, class Cust_sous_cat_service */
	setOSousCatService(oSousCatService) {
		if (this.oSousCatService) {
			this.oSousCatService.hydrate(oSousCatService);
		} else {
			this.oSousCatService = new CustSousCatService(oSousCatService);
		}
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
	setOUniteDureeMin(oUniteDureeMin) {
		if (this.oUniteDureeMin) {
			this.oUniteDureeMin.hydrate(oUniteDureeMin);
		} else {
			this.oUniteDureeMin = new CustUnite(oUniteDureeMin);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteDureeMax(oUniteDureeMax) {
		if (this.oUniteDureeMax) {
			this.oUniteDureeMax.hydrate(oUniteDureeMax);
		} else {
			this.oUniteDureeMax = new CustUnite(oUniteDureeMax);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteQuantiteMin(oUniteQuantiteMin) {
		if (this.oUniteQuantiteMin) {
			this.oUniteQuantiteMin.hydrate(oUniteQuantiteMin);
		} else {
			this.oUniteQuantiteMin = new CustUnite(oUniteQuantiteMin);
		}
	}

	/* Depending object, class Cust_unite */
	setOUniteQuantiteMax(oUniteQuantiteMax) {
		if (this.oUniteQuantiteMax) {
			this.oUniteQuantiteMax.hydrate(oUniteQuantiteMax);
		} else {
			this.oUniteQuantiteMax = new CustUnite(oUniteQuantiteMax);
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

	/* Depending object, class Cust_jour */
	setOJour(oJour) {
		if (this.oJour) {
			this.oJour.hydrate(oJour);
		} else {
			this.oJour = new CustJour(oJour);
		}
	}

	/* Depending object, class Service_avis */
	setAAvis(aAvis) {
		this.aAvis = [];
		for (var i in aAvis) {
			this.setOAvis(aAvis[i]);
		}
	}

	setOAvis(oAvis) {
		var loAvis = new ServiceAvis(oAvis);
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

	setAServiceDispo(aServiceDispo) {
		this.aServiceDispo = [];
		for (var i = 0; i < aServiceDispo.length; i++) {
			this.setOServiceDispo(aServiceDispo[i]);
		}
	}

	setOServiceDispo(oServiceDispo) {
		var loServiceDispo = new ServiceDispo(oServiceDispo);
		this.aServiceDispo.push(loServiceDispo);
	}

	/* Depending object, class Service_t */
	setATexte(aTexte) {
		this.aTexte = [];
		for (var i in aTexte) {
			this.setOTexte(aTexte[i]);
		}
	}

	setOTexte(oTexte) {
		var loTexte = new ServiceT(oTexte);
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

	setAServiceMedia(aServiceMedia) {
		this.aServiceMedia = [];
		for (var i = 0; i < aServiceMedia.length; i++) {
			this.setOServiceMedia(aServiceMedia[i]);
		}
	}

	setOServiceMedia(oServiceMedia) {
		var loServiceMedia = new ServiceMedia(oServiceMedia);
		this.aServiceMedia.push(loServiceMedia);
	}

	/* Depending object, class Sejour */
	setASejour(aSejour) {
		this.aSejour = [];
		for (var i in aSejour) {
			this.setOSejour(aSejour[i]);
		}
	}

	setOSejour(oSejour) {
		var loSejour = new Sejour(oSejour);
		this.aSejour.push(loSejour);
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

	/* Depending object, class Service_degressivite */
	setADegressivites(aDegressivites) {
		this.aDegressivites = [];
		for (var i in aDegressivites) {
			this.setODegressivites(aDegressivites[i]);
		}
	}

	setODegressivites(oDegressivites) {
		var loDegressivites = new ServiceDegressivite(oDegressivites);
		this.aDegressivites.push(loDegressivites);
	}

	/* Depending object, class Cust_delai_prevenance */
	setODelaiPrevenance(oDelaiPrevenance) {
		if (this.oDelaiPrevenance) {
			this.oDelaiPrevenance.hydrate(oDelaiPrevenance);
		} else {
			this.oDelaiPrevenance = new CustDelaiPrevenance(oDelaiPrevenance);
		}
	}

	/* Depending object, class Cust_intervalle_reservation */
	setOIntervalleReservation(oIntervalleReservation) {
		if (this.oIntervalleReservation) {
			this.oIntervalleReservation.hydrate(oIntervalleReservation);
		} else {
			this.oIntervalleReservation = new CustIntervalleReservation(oIntervalleReservation);
		}
	}

}
