/************************************************************************************************************************************/
/*																																  */
/*	sejourService.ts
/*	Auteur : Antoine Alleard
/*	Date : 02/02/2017 22:49:05
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } from './mainObject';

export class SejourService extends MainObject {

	/* Identifiant du sejour */
	sejour: number = 0;		/* Primary key */
	/* Identifiant du service */
	service: number = 0;		/* Primary key */


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

	/* Identifiant du sejour */
	setSejour(sejour) {
		this.sejour = parseInt(sejour);
	}
	/* Identifiant du service */
	setService(service) {
		this.service = parseInt(service);
	}

}
