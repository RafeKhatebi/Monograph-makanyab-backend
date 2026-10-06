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

        var picker = typeSelect.closest('[data-suggest-type-picker]');
        if (picker) {
            var trigger = document.createElement('button');
            var menu = document.createElement('div');
            var options = [];
            var pickerLabel = picker.parentElement.querySelector('span');

            trigger.type = 'button';
            trigger.className = 'submission-type-select submission-type-trigger';
            trigger.setAttribute('aria-label', pickerLabel ? pickerLabel.textContent.trim() : typeSelect.name);
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.setAttribute('aria-controls', 'submission-type-menu');
            trigger.innerHTML = '<span class="submission-type-trigger__text"></span><i class="fa fa-chevron-down" aria-hidden="true"></i>';

            menu.id = 'submission-type-menu';
            menu.className = 'submission-type-menu';
            menu.setAttribute('role', 'listbox');
            menu.setAttribute('aria-label', trigger.getAttribute('aria-label'));
            menu.hidden = true;

            function closeTypeMenu() {
                menu.hidden = true;
                trigger.setAttribute('aria-expanded', 'false');
            }

            function openTypeMenu(focusOption) {
                menu.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
                if (focusOption) {
                    var selected = options.find(function (option) { return option.dataset.value === typeSelect.value; });
                    (selected || options[0]).focus();
                }
            }

            function syncTypeMenu() {
                trigger.querySelector('.submission-type-trigger__text').textContent = typeSelect.selectedOptions[0].textContent;
                options.forEach(function (option) {
                    var isSelected = option.dataset.value === typeSelect.value;
                    option.classList.toggle('is-selected', isSelected);
                    option.setAttribute('aria-selected', String(isSelected));
                });
            }

            Array.from(typeSelect.options).forEach(function (selectOption) {
                var option = document.createElement('button');
                option.type = 'button';
                option.className = 'submission-type-option';
                option.setAttribute('role', 'option');
                option.dataset.value = selectOption.value;
                option.textContent = selectOption.textContent;
                option.tabIndex = -1;
                option.addEventListener('click', function () {
                    typeSelect.value = selectOption.value;
                    typeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    closeTypeMenu();
                    trigger.focus();
                });
                menu.appendChild(option);
                options.push(option);
            });

            picker.appendChild(trigger);
            picker.appendChild(menu);
            picker.classList.add('is-enhanced');
            typeSelect.addEventListener('change', syncTypeMenu);
            syncTypeMenu();

            trigger.addEventListener('click', function () {
                if (menu.hidden) openTypeMenu(false);
                else closeTypeMenu();
            });

            picker.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !menu.hidden) {
                    event.preventDefault();
                    closeTypeMenu();
                    trigger.focus();
                } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (menu.hidden) {
                        openTypeMenu(true);
                    } else {
                        var current = options.indexOf(document.activeElement);
                        var next = event.key === 'ArrowDown'
                            ? (current + 1) % options.length
                            : (current <= 0 ? options.length - 1 : current - 1);
                        options[next].focus();
                    }
                } else if (event.key === 'Tab') {
                    closeTypeMenu();
                }
            });

            document.addEventListener('click', function (event) {
                if (!picker.contains(event.target)) closeTypeMenu();
            });
        }
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
