/************************************************************************************************************************************/
/*																																  */
/*	sejourT.ts
/*	Auteur : Antoine Alleard
/*	Date : 02/02/2017 22:49:05
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { MainObject } from './mainObject';
import { CustLangue } from './custLangue';

export class SejourT extends MainObject {

	/* Identifiant du sejour */
	sejourId: number = 0;		/* Primary key */
	/* Langue des textes */
	langue: string = '';		/* Primary key */
	/* Titre du sejour */
	titre: string = '';
	/* Description longue */
	texteLong: string = '';
	/* Nature du sejour */
	nature: string = '';
	/* Commentaire sur la cible visee */
	commentaire: string = '';
	/* Presentation des repas */
	repas: string = '';

	/* Depending object, class Cust_langue */
	oLangue: CustLangue = new CustLangue({});

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
	setSejourId(sejourId) {
		this.sejourId = parseInt(sejourId);
	}
	/* Langue des textes */
	setLangue(langue) {
		this.langue = langue;
	}
	/* Titre du sejour */
	setTitre(titre) {
		this.titre = titre;
	}
	/* Description longue */
	setTexteLong(texteLong) {
		this.texteLong = texteLong;
	}
	/* Nature du sejour */
	setNature(nature) {
		this.nature = nature;
	}
	/* Commentaire sur la cible visee */
	setCommentaire(commentaire) {
		this.commentaire = commentaire;
	}
	/* Presentation des repas */
	setRepas(repas) {
		this.repas = repas;
	}

	/* Depending object, class Cust_langue */
	setOLangue(oLangue) {
		if (this.oLangue) {
			this.oLangue.hydrate(oLangue);
		} else {
			this.oLangue = new CustLangue(oLangue);
		}
	}

}
