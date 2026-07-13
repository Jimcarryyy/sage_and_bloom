/**
 * AJAX add to cart — shared loading overlay, no full-page transition.
 */
( function () {
	'use strict';

	const config = window.starterThemeAddToCart || {};
	const ajaxUrl = config.wcAjaxUrl || '';
	const isCart = config.isCart === true || config.isCart === '1';
	const cartRedirect = config.cartRedirectAfterAdd === 'yes';
	const cartUrl = config.cartUrl || '/cart/';
	const i18nViewCart = config.i18nViewCart || 'View cart';
	const i18nAddedToCart = config.i18nAddedToCart || 'Added to your bag';
	const i18nViewBag = config.i18nViewBag || 'View bag';
	const toastDuration = Number( config.toastDuration ) || 3500;

	let toastTimer = null;
	let toastDismissTimer = null;

	/**
	 * Toast container — created once on first use.
	 *
	 * @return {HTMLElement}
	 */
	function getToastHost() {
		let host = document.getElementById( 'cart-toast-host' );

		if ( ! host ) {
			host = document.createElement( 'div' );
			host.id = 'cart-toast-host';
			host.className = 'cart-toast-host';
			document.body.appendChild( host );
		}

		return host;
	}

	/**
	 * Resolve a product title from the clicked add-to-cart control.
	 *
	 * @param {Element} triggerEl Trigger element.
	 * @return {string}
	 */
	function getProductName( triggerEl ) {
		const card = triggerEl.closest( '.product, .wc-block-grid__product, form.cart, .product-summary-card' );

		if ( card ) {
			const title = card.querySelector(
				'.woocommerce-loop-product__title, .wc-block-grid__product-title, .product_title, .product-summary-card__title, h1'
			);

			if ( title && title.textContent ) {
				return title.textContent.trim();
			}
		}

		const ariaLabel = triggerEl.getAttribute( 'aria-label' );

		if ( ariaLabel ) {
			const match = ariaLabel.match( /[“"](.+?)[”"]/ );

			if ( match ) {
				return match[1];
			}
		}

		return '';
	}

	/**
	 * Hide the active cart toast.
	 *
	 * @param {HTMLElement|null} toast Toast element.
	 */
	function hideCartToast( toast ) {
		if ( ! toast ) {
			return;
		}

		toast.classList.remove( 'is-visible' );
		toast.classList.add( 'is-hiding' );

		window.clearTimeout( toastTimer );
		window.clearTimeout( toastDismissTimer );

		toastDismissTimer = window.setTimeout( () => {
			toast.remove();
		}, 280 );
	}

	/**
	 * Show a short confirmation toast after add to cart.
	 *
	 * @param {string} productName Product title.
	 */
	function showCartToast( productName ) {
		const host = getToastHost();
		const existing = host.querySelector( '.cart-toast' );

		if ( existing ) {
			existing.remove();
		}

		window.clearTimeout( toastTimer );
		window.clearTimeout( toastDismissTimer );

		const toast = document.createElement( 'div' );
		toast.className = 'cart-toast is-visible';
		toast.setAttribute( 'role', 'status' );
		toast.setAttribute( 'aria-live', 'polite' );

		const icon = document.createElement( 'span' );
		icon.className = 'cart-toast__icon';
		icon.setAttribute( 'aria-hidden', 'true' );
		icon.innerHTML =
			'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>';

		const copy = document.createElement( 'div' );
		copy.className = 'cart-toast__copy';

		const title = document.createElement( 'p' );
		title.className = 'cart-toast__title';
		title.textContent = i18nAddedToCart;
		copy.appendChild( title );

		if ( productName ) {
			const product = document.createElement( 'p' );
			product.className = 'cart-toast__product';
			product.textContent = productName;
			copy.appendChild( product );
		}

		const action = document.createElement( 'a' );
		action.className = 'cart-toast__action';
		action.href = cartUrl;
		action.textContent = i18nViewBag;

		const dismiss = document.createElement( 'button' );
		dismiss.type = 'button';
		dismiss.className = 'cart-toast__dismiss';
		dismiss.setAttribute( 'aria-label', 'Dismiss notification' );
		dismiss.innerHTML =
			'<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>';
		dismiss.addEventListener( 'click', () => hideCartToast( toast ) );

		toast.append( icon, copy, action, dismiss );
		host.appendChild( toast );

		toastTimer = window.setTimeout( () => {
			hideCartToast( toast );
		}, toastDuration );
	}

	/**
	 * Persist toast for cart page reload (blocks cart cannot update in place).
	 *
	 * @param {string} productName Product title.
	 */
	function queueCartToast( productName ) {
		try {
			sessionStorage.setItem(
				'starterThemeCartToast',
				JSON.stringify( {
					productName,
				} )
			);
		} catch ( error ) {
			// Ignore storage failures.
		}
	}

	/**
	 * Show toast queued before a cart page reload.
	 */
	function showQueuedCartToast() {
		try {
			const raw = sessionStorage.getItem( 'starterThemeCartToast' );

			if ( ! raw ) {
				return;
			}

			sessionStorage.removeItem( 'starterThemeCartToast' );
			const payload = JSON.parse( raw );
			showCartToast( payload.productName || '' );
		} catch ( error ) {
			// Ignore malformed storage.
		}
	}

	/**
	 * Build WooCommerce AJAX endpoint URL.
	 *
	 * @param {string} endpoint Endpoint slug.
	 * @return {string}
	 */
	function getAjaxEndpoint( endpoint ) {
		return ajaxUrl.replace( '%%endpoint%%', endpoint );
	}

	/**
	 * Find the nearest surface for the archive-style loading overlay.
	 *
	 * @param {Element} fromEl Trigger element.
	 * @return {Element|null}
	 */
	function findLoadingSurface( fromEl ) {
		return (
			fromEl.closest( '[data-ajax-loading-surface]' ) ||
			fromEl.closest( '[data-archive-results]' ) ||
			fromEl.closest( '.wc-block-product-new' ) ||
			fromEl.closest( '.home-products' ) ||
			fromEl.closest( '.woocommerce-page-content' ) ||
			fromEl.closest( '.site-main' )
		);
	}

	/**
	 * Toggle loading state on a container.
	 *
	 * @param {Element|null} surface Container element.
	 * @param {boolean}      on      Whether loading is active.
	 */
	function setLoading( surface, on ) {
		if ( ! surface ) {
			return;
		}

		surface.classList.add( 'ajax-loading-surface' );
		surface.classList.toggle( 'is-loading', on );

		if ( on ) {
			surface.setAttribute( 'aria-busy', 'true' );
			return;
		}

		surface.removeAttribute( 'aria-busy' );
	}

	/**
	 * Replace fragment targets returned by WooCommerce.
	 *
	 * @param {Record<string, string>|undefined} fragments Fragment map.
	 */
	function applyFragments( fragments ) {
		if ( ! fragments ) {
			return;
		}

		Object.entries( fragments ).forEach( ( [ selector, html ] ) => {
			document.querySelectorAll( selector ).forEach( ( element ) => {
				const template = document.createElement( 'template' );
				template.innerHTML = html.trim();
				const replacement = template.content.firstElementChild;

				if ( replacement ) {
					element.replaceWith( replacement );
				}
			} );
		} );
	}

	/**
	 * Parse product data from an add-to-cart control.
	 *
	 * @param {HTMLAnchorElement|HTMLButtonElement} control Control element.
	 * @return {{product_id: string, quantity: string}|null}
	 */
	function parseProductFromControl( control ) {
		if ( control.dataset.product_id ) {
			return {
				product_id: control.dataset.product_id,
				quantity: control.dataset.quantity || '1',
			};
		}

		if ( control instanceof HTMLAnchorElement && control.href ) {
			try {
				const url = new URL( control.href, window.location.origin );
				const productId = url.searchParams.get( 'add-to-cart' );

				if ( productId ) {
					return {
						product_id: productId,
						quantity: url.searchParams.get( 'quantity' ) || '1',
					};
				}
			} catch ( error ) {
				return null;
			}
		}

		return null;
	}

	/**
	 * Parse product data from a simple product cart form.
	 *
	 * @param {HTMLFormElement} form Cart form.
	 * @return {{product_id: string, quantity: string}|null}
	 */
	function parseProductFromForm( form ) {
		const addField = form.querySelector( '[name="add-to-cart"]' );
		const qtyField = form.querySelector( '[name="quantity"]' );

		if ( ! addField || ! addField.value ) {
			return null;
		}

		return {
			product_id: addField.value,
			quantity: qtyField && qtyField.value ? qtyField.value : '1',
		};
	}

	/**
	 * Update loop button state after a successful add.
	 *
	 * @param {Element} triggerEl Clicked element.
	 */
	function markButtonAdded( triggerEl ) {
		const button = triggerEl.closest( '.add_to_cart_button' ) || triggerEl;

		if ( ! button.classList || ! button.classList.contains( 'add_to_cart_button' ) ) {
			return;
		}

		button.classList.remove( 'loading' );
		button.classList.add( 'added' );

		const product = button.closest( '.product' ) || button.parentElement;

		if ( product && ! product.querySelector( '.added_to_cart' ) ) {
			const viewCart = document.createElement( 'a' );
			viewCart.href = cartUrl;
			viewCart.className = 'added_to_cart wc-forward';
			viewCart.textContent = i18nViewCart;
			button.insertAdjacentElement( 'afterend', viewCart );
		}
	}

	/**
	 * Reload cart page after add (WooCommerce Blocks cart requires a full render).
	 */
	function refreshCartPage() {
		try {
			sessionStorage.setItem( 'starterThemeSkipPageLoader', '1' );
		} catch ( error ) {
			// Ignore storage failures.
		}

		window.location.reload();
	}

	/**
	 * POST to WooCommerce add_to_cart and update the UI.
	 *
	 * @param {{product_id: string, quantity: string}} data      Product payload.
	 * @param {Element}                                triggerEl Trigger element.
	 */
	async function addToCart( data, triggerEl ) {
		if ( ! ajaxUrl ) {
			if ( triggerEl instanceof HTMLAnchorElement && triggerEl.href ) {
				window.location.href = triggerEl.href;
			}
			return;
		}

		const surface = findLoadingSurface( triggerEl );
		let keepLoading = false;
		const productName = getProductName( triggerEl );

		setLoading( surface, true );
		triggerEl.setAttribute( 'aria-disabled', 'true' );

		const body = new URLSearchParams();
		Object.entries( data ).forEach( ( [ key, value ] ) => {
			body.set( key, String( value ) );
		} );

		try {
			const response = await fetch( getAjaxEndpoint( 'add_to_cart' ), {
				method: 'POST',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
					'X-Requested-With': 'XMLHttpRequest',
				},
				body,
			} );

			if ( ! response.ok ) {
				throw new Error( 'Add to cart request failed.' );
			}

			const result = await response.json();

			if ( ! result ) {
				throw new Error( 'Empty add to cart response.' );
			}

			if ( result.error && result.product_url ) {
				window.location.href = result.product_url;
				return;
			}

			applyFragments( result.fragments );

			if ( cartRedirect && ! isCart ) {
				keepLoading = true;
				window.location.href = cartUrl;
				return;
			}

			if ( isCart ) {
				keepLoading = true;
				queueCartToast( productName );
				refreshCartPage();
				return;
			}

			markButtonAdded( triggerEl );
			showCartToast( productName );
		} catch ( error ) {
			if ( triggerEl instanceof HTMLAnchorElement && triggerEl.href ) {
				window.location.href = triggerEl.href;
				return;
			}

			const form = triggerEl.closest( 'form.cart' );
			if ( form ) {
				form.submit();
			}
		} finally {
			if ( ! keepLoading ) {
				setLoading( surface, false );
				triggerEl.removeAttribute( 'aria-disabled' );
			}
		}
	}

	/**
	 * Resolve whether a click should be handled as add-to-cart.
	 *
	 * @param {Event} event Click event.
	 * @return {HTMLAnchorElement|HTMLButtonElement|null}
	 */
	function resolveAddToCartControl( event ) {
		const control = event.target.closest( 'a, button' );

		if ( ! control || control.classList.contains( 'wc-interactive' ) ) {
			return null;
		}

		if ( control.classList.contains( 'add_to_cart_button' ) || control.closest( '.wc-block-grid__product-add-to-cart' ) ) {
			return control;
		}

		if ( control instanceof HTMLAnchorElement && control.href && control.href.includes( 'add-to-cart=' ) ) {
			return control;
		}

		return null;
	}

	document.addEventListener(
		'click',
		( event ) => {
			const trigger = resolveAddToCartControl( event );

			if ( ! trigger ) {
				return;
			}

			const data = parseProductFromControl( trigger );

			if ( ! data ) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();
			event.stopImmediatePropagation();

			addToCart( data, trigger );
		},
		true
	);

	document.addEventListener(
		'submit',
		( event ) => {
			const form = event.target.closest( 'form.cart' );

			if ( ! form || form.closest( '.product-type-variable' ) ) {
				return;
			}

			const data = parseProductFromForm( form );

			if ( ! data ) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();

			const submitter = form.querySelector( '[type="submit"]' ) || form;
			addToCart( data, submitter );
		},
		true
	);

	/**
	 * WooCommerce also binds add-to-cart — disable to avoid double requests.
	 */
	function disableWooCommerceAddToCart() {
		if ( typeof jQuery === 'undefined' ) {
			return;
		}

		jQuery( document.body ).off( 'click', '.add_to_cart_button:not(.wc-interactive)' );
		jQuery( document.body ).off( 'keydown', '.add_to_cart_button:not(.wc-interactive)' );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', () => {
			disableWooCommerceAddToCart();
			showQueuedCartToast();
		} );
	} else {
		disableWooCommerceAddToCart();
		showQueuedCartToast();
	}

	window.addEventListener( 'load', disableWooCommerceAddToCart );
} )();
