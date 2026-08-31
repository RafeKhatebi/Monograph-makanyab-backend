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
            node.classList.toggle('is-hidden', allowed.indexOf(type) === -1);
        });
    }

    form.querySelectorAll('[data-suggest-type]').forEach(function (input) {
        input.addEventListener('change', sync);
    });

    sync();
})();
