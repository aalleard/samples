// Standard components
import { Component, OnInit, NgZone,
            ViewChild, Renderer }            from '@angular/core';
import { ActivatedRoute, Params, 
            Router } 	            from '@angular/router';
import { TranslateService }         from 'ng2-translate/ng2-translate';

// Classes
import { Adresse }                  from '../../../classes/adresse';
import { CustVille }                from '../../../classes/custVille';
import { Sejour }                   from '../../../classes/sejour';
import { SejourT } 					from '../../../classes/sejourT';
import { LienMenuPage }             from '../../../classes/lienMenuPage'; 

// Providers
import { ApiService, 
            AppService }            from '../../../modules/core/providers';
import { SejourService }			from '../../../services/sejour.service';
import { CustVilleService }         from '../../../services/custVille.service';


@Component({
    templateUrl: './sojourn.html',
    styleUrls: ['./sojourn.scss'],
    providers:[
    	SejourService,
        CustVilleService
    ]
})
export class SojournComponent implements OnInit {

	oSejour: Sejour = new Sejour({});
	bDisplayPage = false;
	bCreateMode = false;

    // Gestion de l'upload des fichiers
    iImage: number = 0;
    uploadUrl: string;
    uploadOptions: Object;

    // Gestion de la Map Google
    @ViewChild('mapGoogle') mapGoogle;
    iMapHeight: number = 0;
    lat: number = 0;
    lng: number = 0;

/* ================================================================================================================= */

	constructor(private route: ActivatedRoute,
                private _router: Router,
                private _translate: TranslateService,
                private _apiService: ApiService,
                private _appService: AppService,
				private _sejourService: SejourService,
                public  renderer: Renderer) {
        this.uploadUrl = this._apiService.getUploadUrl();
    }

/* ================================================================================================================= */

    ngOnInit() {
        console.log('Hello Sojourn page');
        this.initLienMenu();

        this.route.params.forEach((params: Params) => {
            this._appService.iSejourId = +params['uid'];
		    if(this._appService.iSejourId == 0){
                this.initSejour();
		    	this.bCreateMode  = true;
		    	this.bDisplayPage = true;
		    } else {
    		    this.loadSejour();
		    }
		});

    }

    ngDoCheck() {
        if(this.mapGoogle) {
            this.iMapHeight = this.mapGoogle.nativeElement.clientWidth;
        }
    }

    initSejour() {
        // Options generales
        this.oSejour.hebergAutonome = true;
        this.oSejour.repasAutonome  = true;
        // Langue par defaut des textes
        this.oSejour.aTexte[this._appService.sLangue] = new SejourT({langue: this._appService.sLangue});
        // Images par defaut
        this.oSejour.oCover.url      = '../img/default/sejour/cover.jpg';
        this.oSejour.oDecouvrir.url  = '../img/default/sejour/decouvrir.jpg';
        this.oSejour.oBouger.url     = '../img/default/sejour/bouger.jpg';
        this.oSejour.oDormir.url     = '../img/default/sejour/dormir.jpg';
        this.oSejour.oManger.url     = '../img/default/sejour/manger.png';
        // Capacite d'accueil
        this.oSejour.dureeMini = 1;
        this.oSejour.dureeMaxi = 14;
        this.oSejour.capaMini  = 1;
        this.oSejour.capaMaxi  = 4;
        // Prix des repas
        this.oSejour.prixProvMatinAdulte  = 2;
        this.oSejour.prixProvMidiAdulte   = 5;
        this.oSejour.prixProvSoirAdulte   = 7;
        this.oSejour.prixProvMatinEnfant  = 2;
        this.oSejour.prixProvMidiEnfant   = 4;
        this.oSejour.prixProvSoirEnfant   = 5;
        this.oSejour.prixRepasMatinAdulte = 4;
        this.oSejour.prixRepasMidiAdulte  = 9;
        this.oSejour.prixRepasSoirAdulte  = 12;
        this.oSejour.prixRepasMatinEnfant = 4;
        this.oSejour.prixRepasMidiEnfant  = 7;
        this.oSejour.prixRepasSoirEnfant  = 9;
    }

/* ================================================================================================================= */

    initLienMenu(){
        let aLiens = [];
        this._appService.aMenuLinks = [];

        this._translate.get("sojourn").subscribe(value => {

            this._appService.sTitre = value.titre;

            aLiens.push(value.section1.titre);
            aLiens.push(value.section2.titre);
            aLiens.push(value.section3.titre);
            aLiens.push(value.section4.titre);
            aLiens.push(value.section5.titre);
            aLiens.push(value.section6.titre);

            for (var i = 0; i < aLiens.length; ++i) {
                let oLien = new LienMenuPage({
                    text: aLiens[i],
                    id: i
                });
                this._appService.aMenuLinks.push(oLien);
            }

        });

    }

/* ================================================================================================================= */

    loadSejour(){
        this._sejourService.read({
            uid: this._appService.iSejourId,
            expand:[
                'adresse',
                'bouger',
                'cover',
                'decouvrir',
                'dormir',
                'manger'
            ]
        })
            .subscribe(
                data => {
                    this.oSejour = new Sejour(data.oData.aSejour[0]);
                    // Affichage de la carte
                    this.setCoordinates();
                    // On affiche la page
                    this.bDisplayPage = true;
                }
            )
    }

/* ================================================================================================================= */

    initUpload(iImage) {
        this.iImage = iImage;
        this.uploadOptions = {
            url: this.uploadUrl,
            data:{
                type: 1,
                session: this._appService.oSession.session
            } 
        };
    }

    handleUpload(oData: any, iImage): void {
        if(iImage != this.iImage) {
            return;
        }

        if(oData && oData.response) {
            let oResponse = JSON.parse(oData.response);
            if(oResponse.oData.aMedia[0]) { 
                switch (iImage) {
                    case 1:
                        this.oSejour.cover = oResponse.oData.aMedia[0].uid;
                        this.oSejour.oCover.hydrate(oResponse.oData.aMedia[0]);
                        break;
                    case 2:
                        this.oSejour.decouvrir = oResponse.oData.aMedia[0].uid;
                        this.oSejour.oDecouvrir.hydrate(oResponse.oData.aMedia[0]);
                        break;
                    case 3:
                        this.oSejour.manger = oResponse.oData.aMedia[0].uid;
                        this.oSejour.oManger.hydrate(oResponse.oData.aMedia[0]);
                        break;
                    case 4:
                        this.oSejour.dormir = oResponse.oData.aMedia[0].uid;
                        this.oSejour.oDormir.hydrate(oResponse.oData.aMedia[0]);
                        break;
                    case 5:
                        this.oSejour.bouger = oResponse.oData.aMedia[0].uid;
                        this.oSejour.oBouger.hydrate(oResponse.oData.aMedia[0]);
                        break;
                }
            } else {
                this._appService.displayMessages(oResponse.oData.aMessage);
            }
        }
    }

/* ================================================================================================================= */

    setAdresseVille(oAdresse: Adresse, oVille: CustVille) {
        oAdresse.setVille(oVille.villeId);
        oAdresse.setCodePostal(oVille.villeCodePostal)
        oAdresse.setOVille(oVille);
        this.setCoordinates();
    }

    setCoordinates() {
        if(this.oSejour.oAdresse.ville != 0) {
            this.lat = this.oSejour.oAdresse.oVille.villeLatitudeDeg;
            this.lng = this.oSejour.oAdresse.oVille.villeLongitudeDeg;
        }
    }

/* ================================================================================================================= */

    save(form){

        if(form) {
            // Si les deux formulaires sont complets
            if(this.bCreateMode){
                // Initialisation de certains paramètres
                this.oSejour.setUserId(this._appService.userInfo.oProfil.uid);
                this.oSejour.setDevise(this._appService.userInfo.oProfil.devise);
            }
        	this._sejourService.post(this.oSejour)
        		.subscribe(
        			data => {
                        if(data.oData.aSejour[0]){
                            this.oSejour = new Sejour(data.oData.aSejour[0]);
                            if(this.oSejour.sHasError) { 
                                this._appService.displayMessages(this.oSejour.aMessages);
                            } else {
                                this._appService.iServiceId = this.oSejour.uid;
                                // Routing vers la page des services
                                let link = ['/hosting/sojourns'];
                                this._router.navigate(link);
                            }
                        } else {
                            this._appService.displayMessages(data.oData.aMessage);
                        }
        			}
        			)
        } else {
            // Les données ne sont pas complètes
            this._translate.get("commun").subscribe(value => alert(value.messages.formIncomplet));
        }
    }

}
