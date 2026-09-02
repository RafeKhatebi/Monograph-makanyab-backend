(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const wraps = document.querySelectorAll('[data-load-more-wrap]');

        if (!wraps.length) {
            return;
        }

        const getCsrfToken = function () {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        };

        wraps.forEach(function (wrap) {
            const trigger = wrap.querySelector('[data-load-more-trigger]');
            const endpoint = wrap.getAttribute('data-endpoint');
            const targetKey = wrap.getAttribute('data-target');

            if (!trigger || !endpoint) {
                return;
            }

            const targetSelector = '[data-load-more-target="' + targetKey + '"]';
            const target = document.querySelector(targetSelector);

            if (!target) {
                return;
            }

            const setLoading = function (loading) {
                if (loading) {
                    trigger.dataset.originalText = trigger.textContent;
                    trigger.textContent = 'Loading...';
                    trigger.disabled = true;
                } else {
                    if (trigger.dataset.originalText) {
                        trigger.textContent = trigger.dataset.originalText;
                    }
                    trigger.disabled = false;
                }
            };

            trigger.addEventListener('click', function (event) {
                event.preventDefault();

                const nextPage = wrap.getAttribute('data-next-page');
                if (!nextPage) {
                    return;
                }

                setLoading(true);

                const url = endpoint + (endpoint.indexOf('?') === -1 ? '?' : '&') +
                    'page=' + encodeURIComponent(nextPage) + '&' + window.location.search.replace(/^\?/, '');

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    credentials: 'same-origin',
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(function (data) {
                        if (data && typeof data.html === 'string') {
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = data.html.trim();

                            while (wrapper.firstChild) {
                                target.appendChild(wrapper.firstChild);
                            }
                        }

                        if (data && data.hasMore) {
                            wrap.setAttribute('data-next-page', data.nextPage);
                            setLoading(false);
                        } else {
                            wrap.remove();
                        }
                    })
                    .catch(function () {
                        setLoading(false);
                    });
            });
        });
    });
})();
