/**
 * Page transition loader.
 */
(function () {
	'use strict';

	var loader = document.getElementById('page-loader');

	if (!loader) {
		return;
	}

	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hideTimer;

	try {
		if (sessionStorage.getItem('starterThemeSkipPageLoader')) {
			sessionStorage.removeItem('starterThemeSkipPageLoader');
			reducedMotion = true;
		}
	} catch (error) {
		// Ignore storage failures.
	}

	function showLoader() {
		window.clearTimeout(hideTimer);
		loader.classList.add('is-active');
		loader.setAttribute('aria-hidden', 'false');
		document.documentElement.classList.add('is-loading');
	}

	function hideLoader() {
		loader.classList.remove('is-active');
		loader.setAttribute('aria-hidden', 'true');
		document.documentElement.classList.remove('is-loading');
	}

	if (!reducedMotion) {
		showLoader();
		window.addEventListener('load', function () {
			hideTimer = window.setTimeout(hideLoader, 400);
		});
	}

	document.addEventListener('click', function (event) {
		var link = event.target.closest('a');

		if (!link || link.target === '_blank' || link.hasAttribute('download')) {
			return;
		}

		if (link.hasAttribute('data-no-loader')) {
			return;
		}

		if (link.classList.contains('add_to_cart_button') || link.closest('.wc-block-grid__product-add-to-cart')) {
			return;
		}

		if (link.closest('button.add_to_cart_button')) {
			return;
		}

		var href = link.getAttribute('href');

		if (!href || href.charAt(0) === '#' || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) {
			return;
		}

		if (href.indexOf('add-to-cart=') !== -1) {
			return;
		}

		var url;

		try {
			url = new URL(link.href, window.location.origin);
		} catch (error) {
			return;
		}

		if (url.origin !== window.location.origin) {
			return;
		}

		if (reducedMotion) {
			return;
		}

		showLoader();
	});

	window.addEventListener('pageshow', function () {
		hideLoader();
	});
})();
