(function () {
    // ── Hamburger ──
    document.getElementById('mk-hamburger').addEventListener('click', function () {
        document.getElementById('mk-mobile').classList.toggle('open');
    });

    // ── Desktop dropdowns ──
    document.querySelectorAll('.mk-dd-item').forEach(function (item) {
        var trigger = item.querySelector('a');
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            var isOpen = item.classList.contains('open');
            // close all
            document.querySelectorAll('.mk-dd-item').forEach(function (i) {
                i.classList.remove('open');
            });
            if (!isOpen) item.classList.add('open');
        });
    });
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.mk-dd-item')) {
            document.querySelectorAll('.mk-dd-item').forEach(function (i) {
                i.classList.remove('open');
            });
        }
    });

    // ── User menu ──
    var userMenu = document.getElementById('mk-user-menu');
    if (userMenu) {
        document.getElementById('mk-user-trigger').addEventListener('click', function (e) {
            e.stopPropagation();
            userMenu.classList.toggle('open');
        });
        document.addEventListener('click', function (e) {
            if (!userMenu.contains(e.target)) userMenu.classList.remove('open');
        });
    }

    // ── Mobile accordions ──
    [
        ['mob-discover-btn', 'mob-discover'],
        ['mob-more-btn', 'mob-more']
    ].forEach(function (pair) {
        var btn = document.getElementById(pair[0]);
        var sub = document.getElementById(pair[1]);
        if (btn && sub) {
            btn.addEventListener('click', function () {
                var isOpen = sub.classList.contains('open');
                sub.classList.toggle('open', !isOpen);
                btn.classList.toggle('open', !isOpen);
            });
        }
    });
})();
