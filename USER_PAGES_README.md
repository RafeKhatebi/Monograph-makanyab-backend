# User App Pages Reference (No Admin)

This document lists only the pages and endpoints related to **public users**.
It excludes all `admin/*` and dashboard management features.

## Scope for Flutter Phase 1
- Build user/public app flows first.
- Skip admin dashboard and moderation screens.
- Skip any endpoint inside admin-only middleware.

## Website Pages (User/Public)

### Public pages (no login required)
1. Home: `GET /`
2. About: `GET /about`
3. Contact form page: `GET /contact`
4. Search page: `GET /search`
5. Suggest place page: `GET /suggest-place`
6. Suggest service page: `GET /suggest-service`
7. Posts list: `GET /posts`
8. Post details: `GET /posts/{post:slug}`
9. Places list: `GET /places`
10. Place details: `GET /places/{place:slug}`
11. Services list: `GET /services`
12. Service details: `GET /services/{service:slug}`
13. Service categories list: `GET /service-categories`
14. Service category details: `GET /service-categories/{slug}`
15. Place categories list: `GET /categories`
16. Place category details: `GET /categories/{slug}`

### Auth pages
1. Register: `GET /register`, submit `POST /register`
2. Login: `GET /login`, submit `POST /login`
3. Forgot password: `GET /forgot-password`, submit `POST /forgot-password`
4. Reset password: `GET /reset-password/{token}`, submit `POST /reset-password`
5. Email verification notice: `GET /verify-email`
6. Verify email link: `GET /verify-email/{id}/{hash}`
7. Resend verification email: `POST /email/verification-notification`
8. Confirm password: `GET /confirm-password`, submit `POST /confirm-password`
9. Update password: `PUT /password`
10. Logout: `POST /logout`

### Logged-in user pages
1. User profile page: `GET /profile`
2. Update profile: `PATCH /profile`
3. Favorites page: `GET /favorites`
4. Toggle favorite: `POST /favorites/toggle`
5. Breeze account page: `GET /account`
6. Delete account: `DELETE /account`
7. Add review on place: `POST /places/{place:slug}/reviews`

## API Endpoints for Flutter (User Side)
Base: `/api`

### Auth
1. Register: `POST /api/auth/register`
2. Login: `POST /api/auth/login`
3. Current user: `GET /api/auth/me` (auth required)
4. Logout: `POST /api/auth/logout` (auth required)

### Public content
1. Place categories list/details:
- `GET /api/place-categories`
- `GET /api/place-categories/{placeCategory}`
2. Service categories list/details:
- `GET /api/service-categories`
- `GET /api/service-categories/{serviceCategory}`
3. Places list/details:
- `GET /api/places`
- `GET /api/places/{place}`
4. Services list/details:
- `GET /api/services`
- `GET /api/services/{service}`
5. Place reviews:
- `GET /api/places/{place}/reviews`
- `GET /api/places/{place}/reviews/{review}`
6. Place opening hours:
- `GET /api/places/{place}/opening-hours`
7. Posts list/details:
- `GET /api/posts`
- `GET /api/posts/{post:slug}`
8. Contact submit:
- `POST /api/contact-messages`
9. Suggestions submit:
- `POST /api/suggestions/place`
- `POST /api/suggestions/service`

### Authenticated user features (non-admin)
1. Update profile:
- `PUT /api/profile`
2. User place actions (if you allow user-created places in app):
- `POST /api/places`
- `PUT /api/places/{place}`
- `PATCH /api/places/{place}`
- `DELETE /api/places/{place}`
3. User review actions:
- `POST /api/places/{place}/reviews`
- `DELETE /api/places/{place}/reviews/{review}`
4. User opening-hours actions:
- `POST /api/places/{place}/opening-hours`
- `PUT /api/places/{place}/opening-hours/{openingHour}`
- `DELETE /api/places/{place}/opening-hours/{openingHour}`
5. Favorites:
- `GET /api/favorites`
- `POST /api/favorites`
- `GET /api/favorites/{place}/check`
- `DELETE /api/favorites/{place}`
6. My suggestions:
- `GET /api/my-suggestions/places`
- `GET /api/my-suggestions/services`

## Explicitly Out of Scope for Phase 1
Do not implement these now:
1. Any route starting with `/admin`
2. Admin moderation/approval queues
3. Admin category CRUD
4. Admin users, posts, services, places management dashboard

## Suggested Flutter Navigation (User First)
1. Splash/Onboarding
2. Home
3. Search
4. Places list -> Place details
5. Services list -> Service details
6. Categories (place/service) -> filtered lists
7. Posts list -> Post details
8. Favorites (requires login)
9. Profile (requires login)
10. Contact + Suggestion forms
11. Auth flow (login/register/forgot password)

## Notes
1. Web routes use slugs in many detail pages (example: `/places/{slug}`), while API uses model IDs/bindings depending on controller setup.
2. For Flutter, prefer API endpoints over web page routes.
3. Keep Sanctum token handling ready for authenticated endpoints.
