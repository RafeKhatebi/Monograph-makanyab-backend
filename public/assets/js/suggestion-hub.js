(function () {
    var form = document.querySelector('[data-suggest-form]');
    if (!form) {
        return;
    }

    function sync() {
        var selected = form.querySelector('[data-suggest-type]:checked');
        var type = selected ? selected.value : 'place';

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

    sync();
})();
