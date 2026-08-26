# Makanyab Project Context Report

## 1. Project Overview

Makanyab is a Laravel web application for discovering places and services in Afghanistan. The product goal is a hyper-local directory where users can search, filter, save, review, and suggest local listings.

Current technology stack:

- Backend: Laravel, Blade, Laravel Sanctum API, Laravel Breeze-style auth.
- Frontend: Laravel Blade templates, Bootstrap-style grid/classes, custom CSS in `public/assets/css`, Font Awesome/Pe icons, Leaflet map usage in forms.
- Database: relational schema with UUID primary keys for users, places, services, suggestions, and reviews.
- Auth: email/password, email verification, password reset, optional Google/Facebook social auth.
- Localization: configured languages are English, Farsi, and Pashto.

Important architecture folders:

- `routes/web.php`: public website, auth pages, user pages, admin panel.
- `routes/api.php`: public API, authenticated API, limited admin API.
- `database/migrations`: database schema.
- `app/Models`: Eloquent models and relationships.
- `app/Http/Controllers`: web, API, frontend, and admin controllers.
- `app/Http/Requests`: validation and authorization.
- `app/Policies`, `app/Http/Middleware`: access control.
- `app/Services`: media, slug, social auth, and suggestion approval logic.
- `resources/views`: frontend/admin Blade views and components.
- `lang/en`, `lang/fa`, `lang/ps`: translations.

## 2. Current User Roles

### `user`

- Default role created by email registration and social auth.
- Can log in, update profile, favorite places/services, review places/services, and submit suggestions.
- Cannot create places/services directly through API.
- Relevant files: `app/Models/User.php`, `app/Http/Controllers/Api/AuthController.php`, `app/Services/SocialAuthenticationService.php`.

### `owner`

- Can create places/services through API.
- Can update/delete own places/services according to policies and request authorization.
- Does not have access to the admin panel.
- Relevant files: `app/Policies/PlacePolicy.php`, `app/Policies/ServicePolicy.php`, `app/Http/Requests/StoreServiceRequest.php`, `app/Http/Controllers/PlaceController.php`.

### `admin`

- Has full admin panel access through `admin` middleware.
- Can manage users, places, services, categories, suggestions, reviews, posts, and contact messages.
- Can create/promote other admins from the admin user form.
- Relevant files: `app/Http/Middleware/AdminMiddleware.php`, `app/Providers/AppServiceProvider.php`, `app/Http/Controllers/Admin/UserController.php`.

Permission inconsistencies:

- `StorePlaceRequest::authorize()` says any authenticated user can create a place, but `PlaceController::store()` blocks non-admin/non-owner users.
- Admin role changes are very powerful; admins can create or promote other admins.
- Inactive users are checked in API login and social login, but web email/password login does not visibly check `is_active`.

## 3. Database Structure

### `users`

- Purpose: accounts, authentication, ownership, roles.
- Important fields: `id`, `name`, `lastname`, `username`, `phone`, `role`, `email`, `password`, `is_active`, `settings`, `profile_picture`, `email_verified_at`, `deleted_at`.
- Relationships: places, services, reviews, favorites, posts, suggestions, contact messages, social accounts.
- Translation fields: none.
- Roles are stored as a plain string.

### `places`

- Purpose: public place listings.
- Important fields: `user_id`, `place_category_id`, `name`, `slug`, `tagline`, `description`, phones, website, social links, address fields, latitude/longitude, `status`, `price_level`, `is_verified`, `is_active`.
- Status/enums: `open`, `closed`, `temporarily_closed`; price `low`, `medium`, `high`, `luxury`.
- Relationships: user owner, place category, reviews, favorites, opening hours, media.
- Media: polymorphic `media`; cover image uses `is_cover`.
- Translation fields: none; listing content is single-language.

### `services`

- Purpose: public service listings.
- Similar to `places`, but uses `service_category_id`.
- Relationships: user owner, service category, reviews, favorites, media.
- Translation fields: none.

### `place_categories`

- Purpose: place category tree.
- Important fields: `parent_id`, `name`, `slug`, `icon_name`, `color_code`, `has_menu`, `has_booking`, `has_delivery`, `keywords`, `schema_type`, `is_active`, `sort_order`.
- Relationships: parent/children and places.
- Translation fields: none.

### `service_categories`

- Purpose: service category tree.
- Similar to place categories, with `description`.
- Translation fields: none.

### `place_suggestions`

- Purpose: submitted place suggestions before approval.
- Important fields: listing fields, submitter fields, `user_id`, `suggestion_status`, `admin_note`, `approved_at`, `rejected_at`.
- Status workflow: `pending`, `approved`, `rejected`.
- Media support: no verified media relationship/table support for suggestions.
- Ownership: `user_id` is nullable, so guest submissions are allowed.

### `service_suggestions`

- Purpose: submitted service suggestions before approval.
- Similar to place suggestions.
- `service_category_id` is nullable in DB but required by validation.
- Media support: no verified media relationship/table support.

### `reviews`

- Purpose: ratings/comments for either a place or a service.
- Important fields: `user_id`, nullable `place_id`, nullable `service_id`, `rating`, `comment`, `is_approved`, `moderation_status`.
- Status workflow: `pending`, `approved`, `rejected`.
- Constraints: app/model and MySQL checks enforce exactly one target.
- Translation fields: none.

### `favorites`

- Purpose: saved places/services.
- Important fields: `user_id`, nullable `place_id`, nullable `service_id`.
- Constraints: exactly one target enforced in model and MySQL checks.

### `media`

- Purpose: polymorphic image/video attachments.
- Important fields: `mediable_type`, `mediable_id`, `file_path`, `disk`, `mime_type`, `file_size`, `type`, `is_cover`, `sort_order`.
- Used by places and services.

### `posts`

- Purpose: content/blog/news.
- Important fields: `user_id`, `title`, `slug`, `image`, `excerpt`, `content`, `is_published`, `published_at`.
- Translation fields: none.

### Contact/social tables

- `contact_messages`: stores contact form submissions with read/archive status.
- `social_accounts`: links users to Google/Facebook accounts.
- `personal_access_tokens`: Sanctum API tokens.

## 4. Current User Journey

### Guest

1. Opens `/` and sees hero search, featured places, featured services, categories, recent verified places, latest posts, and CTA sections.
2. Searches through `/search` with text, location, category, type, status, price, rating, open now, verified, and sorting filters.
3. Opens `/places/{place:slug}` or `/services/{service:slug}` if the listing is active.
4. Can view approved reviews and contact/location data.
5. Cannot favorite or review until logged in.
6. Can submit `/suggest-place` and `/suggest-service` as guest with name/email.

Missing for guest:

- Suggestion photo upload is not supported.
- Guest submission tracking is not available unless later matched manually.

### Logged-in User

1. Logs in by email/password or social auth.
2. Can search/discover listings.
3. Can favorite places/services.
4. Can submit one review per place/service; reviews are pending until admin approval.
5. Can suggest places/services.
6. Can view profile with favorites, favorite services, reviews, and account settings.

Missing for logged-in user:

- Profile does not show submitted place/service suggestions or their approval status.
- No visible user page for submitted places/services.
- No suggestion media upload.

### Admin

1. Logs in and is redirected to `/admin/dashboard`.
2. Can manage users, places, services, categories, service categories, suggestions, reviews, posts, and contact messages.
3. Can approve/reject suggestions.
4. Approval creates a real `Place` or `Service`.
5. Can approve/reject reviews.

Missing or risky for admin:

- No separate permission levels; all admins are powerful.
- Admins can create/promote other admins.
- Admin localization is incomplete.
- Suggestion approval does not transfer suggestion media because suggestion media is not supported.

## 5. Homepage

Current sections in `resources/views/pages/home.blade.php` are loaded by `app/Http/Controllers/Frontend/HomeController.php`.

- Featured places:
  - Data: verified and active places.
  - Order: newest by `created_at`.
  - Limit: 7.
  - Ranking: not true top/popular.

- Featured services:
  - Data: verified and active services with approved review count/average.
  - Order: newest by `created_at`.
  - Limit: 6.
  - Ranking: not true top/popular.

- Categories:
  - Data: active top-level place categories.
  - Order: `sort_order`.
  - Limit: 6.
  - View More: category pages exist.

- Service categories:
  - Data exists in controller with limit 6.
  - It may be displayed through the homepage category/discovery area depending on view markup.

- Recently verified/updated places:
  - Data: verified and active places.
  - Order: newest by `updated_at`.
  - Limit: 3.

- Latest posts:
  - Data: published posts.
  - Order: newest `published_at`, then newest record.
  - Limit: 3.

- Search provinces:
  - Data: distinct province values from active places/services.
  - Fallback: Kabul, Herat, Balkh, Nangarhar.

Notes:

- “Top”, “Popular”, and “Recommended” are not currently real ranking algorithms.
- Homepage still has more than the minimal discovery idea because it includes stats, CTAs, and posts.
- The intended next version should standardize around 6 items per discovery section and clear View More links.

## 6. Search and Filters

Main frontend search controller: `app/Http/Controllers/Frontend/SearchController.php`.

Fields and filters:

- `search`: name, tagline, city, province, district, address, description, category name/slug/keywords.
- `location`: city, province, district, address.
- `city`, `province`, `district`.
- `place_category`, `service_category`.
- `type`: `all`, `places`, `services`.
- `status`: `open`, `closed`, `temporarily_closed`.
- `price_level`: `low`, `medium`, `high`, `luxury`.
- `rating`: 1-5 minimum average.
- `open_now`.
- `verified`.
- `sort`: `relevance`, `newest`, `name_asc`, `name_desc`.

Pagination:

- Search page paginates places and services separately at 8 items.
- Places/services index pages paginate at 12 items.
- API defaults to 15 items, with some favorite/review endpoints capped to 50.

Rules:

- Search only requires `is_active`.
- Verified is optional by filter.
- Place `open_now` uses opening hours.
- Service `open_now` only checks service status as open.

Inconsistencies:

- Place and service “open now” logic are different.
- Search may show active but unverified listings.
- Filters are powerful but may be too much for the simple product goal.

## 7. Place and Service Detail Pages

### Place detail

- Route: `/places/{place:slug}` in `routes/web.php`.
- Controller: `app/Http/Controllers/Frontend/PlaceController.php`.
- Layout: older property template style with gallery, tabs, metadata, reviews, hours, contact.
- Media: shows listing media if available; otherwise demo image.
- Reviews: approved reviews only; logged-in users can submit one review.
- Favorites: authenticated users can toggle favorite.
- Related listings: same category, active, limit 4.
- Location: address fields and opening hours; map display on detail page is not clearly verified.

Hardcoded/localization issues:

- Day names are hardcoded in English.
- `Closed`, `Phone`, `Phone 2`, `WhatsApp`, `Website`, `Star/Stars`, and `ucfirst($price_level)` remain.
- UI uses old classes and inline styles.

### Service detail

- Route: `/services/{service:slug}`.
- Controller: `app/Http/Controllers/Frontend/ServiceController.php`.
- Layout: newer card/box style but heavily inline CSS.
- Media: cover image and thumbnails.
- Reviews: approved reviews only; logged-in users can submit one review.
- Favorites: authenticated users can save/remove.
- Related listings: same category, active, limit 4.

Hardcoded/localization issues:

- `WhatsApp`, `Website`, `star/stars`, `You have already reviewed this service...`, `Log in to review this service.`, and `ucfirst()` status/price are hardcoded.

Slug/duplicate display names:

- `Place::getRouteKeyName()` returns `slug`.
- Places have a unique `slug`.
- Duplicate display names are supported only if unique slugs are generated, such as `/places/duplicate-display-name-place-b`.
- Route model binding loads by slug, not display name.

## 8. Suggest Place / Suggest Service Flow

### Who can submit

- Guests and logged-in users can submit both place and service suggestions.
- Routes: `/suggest-place`, `/suggest-service`, and API routes `POST /api/suggestions/place`, `POST /api/suggestions/service`.

### Form fields

Visible/common fields include:

- Name, category, province, district, city, address, website, phone, WhatsApp, price level, tagline, description, map coordinates, latitude, longitude.
- Guest-only submitter name/email.
- Hidden country defaults to Afghanistan.

### Validation

- Validation lives in `StorePlaceSuggestionRequest`, `StoreServiceSuggestionRequest`, and `HandlesSuggestionValidation`.
- Required: name, category, phone, address, country, province, city, district, price level.
- Website must be URL.
- Description max 2000.
- Duplicate suggestions blocked by name/category/city among pending/approved suggestions.

### Photo/media support

- Not currently supported for suggestions.
- No suggestion image fields verified in forms/requests.
- Approval cannot transfer media because suggestions do not store media.

### Status workflow

- Default status: `pending`.
- Admin can approve or reject.
- Approval creates a `Place` or `Service`.
- Rejection sets `rejected_at` and `admin_note`.

### Ownership

- If logged in, `user_id`, name, and email are preserved.
- If guest, `user_id` remains null and submitter name/email are stored.
- Approved guest suggestion can produce a listing without a real owner.

### User visibility

- API has `GET /api/my-suggestions/places` and `GET /api/my-suggestions/services`.
- Web profile does not currently show submitted suggestions or statuses.

## 9. User Profile / Dashboard

Current web profile:

- Route: `/profile`.
- Controller: `app/Http/Controllers/Frontend/UserProfileController.php`.
- Shows saved places, saved services, reviews, profile settings, connected social accounts.
- Allows profile update, avatar upload, email change, and password change.
- Email change resets verification and sends verification notification.

Missing:

- Submitted place suggestions.
- Submitted service suggestions.
- Submission status page.
- User-owned places/services management page.
- Clear account inactive status UX.

Dashboard behavior:

- `/dashboard` redirects admins to admin dashboard.
- Non-admin users are redirected home.
- There is no separate user dashboard beyond `/profile`.

## 10. Admin Panel

Admin routes are under `/admin` and protected by `auth`, `verified`, and `admin` middleware.

Sections:

- Dashboard: stats and recent platform activity.
- Places: list, create, show, edit, delete, restore, toggle verification, toggle active, media upload/update.
- Services: list, create, show, edit, delete, restore, toggle verification, toggle active, media handling.
- Place categories: list, create, show, edit, delete.
- Service categories: list, create, show, edit, delete.
- Users: list, create, show, edit, delete, activate/deactivate, role changes.
- Place suggestions: list, show, approve, reject.
- Service suggestions: list, show, approve, reject.
- Reviews: list, show, delete, approve, reject.
- Contact messages: list, show, delete, read/unread, archive/restore.
- Posts: CRUD.

Permissions:

- Admin-only middleware controls all admin screens.
- There is no fine-grained admin permission model.

Risks:

- Admins can create/promote other admins.
- Admin pages contain many hardcoded English labels.
- Admin localization is incomplete.
- Business rules/settings management is limited to existing CRUD; no full settings module was verified.

## 11. API

API routes are in `routes/api.php`.

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`

Validation:

- Register uses `RegisterRequest`.
- Login uses `LoginRequest`, but API controller manually checks email/username and password.

Notes:

- Register creates `role=user`.
- API login checks `is_active`.
- API returns raw user model and Sanctum token.

### Users/Profile

- `PUT /api/profile`
- Authenticated by Sanctum.
- Supports avatar upload.
- Email change resets verification.

### Places

- Public:
  - `GET /api/places`
  - `GET /api/places/{place}`
- Authenticated:
  - `POST /api/places`
  - `PUT/PATCH /api/places/{place}`
  - `DELETE /api/places/{place}`

Notes:

- Create is effectively owner/admin only in controller.
- API place create/update does not clearly support media uploads.
- Request authorization and controller authorization are inconsistent.
- Responses are raw paginated models.

### Services

- Public:
  - `GET /api/services`
  - `GET /api/services/{service}`
- Authenticated:
  - `POST /api/services`
  - `PUT/PATCH /api/services/{service}`
  - `DELETE /api/services/{service}`

Issue:

- `StoreServiceRequest` accepts `images`, but `Api\ServiceController::store()` does not attach them.

### Suggestions

- Public:
  - `POST /api/suggestions/place`
  - `POST /api/suggestions/service`
- Authenticated:
  - `GET /api/my-suggestions/places`
  - `GET /api/my-suggestions/services`
- Admin queue:
  - `GET /api/admin/suggestions/places`
  - `GET /api/admin/suggestions/services`

Missing:

- API approve/reject endpoints were not verified.
- Suggestion media upload is missing.

### Favorites

- Places:
  - `GET /api/favorites`
  - `POST /api/favorites`
  - `GET /api/favorites/{place}/check`
  - `DELETE /api/favorites/{place}`
- Services:
  - `GET /api/service-favorites`
  - `POST /api/service-favorites/{service}`
  - `GET /api/service-favorites/{service}/check`
  - `DELETE /api/service-favorites/{service}`

### Reviews

- Places:
  - `GET /api/places/{place}/reviews`
  - `GET /api/places/{place}/reviews/{review}`
  - `POST /api/places/{place}/reviews`
  - `PUT/PATCH /api/places/{place}/reviews/{review}`
  - `DELETE /api/places/{place}/reviews/{review}`
- Services:
  - `GET /api/services/{service}/reviews`
  - `POST /api/services/{service}/reviews`
  - `PUT/PATCH /api/services/{service}/reviews/{review}`
  - `DELETE /api/services/{service}/reviews/{review}`

Notes:

- Reviews are one per user per target.
- New/edited reviews become pending.
- Public lists show approved reviews only.

### Admin

- Admin API is limited to category CRUD and suggestion queue list.
- Full admin operations are mainly web Blade routes, not API.

## 12. Authentication and Security

Implemented:

- Email/password registration.
- Email verification.
- Password reset.
- Strong password rule for registration.
- Sanctum API tokens.
- Social auth/linking for Google/Facebook when configured.
- CSRF for web forms.
- Route throttling for login/register/contact/suggestions/reviews.
- File validation for profile and admin/media uploads.
- Ownership checks for review update/delete and listing update/delete.
- Security headers middleware exists.

Issues:

- Web email/password login does not visibly block inactive users.
- Admins can promote/create admins.
- Some API responses return raw models instead of API resources.
- Some validation messages are hardcoded English.
- Suggestion approval can create listings with nullable owner.
- API accepts service image fields but ignores them.
- `StorePlaceRequest` authorization comment/rule conflicts with controller logic.

## 13. Localization

Configured languages:

- English: `en`
- Farsi: `fa`
- Pashto: `ps`

Language switching:

- `POST /locale` stores selected locale in session.
- Language switcher is a select box.
- Locale files exist under `lang/en`, `lang/fa`, and `lang/ps`.
- Default locale is currently configured as Farsi in `config/app.php` and `.env`.

Translation files include:

- `admin.php`, `auth.php`, `categories.php`, `common.php`, `contact.php`, `content.php`, `errors.php`, `favorites.php`, `footer.php`, `home.php`, `messages.php`, `navigation.php`, `places.php`, `profile.php`, `search.php`, `services.php`, `suggestions.php`, `validation.php`.

Hardcoded frontend examples:

- `resources/views/pages/places/show.blade.php`: English day names, `Closed`, `Phone`, `Website`, `Star/Stars`.
- `resources/views/pages/services/show.blade.php`: `WhatsApp`, `Website`, review login/already-reviewed messages, `star/stars`.
- `resources/views/pages/contact/index.blade.php`: `Herat, Afghanistan`, working hours, social aria labels.
- `resources/views/auth/partials/social-buttons.blade.php`: `with Google`, `with Facebook`, `or continue with email`.
- `resources/views/auth/forgot-password.blade.php`, `reset-password.blade.php`, `verify-email.blade.php`: hardcoded titles/links.

Hardcoded admin examples:

- `resources/views/admin/places/create.blade.php`
- `resources/views/admin/places/show.blade.php`
- `resources/views/admin/service-categories/edit.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/place-suggestions/show.blade.php`

Hardcoded backend validation/errors:

- `app/Http/Requests/StorePlaceRequest.php`
- `app/Http/Requests/UpdatePlaceRequest.php`
- `app/Http/Requests/StoreReviewRequest.php`
- `app/Http/Requests/UpdateServiceCategoryRequest.php`
- `app/Http/Requests/UpdateOpeningHourRequest.php`
- `app/Services/SuggestionService.php`

Database multilingual support:

- Categories, places, services, posts, suggestions, and reviews store content in one language only.
- No translation table or JSON translation fields were verified.

RTL:

- Farsi and Pashto are configured.
- Full RTL layout support was not verified.

## 14. Media and File Uploads

Media model/table:

- `app/Models/Media.php`
- `database/migrations/2026_04_04_101132_create_media_table.php`
- Polymorphic relationship: `mediable_type`, `mediable_id`.
- Storage disk: default `public`.

Place images:

- Places have `media()` and `coverImage()` relationships.
- Admin place create/edit supports image upload through admin requests/controllers.
- Frontend place detail shows media gallery.

Service images:

- Services have `media()` and `coverImage()`.
- Admin service create/edit supports images.
- Frontend service detail shows cover and thumbnails.

Suggestion images:

- Not currently supported.
- No verified suggestion media relation or upload handling.

Review media:

- Not supported.

API uploads:

- Profile picture upload works.
- Service request accepts images, but API service controller ignores image attachment.
- Place API media upload was not verified as supported.

Deletion/update behavior:

- `MediaUploadService` attaches, removes, and sets cover images.
- Force deleting places/services removes related media files after DB commit.

## 15. Frontend Architecture

Layouts:

- Public layout: `resources/views/layouts/app.blade.php`.
- Admin layout: `resources/views/layouts/admin.blade.php`.
- Auth layout: `resources/views/layouts/auth.blade.php`.

Shared components:

- Place card: `resources/views/components/place-card.blade.php`.
- Service card: `resources/views/components/service-card.blade.php`.
- Review card, status badge, rating stars, UI form components.

Cards:

- Place and service cards now use shared modern styling in `public/assets/css/frontend-components.css`.
- Home post cards use similar fixed-card styling in `public/assets/css/frontend-pages.css`.
- Long card text is shortened with `Str::limit()` and CSS line clamping.
- Some pages still use custom/old card-like markup and inline styles.

Forms:

- Suggestion form is shared in `resources/views/pages/shared/suggestion-form.blade.php`.
- Admin forms are mostly separate and contain hardcoded labels.

CSS strategy:

- Mix of custom CSS files and heavy inline styles.
- Inline CSS remains in profile, suggestion forms, place/service detail pages, and admin views.

Responsive behavior:

- Uses Bootstrap-style `row`, `col-md-*`, `col-sm-*`.
- Shared card grid exists but is not universally applied.

## 16. Current Inconsistencies

| Problem | Current behavior | Intended behavior | Area | Files/components involved |
|---|---|---|---|---|
| User cannot see submission status on web | API has my-suggestions, profile does not show them | User profile should show pending/approved/rejected | Frontend/API | `UserProfileController`, `pages/profile/index.blade.php`, `Api/SuggestionController` |
| Suggestion photos missing | Suggest forms store text only | Users should upload photos with suggestions | DB/Backend/Frontend | suggestion migrations, requests, forms |
| Service API ignores images | Request accepts `images`; controller does not attach | Accepted uploads should be stored as media | API/Media | `StoreServiceRequest`, `Api/ServiceController` |
| Place API media missing | Place API create/update does not handle images | API should match admin media support | API/Media | `PlaceController`, `StorePlaceRequest` |
| Inactive users in web login | API/social checks `is_active`; web login does not visibly check it | Inactive users should be blocked everywhere | Auth | `AuthenticatedSessionController`, `LoginRequest` |
| Admin can promote admins | Admin user form allows `admin` role | Role escalation should be restricted | Admin/Security | `Admin/UserController`, admin user views |
| Homepage “top/popular” not real | Uses newest/verified data | Define real ranking by rating/favorites/reviews | Backend/Product | `HomeController` |
| Homepage section limit mismatch | Featured places limit is 7 | Around 6 items per section | Frontend/Backend | `HomeController` |
| Data not multilingual | DB stores one `name/description/title` | Support three-language content | DB/Backend/Frontend | places, services, categories, posts |
| Hardcoded English remains | Many views/requests use English strings | All UI/errors should use lang files | Localization | views, requests, services |
| Place/service detail UI inconsistent | Place uses older property template, service uses newer box style | Shared modern listing detail layout | Frontend | `pages/places/show.blade.php`, `pages/services/show.blade.php` |
| Service open-now differs from place | Service checks status only | Consistent business rule | Backend/Search | `Service::scopeFilterOpenNow`, `Place::scopeFilterOpenNow` |
| Suggestion approval for guests has no owner | `user_id` can be null on approved listing | Assign owner/admin/system or claim flow | DB/Backend | suggestions, `SuggestionAdminService` |

## 17. Already Implemented vs Missing

### Already implemented

- Public homepage and discovery pages.
- Search across places and services.
- Place and service categories.
- Place/service detail pages.
- Email registration/login.
- Email verification and password reset.
- Google/Facebook social auth hooks.
- Favorites for places and services.
- Reviews and ratings for places and services.
- Review moderation.
- Suggest place/service text flow.
- Admin panel.
- Admin user CRUD and role changes.
- Admin place/service/category CRUD.
- Admin suggestion approval/rejection.
- Admin media upload for places/services.
- Posts/content section.
- Contact form and admin contact message management.
- Three language folders and language switcher.

### Missing / incomplete

- Web page for users to see submitted suggestions and status.
- Photo upload for suggestions.
- Media transfer from approved suggestions.
- Full multilingual database content.
- Complete localization of frontend, admin, and validation messages.
- Real top/popular/recommended ranking.
- Consistent API media handling.
- API approve/reject endpoints for suggestions.
- Fine-grained admin permissions.
- Safe restriction around admin promotion.
- Full app settings/business-rule management module.
- Consistent modern UI for detail pages and admin forms.

## 18. Recommended Next Version

### P0 — Critical

- Block inactive users in web login.
- Restrict admin-to-admin promotion or add a protected super-admin rule using existing roles/permissions.
- Add user submission-status page to web profile.
- Add suggestion image upload and media storage.
- Transfer suggestion media when approved.
- Fix API service image upload handling.
- Add or clarify API media support for places.
- Move hardcoded backend validation messages into localization files.

### P1 — Important

- Standardize homepage sections to 6 items and View More links.
- Implement real top/popular/recommended ranking.
- Complete localization for public detail pages, auth pages, admin pages, and form labels.
- Add multilingual content model for categories, places, services, and posts.
- Make place/service detail pages share one modern component system.
- Simplify filters to the discovery options users actually need.
- Add API approve/reject endpoints for suggestions if API admin workflow is needed.

### P2 — Improvements

- Replace inline CSS with shared CSS/components.
- Use Laravel API resources for stable API responses.
- Add Markdown editor or clean rich textarea for long descriptions.
- Add admin settings module for site name, contact info, footer/header content, and business rules.
- Add better map/geocoding support.
- Add dashboard status checks for social login/mail/map integrations.
- Clean old template classes and duplicated card/list markup.

## 19. Important Files

### Database/migrations

- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2026_04_01_122649_create_place_categories_table.php`
- `database/migrations/2026_04_01_122859_create_places_table.php`
- `database/migrations/2026_04_04_095957_create_reviews_table.php`
- `database/migrations/2026_04_04_100150_create_favorites_table.php`
- `database/migrations/2026_04_04_101132_create_media_table.php`
- `database/migrations/2026_04_19_151657_create_posts_table.php`
- `database/migrations/2026_04_20_000000_create_service_categories_table.php`
- `database/migrations/2026_04_20_000001_create_services_table.php`
- `database/migrations/2026_04_30_000000_create_place_suggestions_table.php`
- `database/migrations/2026_04_30_000001_create_service_suggestions_table.php`
- `database/migrations/2026_07_28_000001_add_service_targets_to_reviews_and_favorites.php`
- `database/migrations/2026_08_03_000002_add_moderation_status_and_target_checks.php`

### Models

- `app/Models/User.php`
- `app/Models/Place.php`
- `app/Models/Service.php`
- `app/Models/PlaceCategory.php`
- `app/Models/ServiceCategory.php`
- `app/Models/PlaceSuggestion.php`
- `app/Models/ServiceSuggestion.php`
- `app/Models/Review.php`
- `app/Models/Favorite.php`
- `app/Models/Media.php`
- `app/Models/Post.php`
- `app/Models/ContactMessage.php`
- `app/Models/SocialAccount.php`

### Routes

- `routes/web.php`
- `routes/api.php`
- `routes/auth.php`

### Controllers

- `app/Http/Controllers/Frontend/HomeController.php`
- `app/Http/Controllers/Frontend/SearchController.php`
- `app/Http/Controllers/Frontend/PlaceController.php`
- `app/Http/Controllers/Frontend/ServiceController.php`
- `app/Http/Controllers/Frontend/PlaceSuggestionController.php`
- `app/Http/Controllers/Frontend/ServiceSuggestionController.php`
- `app/Http/Controllers/Frontend/UserProfileController.php`
- `app/Http/Controllers/PlaceController.php`
- `app/Http/Controllers/Api/ServiceController.php`
- `app/Http/Controllers/Api/SuggestionController.php`
- `app/Http/Controllers/Api/AuthController.php`
- `app/Http/Controllers/Api/ProfileController.php`

### Services

- `app/Services/SuggestionService.php`
- `app/Services/SuggestionAdminService.php`
- `app/Services/MediaUploadService.php`
- `app/Services/SlugService.php`
- `app/Services/SocialAuthenticationService.php`

### Policies/middleware

- `app/Policies/PlacePolicy.php`
- `app/Policies/ServicePolicy.php`
- `app/Policies/PlaceCategoryPolicy.php`
- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Middleware/SetLocale.php`
- `app/Http/Middleware/SecurityHeaders.php`
- `app/Providers/AppServiceProvider.php`

### Frontend views/components

- `resources/views/pages/home.blade.php`
- `resources/views/pages/search/index.blade.php`
- `resources/views/pages/places/index.blade.php`
- `resources/views/pages/places/show.blade.php`
- `resources/views/pages/services/index.blade.php`
- `resources/views/pages/services/show.blade.php`
- `resources/views/pages/shared/suggestion-form.blade.php`
- `resources/views/pages/profile/index.blade.php`
- `resources/views/components/place-card.blade.php`
- `resources/views/components/service-card.blade.php`
- `resources/views/components/review-card.blade.php`
- `resources/views/components/status-badge.blade.php`
- `resources/views/partials/navbar.blade.php`
- `resources/views/partials/language-switcher.blade.php`

### Admin

- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/places/*`
- `resources/views/admin/services/*`
- `resources/views/admin/categories/*`
- `resources/views/admin/service-categories/*`
- `resources/views/admin/users/*`
- `resources/views/admin/place-suggestions/*`
- `resources/views/admin/service-suggestions/*`
- `resources/views/admin/reviews/*`
- `resources/views/admin/posts/*`
- `resources/views/admin/contact-messages/*`

### Localization

- `config/locales.php`
- `config/app.php`
- `lang/en/*`
- `lang/fa/*`
- `lang/ps/*`

### API

- `routes/api.php`
- `app/Http/Controllers/Api/*`
- `app/Http/Requests/Api/*`
- `app/Http/Requests/StorePlaceRequest.php`
- `app/Http/Requests/StoreServiceRequest.php`
- `app/Http/Requests/StoreReviewRequest.php`
- `app/Http/Requests/StoreServiceReviewRequest.php`
- `app/Http/Requests/StorePlaceSuggestionRequest.php`
- `app/Http/Requests/StoreServiceSuggestionRequest.php`

## 20. Final Context Summary

What the app is now:

Makanyab is a Laravel directory app with public discovery pages, searchable places/services, user auth, favorites, reviews, suggestions, an admin dashboard, media for approved listings, posts, contact messages, and partial three-language support.

What the intended product is:

A simple, focused discovery platform for Afghanistan where users find nearby places/services, save and review them, suggest new listings with photos, and track their submission status.

What already works:

Core listing discovery, categories, detail pages, auth, favorites, reviews with moderation, text-based suggestions, admin CRUD, admin approval/rejection, media for admin-managed listings, and basic localization infrastructure.

What is missing:

Suggestion photos, web submission-status tracking, complete localization, multilingual database content, real top/popular/recommended ranking, consistent media APIs, API approval actions, safer admin promotion rules, and modern consistent detail/admin UI.

What should be built next:

First fix security and data-flow issues, then complete the user submission workflow and media handling, then finish localization and multilingual content, then refine discovery ranking, homepage structure, shared cards, and UI consistency.
