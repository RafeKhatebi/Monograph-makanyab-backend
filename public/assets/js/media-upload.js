document.addEventListener('DOMContentLoaded', function () {
    var translations = window.AppTranslations || {};

    function translate(key, replacements, fallback) {
        var value = translations[key] || fallback || key;

        Object.keys(replacements || {}).forEach(function (name) {
            value = value.replace(':' + name, replacements[name]);
        });

        return value;
    }

    document.querySelectorAll('[data-media-upload]').forEach(function (root) {
        var input = root.querySelector('input[type="file"]');
        var preview = root.querySelector('[data-image-preview]');
        var dropZone = root.querySelector('[data-drop-zone]');
        var coverInput = root.querySelector('[data-cover-index]');
        var files = [];

        if (!input || !preview || !dropZone || !coverInput) return;

        function syncInput() {
            var transfer = new DataTransfer();
            files.forEach(function (file) { transfer.items.add(file); });
            input.files = transfer.files;
            coverInput.value = files.length ? Math.min(Number(coverInput.value) || 0, files.length - 1) : '';
        }

        function render() {
            preview.innerHTML = '';
            files.forEach(function (file, index) {
                var card = document.createElement('div');
                card.className = 'media-preview-card';

                var image = document.createElement('img');
                image.alt = file.name;
                image.className = 'media-preview-card__image';
                image.src = URL.createObjectURL(file);
                image.onload = function () { URL.revokeObjectURL(image.src); };

                var title = document.createElement('small');
                title.textContent = file.name + ' · ' + Math.ceil(file.size / 1024) + ' KB';
                title.className = 'media-preview-card__title';

                var actions = document.createElement('div');
                actions.className = 'media-preview-card__actions';

                var cover = document.createElement('button');
                cover.type = 'button';
                cover.textContent = (Number(coverInput.value) === index ? '★ ' : '☆ ') + translate('mediaCover', {}, 'Cover');
                cover.setAttribute('aria-label', translate('mediaSetCover', { file: file.name }, 'Set ' + file.name + ' as cover image'));
                cover.onclick = function () { coverInput.value = index; render(); };

                var previous = document.createElement('button');
                previous.type = 'button';
                previous.textContent = '←';
                previous.disabled = index === 0;
                previous.setAttribute('aria-label', translate('mediaMoveEarlier', { file: file.name }, 'Move ' + file.name + ' earlier'));
                previous.onclick = function () {
                    var oldCover = Number(coverInput.value);
                    [files[index - 1], files[index]] = [files[index], files[index - 1]];
                    if (oldCover === index) coverInput.value = index - 1;
                    else if (oldCover === index - 1) coverInput.value = index;
                    syncInput(); render();
                };

                var next = document.createElement('button');
                next.type = 'button';
                next.textContent = '→';
                next.disabled = index === files.length - 1;
                next.setAttribute('aria-label', translate('mediaMoveLater', { file: file.name }, 'Move ' + file.name + ' later'));
                next.onclick = function () {
                    var oldCover = Number(coverInput.value);
                    [files[index], files[index + 1]] = [files[index + 1], files[index]];
                    if (oldCover === index) coverInput.value = index + 1;
                    else if (oldCover === index + 1) coverInput.value = index;
                    syncInput(); render();
                };

                var remove = document.createElement('button');
                remove.type = 'button';
                remove.textContent = translate('remove', {}, 'Remove');
                remove.setAttribute('aria-label', translate('mediaRemoveFile', { file: file.name }, 'Remove ' + file.name));
                remove.onclick = function () {
                    files.splice(index, 1);
                    syncInput(); render();
                };

                [cover, previous, next, remove].forEach(function (button) {
                    button.className = 'media-preview-card__button';
                    actions.appendChild(button);
                });
                card.append(image, title, actions);
                preview.appendChild(card);
            });
        }

        function addFiles(fileList) {
            Array.from(fileList).forEach(function (file) {
                var duplicate = files.some(function (existing) {
                    return existing.name === file.name && existing.size === file.size && existing.lastModified === file.lastModified;
                });
                if (!duplicate && files.length < 10) files.push(file);
            });
            syncInput();
            render();
        }

        input.addEventListener('change', function () {
            addFiles(input.files);
        });
        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.style.borderColor = '#10B981';
            });
        });
        ['dragleave', 'drop'].forEach(function (eventName) {
            dropZone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropZone.style.borderColor = '';
            });
        });
        dropZone.addEventListener('drop', function (event) {
            addFiles(event.dataTransfer.files);
        });
    });

    document.querySelectorAll('form[data-prevent-double-submit]').forEach(function (form) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) return;
            var button = form.querySelector('[data-submit-button]');
            if (!button || button.disabled) return;
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.textContent = button.dataset.loadingText || translate('saving', {}, 'Saving...');
        });
    });
});
