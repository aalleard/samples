import { Component, Input } from '@angular/core';
import { BaseComponent } from '../../base.component';

@Component({
	selector: 'mihy-counter-tile',
	templateUrl: './counter-tile.component.html',
	styleUrls: ['./counter-tile.component.scss']
})
export class CounterTileComponent extends BaseComponent {
	@Input()
	bDummy: boolean = false;
	@Input()
	sColor: string = '';
	@Input()
	sCounter: string = '';
	@Input()
	sTitle: string = '';
	@Input()
	sTooltip: string = '';
}
