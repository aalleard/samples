/************************************************************************************************************************************/
/*																																  */
/*	custDevise.ts
/*	Auteur : Antoine Alleard
/*	Date : 02/02/2017 22:49:05
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } from './mainObject';
import { CustDeviseT } from './custDeviseT';

export class CustDevise extends MainObject {

	/* Devise */
	devise: string = '';		/* Primary key */

	/* Depending object, class Cust_devise_t */
	aTexte: CustDeviseT[] = [];

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

	/* Devise */
	setDevise(devise) {
		this.devise = devise;
	}

	/* Depending object, class Cust_devise_t */
	setATexte(aTexte) {
		this.aTexte = [];
		for (var i in aTexte) {
			this.setOTexte(aTexte[i]);
		}
	}

	setOTexte(oTexte) {
		var loTexte = new CustDeviseT(oTexte);
		this.aTexte[loTexte.langue] = loTexte;
	}

}
