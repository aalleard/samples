/************************************************************************************************************************************/
/*																																  */
/*	mainObject.ts
/*	Auteur : Antoine AllÃ©ard
/*	Date : 10/01/2016 13:44:59
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

import { DateFunctions } from './utilities/dateFunctions';
import { Message } 		 from './message';

export class MainObject {

	sHasError: 	boolean 	= false;
	sDelete:	boolean 	= false;
	aMessages: 	Message[] 	= [];
	uri: string 			= "";

	constructor(oData) {

	}

	setSHasError(sHasError) {
		this.sHasError = this.toBoolean(sHasError);
	}

	setSDelete(sDelete) {
		this.sDelete = this.toBoolean(sDelete);
	}

	setAMessages(aMessages) {
		this.aMessages = [];
		for (var i in aMessages) {
			this.setOMessage(aMessages[i]);
		}
	}

	setOMessage(oMessage) {
		var loMessage = new Message(oMessage);
		this.aMessages.push(loMessage);
	}

	setUri(uri) {
		this.uri = uri;
	}

	toBoolean(value){
		if(value == 1 || value == "1" || value == "X" || value == true) { 
			return true;
		} else {
			return false;
		}
	}

	toDateObject(date) {
		return DateFunctions.toDateObject(date);
	}

}