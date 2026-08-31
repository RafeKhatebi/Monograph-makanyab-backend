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
