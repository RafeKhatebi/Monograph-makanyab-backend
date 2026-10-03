# Implementation Plan

Status: Phase 1 complete on 2026-10-03; awaiting owner verification before Phase 2. Public shared layout only; no reference code or assets copied.

## 1. Repository Analysis

### Scope and architecture

- [x] Architecture reviewed. Local reference: `afghan-cultural-platform-main` is a pnpm monorepo, with Next.js 16/React 19/TypeScript/Tailwind/shadcn in `apps/web`, NestJS 11 REST API in `apps/api`, Prisma 7 and PostgreSQL. Business rules live in API services. Target: this repository is Laravel 12/PHP 8.2+, Blade, Eloquent, Vite, Tailwind 4 plus existing Bootstrap/jQuery assets and SQLite/MySQL compatible migrations. Reuse concepts and visual patterns through Laravel rather than importing the reference applications wholesale.
- [x] Folder structure reviewed. Reference: `apps/web/src/app`, `features`, `components`, `lib`; `apps/api/src/modules`, `common`, `prisma`; `docs`. Target: `app`, `routes`, `database`, `resources/views`, `public/assets`, `tests`.
- [x] Models and relationships reviewed. Reference Prisma models: `User`, `OAuthAccount`, `CulturalEntry`, `ContentVersion`, `EntryRevision`, `EntryReference`, `ModerationReview`, `Province`, `District`, `Category`, `ContentType`, `Tag`, `EntryTag`, `Image`, `YouTubeVideo`, `Source`, `Like`, `EntryComment`, `CommentLike`, `Bookmark`, `CorrectionSuggestion`, `Report`, `RefreshSession`, `PasswordResetToken`, `EmailVerificationToken`, `AuditLog`. `CulturalEntry` belongs to author, geographic and taxonomy entities and has tags, images, sources, versions, moderation, engagement, and reports. Target Eloquent models are listed below.
- [x] Database reviewed. Reference schema is `apps/api/prisma/schema.prisma` with SQL migrations, UUID keys, enum workflow states, constraints/indexes, and taxonomy/demo seed data. Target schema lives in `database/migrations`, with 15 domain models, seeders and factories. Do not copy Prisma migrations into Laravel.
- [x] Routes and controllers reviewed. Reference uses versioned REST API (`apps/api/src/modules/*/*.controller.ts`) and Next App Router groups; target has `routes/web.php`, `routes/api.php`, `routes/auth.php`, grouped web/admin routes and separate frontend/admin/API controllers.
- [x] Authentication reviewed. Reference: password, email verification, OAuth, JWT access token plus HTTP-only refresh cookie, reset. Target: Laravel Breeze/Sanctum, verified routes, OTP email verification, password reset and Socialite accounts. Keep target session/API authentication.
- [x] Authorization reviewed. Reference roles `USER`, `MODERATOR`, `ADMIN` with guards/decorators and workflow checks. Target has user/admin/owner role helpers, `admin` middleware, policies for Place, Service, PlaceCategory, form request authorization. Moderator is not present in target.
- [x] UI components reviewed. Reference has shadcn buttons, cards, forms, dialog, table, pagination, alert, skeleton, sidebar, mobile shell, icons. Target already has Blade equivalents under `resources/views/components/ui` and domain cards under `resources/views/components`.
- [x] Layouts and forms reviewed. Reference public header, home footer, auth, profile, moderator, admin layouts and React Hook Form/Zod forms. Target has Blade public/auth/admin layouts, navbar/footer, reusable form fields, Laravel FormRequests, flash messages.
- [x] Email configuration reviewed. Reference uses Nodemailer SMTP and templates in `apps/api/src/common/mail`. Target uses Laravel `config/mail.php`, `.env.example` defaults to `MAIL_MAILER=log`, verification notification, password reset and OTP. No production credential should be copied.
- [x] Markdown/content handling reviewed. Reference user editor is Tiptap and stores `contentJson` (`apps/web/src/components/common/rich-text-editor.tsx`); seed normalization parses Markdown into Tiptap JSON (`apps/api/src/modules/entries/utils/normalized-content.util.ts`). Target Post `content` is plain text, rendered escaped with line breaks; no submission Markdown editor/renderer exists. Markdown source storage needs a target-specific implementation.
- [x] Uploads reviewed. Reference uses Cloudinary image service, metadata, source/alt text and permission flags. Target has `MediaUploadService` using Laravel public disk for Place/Service/Suggestion media and Post image upload through storage. Extend target upload conventions; do not require Cloudinary.
- [x] Assets and design reviewed. Reference uses Estedad font, teal `#0F766E`, cream `#FAF8F3`, terracotta `#C65D3A`, gold `#D6A84B`, RTL, rounded cards and restrained motion. Source imagery/logo under `apps/web/public/images`, screenshots under `docs/screenshots`. Target already has Makanyab logos and imagery in `public/assets/img`, plus `mirasaf-inspired.css`; existing branding should be retained unless explicitly replaced.
- [x] JavaScript reviewed. Reference uses small client components, TanStack Query, URL filters, Motion, responsive mobile shell, loading/empty/error states. Target uses local JS modules for navbar, hero, search, load more, suggestions/maps/uploads and admin layout. Port behavior where appropriate, not React runtime.
- [x] Utilities, validation and API patterns reviewed. Reference DTOs/class-validator, feature API modules, error mapping, cached public content and SEO helpers. Target uses FormRequests, Laravel validation, Eloquent scopes, API routes/resources, slug and sanitizing services. Use target patterns.

### Existing target models

`User` has places, services, posts, suggestions, reviews, favorites, social accounts and OTPs. `Place` belongs to user/category and has reviews, favorites, hours and polymorphic media. `Service` has the parallel category/media/review/favorite relationships. `Post` belongs to user and has favorites, title/slug/excerpt/content/image, draft/submission/publishing fields. `PlaceCategory` and `ServiceCategory` are hierarchical. `PlaceSuggestion` and `ServiceSuggestion` belong to user/category and have media. `Review` targets a place or service. `Favorite` targets a place, service or post. `Media` is polymorphic. `OpeningHour` belongs to Place. `ContactMessage`, `SocialAccount`, and `EmailVerificationOtp` complete the current set. See `app/Models` and `database/migrations` before extending any model.

### Existing target sections and backend features

Public pages: home, places, services, categories, posts, search, about, contact, legal, login/register, profile, favorites, and submission hub. Admin pages: dashboard, users, places, services, categories, suggestions, posts, and contact messages. Backend includes search/filter/pagination, reviews, favorites, public APIs, draft and review submission status, media upload, authentication, email verification, and admin moderation for current submissions. A separate moderator workspace, cultural-entry version history, corrections, reports, comments, province taxonomy, and audit log are reference-only features.

## 2. Reusable Existing Features

### Layout

- Header: reference `apps/web/src/components/layout/public-header.tsx`; target `resources/views/partials/navbar.blade.php`.
- Footer: reference `apps/web/src/features/home/components/home-footer.tsx`; target `resources/views/partials/footer.blade.php`.
- Main layout: reference `apps/web/src/app/(public)/layout.tsx` and `apps/web/src/app/layout.tsx`; target `resources/views/layouts/app.blade.php`.
- Dashboard layout: reference `apps/web/src/features/admin/components/admin-navigation.tsx` and `(admin)/admin/layout.tsx`; target `resources/views/layouts/admin.blade.php`.
- Mobile layout: reference `apps/web/src/components/layout/mobile/*`; target navbar and responsive CSS/JS.

### Components

- Buttons/cards/forms/modals/tables/alerts/pagination/search: reference `apps/web/src/components/ui/*`, `features/home/components/home-content.tsx`, `features/admin/components/*`; target `resources/views/components/ui/*`, listing cards, form fields, flash message, listing/search views. Adapt CSS and states to Blade.
- Empty/loading/error/confirmation: reference `components/ui/skeleton.tsx`, `features/home/components/home-content.tsx`, route `loading.tsx`/`error.tsx`, dialogs; target `components/ui/empty-state.blade.php`, `errors/*`, `components/ui/confirm-form.blade.php`; fill gaps per page.
- Auth/profile/about: reference `features/auth/components/*`, `features/profile/components/*`, `app/(public)/about/page.tsx`; target `resources/views/auth/*`, `pages/profile`, `pages/about`.
- Developer/team: reference About credits a developer but has no dedicated team page; target About page exists. Dedicated team section needs content and approval.

### Backend

- Models/controllers/services: use target `app/Models/*`, `app/Http/Controllers/{Frontend,Admin,Api}`, `app/Services/*`. Reference entry/moderation/taxonomy services are behavioral examples only.
- Middleware/policies/validation: target `app/Http/Middleware`, `app/Policies`, `app/Http/Requests`; reference guards, role decorators and DTOs inform design.
- Upload handling: target `app/Services/MediaUploadService.php`, Post storage paths; reference `apps/api/src/modules/media/cloudinary-media.service.ts` for validation and metadata concepts.
- Email/notifications: target `config/mail.php`, `app/Notifications/SendEmailVerificationOtpNotification.php`, Laravel reset flow; reference mail templates for visual/copy inspiration.
- Seeders/factories: target `database/seeders` and `database/factories`; reference `apps/api/prisma/seed-data` only for permitted relevant taxonomy/demo concepts.

### Assets and styles

- Images: target Makanyab assets under `public/assets/img` first. Reference local cultural/province imagery in `apps/web/public/images` only where relevant and reuse rights are confirmed.
- Icons: target Font Awesome/Pe icon set; reference Heroicons/Lucide choices can guide equivalent icons.
- Fonts: reference Estedad via `@fontsource-variable/estedad`; target currently uses Inter/Noto Sans Arabic/Noto Naskh Arabic. Font switch is a deliberate visual decision, not a drop-in copy.
- Styles: reference `apps/web/src/app/globals.css`, `docs/design-system.md`; target `public/assets/css/{design-system,mirasaf-inspired,rtl,frontend-components,ui-system}.css` and other existing layers.

## 3. New Application Requirements (proposed scope for verification)

- [ ] Confirm whether the goal is a Makanyab visual refresh of existing pages, a new cultural-content area, or both. Do not assume all reference domain features belong in Makanyab.
- [ ] Confirm pages to include: existing home, listings/details, search, posts, submission, auth, profile, favorites, admin; optional cultural categories/provinces, moderator workspace, team page.
- [ ] Confirm whether current Makanyab logo/content stays. Reference logo and cultural photos have separate rights from source code.
- [ ] For existing post submission, retain `Post`, user and admin review flow; add category/type, tags and supporting image relationships only if needed by approved scope. Define migration, model relationships, form, draft/submit/preview/publish status, admin review and rendered page together.
- [ ] Store Markdown source for content submissions, provide toolbar/preview for headings, emphasis, lists, links, quotes, code, tables and images as approved; render sanitized HTML server side. Existing Post content must remain readable, with a migration/backward-compatibility choice documented before changing rendering.
- [ ] Use consistent validation, labels, required indicators, error/success/loading/disabled/focus states across affected forms.
- [ ] Reuse Laravel notification/mail configuration; keep local log mailer unless local SMTP/Mailpit is requested. Verify verification/reset messages after visual changes.
- [ ] Preserve existing Place/Service searches, filters, pagination, reviews, favorites, drafts, and admin workflows while changing presentation.
- [ ] Define whether contributor revisions, corrections, comments, reports, moderator role, audit history, district/province taxonomy, and YouTube/source metadata are in scope; each is a distinct backend feature with data and permission design.

## 4. Reuse Mapping

| New requirement | Existing reference | Reuse strategy |
|---|---|---|
| Public header/mobile nav | `apps/web/src/components/layout/public-header.tsx`, `components/layout/mobile/*` | Adapt visual behavior into existing Blade navbar and JS |
| Home hero/sections/cards | `apps/web/src/features/home/components/home-hero.tsx`, `home-content.tsx` | Adapt page composition and states to Makanyab data and branding |
| Footer | `apps/web/src/features/home/components/home-footer.tsx` | Adapt structure into existing footer |
| Listing/search/filter | `apps/web/src/app/(public)/explore/page.tsx`, `features/entries/components/*` | Adapt UI to existing Place/Service/Post queries and URL parameters |
| Detail reading page | `apps/web/src/features/entries/components/entry-detail-content.tsx` | Adapt typography/metadata to existing detail pages; Markdown only when approved |
| Auth and profile | `apps/web/src/features/auth/components/*`, `features/profile/components/*` | Adapt presentation; keep Laravel auth and models |
| Contributor form | `apps/web/src/features/entries/components/create-entry-writing-surface.tsx`, `create-entry-overlays.tsx` | Rebuild Markdown form/preview for Laravel Post; do not copy Tiptap JSON model |
| Admin dashboard/tables/dialogs | `apps/web/src/features/admin/components/*` | Adapt visual design to existing admin routes and permissions |
| Design tokens | `apps/web/src/app/globals.css`, `docs/design-system.md` | Reconcile with target CSS tokens and RTL |
| Images/icons/font | `apps/web/public/images`, `components/icons`, `@fontsource-variable/estedad` | Use selected assets only after rights and relevance review |
| Entry workflows | `apps/api/src/modules/entries`, `moderation` | Map concepts onto Post/suggestion flows before adding models |
| Email | `apps/api/src/common/mail`, target `config/mail.php` | Keep Laravel mailer; adapt copy/template if requested |
| Uploads | `apps/api/src/modules/media`, target `app/Services/MediaUploadService.php` | Extend target storage/validation, with metadata if needed |

## 5. Implementation Steps (each phase requires verification before the next)

### Phase 1 — Shared presentation foundation
- [x] Scope confirmed for Phase 1: refresh Makanyab shared layout, retain Makanyab branding, adapt reference design patterns without copying reference code or assets.
- [x] Reconcile public header/footer/mobile nav and shared public layout styles with the reference's teal/cream visual rhythm. Keep Makanyab logos, existing fonts, routes, data and Blade components. Add skip link and active page semantics. Remove social icons that misleadingly linked to Contact.
- [x] Verify RTL, keyboard/mobile menu, and responsive layout at 390px and 1440px; build and compile Blade views; run focused public navigation/footer tests. Full application test suite and auth page visual check remain for later relevant phases.

### Phase 2 — Public discovery and reading
- [ ] Adapt home, place/service/post cards, search/filter/pagination, categories and detail layouts with real target data.
- [ ] Add consistent loading/empty/error states; verify routes, filters and current interactions.

### Phase 3 — Contribution and Markdown
- [ ] Map approved post fields/statuses to existing Post model and migrations; choose safe Markdown editor/renderer fitting Laravel and browser stack.
- [ ] Add draft, preview, submission and admin review UI with validated uploads; preserve old posts and test sanitized rendering.

### Phase 4 — Account, admin and optional domain features
- [ ] Align auth/profile/admin presentation and email templates, preserving Laravel guards/notifications.
- [ ] Implement only approved optional features (team, new taxonomy, moderator, revisions, comments/reports, etc.) as separate logical phases with their own migrations, policies and checks.

## 6. Verification

- [x] Phase 1 shared UI checked against the local reference design and rendered home/footer screenshots.
- [x] Phase 1 responsive and RTL behavior checked at 390px and 1440px; neither viewport had horizontal overflow.
- [x] Phase 1 Vite build, Blade view compilation, and focused public navigation/footer tests passed (4 tests, 24 assertions). `node --check` and `git diff --check` passed. Initial generic `php artisan test --compact` could not run because `tests/Unit` is absent; targeted feature tests were run instead.
- [ ] Validation and form feedback checked.
- [ ] Models, relationships and migrations checked.
- [ ] Local email verification/reset checked using log or configured local mail catcher.
- [ ] Markdown storage, preview and safe rendering checked, including existing Post compatibility.
- [ ] Upload validation, ownership and cleanup checked.
- [ ] Authentication and authorization checked.
- [ ] Existing place/service/post workflows regression checked.

## 7. Risks and decisions for owner verification

1. The reference is in `.gitignore` and stays a local study copy; direct imports from `apps/web` cannot run in Blade without bringing React/Next.js. Avoid a second application in this repository unless architecture migration is explicitly desired.
2. No root LICENSE file was found in the local reference and its API package declares `UNLICENSED`. Before copying its code, images or logo into deliverable files, obtain permission/license terms from the owner; visual pattern analysis can proceed.
3. The reference editor stores Tiptap JSON. The requested Markdown source format needs a deliberate target implementation and safe rendering rules.
4. The reference's cultural-entry domain does not match Makanyab's place/service/post domain one-to-one. Reusing every model would duplicate or distort current architecture.
5. Current CSS already contains a Mirasaf-inspired layer; audit cascade/visual regressions before adding styles. Existing Makanyab branding should not be overwritten by reference branding by accident.
6. No implementation tests or build were run during this read-only analysis. Start with a baseline check after plan verification and before Phase 1 changes.
