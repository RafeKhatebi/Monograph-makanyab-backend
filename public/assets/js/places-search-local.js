document.addEventListener('DOMContentLoaded', function () {
    var mapElement = document.getElementById('place-map');
    if (!mapElement) return;

    var provinceSearch = document.getElementById('province-search');
    var provinceSelect = document.getElementById('province-select');
    var districtSelect = document.getElementById('district-select');
    var latInput = document.querySelector('input[name="latitude"]');
    var lngInput = document.querySelector('input[name="longitude"]');
    var coordsLabel = document.getElementById('selected-coords');
    var dataElement = document.getElementById('place-location-data');

    if (!provinceSelect || !districtSelect || !latInput || !lngInput || !dataElement) {
        console.error('The place location form is incomplete.');
        return;
    }

    var locationData;
    try {
        locationData = JSON.parse(dataElement.textContent);
    } catch (error) {
        console.error('The place location data could not be loaded.', error);
        return;
    }

    var centers = {
        Badakhshan: [36.7348, 70.8119], Badghis: [35.1671, 63.7695],
        Baghlan: [36.1307, 68.7083], Balkh: [36.7569, 66.8972],
        Bamyan: [34.8212, 67.8272], Daykundi: [33.7476, 66.0464],
        Farah: [32.3745, 62.1164], Faryab: [36.0796, 64.9052],
        Ghazni: [33.5539, 68.4209], Ghor: [34.0996, 64.9059],
        Helmand: [31.5799, 64.3692], Herat: [34.3529, 62.2040],
        Jowzjan: [36.8969, 65.6659], Kabul: [34.5553, 69.2075],
        Kandahar: [31.6289, 65.7372], Kapisa: [34.9811, 69.6215],
        Khost: [33.3395, 69.9204], Kunar: [34.8466, 71.0973],
        Kunduz: [36.7280, 68.8570], Laghman: [34.6898, 70.1456],
        Logar: [34.0146, 69.1924], Nangarhar: [34.4342, 70.4478],
        Nimruz: [31.0261, 61.8383], Nuristan: [35.3250, 70.9071],
        Paktia: [33.7062, 69.3831], Paktika: [32.2645, 68.5247],
        Panjshir: [35.5021, 69.9989], Parwan: [35.0095, 69.2642],
        Samangan: [36.3150, 67.9640], 'Sar-e Pol': [36.2167, 65.9333],
        Takhar: [36.7361, 69.5345], Urozgan: [32.9277, 66.6325],
        Wardak: [34.3495, 68.8570], Zabul: [32.1919, 67.1894]
    };

    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);
    var hasInitial = Number.isFinite(initialLat) && Number.isFinite(initialLng);
    var initialProvince = provinceSelect.dataset.selected || provinceSelect.value || 'Kabul';
    var map = null;
    var marker = null;

    if (typeof L !== 'undefined') {
        map = L.map(mapElement, {
            scrollWheelZoom: mapElement.dataset.scrollWheel !== 'false'
        }).setView(hasInitial ? [initialLat, initialLng] : (centers[initialProvince] || centers.Kabul), hasInitial ? 13 : 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    } else {
        mapElement.innerHTML = '<p style="padding:16px">The map is unavailable. Enter coordinates in the fields below.</p>';
        latInput.readOnly = false;
        lngInput.readOnly = false;
    }

    function populateDistricts(province) {
        var districts = locationData[province] || [];
        var selected = districtSelect.dataset.selected || districtSelect.value;
        districtSelect.innerHTML = '<option value="">Select District</option>';

        districts.forEach(function (district) {
            var option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            option.selected = district === selected;
            districtSelect.appendChild(option);
        });

        districtSelect.disabled = districts.length === 0;
        if (map && centers[province] && !hasInitial) map.setView(centers[province], 8);
    }

    function populateProvinces(filter) {
        var query = (filter || '').trim().toLowerCase();
        var selected = provinceSelect.dataset.selected || provinceSelect.value;
        var provinces = Object.keys(locationData).filter(function (province) {
            return province.toLowerCase().includes(query);
        });

        provinceSelect.innerHTML = '<option value="">Select Province</option>';
        provinces.forEach(function (province) {
            var option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            option.selected = province === selected;
            provinceSelect.appendChild(option);
        });

        if (selected && provinces.includes(selected)) provinceSelect.value = selected;
        populateDistricts(provinceSelect.value);
    }

    function updatePosition(latitude, longitude) {
        latInput.value = latitude.toFixed(6);
        lngInput.value = longitude.toFixed(6);
        if (coordsLabel) coordsLabel.textContent = latitude.toFixed(6) + ', ' + longitude.toFixed(6);

        if (!map) return;
        if (marker) {
            marker.setLatLng([latitude, longitude]);
        } else {
            marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);
            marker.on('dragend', function (event) {
                var position = event.target.getLatLng();
                updatePosition(position.lat, position.lng);
            });
        }
    }

    if (provinceSearch) {
        provinceSearch.addEventListener('input', function () {
            populateProvinces(this.value);
        });
    }
    provinceSelect.addEventListener('change', function () {
        provinceSelect.dataset.selected = this.value;
        districtSelect.dataset.selected = '';
        populateDistricts(this.value);
    });

    populateProvinces(provinceSearch ? provinceSearch.value : '');
    if (hasInitial) updatePosition(initialLat, initialLng);
    if (map) {
        map.on('click', function (event) {
            updatePosition(event.latlng.lat, event.latlng.lng);
        });
    }
});
