/************************************************************************************************************************************/
/*																																  */
/*	sejourAvis.ts
/*	Auteur : Antoine Alleard
/*	Date : 07/02/2017 14:00:39
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } from './mainObject';

export class SejourAvis extends MainObject {

	/* Identifiant de l'avis */
	uid: number = 0;		/* Primary key */
	/* Identifiant du sejour */
	sejourId: number = 0;
	/* Identifiant de l'utilisateur */
	userId: number = 0;
	/* Date de creation */
	creaDate: Date;
	/* Note de l'implication de l'hote */
	implication: number = 0;
	/* Note de proprete */
	proprete: number = 0;
	/* Note de la description */
	description: number = 0;
	/* Rapport qualite prix */
	qualitePrix: number = 0;
	/* Commentaire du voyageur */
	commentaire: string = '';


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

	/* Identifiant de l'avis */
	setUid(uid) {
		this.uid = parseInt(uid);
	}
	/* Identifiant du sejour */
	setSejourId(sejourId) {
		this.sejourId = parseInt(sejourId);
	}
	/* Identifiant de l'utilisateur */
	setUserId(userId) {
		this.userId = parseInt(userId);
	}
	/* Date de creation */
	setCreaDate(creaDate) {
		this.creaDate = this.toDateObject(creaDate);
	}
	/* Note de l'implication de l'hote */
	setImplication(implication) {
		this.implication = parseInt(implication);
	}
	/* Note de proprete */
	setProprete(proprete) {
		this.proprete = parseInt(proprete);
	}
	/* Note de la description */
	setDescription(description) {
		this.description = parseInt(description);
	}
	/* Rapport qualite prix */
	setQualitePrix(qualitePrix) {
		this.qualitePrix = parseInt(qualitePrix);
	}
	/* Commentaire du voyageur */
	setCommentaire(commentaire) {
		this.commentaire = commentaire;
	}

}
