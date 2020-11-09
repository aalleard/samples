import { Filter } from '@app/shared/utilities/models/filter';
import { Sorter } from '@app/shared/utilities/models/sorter';

export interface IQueryOptions {
	aExpand?: string[];
	aFilters?: Filter[];
	aSorters?: Sorter[];
	bCount?: boolean;
	bIndicator?: boolean;
	bKeepSelection?: boolean;
	iSkip?: number;
	iTop?: number;
	sUrl?: string;
}

export class QueryOptions implements IQueryOptions {
	aExpand: string[];
	aFilters: Filter[];
	aSorters: Sorter[];
	bCount: boolean;
	bIndicator: boolean;
	bKeepSelection: boolean;
	iSkip: number;
	iTop: number;
	sUrl: string;

	constructor(sUrl: string, oOptions?: IQueryOptions) {
		this.aExpand = !!oOptions?.aExpand ? oOptions.aExpand : [];
		this.aFilters = !!oOptions?.aFilters ? oOptions.aFilters : [];
		this.aSorters = !!oOptions?.aSorters ? oOptions.aSorters : [];
		this.bCount = !!oOptions?.bCount ? oOptions.bCount : false;
		this.bIndicator = typeof oOptions?.bIndicator === 'undefined' ? true : oOptions.bIndicator;
		this.bKeepSelection = typeof oOptions?.bKeepSelection === 'undefined' ? true : oOptions.bKeepSelection;
		this.iSkip = !!oOptions?.iSkip ? oOptions.iSkip : null;
		this.iTop = !!oOptions?.iTop ? oOptions.iTop : null;
		this.sUrl = !!oOptions?.sUrl ? oOptions.sUrl : sUrl;
	}

	/**
	 * Convert all query parameters into an URL string
	 * 
	 * @returns {string} The URL to query
	 */
	public toUrlString(): string {
		let sUrl = this.sUrl;
		let bOptions: boolean = false;

		if (this.aExpand.length > 0) {
			sUrl += bOptions ? '&' : '?';
			sUrl += '$expand=';
			sUrl += this.aExpand.join(',');
			bOptions = true;
		}

		if (this.aFilters.length > 0) {
			sUrl += bOptions ? '&' : '?';
			sUrl += Filter.toUrlString(this.aFilters);
			bOptions = true;
		}

		if (this.aSorters.length > 0) {
			sUrl += bOptions ? '&' : '?';
			sUrl += Sorter.toUrlString(this.aSorters);
			bOptions = true;
		}

		if (this.iSkip) {
			sUrl += bOptions ? '&' : '?';
			sUrl += '$skip=';
			sUrl += this.iSkip;
			bOptions = true;
		}

		if (this.iTop) {
			sUrl += bOptions ? '&' : '?';
			sUrl += '$top=';
			sUrl += this.iTop;
			bOptions = true;
		}

		if (this.bCount) {
			sUrl += bOptions ? '&' : '?';
			sUrl += '$count=true';
		}

		return sUrl;
	}
}
