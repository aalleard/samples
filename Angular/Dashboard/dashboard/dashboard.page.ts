import { Component, ViewChild, ElementRef } from '@angular/core';
import { NavigationExtras } from '@angular/router';

// Components
import { BaseComponent } from '@app/shared/components/base.component';
import { LegalEntityExtractAccountingFormDialogComponent } from '@app/legal-entity/components/legal-entity-extract-accounting-form-dialog/legal-entity-extract-accounting-form-dialog.component';

// Classes
import { BankAccount } from '@app/shared/models/bank-account.model';
import { Document } from '@app/shared/models/document.model';
import { File } from '@app/shared/models/file.model';
import { Kpi } from '@app/shared/models/kpi.model';

// Utilities
import { Filter } from '@app/shared/utilities/models/filter';
import { Sorter } from '@app/shared/utilities/models/sorter';
import { Sorting } from '@app/shared/utilities/array/sorting';

@Component({
	selector: 'mihy-dashboard',
	templateUrl: './dashboard.page.html',
	styleUrls: ['./dashboard.page.scss']
})
export class DashboardPage extends BaseComponent {

	// Data
	private _aBankAccounts: BankAccount[] = [];
	private _aKpis: Kpi[] = [];

	// View elements
	@ViewChild('documentInput', { static: true }) oDocumentInput: ElementRef;
	@ViewChild('page', { static: true }) oPage: ElementRef;
	
	// Query parameters
	private _oBankAccountsFilter: Filter;

	ngOnInit() {
		super.ngOnInit();

		if (this.appService?.aManagedEntities.length > 1) {
			this.appService.showNavButton();
		} else {
			this.appService.hideNavButton();
		}

		this._initDummyKpis();
	}

	/**
	 * Build filters to query bank accounts
	 */
	private _buildBankAccountsFilter(): void {
		this._oBankAccountsFilter = new Filter({
			aFilters: [
				// Owner
				new Filter({
					sProperty: 'owner.id',
					sComparator: Filter.COMPARATOR.EQ,
					value: this._iEntityId
				}),
				// Archived
				new Filter({
					sProperty: 'dashboard',
					sComparator: Filter.COMPARATOR.EQ,
					value: true
				})
			]
		});
	}

	/**
	 * Method that creates a new document from the media file that was uploaded
	 * @param {File} oFile the File object that was created
	 */
	private _createDocument(oFile: File): void {
		let oDocument: Document = Document.init();
		oDocument.setFile(oFile);
		oDocument.setOwner({
			id: this._iEntityId
		});
		this.documentService.save(oDocument, { bSelect: false });
	}

	/**
	 * Getter for _aBankAccounts
	 * @returns {BankAccount[]} An array of BankAccount objects
	 */
	public get aBankAccounts(): BankAccount[] {
		return this._aBankAccounts;
	}
	
	/**
	 * Getter for _aKpis
	 * @returns {Kpi[]} An array of Kpi objects
	 */
	public get aKpis(): Kpi[] {
		return this._aKpis;
	}

	/**
	 * Get wrapper class for Kpi tile, depending on Kpi's type
	 * @param {Kpi} oKpi a Kpi
	 * @returns {string} The class to apply
	 */
	public getKpiWrapperClass(oKpi: Kpi): string {
		switch (oKpi.type) {
			case 'amount':
				return 'amount-tile-wrapper';
			case 'counter':
				return 'counter-tile-wrapper';
		}
	}

	/**
	 * Init a list of dummy Kpi to display while data is loading
	 */
	private _initDummyKpis(): void {
		// Create 12 dummy Kpis
		for (let i=0; i<12; i++) {
			let oKpi = new Kpi({
				dummy: true,
				type: 'counter'
			});
			this._aKpis.push(oKpi);
		}
	}

	/**
	 * Method to define onboarding section visibility
	 * @returns {boolean} True if the section must be visible, False otherwise
	 */
	public isOnboardingVisible(): boolean {
		return !this.appService?.oSelectedEntity?.onboarding_completed;
	}

	/**
	 * Method that calls service to fetch BankAccount objects
	 */
	private _loadBankAccounts(): void {
		// Build query filters
		this._buildBankAccountsFilter();
		// Call service
		this.bankAccountService
			.getList$({
				aFilters: [this._oBankAccountsFilter]
			})
			.subscribe(aBankAccounts => {
				this.aBankAccounts = aBankAccounts;
			});
	}

	/**
	 * Call all services to load needed data
	 */
	protected _loadData(): void {
		this._loadBankAccounts();
		this._loadKpis();
	}

	/**
	 * Method that calls service to fetch Kpi objects
	 */
	private _loadKpis(): void {
		this.kpiService.getFromNav$('legal_entity', { id: this._iEntityId }).subscribe(aKpis => {
			this.aKpis = aKpis;
		});
	}

	/**
	 * Event triggered when the user selects a file in the document input
	 * @param {FileList} oEvent The DOM event
	 */
	public onChangeDocumentInput(oEvent: FileList): void {
		this._uploadFiles(oEvent);
	}

	/**
	 * Event triggered when the user clicks on assigned cases tile
	 */
	public onClickAssignedCases(): void {
		// Build URL parameters
		let oNavigationExtras: NavigationExtras = {
			queryParams: { assignee: 'me' }
		};

		// Navigate to the cases page with extras
		this.oRouter.navigate(['/cases', this._iEntityId, 'list'], oNavigationExtras);
	}

	/**
	 * Event triggered when the user clicks on the download documents action tile
	 */
	public onClickDownloadValidatedDocuments(): void {
		this._openExtractAccountingDialog();
	}

	/**
	 * Event triggered when the user clicks on the new message button
	 */
	public onClickNewConversation(): void {
		this._openNewConversationDialog();
	}

	/**
	 * Event triggered when the user clicks on the 'close' button when oboarding is completed
	 */
	public onOnboardingCompleted(): void {
		// Define completion flag to true
		this.appService.oSelectedEntity.setOnboarding_completed(true);
		// Save entity data
		this.legalEntityService.save(this.appService.oSelectedEntity);
	}

	/**
	 * Open a dialog to display extraction options
	 */
	private _openExtractAccountingDialog(): void {
		let oOptions = {
			panelClass: 'mihy-dialog',
			width: this.appService.isPhone() ? '98%' : '50vw'
		};
		this._openDialog(LegalEntityExtractAccountingFormDialogComponent, oOptions);
	}

	/**
	 * Setter for _aBankAccounts
	 * @param {BankAccount[]} aBankAccounts The fetched BankAccount objects
	 */
	public set aBankAccounts(aBankAccounts: BankAccount[]) {
		this._aBankAccounts = [].concat(aBankAccounts);
	}
	
	/**
	 * Setter for _aKpis
	 * @param {Kpi[]} aKpis The fetched Kpi objects
	 */
	public set aKpis(aKpis: Kpi[]) {
		let oSorter = new Sorter({
			sProperty: 'index'
		});
		this._aKpis = [].concat(Sorting.sort(aKpis, [oSorter]));
	}
	
	/**
	 * Create subscriptions for all Observables we want to listen to
	 */
	protected _subscribeToObservables(): void {
		// List of managed entities
		this.oSubscription.add(
			this.appService.eEntitiesLoaded$.subscribe(aEntities => {
				if (aEntities.length > 1) {
					this.appService.showNavButton();
				} else {
					this.appService.hideNavButton();
				}
			})
		);
	}

	/**
	 * Upload files that were selected
	 * @param {FileList} oFileList An array of files
	 */
	private _uploadFiles(oFileList: FileList) {
		for (let index = 0; index < oFileList.length; index++) {
			const oElement = oFileList[index];
			this.uploadService.uploadFile$(oElement, true).subscribe((oFile: File) => {
				if (!!oFile) {
					// After the File is successfully created, create a Document
					this._createDocument(oFile);
				}
			});
		}
	}
}
