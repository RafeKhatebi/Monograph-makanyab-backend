(function () {
    var translations = document.documentElement.dataset;
    var dom = {
        hamburger: document.getElementById('mk-hamburger'),
        mobilePanel: document.getElementById('mk-mobile'),
        dropdownItems: document.querySelectorAll('.mk-dd-item'),
        userMenu: document.getElementById('mk-user-menu'),
        userTrigger: document.getElementById('mk-user-trigger'),
        languageMenus: document.querySelectorAll('[data-language-menu]'),
        mobileGroups: [
            { buttonId: 'mob-discover-btn', panelId: 'mob-discover' }
        ]
    };

    function toggleMobilePanel() {
        if (!dom.mobilePanel) return;
        var isOpen = dom.mobilePanel.classList.toggle('open');
        dom.mobilePanel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        if (dom.hamburger) {
            dom.hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            dom.hamburger.setAttribute('aria-label', isOpen ? translations.closeMenu : translations.openMenu);
        }
    }

    function closeAllDropdowns() {
        dom.dropdownItems.forEach(function (item) {
            item.classList.remove('open');
            var trigger = item.querySelector('.mk-nav-dropdown-trigger');
            var menu = item.querySelector('.mk-dd');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            if (menu) menu.setAttribute('aria-hidden', 'true');
        });
    }

    function closeUserMenu() {
        if (!dom.userMenu) return;
        dom.userMenu.classList.remove('open');
        if (dom.userTrigger) dom.userTrigger.setAttribute('aria-expanded', 'false');
        var menu = document.getElementById('mk-user-dropdown');
        if (menu) menu.setAttribute('aria-hidden', 'true');
    }

    function closeLanguageMenus(exceptMenu, returnFocus) {
        dom.languageMenus.forEach(function (menu) {
            if (exceptMenu && menu === exceptMenu) return;
            var wasOpen = menu.classList.contains('open');
            menu.classList.remove('open');
            var trigger = menu.querySelector('[data-language-trigger]');
            var popover = menu.querySelector('[data-language-popover]');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            if (popover) popover.setAttribute('aria-hidden', 'true');
            if (returnFocus && wasOpen && trigger) trigger.focus();
        });
    }

    function setupHamburger() {
        if (!dom.hamburger) return;
        dom.hamburger.addEventListener('click', toggleMobilePanel);
    }

    function setupDesktopDropdowns() {
        if (!dom.dropdownItems.length) return;

        dom.dropdownItems.forEach(function (item) {
            var trigger = item.querySelector('.mk-nav-dropdown-trigger');
            var menu = item.querySelector('.mk-dd');
            if (!trigger) return;

            trigger.addEventListener('click', function (event) {
                event.preventDefault();

                var isOpen = item.classList.contains('open');
                closeAllDropdowns();

                if (!isOpen) {
                    item.classList.add('open');
                    trigger.setAttribute('aria-expanded', 'true');
                    if (menu) menu.setAttribute('aria-hidden', 'false');
                }
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('.mk-dd-item')) {
                closeAllDropdowns();
            }
        });
    }

    function setupUserMenu() {
        if (!dom.userMenu || !dom.userTrigger) return;

        dom.userTrigger.addEventListener('click', function (event) {
            event.stopPropagation();
            var isOpen = dom.userMenu.classList.toggle('open');
            dom.userTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            var menu = document.getElementById('mk-user-dropdown');
            if (menu) menu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        });

        document.addEventListener('click', function (event) {
            if (!dom.userMenu.contains(event.target)) {
                closeUserMenu();
            }
        });
    }

    function setupLanguageMenus() {
        if (!dom.languageMenus.length) return;

        dom.languageMenus.forEach(function (menu) {
            var trigger = menu.querySelector('[data-language-trigger]');
            var popover = menu.querySelector('[data-language-popover]');
            if (!trigger || !popover) return;

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                var shouldOpen = !menu.classList.contains('open');
                closeAllDropdowns();
                closeUserMenu();
                closeLanguageMenus(menu);

                menu.classList.toggle('open', shouldOpen);
                trigger.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                popover.setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');

                if (shouldOpen) {
                    var activeOption = popover.querySelector('.mk-language-option.is-active');
                    var firstOption = popover.querySelector('.mk-language-option');
                    (activeOption || firstOption || trigger).focus();
                }
            });

            menu.addEventListener('click', function (event) {
                event.stopPropagation();
            });

            popover.addEventListener('keydown', function (event) {
                if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

                var options = Array.prototype.slice.call(popover.querySelectorAll('.mk-language-option'));
                if (!options.length) return;

                event.preventDefault();
                var currentIndex = options.indexOf(document.activeElement);
                var nextIndex = event.key === 'ArrowDown'
                    ? (currentIndex + 1) % options.length
                    : (currentIndex <= 0 ? options.length - 1 : currentIndex - 1);
                options[nextIndex].focus();
            });
        });

        document.addEventListener('click', function (event) {
            if (!event.target.closest('[data-language-menu]')) {
                closeLanguageMenus();
            }
        });
    }

    function setupMobileAccordions() {
        dom.mobileGroups.forEach(function (group) {
            var button = document.getElementById(group.buttonId);
            var panel = document.getElementById(group.panelId);
            if (!button || !panel) return;

            button.addEventListener('click', function () {
                var shouldOpen = !panel.classList.contains('open');
                panel.classList.toggle('open', shouldOpen);
                button.classList.toggle('open', shouldOpen);
                button.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                panel.setAttribute('aria-hidden', shouldOpen ? 'false' : 'true');
            });
        });
    }

    function setupKeyboard() {
        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') return;

            closeAllDropdowns();
            closeUserMenu();
            closeLanguageMenus(null, true);

            if (dom.mobilePanel && dom.mobilePanel.classList.contains('open')) {
                dom.mobilePanel.classList.remove('open');
                dom.mobilePanel.setAttribute('aria-hidden', 'true');
                if (dom.hamburger) {
                    dom.hamburger.setAttribute('aria-expanded', 'false');
                    dom.hamburger.setAttribute('aria-label', 'Open menu');
                    dom.hamburger.focus();
                }
            }
        });
    }

    function init() {
        setupHamburger();
        setupDesktopDropdowns();
        setupUserMenu();
        setupLanguageMenus();
        setupMobileAccordions();
        setupKeyboard();
    }

    init();
})();
