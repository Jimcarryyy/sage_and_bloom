/**
 * AJAX archive filtering — refresh results without full page reload.
 */
( function () {
	'use strict';

	const config = window.starterThemeArchiveFiltering || {};
	const toolbarSelector = config.toolbarSelector || '.services-toolbar';
	const resultsSelector = config.resultsSelector || '[data-archive-results]';
	const filterSelector = config.filterSelector || '[data-archive-filter]';
	let activeRequest = 0;

	/**
	 * Fetch and swap archive toolbar + results.
	 *
	 * @param {string} url Target URL.
	 */
	async function refreshArchive( url ) {
		const requestId = ++activeRequest;
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

			if ( requestId !== activeRequest ) {
				return;
			}

			if ( ! response.ok ) {
				throw new Error( 'Archive request failed.' );
			}

			const html = await response.text();
			const doc = new DOMParser().parseFromString( html, 'text/html' );
			const newToolbar = doc.querySelector( toolbarSelector );
			const newResults = doc.querySelector( resultsSelector );

			if ( requestId !== activeRequest ) {
				return;
			}

			if ( ! newResults ) {
				throw new Error( 'Archive results markup missing.' );
			}

			if ( newToolbar && toolbar ) {
				toolbar.innerHTML = newToolbar.innerHTML;
			}

			results.innerHTML = newResults.innerHTML;

			if ( typeof window.starterThemeRevealArchiveContent === 'function' ) {
				window.starterThemeRevealArchiveContent();
			} else {
				results.querySelectorAll( '.reveal, .service-card' ).forEach( ( el ) => {
					el.classList.add( 'is-visible' );
				} );
			}

			window.history.pushState( { archiveFiltering: true }, '', url );

			const scrollTarget = document.querySelector( '[data-archive-scroll-target]' ) || toolbar || results;
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
			if ( requestId === activeRequest ) {
				window.location.href = url;
			}
		} finally {
			if ( requestId === activeRequest ) {
				results.classList.remove( 'is-loading' );
				results.removeAttribute( 'aria-busy' );
				if ( toolbar ) {
					toolbar.classList.remove( 'is-loading' );
				}
			}
		}
	}

	/**
	 * Intercept filter link clicks.
	 *
	 * @param {Event} event Click event.
	 */
	function handleFilterClick( event ) {
		if ( event.defaultPrevented ) {
			return;
		}

		const link = event.target.closest( filterSelector + ' a[href]' );

		if ( ! link || ! document.querySelector( resultsSelector ) ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();
		refreshArchive( link.href );
	}

	function init() {
		document.addEventListener( 'click', handleFilterClick, true );

		window.addEventListener( 'popstate', () => {
			if ( ! document.querySelector( resultsSelector ) ) {
				return;
			}

			refreshArchive( window.location.href );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
