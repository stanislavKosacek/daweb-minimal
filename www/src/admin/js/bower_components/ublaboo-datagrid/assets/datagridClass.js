var dataGridRegisterExtension, dataGridRegisterAjaxCall, dataGridLoad, dataGridSubmitForm;

var naja = window.naja;
class LoadingIndicatorExtension {
	constructor(defaultLoadingIndicatorSelector) {
		this.defaultLoadingIndicatorSelector = defaultLoadingIndicatorSelector;
	}

	initialize(naja) {
		this.defaultLoadingIndicator = document.querySelector(this.defaultLoadingIndicatorSelector);

		naja.uiHandler.addEventListener('interaction', this.locateLoadingIndicator.bind(this));
		naja.addEventListener('start', this.showLoader.bind(this));
		naja.addEventListener('complete', this.hideLoader.bind(this));
	}

	locateLoadingIndicator({detail}) {
		const loadingIndicator = detail.element.closest('[data-loading-indicator]');
		detail.options.loadingIndicator = loadingIndicator || this.defaultLoadingIndicator;
	}

	showLoader({detail}) {
		detail.options.loadingIndicator.classList.add('is-loading');
	}

	hideLoader() {
		detail.options.loadingIndicator.classList.remove('is-loading');
	}
}
