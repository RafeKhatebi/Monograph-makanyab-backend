(function () {
    var dom = {
        hamburger: document.getElementById('mk-hamburger'),
        mobilePanel: document.getElementById('mk-mobile'),
        dropdownItems: document.querySelectorAll('.mk-dd-item'),
        userMenu: document.getElementById('mk-user-menu'),
        userTrigger: document.getElementById('mk-user-trigger'),
        mobileGroups: [
            { buttonId: 'mob-discover-btn', panelId: 'mob-discover' }
        ]
    };

    function toggleMobilePanel() {
        if (!dom.mobilePanel) return;
        dom.mobilePanel.classList.toggle('open');
    }

    function closeAllDropdowns() {
        dom.dropdownItems.forEach(function (item) {
            item.classList.remove('open');
        });
    }

    function setupHamburger() {
        if (!dom.hamburger) return;
        dom.hamburger.addEventListener('click', toggleMobilePanel);
    }

    function setupDesktopDropdowns() {
        if (!dom.dropdownItems.length) return;

        dom.dropdownItems.forEach(function (item) {
            var trigger = item.querySelector('a');
            if (!trigger) return;

            trigger.addEventListener('click', function (event) {
                event.preventDefault();

                var isOpen = item.classList.contains('open');
                closeAllDropdowns();

                if (!isOpen) {
                    item.classList.add('open');
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
            dom.userMenu.classList.toggle('open');
        });

        document.addEventListener('click', function (event) {
            if (!dom.userMenu.contains(event.target)) {
                dom.userMenu.classList.remove('open');
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
            });
        });
    }

    function init() {
        setupHamburger();
        setupDesktopDropdowns();
        setupUserMenu();
        setupMobileAccordions();
    }

    init();
})();
