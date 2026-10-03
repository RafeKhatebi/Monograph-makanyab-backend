(function () {
    var root = document.documentElement;

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-password-toggle]');
        if (!toggle) return;

        var input = document.getElementById(toggle.getAttribute('aria-controls'));
        if (!input) return;

        var shouldShow = input.type === 'password';
        input.type = shouldShow ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
        toggle.setAttribute('aria-label', shouldShow ? root.dataset.hidePassword : root.dataset.showPassword);
    });

    document.querySelectorAll('[data-auth-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var button = form.querySelector('[data-loading-text]');
            if (!button || button.disabled) return;

            button.textContent = button.dataset.loadingText;
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
        });
    });
})();
