(function () {
    var translations = document.documentElement.dataset;
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('.filter-toggle');
        const panel = document.getElementById('search-filter-panel');

        if (toggle && panel) {
            toggle.addEventListener('click', function () {
                const open = panel.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(open));

                toggle.childNodes.forEach(function (node) {
                    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                        node.textContent = open ? ' ' + translations.hideFilters + ' ' : ' ' + translations.showFilters + ' ';
                    }
                });
            });
        }

        const searchType = document.querySelector('[data-search-type]');

        if (searchType) {
            const syncSearchCategories = function () {
                const activeType = searchType.value === 'services' ? 'services' : 'places';

                document.querySelectorAll('[data-category-group], [data-filter-category-group]').forEach(function (group) {
                    const groupType = group.dataset.categoryGroup || group.dataset.filterCategoryGroup;
                    group.classList.toggle('is-hidden', groupType !== activeType);
                });
            };

            searchType.addEventListener('change', syncSearchCategories);
            syncSearchCategories();
        }

        const discoverForm = document.querySelector('[data-discover-form]');

        if (discoverForm && window.DiscoverCategories) {
            const categorySelect = discoverForm.querySelector('[data-discover-category]');
            const typeInputs = discoverForm.querySelectorAll('[data-discover-type]');

            const syncDiscoverCategories = function () {
                const selected = discoverForm.querySelector('[data-discover-type]:checked');
                const type = selected && selected.value === 'service' ? 'service' : 'place';
                const categories = window.DiscoverCategories[type] || [];

                if (!categorySelect) {
                    return;
                }

                categorySelect.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = window.DiscoverCategories.placeholder || '';
                categorySelect.appendChild(placeholder);

                categories.forEach(function (category) {
                    const option = document.createElement('option');
                    option.value = category.slug;
                    option.textContent = category.name;
                    categorySelect.appendChild(option);
                });
            };

            typeInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    syncDiscoverCategories();

                    if (categorySelect) {
                        categorySelect.value = '';
                    }

                    if (typeof discoverForm.requestSubmit === 'function') {
                        discoverForm.requestSubmit();
                    } else {
                        discoverForm.submit();
                    }
                });
            });
        }

        document.querySelectorAll('[data-discover-select-picker]').forEach(function (picker) {
            const select = picker.querySelector('select');
            const label = document.querySelector('label[for="' + select.id + '"]');
            const trigger = document.createElement('button');
            const menu = document.createElement('div');
            const optionButtons = [];

            trigger.type = 'button';
            trigger.id = select.id + '-trigger';
            trigger.className = 'search-select discover-select-trigger';
            trigger.setAttribute('aria-haspopup', 'listbox');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.setAttribute('aria-controls', select.id + '-menu');
            trigger.setAttribute('aria-label', label ? label.textContent.trim() : select.name);
            trigger.innerHTML = '<span class="discover-select-trigger__text"></span><i class="fa fa-chevron-down" aria-hidden="true"></i>';

            menu.id = select.id + '-menu';
            menu.className = 'discover-select-menu';
            menu.setAttribute('role', 'listbox');
            menu.setAttribute('aria-label', trigger.getAttribute('aria-label'));
            menu.hidden = true;

            function syncSelect() {
                const selected = select.selectedOptions[0] || select.options[0];
                trigger.querySelector('.discover-select-trigger__text').textContent = selected.textContent;
                optionButtons.forEach(function (button) {
                    const isSelected = button.dataset.value === select.value;
                    button.classList.toggle('is-selected', isSelected);
                    button.setAttribute('aria-selected', String(isSelected));
                });
            }

            function closeSelect() {
                menu.hidden = true;
                trigger.setAttribute('aria-expanded', 'false');
            }

            function openSelect(focusOption) {
                menu.hidden = false;
                trigger.setAttribute('aria-expanded', 'true');
                if (focusOption) {
                    const selected = optionButtons.find(function (button) { return button.dataset.value === select.value; });
                    (selected || optionButtons[0]).focus();
                }
            }

            Array.from(select.options).forEach(function (option) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'discover-select-option';
                button.setAttribute('role', 'option');
                button.dataset.value = option.value;
                button.textContent = option.textContent;
                button.tabIndex = -1;
                button.addEventListener('click', function () {
                    select.value = option.value;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    closeSelect();
                    trigger.focus();
                });
                menu.appendChild(button);
                optionButtons.push(button);
            });

            picker.appendChild(trigger);
            picker.appendChild(menu);
            picker.classList.add('is-enhanced');
            if (label) label.htmlFor = trigger.id;
            select.addEventListener('change', syncSelect);
            syncSelect();

            trigger.addEventListener('click', function () {
                if (menu.hidden) openSelect(false);
                else closeSelect();
            });

            picker.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !menu.hidden) {
                    event.preventDefault();
                    closeSelect();
                    trigger.focus();
                } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (menu.hidden) {
                        openSelect(true);
                    } else {
                        const current = optionButtons.indexOf(document.activeElement);
                        const next = event.key === 'ArrowDown'
                            ? (current + 1) % optionButtons.length
                            : (current <= 0 ? optionButtons.length - 1 : current - 1);
                        optionButtons[next].focus();
                    }
                } else if (event.key === 'Tab') {
                    closeSelect();
                }
            });

            document.addEventListener('click', function (event) {
                if (!picker.contains(event.target)) closeSelect();
            });
        });

        if (window.jQuery && jQuery.fn.lightSlider && document.getElementById('image-gallery')) {
            const gallery = jQuery('#image-gallery');
            if (gallery.children().length) {
                gallery.lightSlider({
                    gallery: true,
                    item: 1,
                    thumbItem: 4,
                    slideMargin: 0,
                    speed: 500,
                    auto: true,
                    loop: true,
                    onSliderLoad: function () {
                        gallery.removeClass('cS-hidden');
                    }
                });
            }
        }

    });
})();
