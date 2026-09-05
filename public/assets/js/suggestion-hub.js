(function () {
    var form = document.querySelector('[data-suggest-form]');
    if (!form) {
        return;
    }

    function sync() {
        var selected = form.querySelector('[data-suggest-type]:checked');
        var type = selected ? selected.value : 'place';

        form.querySelectorAll('[data-suggest-for]').forEach(function (node) {
            var allowed = node.getAttribute('data-suggest-for').split(/\s+/);
            var isHidden = allowed.indexOf(type) === -1;

            node.classList.toggle('is-hidden', isHidden);
            node.querySelectorAll('input:not([data-suggest-type]), select, textarea, button').forEach(function (field) {
                field.disabled = isHidden;
            });
        });
    }

    form.querySelectorAll('[data-suggest-type]').forEach(function (input) {
        input.addEventListener('change', sync);
    });

    sync();
})();
