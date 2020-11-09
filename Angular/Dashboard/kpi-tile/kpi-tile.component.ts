import { Component, Input } from '@angular/core';
import { NavigationExtras } from '@angular/router';

// Components
import { BaseComponent } from '../../base.component';

// Models
import { Kpi } from '@app/shared/models/kpi.model';

@Component({
	selector: 'mihy-kpi-tile',
	templateUrl: './kpi-tile.component.html',
	styleUrls: ['./kpi-tile.component.scss']
})
export class KpiTileComponent extends BaseComponent {
	// Kpi object
	private _oKpi: Kpi;

	/**
	 * Getter for _oKpi
	 * 
	 * @returns {Kpi} The Kpi object
	 */
	public get oKpi(): Kpi {
		return this._oKpi;
	}

	/**
	 * Getter for Kpi display color
	 * 
	 * This color is calculated from Kpi object's level
	 * 
	 * @returns {string} The color to use
	 */
	public get sCounterColor(): string {
		switch (this._oKpi.level) {
			case 'low':
				return 'secondary';
			case 'medium':
				return 'info';
			case 'high':
				return 'accent';
			default:
				return 'primary';
		}
	}

	/**
	 * Event triggered when the user clicks on the Kpi
	 */
	public onClickKpi(): void {
		this._performKpiAction();
	}

	/**
	 * Execute action corresponding to Kpi that was clicked on
	 */
	private _performKpiAction(): void {
		let oNavigationExtras: NavigationExtras = null;

		// Depending on Kpi's technical code
		switch (this._oKpi.code) {
			case 'documents_to_validate':
				this.oRouter.navigate(['/inbox', this._iEntityId]);
				break;
			case 'open_assigned_questions':
				oNavigationExtras = {
					queryParams: { assignee: 'me' }
				};
				this.oRouter.navigate(['/questions', this._iEntityId, 'list'], oNavigationExtras);
				break;
			case 'open_assigned_tasks':
				oNavigationExtras = {
					queryParams: { assignee: 'me' }
				};
				this.oRouter.navigate(['/cases', this._iEntityId, 'list'], oNavigationExtras);
				break;
			case 'open_questions':
				this.oRouter.navigate(['/questions', this._iEntityId, 'list']);
				break;
			case 'open_tasks':
				this.oRouter.navigate(['/cases', this._iEntityId, 'list']);
				break;
			case 'unjustified_bank_account_lines':
				oNavigationExtras = {
					queryParams: { unjustified: true }
				};
				this.oRouter.navigate(['/banking', this._iEntityId, 'matching'], oNavigationExtras);
				break;
			default:
				// Nothing to do
				break;
		}
	}

	/**
	 * Setter for _oKpi
	 * 
	 * Data is received from parent component
	 * 
	 * @param {Kpi} oKpi The Kpi object
	 */
	@Input()
	public set oKpi(oKpi: Kpi) {
		this._oKpi = oKpi;
	}

}
