document.addEventListener('DOMContentLoaded', function () {
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
                card.style.cssText = 'border:1px solid #E5E7EB;border-radius:8px;padding:8px;background:#fff;';

                var image = document.createElement('img');
                image.alt = file.name;
                image.style.cssText = 'width:100%;height:95px;object-fit:cover;border-radius:6px;';
                image.src = URL.createObjectURL(file);
                image.onload = function () { URL.revokeObjectURL(image.src); };

                var title = document.createElement('small');
                title.textContent = file.name + ' · ' + Math.ceil(file.size / 1024) + ' KB';
                title.style.cssText = 'display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin:6px 0;';

                var actions = document.createElement('div');
                actions.style.cssText = 'display:flex;gap:5px;flex-wrap:wrap;';

                var cover = document.createElement('button');
                cover.type = 'button';
                cover.textContent = Number(coverInput.value) === index ? '★ Cover' : '☆ Cover';
                cover.setAttribute('aria-label', 'Set ' + file.name + ' as cover image');
                cover.onclick = function () { coverInput.value = index; render(); };

                var previous = document.createElement('button');
                previous.type = 'button';
                previous.textContent = '←';
                previous.disabled = index === 0;
                previous.setAttribute('aria-label', 'Move ' + file.name + ' earlier');
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
                next.setAttribute('aria-label', 'Move ' + file.name + ' later');
                next.onclick = function () {
                    var oldCover = Number(coverInput.value);
                    [files[index], files[index + 1]] = [files[index + 1], files[index]];
                    if (oldCover === index) coverInput.value = index + 1;
                    else if (oldCover === index + 1) coverInput.value = index;
                    syncInput(); render();
                };

                var remove = document.createElement('button');
                remove.type = 'button';
                remove.textContent = 'Remove';
                remove.setAttribute('aria-label', 'Remove ' + file.name);
                remove.onclick = function () {
                    files.splice(index, 1);
                    syncInput(); render();
                };

                [cover, previous, next, remove].forEach(function (button) {
                    button.style.cssText = 'border:1px solid #D1D5DB;background:#fff;border-radius:5px;padding:3px 6px;font-size:11px;';
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
            button.textContent = button.dataset.loadingText || 'Saving…';
        });
    });
});
