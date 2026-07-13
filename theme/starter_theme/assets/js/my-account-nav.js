/**
 * AJAX My Account navigation — swap endpoint content without a full page reload.
 */
( function () {
	'use strict';

	const config = window.starterThemeMyAccount || {};
	const contentSelector = config.contentSelector || '[data-my-account-content]';
	const navSelector = config.navSelector || '.woocommerce-MyAccount-navigation';
	const accountBaseUrl = config.accountBaseUrl || '';

	let activeRequest = 0;
	let accountPath = '';

	if ( accountBaseUrl ) {
		try {
			accountPath = new URL( accountBaseUrl, window.location.origin ).pathname.replace( /\/$/, '' ) || '/';
		} catch ( error ) {
			accountPath = '';
		}
	}

	/**
	 * Whether a URL is an in-account endpoint (not logout).
	 *
	 * @param {string} href Link href.
	 * @return {boolean}
	 */
	function isAccountEndpointHref( href ) {
		if ( ! href || ! accountPath ) {
			return false;
		}

		if ( href.indexOf( 'customer-logout' ) !== -1 ) {
			return false;
		}

		try {
			const url = new URL( href, window.location.origin );

			if ( url.origin !== window.location.origin ) {
				return false;
			}

			const path = url.pathname.replace( /\/$/, '' ) || '/';

			return path === accountPath || path.indexOf( accountPath + '/' ) === 0;
		} catch ( error ) {
			return false;
		}
	}

	/**
	 * Re-init WooCommerce address fields after content swap.
	 *
	 * @param {HTMLElement} content Content container.
	 */
	function initWooCommerceFields( content ) {
		if ( typeof window.jQuery === 'undefined' ) {
			return;
		}

		const $content = window.jQuery( content );
		const $countrySelects = $content.find( 'select.country_to_state, select.country_select' );

		if ( $countrySelects.length ) {
			$countrySelects.each( function () {
				window.jQuery( document.body ).trigger( 'country_to_state_changing', [ window.jQuery( this ).val(), $content ] );
			} );
		}

		window.jQuery( document.body ).trigger( 'wc_address_i18n_ready' );
		window.jQuery( document.body ).trigger( 'init_tooltips' );
	}

	/**
	 * Update active state on the sidebar navigation.
	 *
	 * @param {Document} doc Parsed response document.
	 * @param {HTMLElement} nav Current navigation element.
	 */
	function updateNavigation( doc, nav ) {
		const currentItems = nav.querySelectorAll( 'li' );
		const newItems = doc.querySelectorAll( navSelector + ' li' );

		newItems.forEach( ( newItem, index ) => {
			const currentItem = currentItems[ index ];

			if ( ! currentItem ) {
				return;
			}

			currentItem.className = newItem.className;

			const currentLink = currentItem.querySelector( 'a' );
			const newLink = newItem.querySelector( 'a' );

			if ( ! currentLink || ! newLink ) {
				return;
			}

			if ( newLink.hasAttribute( 'aria-current' ) ) {
				currentLink.setAttribute( 'aria-current', 'page' );
			} else {
				currentLink.removeAttribute( 'aria-current' );
			}
		} );
	}

	/**
	 * Reveal newly loaded account content.
	 *
	 * @param {HTMLElement} content Content container.
	 */
	function revealContent( content ) {
		if ( typeof window.starterThemeRevealArchiveContent === 'function' ) {
			window.starterThemeRevealArchiveContent();
			return;
		}

		content.querySelectorAll( '.reveal, .account-panel, .account-quick-link, .account-dashboard' ).forEach( ( el ) => {
			el.classList.add( 'is-visible' );
		} );
	}

	/**
	 * Scroll the account content into view after navigation.
	 *
	 * @param {HTMLElement} content Content container.
	 */
	function scrollToContent( content ) {
		const headerOffset = parseInt( getComputedStyle( document.documentElement ).getPropertyValue( '--header-height' ), 10 ) || 72;
		const top = content.getBoundingClientRect().top + window.scrollY - headerOffset - 16;

		window.scrollTo( {
			top: Math.max( 0, top ),
			behavior: window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ? 'auto' : 'smooth',
		} );
	}

	/**
	 * Fetch and swap account endpoint content.
	 *
	 * @param {string} url Target endpoint URL.
	 */
	async function loadAccountEndpoint( url ) {
		const requestId = ++activeRequest;
		const content = document.querySelector( contentSelector );
		const nav = document.querySelector( navSelector );

		if ( ! content ) {
			window.location.href = url;
			return;
		}

		content.classList.add( 'is-loading' );
		content.setAttribute( 'aria-busy', 'true' );

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
				throw new Error( 'Account request failed.' );
			}

			const html = await response.text();
			const doc = new DOMParser().parseFromString( html, 'text/html' );
			const newContent = doc.querySelector( contentSelector );

			if ( requestId !== activeRequest ) {
				return;
			}

			if ( ! newContent ) {
				throw new Error( 'Account content markup missing.' );
			}

			content.innerHTML = newContent.innerHTML;

			if ( nav ) {
				updateNavigation( doc, nav );
			}

			const newTitle = doc.querySelector( 'title' );

			if ( newTitle && newTitle.textContent ) {
				document.title = newTitle.textContent;
			}

			window.history.pushState( { myAccountNav: true }, '', url );

			initWooCommerceFields( content );
			revealContent( content );
			scrollToContent( content );

			document.dispatchEvent(
				new CustomEvent( 'starterThemeMyAccountUpdated', {
					detail: { url },
				} )
			);
		} catch ( error ) {
			if ( requestId === activeRequest ) {
				window.location.href = url;
			}
		} finally {
			if ( requestId === activeRequest ) {
				content.classList.remove( 'is-loading' );
				content.removeAttribute( 'aria-busy' );
			}
		}
	}

	/**
	 * Intercept account navigation and in-content endpoint links.
	 *
	 * @param {Event} event Click event.
	 */
	function handleClick( event ) {
		if ( event.defaultPrevented ) {
			return;
		}

		const link = event.target.closest( 'a[href]' );

		if ( ! link || link.target === '_blank' || link.hasAttribute( 'download' ) || link.hasAttribute( 'data-no-ajax' ) ) {
			return;
		}

		const content = document.querySelector( contentSelector );

		if ( ! content ) {
			return;
		}

		const inNav = Boolean( link.closest( navSelector ) );
		const isNavEndpoint = inNav && link.hasAttribute( 'data-my-account-nav-link' );
		const isContentEndpoint = ! inNav && isAccountEndpointHref( link.href );

		if ( ! isNavEndpoint && ! isContentEndpoint ) {
			return;
		}

		event.preventDefault();
		event.stopPropagation();

		loadAccountEndpoint( link.href );
	}

	function init() {
		if ( ! document.querySelector( contentSelector ) ) {
			return;
		}

		document.addEventListener( 'click', handleClick, true );

		window.addEventListener( 'popstate', () => {
			if ( ! document.querySelector( contentSelector ) ) {
				return;
			}

			if ( isAccountEndpointHref( window.location.href ) ) {
				loadAccountEndpoint( window.location.href );
			}
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
