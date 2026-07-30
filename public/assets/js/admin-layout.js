(function () {
    function setExpanded(element, expanded) {
        if (element) {
            element.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        }
    }

    window.toggleSidebar = function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggler = document.querySelector('.sidebar-toggler');

        if (!sidebar || !overlay) {
            return;
        }

        const isOpen = sidebar.classList.toggle('show');
        overlay.classList.toggle('show', isOpen);
        setExpanded(toggler, isOpen);

        if (isOpen) {
            const firstLink = sidebar.querySelector('a, button');
            if (firstLink) {
                firstLink.focus();
            }
        }
    };

    window.toggleUserDropdown = function () {
        const dropdown = document.getElementById('userDropdown');
        const toggle = document.querySelector('.user-dropdown-toggle');

        if (!dropdown) {
            return;
        }

        const isOpen = dropdown.classList.toggle('show');
        setExpanded(toggle, isOpen);

        if (isOpen) {
            const firstItem = dropdown.querySelector('button, a');
            if (firstItem) {
                firstItem.focus();
            }
        }
    };

    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('userDropdown');
        const toggle = document.querySelector('.user-dropdown-toggle');

        if (!dropdown || !toggle) {
            return;
        }

        if (!toggle.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
            setExpanded(toggle, false);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        const dropdown = document.getElementById('userDropdown');
        const toggle = document.querySelector('.user-dropdown-toggle');

        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
            setExpanded(toggle, false);
            if (toggle) {
                toggle.focus();
            }
        }
    });
})();
