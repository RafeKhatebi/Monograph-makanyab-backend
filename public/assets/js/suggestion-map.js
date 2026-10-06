document.addEventListener('DOMContentLoaded', function () {
    var mapElement = document.getElementById('suggestion-map');
    if (!mapElement) {
        return;
    }

    var hasLeaflet = typeof L !== 'undefined';
    if (!hasLeaflet) {
        console.error('Leaflet did not load. Map interaction is disabled, but form submission is still available.');
    }

    var provinceSelect = document.getElementById('province-select');
    var districtSelect = document.getElementById('district-select');
    var cityInput = document.getElementById('city-value');
    var latInput = document.querySelector('input[name="latitude"]');
    var lngInput = document.querySelector('input[name="longitude"]');
    var coordsLabel = document.getElementById('selected-coords');
    var map = null;
    var marker;

    var locale = document.documentElement.lang;
    var provinces = window.SuggestionLocations || [];
    var locationData = Object.fromEntries(provinces.map(function (province) {
        return [province.value, province];
    }));

    function displayName(location) {
        return location.labels[locale] || location.labels.en;
    }

    function normalizeSearch(value) {
        return value.toLocaleLowerCase().replace(/[يى]/g, 'ی').replace(/ك/g, 'ک').trim();
    }

    function populateProvinces() {
        if (!provinceSelect) {
            return;
        }

        var selectedProvince = provinceSelect.dataset.selected || '';
        provinceSelect.replaceChildren(provinceSelect.options[0]);

        Object.keys(locationData).forEach(function (province) {
            var option = document.createElement('option');
            option.value = province;
            option.textContent = displayName(locationData[province]);
            provinceSelect.appendChild(option);
        });

        provinceSelect.value = locationData[selectedProvince] ? selectedProvince : '';
        populateDistricts(provinceSelect.value);
    }

    function populateDistricts(province) {
        if (!districtSelect) {
            return;
        }

        var data = locationData[province];
        districtSelect.innerHTML = '';

        if (!data) {
            var firstProvinceOption = document.createElement('option');
            firstProvinceOption.value = '';
            firstProvinceOption.textContent = districtSelect.dataset.selectProvinceFirst;
            districtSelect.appendChild(firstProvinceOption);
            districtSelect.disabled = true;
            if (cityInput) cityInput.value = '';
            return;
        }

        var placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = districtSelect.dataset.placeholder;
        districtSelect.appendChild(placeholder);

        data.districts.forEach(function (district) {
            var option = document.createElement('option');
            option.value = district.value;
            option.textContent = displayName(district);
            districtSelect.appendChild(option);
        });

        districtSelect.disabled = false;
        var selectedDistrict = districtSelect.dataset.selected;
        if (selectedDistrict && data.districts.some(function (district) { return district.value === selectedDistrict; })) {
            districtSelect.value = selectedDistrict;
        } else if (selectedDistrict) {
            var legacyOption = document.createElement('option');
            legacyOption.value = selectedDistrict;
            legacyOption.textContent = selectedDistrict;
            districtSelect.appendChild(legacyOption);
            districtSelect.value = selectedDistrict;
        } else {
            districtSelect.value = '';
        }

        syncCityFromDistrict();

        if (data.center && map) {
            map.setView(data.center, 8);
        }
    }

    function syncCityFromDistrict() {
        if (cityInput && districtSelect) {
            cityInput.value = districtSelect.value || '';
        }
    }

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
        } else if (map) {
            marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);
            marker.on('dragend', function (event) {
                var pos = event.target.getLatLng();
                updatePosition(pos.lat, pos.lng);
            });
        }
    }

    if (provinceSelect) {
        provinceSelect.addEventListener('change', function () {
            populateDistricts(this.value);
        });
    }

    function setupProvincePicker() {
        var picker = document.querySelector('[data-province-picker]');
        if (!picker || !provinceSelect) return;

        var input = document.createElement('input');
        var menu = document.createElement('div');
        var provinceNames = Object.keys(locationData);
        var visibleOptions = [];
        var activeIndex = -1;
        var suppressFocusOpen = false;
        var label = document.querySelector('label[for="province-select"]');

        input.id = 'province-search';
        input.type = 'text';
        input.setAttribute('inputmode', 'search');
        input.dir = 'auto';
        input.className = 'form-control submission-province-input';
        input.autocomplete = 'off';
        input.required = true;
        input.disabled = provinceSelect.disabled;
        input.placeholder = picker.dataset.placeholder;
        input.value = provinceSelect.value ? displayName(locationData[provinceSelect.value]) : '';
        input.setAttribute('role', 'combobox');
        input.setAttribute('aria-autocomplete', 'list');
        input.setAttribute('aria-controls', 'province-options');
        input.setAttribute('aria-expanded', 'false');

        menu.id = 'province-options';
        menu.className = 'submission-province-options';
        menu.setAttribute('role', 'listbox');
        menu.hidden = true;

        function closeMenu() {
            menu.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            input.removeAttribute('aria-activedescendant');
            activeIndex = -1;
        }

        function setActive(index) {
            activeIndex = index;
            visibleOptions.forEach(function (option, optionIndex) {
                option.classList.toggle('is-active', optionIndex === index);
            });
            if (index >= 0) {
                input.setAttribute('aria-activedescendant', visibleOptions[index].id);
                visibleOptions[index].scrollIntoView({ block: 'nearest' });
            } else {
                input.removeAttribute('aria-activedescendant');
            }
        }

        function chooseProvince(province) {
            provinceSelect.value = province;
            provinceSelect.dataset.selected = province;
            if (districtSelect) districtSelect.dataset.selected = '';
            input.value = displayName(locationData[province]);
            input.setCustomValidity('');
            provinceSelect.dispatchEvent(new Event('change', { bubbles: true }));
            closeMenu();
            suppressFocusOpen = document.activeElement !== input;
            input.focus();
        }

        function renderOptions(filter) {
            var query = normalizeSearch(filter === undefined ? input.value : filter);
            var matches = provinceNames.filter(function (province) {
                return normalizeSearch(displayName(locationData[province])).includes(query)
                    || normalizeSearch(province).includes(query);
            });

            menu.replaceChildren();
            visibleOptions = [];
            activeIndex = -1;
            input.removeAttribute('aria-activedescendant');

            if (!matches.length) {
                var empty = document.createElement('div');
                empty.className = 'submission-province-empty';
                empty.textContent = picker.dataset.noResults;
                menu.appendChild(empty);
            }

            matches.forEach(function (province, index) {
                var option = document.createElement('button');
                option.type = 'button';
                option.id = 'province-option-' + index;
                option.className = 'submission-province-option';
                option.setAttribute('role', 'option');
                option.setAttribute('aria-selected', String(provinceSelect.value === province));
                option.dataset.value = province;
                option.tabIndex = -1;
                option.dir = 'auto';
                option.textContent = displayName(locationData[province]);
                option.addEventListener('click', function () { chooseProvince(province); });
                menu.appendChild(option);
                visibleOptions.push(option);
            });

            menu.hidden = false;
            input.setAttribute('aria-expanded', 'true');
        }

        picker.appendChild(input);
        picker.appendChild(menu);
        picker.classList.add('is-enhanced');
        provinceSelect.required = false;
        if (label) label.htmlFor = input.id;

        input.addEventListener('focus', function () {
            if (suppressFocusOpen) {
                suppressFocusOpen = false;
                return;
            }
            renderOptions('');
        });
        input.addEventListener('click', function () {
            if (menu.hidden) renderOptions('');
        });
        input.addEventListener('input', function () {
            var query = normalizeSearch(input.value);
            var exact = provinceNames.find(function (province) {
                return normalizeSearch(displayName(locationData[province])) === query
                    || normalizeSearch(province) === query;
            });
            var nextValue = exact || '';
            if (provinceSelect.value !== nextValue) {
                provinceSelect.value = nextValue;
                provinceSelect.dataset.selected = nextValue;
                if (districtSelect) districtSelect.dataset.selected = '';
                provinceSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
            if (exact) input.value = displayName(locationData[exact]);
            input.setCustomValidity(query && !exact ? picker.dataset.selectMessage : '');
            renderOptions();
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !menu.hidden) {
                event.preventDefault();
                closeMenu();
            } else if ((event.key === 'ArrowDown' || event.key === 'ArrowUp') && !menu.hidden && visibleOptions.length) {
                event.preventDefault();
                var next = event.key === 'ArrowDown'
                    ? (activeIndex + 1) % visibleOptions.length
                    : (activeIndex <= 0 ? visibleOptions.length - 1 : activeIndex - 1);
                setActive(next);
            } else if (event.key === 'Enter' && !menu.hidden && visibleOptions.length) {
                event.preventDefault();
                chooseProvince(visibleOptions[activeIndex < 0 ? 0 : activeIndex].dataset.value);
            } else if (event.key === 'Tab') {
                closeMenu();
            }
        });

        document.addEventListener('click', function (event) {
            if (!picker.contains(event.target)) closeMenu();
        });
    }

    if (districtSelect) {
        districtSelect.addEventListener('change', syncCityFromDistrict);
    }

    [latInput, lngInput].forEach(function (input) {
        if (!input) {
            return;
        }

        input.addEventListener('change', function () {
            var latitude = latInput ? parseFloat(latInput.value) : NaN;
            var longitude = lngInput ? parseFloat(lngInput.value) : NaN;

            if (!isNaN(latitude) && !isNaN(longitude)) {
                updatePosition(latitude, longitude);
                if (map) {
                    map.setView([latitude, longitude], Math.max(map.getZoom(), 13));
                }
            }
        });
    });

    var initialProvince = provinceSelect ? provinceSelect.dataset.selected || 'Kabul' : 'Kabul';
    var initialCenter = locationData[initialProvince] ? locationData[initialProvince].center : [34.5553, 69.2075];
    var initialLat = latInput ? parseFloat(latInput.value) : NaN;
    var initialLng = lngInput ? parseFloat(lngInput.value) : NaN;
    var hasInitial = !isNaN(initialLat) && !isNaN(initialLng);

    if (provinceSelect) {
        populateProvinces();
        setupProvincePicker();
    }

    if (hasLeaflet) {
        map = L.map(mapElement).setView(hasInitial ? [initialLat, initialLng] : initialCenter, hasInitial ? 13 : 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        if (hasInitial) {
            updatePosition(initialLat, initialLng);
        }

        map.on('click', function (event) {
            updatePosition(event.latlng.lat, event.latlng.lng);
        });
    } else if (coordsLabel && !hasInitial) {
        coordsLabel.textContent = 'Map unavailable (Leaflet failed to load)';
    }
});
