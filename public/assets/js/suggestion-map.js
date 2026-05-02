document.addEventListener('DOMContentLoaded', function () {
    var mapElement = document.getElementById('suggestion-map');
    if (!mapElement) {
        return;
    }

    if (typeof L === 'undefined') {
        console.error('Leaflet did not load.');
        return;
    }

    var latInput = document.querySelector('input[name="latitude"]');
    var lngInput = document.querySelector('input[name="longitude"]');
    var coordsLabel = document.getElementById('selected-coords');

    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);
    var initialCenter = [34.5553, 69.2075];
    var hasInitial = !isNaN(initialLat) && !isNaN(initialLng);

    var map = L.map(mapElement).setView(hasInitial ? [initialLat, initialLng] : initialCenter, hasInitial ? 13 : 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;

    function updatePosition(latitude, longitude) {
        var roundedLat = parseFloat(latitude).toFixed(6);
        var roundedLng = parseFloat(longitude).toFixed(6);

        if (latInput) {
            latInput.value = roundedLat;
        }

        if (lngInput) {
            lngInput.value = roundedLng;
        }

        if (coordsLabel) {
            coordsLabel.textContent = roundedLat + ', ' + roundedLng;
        }

        if (marker) {
            marker.setLatLng([latitude, longitude]);
        } else {
            marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);
            marker.on('dragend', function (event) {
                var pos = event.target.getLatLng();
                updatePosition(pos.lat, pos.lng);
            });
        }
    }

    if (hasInitial) {
        updatePosition(initialLat, initialLng);
    }

    map.on('click', function (event) {
        updatePosition(event.latlng.lat, event.latlng.lng);
    });
});
