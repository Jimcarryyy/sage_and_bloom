(function () {
	'use strict';

	if (typeof Stripe === 'undefined' || !window.sbcStripeCheckout) {
		return;
	}

	var config = window.sbcStripeCheckout;
	var stripe = Stripe(config.publishableKey);
	var elements = stripe.elements();
	var card = elements.create('card', {
		hidePostalCode: true,
		style: {
			base: {
				fontSize: '16px',
				color: '#1f2937',
				'::placeholder': { color: '#9ca3af' }
			}
		}
	});

	var mountEl = document.getElementById('sbc-stripe-card-element');
	if (mountEl) {
		card.mount('#sbc-stripe-card-element');
		card.on('change', function (event) {
			var errorEl = document.getElementById('sbc-stripe-card-errors');
			if (errorEl) {
				errorEl.textContent = event.error ? event.error.message : '';
			}
		});
	}

	function isStripeSelected() {
		var selected = document.querySelector('input[name="payment_method"]:checked');
		return selected && selected.value === config.gatewayId;
	}

	function setProcessing(form, processing) {
		var button = form.querySelector('#place_order');
		if (button) {
			button.disabled = processing;
			button.textContent = processing ? config.i18nProcessing : button.dataset.label || button.textContent;
			if (!button.dataset.label) {
				button.dataset.label = button.textContent;
			}
		}
	}

	document.body.addEventListener('checkout_error', function () {
		var form = document.querySelector('form.checkout');
		if (form) {
			setProcessing(form, false);
		}
	});

	jQuery('form.checkout').on('checkout_place_order_' + config.gatewayId, function () {
		var form = this;
		var hidden = document.getElementById('sbc-stripe-payment-method-id');

		if (!isStripeSelected()) {
			return true;
		}

		if (hidden && hidden.value) {
			return true;
		}

		setProcessing(form, true);

		stripe.createPaymentMethod({
			type: 'card',
			card: card
		}).then(function (result) {
			if (result.error) {
				var errorEl = document.getElementById('sbc-stripe-card-errors');
				if (errorEl) {
					errorEl.textContent = result.error.message || config.i18nError;
				}
				setProcessing(form, false);
				jQuery(document.body).trigger('checkout_error');
				return;
			}

			if (hidden) {
				hidden.value = result.paymentMethod.id;
			}

			jQuery(form).trigger('submit');
		});

		return false;
	});
})();
