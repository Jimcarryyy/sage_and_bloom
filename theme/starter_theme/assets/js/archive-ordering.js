/**
 * AJAX archive ordering — refresh results without full page reload.
 */
( function () {
	'use strict';

	const config = window.starterThemeArchiveOrdering || {};
	const toolbarSelector = config.toolbarSelector || '.shop-toolbar';
	const resultsSelector = config.resultsSelector || '[data-archive-results]';
	const orderingSelector = '[data-archive-ordering]';

	/**
	 * Build a GET URL from an ordering form.
	 *
	 * @param {HTMLFormElement} form Ordering form.
	 * @return {URL}
	 */
	function buildUrlFromForm( form ) {
		const url = new URL( form.action || window.location.href, window.location.origin );

		url.searchParams.delete( 'paged' );
		url.searchParams.delete( 'product-page' );

		const formData = new FormData( form );
		formData.forEach( ( value, key ) => {
			if ( '' === value ) {
				url.searchParams.delete( key );
				return;
			}
			url.searchParams.set( key, value );
		} );

		return url;
	}

	/**
	 * WooCommerce submits the ordering form on select change — disable that.
	 */
	function disableWooCommerceOrderingSubmit() {
		if ( typeof jQuery === 'undefined' ) {
			return;
		}

		jQuery( '.woocommerce-ordering' ).off( 'change', 'select.orderby' );
	}

	/**
	 * Re-bind ordering forms inside a container.
	 *
	 * @param {ParentNode} root Root node.
	 */
	function bindOrderingForms( root ) {
		root.querySelectorAll( orderingSelector ).forEach( ( form ) => {
			if ( form.dataset.orderingBound ) {
				return;
			}

			form.dataset.orderingBound = '1';

			const select = form.querySelector( 'select' );
			if ( ! select ) {
				return;
			}

			const handleOrdering = ( event ) => {
				event.preventDefault();
				event.stopPropagation();
				event.stopImmediatePropagation();
				refreshArchive( buildUrlFromForm( form ).toString() );
			};

			select.addEventListener( 'change', handleOrdering, true );

			form.addEventListener( 'submit', handleOrdering, true );
		} );
	}

	/**
	 * Fetch and swap archive toolbar + results.
	 *
	 * @param {string} url Target URL.
	 */
	async function refreshArchive( url ) {
		const toolbar = document.querySelector( toolbarSelector );
		const results = document.querySelector( resultsSelector );

		if ( ! results ) {
			window.location.href = url;
			return;
		}

		results.classList.add( 'is-loading' );
		results.setAttribute( 'aria-busy', 'true' );
		if ( toolbar ) {
			toolbar.classList.add( 'is-loading' );
		}

		try {
			const response = await fetch( url, {
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
				},
				credentials: 'same-origin',
			} );

			if ( ! response.ok ) {
				throw new Error( 'Archive request failed.' );
			}

			const html = await response.text();
			const doc = new DOMParser().parseFromString( html, 'text/html' );
			const newToolbar = doc.querySelector( toolbarSelector );
			const newResults = doc.querySelector( resultsSelector );

			if ( newToolbar && toolbar ) {
				toolbar.innerHTML = newToolbar.innerHTML;
				bindOrderingForms( toolbar );
			}

			if ( newResults ) {
				results.innerHTML = newResults.innerHTML;
			}

			if ( typeof window.starterThemeRevealArchiveContent === 'function' ) {
				window.starterThemeRevealArchiveContent();
			}

			window.history.pushState( { archiveOrdering: true }, '', url );

			const scrollTarget = document.querySelector( '[data-archive-scroll-target]' ) || results;
			const headerOffset = parseInt( getComputedStyle( document.documentElement ).getPropertyValue( '--header-height' ), 10 ) || 72;

			if ( scrollTarget ) {
				const top = scrollTarget.getBoundingClientRect().top + window.scrollY - headerOffset - 16;
				window.scrollTo( { top: Math.max( 0, top ), behavior: 'smooth' } );
			}

			document.dispatchEvent(
				new CustomEvent( 'starterThemeArchiveUpdated', {
					detail: { url },
				} )
			);
		} catch ( error ) {
			window.location.href = url;
		} finally {
			results.classList.remove( 'is-loading' );
			results.removeAttribute( 'aria-busy' );
			if ( toolbar ) {
				toolbar.classList.remove( 'is-loading' );
			}
		}
	}

	window.addEventListener( 'popstate', () => {
		if ( ! document.querySelector( resultsSelector ) ) {
			return;
		}

		refreshArchive( window.location.href );
	} );

	document.addEventListener( 'DOMContentLoaded', () => {
		disableWooCommerceOrderingSubmit();
		bindOrderingForms( document );
	} );
} )();
