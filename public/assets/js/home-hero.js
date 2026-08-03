(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const hero = document.querySelector('[data-home-hero]');
        const form = document.querySelector('[data-home-search]');

        if (form) {
            const categoryPicker = form.querySelector('[data-category-picker]');
            const placeInput = form.querySelector('[data-place-category-input]');
            const serviceInput = form.querySelector('[data-service-category-input]');
            const typeInput = form.querySelector('select[name="type"]');

            const syncCategory = function () {
                if (!categoryPicker || !placeInput || !serviceInput) {
                    return;
                }

                const value = categoryPicker.value || '';
                const parts = value.split(':');

                placeInput.value = '';
                serviceInput.value = '';

                if (parts.length !== 2) {
                    return;
                }

                if (parts[0] === 'place') {
                    placeInput.value = parts[1];
                    if (typeInput && typeInput.value === 'services') {
                        typeInput.value = 'places';
                    }
                }

                if (parts[0] === 'service') {
                    serviceInput.value = parts[1];
                    if (typeInput && typeInput.value === 'places') {
                        typeInput.value = 'services';
                    }
                }
            };

            if (categoryPicker) {
                categoryPicker.addEventListener('change', syncCategory);
            }

            form.addEventListener('submit', syncCategory);
        }

        if (!hero) {
            return;
        }

        const slides = Array.from(hero.querySelectorAll('[data-hero-slide]'));
        const dots = Array.from(hero.querySelectorAll('[data-hero-dot]'));
        const previous = hero.querySelector('[data-hero-prev]');
        const next = hero.querySelector('[data-hero-next]');
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let activeIndex = slides.findIndex(function (slide) {
            return slide.classList.contains('is-active');
        });
        let autoplayTimer = null;

        if (activeIndex < 0) {
            activeIndex = 0;
        }

        const showSlide = function (index) {
            if (!slides.length) {
                return;
            }

            activeIndex = (index + slides.length) % slides.length;

            slides.forEach(function (slide, slideIndex) {
                const active = slideIndex === activeIndex;
                slide.classList.toggle('is-active', active);
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
            });

            dots.forEach(function (dot, dotIndex) {
                const active = dotIndex === activeIndex;
                dot.classList.toggle('is-active', active);
                dot.setAttribute('aria-current', active ? 'true' : 'false');
            });
        };

        const stopAutoplay = function () {
            if (autoplayTimer) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = function () {
            if (reduceMotion || hero.dataset.autoplay !== 'true' || slides.length < 2) {
                return;
            }

            stopAutoplay();
            autoplayTimer = window.setInterval(function () {
                showSlide(activeIndex + 1);
            }, 6500);
        };

        if (previous) {
            previous.addEventListener('click', function () {
                showSlide(activeIndex - 1);
                startAutoplay();
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                showSlide(activeIndex + 1);
                startAutoplay();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                showSlide(Number(dot.dataset.heroDot || 0));
                startAutoplay();
            });
        });

        hero.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                showSlide(activeIndex - 1);
                startAutoplay();
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                showSlide(activeIndex + 1);
                startAutoplay();
            }
        });

        hero.addEventListener('mouseenter', stopAutoplay);
        hero.addEventListener('mouseleave', startAutoplay);
        hero.addEventListener('focusin', stopAutoplay);
        hero.addEventListener('focusout', startAutoplay);

        showSlide(activeIndex);
        startAutoplay();
    });
})();
