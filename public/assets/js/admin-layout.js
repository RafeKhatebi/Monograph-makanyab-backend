(function () {
    function setExpanded(element, expanded) {
        if (element) {
            element.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        }
    }

    function setupLanguageMenus() {
        var menus = document.querySelectorAll('[data-language-menu]');
        if (!menus.length) return;

        var closeAll = function (exceptMenu, returnFocus) {
            menus.forEach(function (menu) {
                if (exceptMenu && menu === exceptMenu) return;
                var wasOpen = menu.classList.contains('open');
                menu.classList.remove('open');
                var trigger = menu.querySelector('[data-language-trigger]');
                var popover = menu.querySelector('[data-language-popover]');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
                if (popover) popover.setAttribute('aria-hidden', 'true');
                if (returnFocus && wasOpen && trigger) trigger.focus();
            });
        };

        menus.forEach(function (menu) {
            var trigger = menu.querySelector('[data-language-trigger]');
            var popover = menu.querySelector('[data-language-popover]');
            if (!trigger || !popover) return;

            trigger.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                var shouldOpen = !menu.classList.contains('open');
                closeAll(menu);

                menu.classList.toggle('open', shouldOpen);
                setExpanded(trigger, shouldOpen);
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
                closeAll();
            }
        });
    }

    setupLanguageMenus();

    function setupDeleteDialog() {
        const dialog = document.getElementById('admin-delete-dialog');
        if (!dialog) return;

        const message = document.getElementById('admin-delete-message');
        const cancelButton = dialog.querySelector('[data-confirm-cancel]');
        const acceptButton = dialog.querySelector('[data-confirm-accept]');
        let pendingForm = null;
        let pendingSubmitter = null;
        let confirmedForm = null;

        function submitConfirmed(form, submitter) {
            confirmedForm = form;
            if (submitter && submitter.form === form) {
                form.requestSubmit(submitter);
            } else {
                form.requestSubmit();
            }
        }

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!form.matches('[data-confirm-delete]')) return;
            if (form === confirmedForm) {
                confirmedForm = null;
                return;
            }

            event.preventDefault();
            pendingForm = form;
            pendingSubmitter = event.submitter;

            if (typeof dialog.showModal !== 'function') {
                if (window.confirm(dialog.dataset.messageTemplate.replace(':item', form.dataset.confirmDelete))) {
                    submitConfirmed(form, pendingSubmitter);
                }
                return;
            }

            message.textContent = dialog.dataset.messageTemplate.replace(':item', form.dataset.confirmDelete);
            dialog.showModal();
            cancelButton.focus();
        });

        cancelButton.addEventListener('click', function () {
            dialog.close();
        });

        acceptButton.addEventListener('click', function () {
            const form = pendingForm;
            const submitter = pendingSubmitter;
            dialog.close();
            if (!form) return;
            submitConfirmed(form, submitter);
        });

        dialog.addEventListener('close', function () {
            pendingForm = null;
            pendingSubmitter = null;
        });
    }

    setupDeleteDialog();

    document.addEventListener('click', function (event) {
        const toggle = event.target.closest('[data-admin-password-toggle]');
        if (!toggle) {
            return;
        }

        const input = document.getElementById(toggle.getAttribute('aria-controls'));
        if (!input) {
            return;
        }

        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(visible));
        toggle.setAttribute('aria-label', visible ? toggle.dataset.hideLabel : toggle.dataset.showLabel);
    });

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

        document.querySelectorAll('[data-language-menu]').forEach(function (menu) {
            if (menu.classList.contains('open')) {
                menu.classList.remove('open');
                var trigger = menu.querySelector('[data-language-trigger]');
                if (trigger) {
                    setExpanded(trigger, false);
                    trigger.focus();
                }
            }
        });
    });
})();
