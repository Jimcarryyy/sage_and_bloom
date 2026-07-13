( function () {
	'use strict';

	if (
		typeof wc === 'undefined' ||
		typeof wc.wcBlocksRegistry === 'undefined' ||
		typeof wc.wcSettings === 'undefined'
	) {
		return;
	}

	var registerPaymentMethod = wc.wcBlocksRegistry.registerPaymentMethod;
	var getPaymentMethodData = wc.wcSettings.getPaymentMethodData;
	var decodeEntities = wp.htmlEntities.decodeEntities;
	var sanitizeHTML = wc.sanitize.sanitizeHTML;
	var createElement = wp.element.createElement;
	var useEffect = wp.element.useEffect;
	var useRef = wp.element.useRef;
	var RawHTML = wp.element.RawHTML;

	var settings = getPaymentMethodData( 'sbc_stripe_test', {} );
	var title = decodeEntities( settings.title || '' ) || 'Credit / Debit Card (Test)';

	window.sbcStripeBlocks = window.sbcStripeBlocks || {
		stripe: null,
		card: null,
	};

	function getStripeState() {
		return window.sbcStripeBlocks;
	}

	function Label( props ) {
		var components = props.components || {};
		var PaymentMethodLabel = components.PaymentMethodLabel;

		if ( ! PaymentMethodLabel ) {
			return createElement( 'span', {}, title );
		}

		return createElement( PaymentMethodLabel, { text: title } );
	}

	function Description() {
		if ( ! settings.description ) {
			return null;
		}

		return createElement( RawHTML, {
			children: sanitizeHTML( settings.description ),
		} );
	}

	function StripeCardForm( props ) {
		var mountRef = useRef( null );
		var eventRegistration = props.eventRegistration || {};
		var emitResponse = props.emitResponse || {};
		var onPaymentSetup = eventRegistration.onPaymentSetup;
		var responseTypes = emitResponse.responseTypes || {
			SUCCESS: 'success',
			ERROR: 'error',
			FAIL: 'failure',
		};

		useEffect( function () {
			if ( ! settings.publishableKey || typeof Stripe === 'undefined' || ! mountRef.current ) {
				return undefined;
			}

			var state = getStripeState();

			if ( state.card ) {
				try {
					state.card.unmount();
				} catch ( unmountError ) {
					// Card element may already be unmounted.
				}
			}

			var stripe = Stripe( settings.publishableKey );
			var elements = stripe.elements();
			var card = elements.create( 'card', {
				hidePostalCode: true,
				disableLink: true,
				style: {
					base: {
						fontSize: '16px',
						color: '#1f2937',
					},
				},
			} );

			card.mount( mountRef.current );
			state.stripe = stripe;
			state.card = card;

			return function () {
				try {
					card.unmount();
				} catch ( unmountError ) {
					// Card element may already be unmounted.
				}
			};
		}, [] );

		useEffect(
			function () {
				if ( ! onPaymentSetup ) {
					return undefined;
				}

				var unsubscribe = onPaymentSetup( function () {
					var state = getStripeState();

					if ( ! state.stripe || ! state.card ) {
						return {
							type: responseTypes.ERROR,
							message:
								'Payment form is not ready yet. Please wait a moment and try again.',
						};
					}

					return state.stripe
						.createPaymentMethod( {
							type: 'card',
							card: state.card,
						} )
						.then( function ( result ) {
							if ( result.error ) {
								return {
									type: responseTypes.ERROR,
									message: result.error.message,
								};
							}

							return {
								type: responseTypes.SUCCESS,
								meta: {
									paymentMethodData: {
										sbc_stripe_payment_method_id: result.paymentMethod.id,
									},
								},
							};
						} )
						.catch( function ( error ) {
							return {
								type: responseTypes.ERROR,
								message:
									error && error.message
										? error.message
										: 'Payment failed. Please try again.',
							};
						} );
				} );

				return unsubscribe;
			},
			[ onPaymentSetup, responseTypes.ERROR, responseTypes.SUCCESS ]
		);

		return createElement(
			'div',
			{ className: 'sbc-stripe-blocks-form' },
			createElement( Description ),
			createElement( 'div', {
				ref: mountRef,
				className: 'sbc-stripe-card-element',
			} ),
			createElement( 'div', {
				id: 'sbc-stripe-card-errors-blocks',
				className: 'sbc-stripe-card-errors',
				role: 'alert',
			} ),
			settings.testNote
				? createElement(
						'p',
						{ className: 'sbc-stripe-test-note' },
						createElement( 'small', {}, settings.testNote )
				  )
				: null
		);
	}

	registerPaymentMethod( {
		name: 'sbc_stripe_test',
		label: createElement( Label ),
		content: createElement( StripeCardForm ),
		edit: createElement( StripeCardForm ),
		canMakePayment: function () {
			return Boolean( settings.publishableKey );
		},
		ariaLabel: title,
		supports: {
			features: settings.supports || [ 'products' ],
		},
	} );
} )();
