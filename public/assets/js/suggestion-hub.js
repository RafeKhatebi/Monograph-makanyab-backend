(function () {
    var form = document.querySelector('[data-suggest-form]');
    if (!form) {
        return;
    }

    var lastSharedName = '';

    function sync() {
        var typeSelect = document.querySelector('[data-suggest-type-select]');
        var selected = typeSelect || form.querySelector('[data-suggest-type]:checked') || form.querySelector('input[name="type"]');
        var type = selected ? selected.value : 'place';
        var activeName = Array.from(form.querySelectorAll('input[name="name"]')).find(function (input) {
            return !input.disabled && input.value;
        });

        if (activeName) {
            lastSharedName = activeName.value;
        }

        form.querySelectorAll('.suggestion-tab').forEach(function (tab) {
            var input = tab.querySelector('[data-suggest-type]');
            var isActive = input && input.value === type;

            tab.classList.toggle('is-active', isActive);
            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        form.querySelectorAll('[data-suggest-for]').forEach(function (node) {
            var allowed = node.getAttribute('data-suggest-for').split(/\s+/);
            var isHidden = allowed.indexOf(type) === -1;

            node.classList.toggle('is-hidden', isHidden);
            node.querySelectorAll('input:not([data-suggest-type]), select, textarea, button').forEach(function (field) {
                field.disabled = isHidden;
            });
        });

        form.querySelectorAll('input[name="name"]').forEach(function (input) {
            if (!input.disabled && lastSharedName && !input.value) {
                input.value = lastSharedName;
            }
        });

        if (window.history && window.history.replaceState) {
            var url = new URL(window.location.href);
            url.searchParams.set('type', type);
            window.history.replaceState({}, '', url.toString());
        }
    }

    form.querySelectorAll('[data-suggest-type]').forEach(function (input) {
        input.addEventListener('change', sync);

        var tab = input.closest('.suggestion-tab');
        if (tab) {
            tab.addEventListener('click', function () {
                if (!input.checked) {
                    input.checked = true;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }
    });

    if (window.jQuery) {
        window.jQuery(form).on('ifChecked ifChanged', '[data-suggest-type]', sync);
    }

    var typeSelect = document.querySelector('[data-suggest-type-select]');
    if (typeSelect) {
        typeSelect.addEventListener('change', sync);
    }

    sync();

    var firstInvalid = form.querySelector('.is-invalid, [aria-invalid="true"]');
    if (firstInvalid) {
        setTimeout(function () {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof firstInvalid.focus === 'function') {
                firstInvalid.focus({ preventScroll: true });
            }
        }, 100);
    }
})();
