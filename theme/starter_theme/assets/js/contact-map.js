/**
 * Interactive Leaflet map for the contact page.
 */
(function () {
	'use strict';

	var mapEl = document.getElementById('contact-map');

	if (!mapEl || typeof L === 'undefined') {
		return;
	}

	var lat = parseFloat(mapEl.dataset.lat, 10);
	var lng = parseFloat(mapEl.dataset.lng, 10);
	var zoom = parseInt(mapEl.dataset.zoom, 10) || 15;
	var address = mapEl.dataset.address || '';
	var label = mapEl.dataset.label || '';

	function initMap(mapLat, mapLng) {
		var map = L.map(mapEl, {
			scrollWheelZoom: false,
		}).setView([mapLat, mapLng], zoom);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
		}).addTo(map);

		var marker = L.marker([mapLat, mapLng]).addTo(map);
		var popup = label;

		if (address) {
			popup += '<br><span style="font-size:0.875rem;">' + address + '</span>';
		}

		marker.bindPopup(popup).openPopup();

		mapEl.classList.add('is-ready');

		// Enable zoom on click so scroll doesn't hijack the page.
		map.on('click', function () {
			map.scrollWheelZoom.enable();
		});
	}

	if (!isNaN(lat) && !isNaN(lng)) {
		initMap(lat, lng);
		return;
	}

	if (!address) {
		return;
	}

	fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(address))
		.then(function (response) {
			return response.json();
		})
		.then(function (results) {
			if (!results || !results.length) {
				return;
			}

			initMap(parseFloat(results[0].lat, 10), parseFloat(results[0].lon, 10));
		})
		.catch(function () {
			mapEl.classList.add('contact-map--error');
		});
})();
