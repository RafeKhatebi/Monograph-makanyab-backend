# Frontend Asset Cleanup Report

## Files Removed

- `public/assets/css/admin.css`
- `public/assets/js/easypiechart.min.js`
- `public/assets/js/jquery.easypiechart.min.js`
- `public/bootstrap/css/bootstrap.css`
- `public/bootstrap/css/bootstrap.css.map`
- `public/bootstrap/js/bootstrap.js`
- `public/bootstrap/js/npm.js`
- `public/dashmin/css/bootstrap.min.css`
- `public/dashmin/css/style.css`
- `public/dashmin/js/main.js`
- `public/dashmin/lib/chart/chart.min.js`
- `public/dashmin/lib/easing/easing.js`
- `public/dashmin/lib/easing/easing.min.js`
- `public/dashmin/lib/owlcarousel/LICENSE`
- `public/dashmin/lib/owlcarousel/assets/ajax-loader.gif`
- `public/dashmin/lib/owlcarousel/assets/owl.carousel.min.css`
- `public/dashmin/lib/owlcarousel/assets/owl.theme.default.css`
- `public/dashmin/lib/owlcarousel/assets/owl.theme.default.min.css`
- `public/dashmin/lib/owlcarousel/assets/owl.theme.green.css`
- `public/dashmin/lib/owlcarousel/assets/owl.theme.green.min.css`
- `public/dashmin/lib/owlcarousel/assets/owl.video.play.png`
- `public/dashmin/lib/owlcarousel/owl.carousel.js`
- `public/dashmin/lib/tempusdominus/css/tempusdominus-bootstrap-4.css`
- `public/dashmin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css`
- `public/dashmin/lib/tempusdominus/js/moment-timezone.min.js`
- `public/dashmin/lib/tempusdominus/js/moment.min.js`
- `public/dashmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.js`
- `public/dashmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js`
- `public/dashmin/lib/waypoints/links.php`
- `public/dashmin/lib/waypoints/waypoints.min.js`

Each removed file had no references from `resources`, `app`, `routes`, `config`, or retained public entry points.
The empty `public/dashmin` directory tree was removed after its remaining files were confirmed unused.

## Files Consolidated Or Added

- Added `public/assets/css/admin-layout.css` for admin shell, dropdown, flash, and responsive layout styles.
- Added `public/assets/js/admin-layout.js` for admin sidebar and account dropdown interactions.
- Added `public/assets/css/frontend-pages.css` for shared frontend page sections, hero blocks, cards, buttons, forms, alerts, avatars, hover states, and responsive utilities.
- Added `public/assets/js/frontend-pages.js` for shared search-filter toggling and place gallery initialization.
- Updated `resources/views/layouts/app.blade.php`, `resources/views/layouts/auth.blade.php`, and `resources/views/layouts/admin.blade.php` to load the shared assets.
- Removed unused EasyPieChart script includes from the public and auth layouts.

## Inline/Internal Code Moved

- Moved admin layout `<style>` and `<script>` code into `admin-layout.css` and `admin-layout.js`.
- Moved repeated frontend hover, responsive, search toggle, and gallery initialization code into `frontend-pages.css` and `frontend-pages.js`.
- Converted large inline style blocks in About, Contact, Favorites, auth card, review card, and profile header/sidebar panels to reusable classes.
- Rebuilt the search page markup around reusable search form, filter, chip, result card, badge, and empty-state classes.
- Replaced footer inline hover handlers with CSS classes.

## Shared Classes Created

- Layout: `mk-hero`, `mk-page-section`, `mk-stack-sm`, `mk-stack-md`, `mk-stack-lg`
- Components: `mk-card`, `mk-button`, `mk-alert`, `mk-avatar`, `mk-icon-item`, `mk-social__link`
- Forms: `mk-form-group`, `mk-label`, `mk-input`, `mk-textarea`, `mk-error`
- Admin: `admin-shell`, `admin-main`, `admin-filter-form`, `admin-form-grid`, `admin-detail-grid`, `admin-stats-grid`
- Page helpers: `profile-tab-link`, `profile-panel`, `search-card`, `cat-card`, `post-card`, `scat-card`
- Search: `search-bar`, `search-input`, `search-chip`, `search-filter-panel`, `search-section-title`, `search-card__media`, `search-card__badge`, `search-card__footer`

## Broken References Fixed

- Replaced missing `assets/img/client-face1.png` usage with reusable initial avatars.
- Replaced missing `assets/img/demo/small-property-1.jpg` with existing media/placeholder logic.
- Removed stale CSS references to missing `page-header.jpg`, `welcome-bg.png`, `bg-footer.jpg`, `AjaxLoader.gif`, `grabbing.png`, and `controls.png`.
- Fixed navbar script loading from a route-relative path to `asset('assets/js/navbar.js')`.

## Pages Tested

- `/`
- `/about`
- `/contact`
- `/search`
- `/places`
- `/categories`
- `/service-categories`
- `/posts`
- `/login`
- `/favorites` and `/profile` returned expected auth redirects.

## Verification

- `npm run build`
- `composer test`
- `php artisan route:list --except-vendor`
- Local HTTP smoke checks against `http://127.0.0.1:8000`
- Local linked asset scan for CSS, JavaScript, image, font, favicon, and Vite build URLs
- Stale-reference scans for deleted Dashmin, EasyPieChart, unminified Bootstrap, and missing image assets

## Remaining Risks

- Playwright is not installed in this project, so a real browser console/network-panel pass was not available without adding a new dependency.
- Some legacy frontend templates still contain inline styles where moving them would require a broader component rewrite. The most duplicated and unstable blocks were consolidated first.
- Legacy theme assets loaded by the public layouts were retained unless confirmed unused, to avoid breaking pages that still depend on Bootstrap 3/theme plugins.
