/* Standard components */
import { Injectable }     		from '@angular/core';
import { Http, 
			Response,
			URLSearchParams,
			Headers,
			RequestOptions }  	from '@angular/http';
import { Observable }     		from 'rxjs/Observable';

// Serveur data
import { ApiService } from '../modules/core/providers/api.service';

// Class
import { Sejour } from '../classes/Sejour';

// MOCK Data
// import { SEJOUR }     from './mock/mock-sejour';

@Injectable()
export class SejourService {

	sUrl: string;

	constructor (private http: Http, private apiService: ApiService) {
		this.setUrl(this.apiService.getServeur()+'Sejour/');  // URL to web api
	}

	setUrl(url){
		this.sUrl = url;
	}

	// Delete a record
	delete(oSejour: Sejour): Observable<any>{
		return this.http.delete(oSejour.uri)
					.map(this.extractData)
					.catch(this.handleError);
	}

	// Read a record with its URI
	get(sUri): Observable<any> {
		// Call the backend
		return this.http.get(sUri)
					.map(this.extractData)
					.catch(this.handleError);
	}

	// Read a set of records
	read(oParam): Observable<any> {
		// Define new object for GET parameters
		var oParams = new URLSearchParams();

		// Loop on each attributes of oParam object
		for (var sKey in oParam){
			if(oParam.hasOwnProperty(sKey)){
				oParams.set(sKey, oParam[sKey]);
			}
		}

		// Call the backend
		return this.http.get(this.sUrl,{
						search: oParams
					})
					.map(this.extractData)
					.catch(this.handleError);
	}

	// Create records
	post(sejour): Observable<any> {
		let aData = [];
		if( Object.prototype.toString.call( sejour ) === '[object Array]' ) {
			aData = sejour;
		} else {
			aData.push(sejour);
		}
		let oData = {
			aSejour: aData
		}
		/**
			Debut: Gestion des texte
		*/
		for (var i in oData.aSejour) {
			// Sejour
			for (var sLangue in oData.aSejour[i].aTexte) {
				oData.aSejour[i].aTexte.push(oData.aSejour[i].aTexte[sLangue]);
			}
			// Service
			for (var j in oData.aSejour[i].aService) {
				for (var sLangue in oData.aSejour[i].aService[j].aTexte) {
					oData.aSejour[i].aService[j].aTexte.push(oData.aSejour[i].aService[j].aTexte[sLangue]);
				}
			}
		}
		/**
			Fin: gestion des textes
		*/
		let oBody = JSON.stringify({oData: oData});
		let oHeaders = new Headers({ 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8' });
		let oOptions = new RequestOptions({ headers: oHeaders });

		return this.http.post(this.sUrl, oBody, oOptions)
					.map(this.extractData)
					.catch(this.handleError);
	}

	// Update a record
	put(oSejour: Sejour): Observable<any>{
		let oBody = JSON.stringify({oData: oSejour});
		let oHeaders = new Headers({ 'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8' });
		let oOptions = new RequestOptions({ headers: oHeaders });

		return this.http.put(oSejour.uri, oBody, oOptions)
					.map(this.extractData)
					.catch(this.handleError);
	}

	// handle server response
	private extractData(res: Response) {
		if (res.status < 200 || res.status >= 300) {
			throw new Error('Bad response status: ' + res.status);
		}
		let body = res.json();
		return body || { };
	}

	// Error handling
	private handleError (error: any) {
		// In a real world app, we might send the error to remote logging infrastructure
		let errMsg = error.message || 'Server error';
		console.error(errMsg); // log to console instead
		return Observable.throw(errMsg);
	}

}
