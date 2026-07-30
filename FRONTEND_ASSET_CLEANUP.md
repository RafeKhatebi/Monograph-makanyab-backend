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
- Moved repeated admin index, form, detail, suggestion review, table, empty-state, media-upload, map, and dashboard layout styles into `admin-layout.css`.
- Moved repeated frontend hover, responsive, search toggle, and gallery initialization code into `frontend-pages.css` and `frontend-pages.js`.
- Converted large inline style blocks in About, Contact, Favorites, auth card, review card, and profile header/sidebar panels to reusable classes.
- Rebuilt the search page markup around reusable search form, filter, chip, result card, badge, and empty-state classes.
- Replaced footer inline hover handlers with CSS classes.
- Admin templates now have no inline `style` attributes except the service-category color swatch, which is data-driven by `color_code` and intentionally retained.

## Shared Classes Created

- Layout: `mk-hero`, `mk-page-section`, `mk-stack-sm`, `mk-stack-md`, `mk-stack-lg`
- Components: `mk-card`, `mk-button`, `mk-alert`, `mk-avatar`, `mk-icon-item`, `mk-social__link`
- Forms: `mk-form-group`, `mk-label`, `mk-input`, `mk-textarea`, `mk-error`
- Admin: `admin-shell`, `admin-main`, `admin-filter-form`, `admin-form-grid`, `admin-detail-grid`, `admin-stats-grid`
- Admin layout: `admin-card-header`, `admin-card-title`, `admin-header-actions`, `admin-dashboard-stats`, `admin-dashboard-grid`, `admin-card-body-flush`
- Admin tables: `admin-table-wrap`, `admin-table-actions`, `admin-table-cell-heading`, `admin-table-muted`, `admin-actions`, `admin-action-form`, `admin-pagination`, `admin-empty`
- Admin forms/media: `admin-filter-field`, `admin-filter-select`, `admin-form-actions`, `admin-check-row`, `admin-help-text`, `admin-drop-zone`, `admin-image-preview-grid`, `admin-current-image-grid`, `admin-map`, `admin-readonly`
- Admin detail/review panels: `admin-detail-card`, `admin-detail-label`, `admin-detail-value`, `admin-suggestion-grid`, `admin-action-panel`, `admin-note-box`
- Admin buttons/badges: `btn-secondary`, `btn-success`, `btn-warning`, `btn-danger`, `btn-outline-warning`, `btn-lg`, `admin-btn-block`, `badge-info`
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
- Admin feature coverage from automated tests: places index/create/show/edit/store/update/delete/toggle/filter/upload, service upload/edit removal, suggestion queues, admin middleware.
- Admin route smoke checks while unauthenticated: `/admin`, `/admin/dashboard`, `/admin/places`, `/admin/places/create`, `/admin/users`, `/admin/users/create`, `/admin/reviews`, `/admin/services`, `/admin/services/create`, `/admin/categories`, `/admin/service-categories`, `/admin/place-suggestions`, and `/admin/service-suggestions` all returned `302` redirects to `/login`, as expected.

## Verification

- `npm run build`
- `php artisan view:clear && php artisan view:cache`
- `composer test`
- `php artisan route:list --except-vendor`
- Local HTTP smoke checks against `http://127.0.0.1:8000`
- Local linked asset scan for CSS, JavaScript, image, font, favicon, and Vite build URLs
- Stale-reference scans for deleted Dashmin, EasyPieChart, unminified Bootstrap, and missing image assets
- Admin inline style/script scan: one remaining inline style, the dynamic service-category color swatch; remaining admin `<script>` tags are external page assets or JSON data for Leaflet.
- Retained admin assets verified on disk: `variables.css`, `admin-utilities.css`, `admin-layout.css`, `admin-layout.js`, `places-search-local.js`, and `media-upload.js`.

## Remaining Risks

- Playwright is not installed in this project, so a real browser console/network-panel pass was not available without adding a new dependency.
- The admin service create/edit forms still use existing utility-style class names for much of their field layout; their inline media upload styles were moved, and behavior was verified by existing upload tests.
- One inline dynamic swatch style remains in `resources/views/admin/service-categories/show.blade.php` because it renders user-configured category color values.
- Some legacy frontend templates still contain inline styles where moving them would require a broader component rewrite. The most duplicated and unstable blocks were consolidated first.
- Legacy theme assets loaded by the public layouts were retained unless confirmed unused, to avoid breaking pages that still depend on Bootstrap 3/theme plugins.
