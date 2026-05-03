document.addEventListener('DOMContentLoaded', function () {
    var mapElement = document.getElementById('place-map');
    if (!mapElement) return;

    if (typeof L === 'undefined') {
        console.error('Leaflet did not load. Check the Leaflet script URL and network access.');
        return;
    }

    var locationData = {
        "Badakhshan": {
            center: [36.7348, 70.8119],
            districts: ["Faizabad", "Argo", "Baharak", "Darayim", "Jurm", "Kishim", "Raghistan", "Shuhada", "Tagab", "Wakhan", "Yawan", "Zebak", "Kuran wa Munjan", "Maimay", "Nusay", "Shekay", "Shuhada", "Wurduj", "Yamgan",]
        },
        "Badghis": {
            center: [35.1671, 63.7695],
            districts: ["Qala-e-Naw", "Ab Kamari", "Bala Murghab", "Ghormach", "Jawand", "Muqur", "Qadis", "Qala-e-Naw", "Murghab", "Muqur", "Bala Murghab", "Ghormach", "Jawand", "Qadis", "Ab Kamari"]
        },
        "Baghlan": {
            center: [36.1307, 68.7083],
            districts: ["Pul-e-Khumri", "Baghlan-e-Jadid", "Dahana-i-Ghori", "Doshi", "Khinjan", "Nahrin", "Puli Hisar", "Andarab", "Khost wa Fereng", "Dushi", "Burka", "Khinjan", "Nahrin", "Puli Hisar", "Andarab", "Khost wa Fereng", "Dushi", "Burka"]
        },
        "Balkh": {
            center: [36.7569, 66.8972],
            districts: ["Mazar-e-Sharif", "Dehdadi", "Chahar Bolak", "Chahar Kint", "Dawlatabad", "Khulm", "Sholgara", "Shortepa", "Zari", "Nawbahar", "Marmul", "Kaldar", "Kishindih", "Sholgar", "Chahar Bolak", "Chahar Kint", "Dawlatabad", "Khulm", "Sholgara", "Shortepa", "Zari", "Nawbahar", "Marmul", "Kaldar", "Kishindih"]
        },
        "Bamyan": {
            center: [34.8212, 67.8272],
            districts: ["Bamyan", "Yakawlang", "Panjab", "Waras", "Kahmard", "Shibar", "Sayghan", "Shuhil", "Bamyan", "Yakawlang", "Panjab", "Waras", "Kahmard", "Shibar", "Sayghan", "Shuhil"]
        },
        "Daykundi": {
            center: [33.7476, 66.0464],
            districts: ["Nili", "Ashtarlay", "Kiti", "Miramor", "Sangi Takht", "Shahristan", "Nili", "Ashtarlay", "Kiti", "Miramor", "Sangi Takht", "Shahristan"]
        },
        "Farah": {
            center: [32.3745, 62.1164],
            districts: ["Farah", "Anar Dara", "Bakwa", "Bala Buluk", "Gulistan", "Khak-e-Safed", "Lash wa Juwayn", "Pusht Rod", "Salahuddin", "Shib Koh", "Farah", "Anar Dara", "Bakwa", "Bala Buluk", "Gulistan", "Khak-e-Safed", "Lash wa Juwayn", "Pusht Rod", "Salahuddin", "Shib Koh"]
        },
        "Faryab": {
            center: [36.0796, 64.9052],
            districts: ["Maymana", "Almar", "Andkhoy", "Dawlatabad", "Ghormach", "Qaisar", "Shirin Tagab", "Pashtun Kot", "Khani Chahar Bagh", "Maimana", "Almar", "Andkhoy", "Dawlatabad", "Ghormach", "Qaisar", "Shirin Tagab", "Pashtun Kot", "Khani Chahar Bagh"]
        },
        "Ghazni": {
            center: [33.5539, 68.4209],
            districts: ["Ghazni", "Andar", "Deh Yak", "Gelan", "Jaghatu", "Malistan", "Nawa", "Qarabagh", "Rika", "Waghaz", "Zana Khan", "Ghazni", "Andar", "Deh Yak", "Gelan", "Jaghatu", "Malistan", "Nawa", "Qarabagh", "Rika", "Waghaz", "Zana Khan"]
        },
        "Ghor": {
            center: [34.0996, 64.9059],
            districts: ["Chaghcharan", "Dawlat Yar", "Lal wa Sarjangal", "Pasaband", "Shahrak", "Taywara", "Chaghcharan", "Dawlat Yar", "Lal wa Sarjangal", "Pasaband", "Shahrak", "Taywara"]
        },
        "Helmand": {
            center: [31.5799, 64.3692],
            districts: ["Lashkar Gah", "Gereshk", "Kajaki", "Musa Qala", "Nad Ali", "Nawa", "Sangin", "Marja", "Garmser", "Washir", "Lashkar Gah", "Gereshk", "Kajaki", "Musa Qala", "Nad Ali", "Nawa", "Sangin", "Marja", "Garmser", "Washir"]
        },
        "Herat": {
            center: [34.3529, 62.2040],
            districts: ["Herat", "Injil", "Guzara", "Karukh", "Kushk", "Obe", "Pashtun Zarghun", "Zinda Jan", "Herat", "Injil", "Guzara", "Karukh", "Kushk", "Obe", "Pashtun Zarghun", "Zinda Jan"]
        },
        "Jowzjan": {
            center: [36.8969, 65.6659],
            districts: ["Sheberghan", "Aqcha", "Darzab", "Faizabad", "Khamyab", "Mingajik", "Qush Tepa,", "Shibirghan", "Aqcha", "Darzab", "Faizabad", "Khamyab", "Mingajik", "Qush Tepa"]
        },
        "Kabul": {
            center: [34.5553, 69.2075],
            districts: ["Kabul", "Bagrami", "Chahar Asyab", "Deh Sabz", "Farza", "Guldara", "Kalakan", "Mir Bacha Kot", "Paghman", "Surobi,"]
        },
        "Kandahar": {
            center: [31.6289, 65.7372],
            districts: ["Kandahar", "Arghandab", "Daman", "Dand", "Khakrez", "Maiwand", "Panjwai", "Spin Boldak", "Zheray", "Kandahar", "Arghandab", "Daman", "Dand", "Khakrez", "Maiwand", "Panjwai", "Spin Boldak", "Zheray"]
        },
        "Kapisa": {
            center: [34.9811, 69.6215],
            districts: ["Mahmud Raqi", "Alasay", "Hesa Awal Kohistan", "Koh Band", "Nijrab", "Tagab", "Kapisa", "Alasay", "Hesa Awal Kohistan", "Koh Band", "Nijrab", "Tagab"]
        },
        "Khost": {
            center: [33.3395, 69.9204],
            districts: ["Khost", "Bak", "Gurbuz", "Mandozai", "Musa Khel", "Nadir Shah Kot", "Sabari", "Tani"]
        },
        "Kunar": {
            center: [34.8466, 71.0973],
            districts: ["Asadabad", "Chapa Dara", "Dangam", "Marawara", "Narang", "Nari", "Shigal"]
        },
        "Kunduz": {
            center: [36.7280, 68.8570],
            districts: ["Kunduz", "Aliabad", "Chahar Dara", "Dasht-e-Archi", "Imam Sahib", "Khanabad", "Qalai Zal", "Kunduz", "Aliabad", "Chahar Dara", "Dasht-e-Archi", "Imam Sahib", "Khanabad", "Qalai Zal"]
        },
        "Laghman": {
            center: [34.6898, 70.1456],
            districts: ["Mehtar Lam", "Alingar", "Alishing", "Dawlat Shah", "Qarghayi", "Badpash", "Alingar", "Alishing", "Dawlat Shah", "Qarghayi", "Badpash"]
        },
        "Logar": {
            center: [34.0146, 69.1924],
            districts: ["Pul-e-Alam", "Azra", "Baraki Barak", "Charkh", "Kharwar", "Mohammad Agha"]
        },
        "Nangarhar": {
            center: [34.4342, 70.4478],
            districts: ["Jalalabad", "Achin", "Bati Kot", "Behsud", "Chaparhar", "Khogyani", "Kuz Kunar", "Surkh Rod"]
        },
        "Nimruz": {
            center: [31.0261, 61.8383],
            districts: ["Zaranj", "Chakhansur", "Kang", "Khash Rod"]
        },
        "Nuristan": {
            center: [35.3250, 70.9071],
            districts: ["Parun", "Bargi Matal", "Kamdesh", "Mandol", "Nurgram", "Wama", "Waygal"]
        },
        "Paktia": {
            center: [33.7062, 69.3831],
            districts: ["Gardez", "Ahmad Aba", "Dand Patan", "Jaji", "Sayed Karam", "Zurmat"]
        },
        "Paktika": {
            center: [32.2645, 68.5247],
            districts: ["Sharana", "Barmal", "Gayan", "Mata Khan", "Sar Hawza", "Urgun", "Yahya Khel"]
        },
        "Panjshir": {
            center: [35.5021, 69.9989],
            districts: ["Bazarak", "Anaba", "Darah", "Khenj", "Paryan", "Rukha", "Shotul"]
        },
        "Parwan": {
            center: [35.0095, 69.2642],
            districts: ["Charikar", "Bagram", "Ghorband", "Jabal Saraj", "Koh Safi", "Salang", "Shinwari", "Sayed Khel"]
        },
        "Samangan": {
            center: [36.3150, 67.9640],
            districts: ["Aybak", "Dara-i-Sufi Bala", "Dara-i-Sufi Payin", "Hazrat Sultan", "Khuram wa Sarbagh", "Ruyi Du Ab"]
        },
        "Sar-e Pol": {
            center: [36.2167, 65.9333],
            districts: ["Sar-e Pol", "Balkhab", "Gosfandi", "Kohistanat", "Sayyad", "Sozma Qala"]
        },
        "Takhar": {
            center: [36.7361, 69.5345],
            districts: ["Taloqan", "Baharak", "Bangi", "Chah Ab", "Darqad", "Farkhar", "Khwaja Ghar", "Rustaq"]
        },
        "Urozgan": {
            center: [32.9277, 66.6325],
            districts: ["Tirin Kot", "Chora", "Deh Rawud", "Gizab", "Khas Urozgan"]
        },
        "Wardak": {
            center: [34.3495, 68.8570],
            districts: ["Maidan Shar", "Chak", "Daymirdad", "Jaghatu", "Jalrez", "Nirkh", "Saydabad"]
        },
        "Zabul": {
            center: [32.1919, 67.1894],
            districts: ["Qalat", "Arghandab", "Atghar", "Day Chopan", "Mizan", "Shah Joy", "Shinkay"]
        }
    };


    var countrySelect = document.querySelector('select[name="country"]');
    var provinceSearch = document.getElementById('province-search');
    var provinceSelect = document.getElementById('province-select');
    var districtSelect = document.getElementById('district-select');
    var latInput = document.querySelector('input[name="latitude"]');
    var lngInput = document.querySelector('input[name="longitude"]');
    var coordsLabel = document.getElementById('selected-coords');

    function populateProvinces(filter) {
        var query = filter ? filter.toLowerCase() : '';
        var provinceNames = Object.keys(locationData).filter(function (province) {
            return province.toLowerCase().includes(query);
        });

        provinceSelect.innerHTML = '';
        provinceNames.forEach(function (province) {
            var option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            provinceSelect.appendChild(option);
        });

        if (!provinceNames.length) {
            provinceSelect.innerHTML = '<option value="">No provinces found</option>';
            districtSelect.innerHTML = '<option value="">Select province first</option>';
            districtSelect.disabled = true;
            return;
        }

        var selectedProvince = provinceSelect.dataset.selected || provinceNames[0];
        if (provinceNames.includes(selectedProvince)) {
            provinceSelect.value = selectedProvince;
        } else {
            provinceSelect.value = provinceNames[0];
        }

        populateDistricts(provinceSelect.value);
    }

    function populateDistricts(province) {
        var data = locationData[province];
        districtSelect.innerHTML = '';

        if (!data) {
            districtSelect.innerHTML = '<option value="">Select province first</option>';
            districtSelect.disabled = true;
            return;
        }

        data.districts.forEach(function (district) {
            var option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });

        districtSelect.disabled = false;
        var selectedDistrict = districtSelect.dataset.selected;
        if (selectedDistrict && data.districts.includes(selectedDistrict)) {
            districtSelect.value = selectedDistrict;
        }

        if (data.center && map) {
            map.setView(data.center, 8);
        }
    }

    provinceSearch.addEventListener('input', function () {
        populateProvinces(this.value);
    });

    provinceSelect.addEventListener('change', function () {
        populateDistricts(this.value);
    });

    var initialProvince = provinceSelect.dataset.selected || 'Kabul';
    var initialCenter = locationData[initialProvince] ? locationData[initialProvince].center : [34.5553, 69.2075];
    var initialLat = parseFloat(latInput.value);
    var initialLng = parseFloat(lngInput.value);
    var hasInitial = !isNaN(initialLat) && !isNaN(initialLng);
    var enableScrollWheel = mapElement.dataset.scrollWheel !== 'false';
    var map = L.map(mapElement, {
        scrollWheelZoom: enableScrollWheel
    }).setView(hasInitial ? [initialLat, initialLng] : initialCenter, hasInitial ? 13 : 5);

    populateProvinces(provinceSearch.value);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;

    function updatePosition(latitude, longitude) {
        latInput.value = latitude.toFixed(6);
        lngInput.value = longitude.toFixed(6);
        coordsLabel.textContent = latitude.toFixed(6) + ', ' + longitude.toFixed(6);

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
