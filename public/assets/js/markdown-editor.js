(function () {
    var token = document.querySelector('meta[name="csrf-token"]');

    function replaceSelection(input, before, after, fallback) {
        var start = input.selectionStart;
        var end = input.selectionEnd;
        var selected = input.value.slice(start, end) || fallback;
        var replacement = before + selected + after;

        input.setRangeText(replacement, start, end, 'end');
        input.focus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function prefixLines(input, prefix, fallback) {
        var start = input.selectionStart;
        var end = input.selectionEnd;
        var lineStart = input.value.lastIndexOf('\n', Math.max(0, start - 1)) + 1;
        var selected = input.value.slice(lineStart, end) || fallback;
        var replacement = selected.split('\n').map(function (line) { return prefix + line; }).join('\n');

        input.setRangeText(replacement, lineStart, end, 'end');
        input.focus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function applyAction(input, action) {
        var actions = {
            heading: function () { prefixLines(input, '## ', 'Heading'); },
            bold: function () { replaceSelection(input, '**', '**', 'bold text'); },
            italic: function () { replaceSelection(input, '*', '*', 'italic text'); },
            quote: function () { prefixLines(input, '> ', 'Quote'); },
            list: function () { prefixLines(input, '- ', 'List item'); },
            link: function () { replaceSelection(input, '[', '](https://)', 'link text'); },
            code: function () { replaceSelection(input, '```\n', '\n```', 'code'); },
            table: function () { replaceSelection(input, '', '\n', '| Column 1 | Column 2 |\n| --- | --- |\n| Value 1 | Value 2 |'); },
            image: function () { replaceSelection(input, '![', '](https://)', 'image description'); }
        };

        if (actions[action]) {
            actions[action]();
        }
    }

    document.querySelectorAll('[data-markdown-editor]').forEach(function (editor) {
        var input = editor.querySelector('[data-markdown-input]');
        var preview = editor.querySelector('[data-markdown-preview]');
        var writeTab = editor.querySelector('[data-markdown-tab="write"]');
        var previewTab = editor.querySelector('[data-markdown-tab="preview"]');
        var timer;
        var requestNumber = 0;

        function showWrite() {
            input.hidden = false;
            preview.hidden = true;
            writeTab.setAttribute('aria-selected', 'true');
            previewTab.setAttribute('aria-selected', 'false');
        }

        function refreshPreview() {
            var currentRequest = ++requestNumber;
            var content = input.value;

            if (!content.trim()) {
                preview.textContent = editor.dataset.previewEmpty;
                return;
            }

            fetch(editor.dataset.previewUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token ? token.content : ''
                },
                body: JSON.stringify({ content: content })
            }).then(function (response) {
                if (!response.ok) {
                    throw new Error('Preview request failed');
                }
                return response.json();
            }).then(function (data) {
                if (currentRequest === requestNumber) {
                    preview.innerHTML = data.html;
                }
            }).catch(function () {
                if (currentRequest === requestNumber) {
                    preview.textContent = editor.dataset.previewError;
                }
            });
        }

        function showPreview() {
            input.hidden = true;
            preview.hidden = false;
            writeTab.setAttribute('aria-selected', 'false');
            previewTab.setAttribute('aria-selected', 'true');
            refreshPreview();
        }

        editor.querySelectorAll('[data-markdown-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                applyAction(input, button.dataset.markdownAction);
            });
        });

        writeTab.addEventListener('click', showWrite);
        previewTab.addEventListener('click', showPreview);
        input.addEventListener('input', function () {
            if (preview.hidden) {
                return;
            }
            window.clearTimeout(timer);
            timer = window.setTimeout(refreshPreview, 350);
        });
    });
})();
