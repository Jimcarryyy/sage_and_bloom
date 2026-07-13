(function () {
	'use strict';

	var root = document.querySelector('[data-booking-app]');
	if (!root || !window.sbcBooking) {
		return;
	}

	var config = window.sbcBooking;
	var serviceSelect = root.querySelector('[data-booking-service]');
	var dateInput = root.querySelector('[data-booking-date]');
	var slotsWrap = root.querySelector('[data-booking-slots]');
	var slotsList = root.querySelector('[data-booking-slots-list]');
	var timeInput = root.querySelector('[data-booking-time]');
	var form = root.querySelector('[data-booking-form]');
	var notice = root.querySelector('[data-booking-notice]');
	var submitBtn = root.querySelector('[data-booking-submit]');

	function showNotice(message, type) {
		if (!notice) {
			return;
		}
		notice.hidden = !message;
		notice.textContent = message || '';
		notice.className = 'form-notice form-notice--' + (type || 'success');
	}

	function clearSlots() {
		if (slotsList) {
			slotsList.innerHTML = '';
		}
		if (slotsWrap) {
			slotsWrap.hidden = true;
		}
		if (timeInput) {
			timeInput.value = '';
		}
	}

	function renderSlots(slots) {
		clearSlots();
		if (!slots.length) {
			showNotice(config.i18nNoSlots, 'error');
			return;
		}

		showNotice('', 'success');
		slotsWrap.hidden = false;

		slots.forEach(function (slot) {
			var btn = document.createElement('button');
			btn.type = 'button';
			btn.className = 'booking-slot';
			btn.textContent = slot.label;
			btn.dataset.time = slot.time;
			btn.addEventListener('click', function () {
				Array.prototype.forEach.call(slotsList.querySelectorAll('.booking-slot'), function (el) {
					el.classList.remove('is-selected');
				});
				btn.classList.add('is-selected');
				timeInput.value = slot.time;
			});
			slotsList.appendChild(btn);
		});
	}

	function fetchSlots() {
		var serviceId = serviceSelect ? serviceSelect.value : '';
		var date = dateInput ? dateInput.value : '';

		clearSlots();
		if (!serviceId || !date) {
			return;
		}

		var url = config.restUrl + 'availability?service_id=' + encodeURIComponent(serviceId) + '&date=' + encodeURIComponent(date);

		fetch(url, {
			headers: {
				'X-WP-Nonce': config.nonce
			}
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (data) {
				renderSlots(data.slots || []);
			})
			.catch(function () {
				showNotice(config.i18nError, 'error');
			});
	}

	if (serviceSelect) {
		serviceSelect.addEventListener('change', fetchSlots);
	}

	if (dateInput) {
		dateInput.min = new Date().toISOString().split('T')[0];
		dateInput.addEventListener('change', fetchSlots);
	}

	if (form) {
		form.addEventListener('submit', function (event) {
			event.preventDefault();
			showNotice('', 'success');

			if (!timeInput || !timeInput.value) {
				showNotice(config.i18nSelectSlot, 'error');
				return;
			}

			submitBtn.disabled = true;
			submitBtn.textContent = config.i18nSubmitting;

			var payload = {
				service_id: serviceSelect ? serviceSelect.value : '',
				booking_date: dateInput ? dateInput.value : '',
				booking_time: timeInput.value,
				customer_name: form.querySelector('[name="customer_name"]').value,
				customer_email: form.querySelector('[name="customer_email"]').value,
				customer_phone: form.querySelector('[name="customer_phone"]').value,
				message: form.querySelector('[name="message"]').value
			};

			fetch(config.restUrl + 'bookings', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': config.nonce
				},
				body: JSON.stringify(payload)
			})
				.then(function (response) {
					return response.json().then(function (data) {
						return { ok: response.ok, data: data };
					});
				})
				.then(function (result) {
					if (!result.ok) {
						throw new Error(result.data.message || config.i18nError);
					}

					form.reset();
					clearSlots();
					showNotice(result.data.message || config.i18nSuccess, 'success');
				})
				.catch(function (error) {
					showNotice(error.message || config.i18nError, 'error');
				})
				.finally(function () {
					submitBtn.disabled = false;
					submitBtn.textContent = config.i18nSubmit;
				});
		});
	}
})();
