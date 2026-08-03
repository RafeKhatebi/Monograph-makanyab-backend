# Makanyab Full System Review, Testing, Repair, and UI Improvement

You are working on **Makanyab**, a Laravel-based web application for discovering, searching, reviewing, favoriting, and suggesting places and services in Afghanistan.

The application includes:

* Laravel 12
* PHP 8.2+
* MySQL
* Blade templates
* Vite
* Tailwind CSS
* Alpine.js
* Bootstrap/theme assets
* Custom CSS and JavaScript
* Laravel Sanctum API authentication
* Pest/PHPUnit tests
* Playwright browser testing

The system has four main user types:

* Guest
* Registered user
* Place/service owner
* Administrator

Your task is to perform a complete, systematic audit of the project from the beginning, test every important workflow, identify defects, fix confirmed problems, improve the UI where necessary, and prepare the application for production.

## Critical Working Rules

1. Inspect the existing implementation before modifying anything.
2. Do not rewrite working sections without a clear reason.
3. Do not remove files merely because their purpose is not immediately obvious.
4. Search all Blade templates, JavaScript imports, CSS imports, Vite configuration, controllers, routes, and dynamic references before deleting assets.
5. Do not introduce duplicate frameworks, components, services, or styles.
6. Preserve existing functionality while improving the system.
7. Fix root causes instead of hiding errors.
8. Use Laravel conventions and the existing project architecture.
9. Use Form Requests, policies, services, reusable Blade components, and Eloquent relationships where appropriate.
10. Avoid unnecessary inline CSS and JavaScript.
11. Do not move to the next phase until the current phase is verified.
12. Run relevant tests after every meaningful change.
13. Never use `migrate:fresh` on the main development database without creating and confirming a backup.
14. Do not expose secrets, tokens, passwords, or `.env` values.
15. Document every file changed and every issue fixed.

## Required Response Format for Every Phase

Before making changes, report:

### Phase

Name and objective of the current phase.

### Files Inspected

List all relevant files inspected.

### Existing Implementation

Explain how the current implementation works.

### Problems Found

List confirmed defects, inconsistencies, security risks, missing functionality, and UI problems.

### Proposed Changes

Explain the exact changes required and why.

Then implement the changes.

After implementation, report:

### Files Changed

List each changed file and explain the modification.

### Tests Performed

List commands, automated tests, browser tests, and manual checks performed.

### Results

Report passes, failures, warnings, and unresolved issues.

### Regression Check

Confirm which existing workflows were retested.

### Remaining Work

State what still needs attention in this phase.

### Phase Status

Use exactly one:

* `PASSED`
* `PASSED WITH WARNINGS`
* `FAILED`
* `BLOCKED`

Do not start another phase until the current phase is `PASSED` or `PASSED WITH WARNINGS`.

---

# Phase 0 — Backup and Baseline

Before changing code:

1. Inspect Git status and current branch.
2. Create a dedicated audit branch if appropriate.
3. Record the latest commit.
4. Back up the MySQL database.
5. Record installed PHP and npm dependencies.
6. Record all Laravel routes.
7. Run the existing backend tests.
8. Run the current frontend build.
9. Run existing linting or formatting tools.
10. Run existing Playwright tests if configured.
11. Record existing browser-console errors.
12. Create a baseline defect list.

Suggested commands may include:

```bash
git status
git branch --show-current
git log -1 --oneline
php artisan about
php artisan route:list
php artisan test
composer validate
npm run build
```

Do not change application functionality during this phase.

---

# Phase 1 — Project Structure and Architecture

Audit:

* Models
* Controllers
* Form Requests
* Services
* Policies
* Middleware
* Providers
* Events and listeners
* Jobs
* Notifications
* Mail classes
* Routes
* Blade views
* Components
* Helpers
* Tests

Check for:

* Unused classes
* Duplicate logic
* Fat controllers
* Business logic inside views
* Incorrect model relationships
* Missing authorization
* Inconsistent naming
* Invalid imports
* Dead routes
* Duplicate routes
* Direct database queries that should use services or models
* Circular dependencies
* Debug code
* Commented-out obsolete code

Refactor only when the benefit and regression risk are understood.

---

# Phase 2 — Environment and Configuration

Audit:

* `.env.example`
* `config/app.php`
* Database configuration
* Filesystem configuration
* Cache configuration
* Session configuration
* Queue configuration
* Mail configuration
* Logging
* Application timezone
* Application locale
* Trusted hosts and proxies
* Vite configuration

Confirm:

* MySQL is the intended database.
* The SQLite file is not accidentally used.
* Storage linking works.
* Session and cache drivers are appropriate.
* Queue behavior is documented.
* Required environment variables exist in `.env.example`.
* Secrets are not committed.

Do not print secret values.

---

# Phase 3 — Database, Migrations, Models, and Relationships

Inspect all migrations and models.

Verify:

* UUID generation
* Primary keys
* Foreign keys
* Unique constraints
* Indexes
* Soft deletes
* Cascade and restrict behavior
* Nullable fields
* Status fields
* Timestamps
* Model casts
* Fillable or guarded properties
* Relationship correctness
* Database-level integrity

Audit these main entities:

* users
* place_categories
* places
* service_categories
* services
* reviews
* favorites
* opening_hours
* media
* posts
* place_suggestions
* service_suggestions
* contact_messages
* sessions
* cache
* jobs
* personal_access_tokens

Test migrations using a separate test database.

---

# Phase 4 — Factories, Seeders, and Realistic Test Data

Create or improve factories and seeders for:

* Administrator
* Owners
* Normal users
* Inactive users
* Place categories
* Service categories
* Places
* Services
* Opening hours
* Media
* Approved reviews
* Pending reviews
* Rejected reviews
* Favorites
* Suggestions
* Posts
* Contact messages

Include edge cases:

* Missing images
* Long names
* Long descriptions
* Dari/Persian content
* English content
* Duplicate names
* Empty categories
* Inactive records
* Unverified records
* Soft-deleted records
* Records without reviews
* Invalid combinations for test cases

The seeded system must provide enough data to test pagination, filtering, search, responsive layouts, and empty states.

---

# Phase 5 — Authentication

Audit and test:

* Registration
* Email/password login
* Logout
* Remember me
* Password reset
* Password update
* Email verification
* Session invalidation
* Login throttling
* Inactive account handling
* Redirect behavior
* Validation messages
* Old-input preservation
* Unauthorized access

Test success and failure cases.

## Add Google Authentication

Implement secure login and registration with Google using the standard Laravel-compatible OAuth approach, preferably Laravel Socialite unless the project already uses another maintained solution.

Requirements:

1. Add “Continue with Google” to the login page.
2. Add Google authentication to the registration experience where appropriate.
3. Create the OAuth redirect route.
4. Create the OAuth callback route.
5. Validate the provider response.
6. Find users by provider ID or verified email.
7. Create a user when no account exists.
8. Link Google authentication to an existing account only through a safe process.
9. Store provider identity securely.
10. Do not store OAuth access tokens unless the application genuinely needs them.
11. Handle cancelled authorization.
12. Handle invalid callback data.
13. Handle duplicate-email conflicts.
14. Handle accounts created with email/password and later connected to Google.
15. Redirect users safely after login.
16. Add automated tests using mocked OAuth responses.
17. Add required variables to `.env.example`.
18. Do not expose Google client secrets.

Suggested environment names:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=
```

## Add Facebook Authentication

Implement secure Facebook login and registration through Laravel Socialite or the project’s existing maintained OAuth system.

Requirements:

1. Add “Continue with Facebook” to the authentication UI.
2. Add redirect and callback routes.
3. Handle missing email addresses from Facebook.
4. Use Facebook provider ID as a stable identity.
5. Prevent duplicate accounts.
6. Handle existing email/password accounts safely.
7. Handle cancelled or denied authorization.
8. Handle invalid and expired callback requests.
9. Store only required provider data.
10. Add tests with mocked provider responses.
11. Add environment variables to `.env.example`.
12. Do not expose Facebook secrets.

Suggested environment names:

```env
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=
```

## Social Account Data Model

Review the current user table before deciding the schema.

Prefer a scalable structure such as a `social_accounts` table if users may connect multiple providers.

Possible fields:

* id
* user_id
* provider
* provider_user_id
* provider_email
* avatar_url
* created_at
* updated_at

Add a unique constraint for:

```text
provider + provider_user_id
```

Do not add provider-specific columns directly to `users` unless the current architecture clearly justifies it.

## Authentication UI

Improve the login and registration pages:

* Consistent layout
* Clear email/password fields
* Password visibility control
* “Forgot password” link
* Remember-me option
* Google button
* Facebook button
* Divider such as “or continue with email”
* Loading and disabled states
* Visible focus states
* Correct validation messages
* Mobile responsiveness
* Accessible labels
* Correct provider icons
* No misleading authentication claims

The social-login buttons must remain usable when one provider is temporarily unavailable.

---

# Phase 6 — Roles, Policies, and Permissions

Test all roles:

* Guest
* User
* Owner
* Administrator

Verify permissions for:

* Public browsing
* Favorites
* Reviews
* Suggestions
* Profile management
* Owner place management
* Owner service management
* Admin moderation
* User management
* Category management
* Post management
* API actions

Attempt authorization bypass using:

* Direct URLs
* Modified form IDs
* Modified UUIDs
* API requests
* Requests involving another owner’s content
* Hidden or disabled frontend controls

Frontend visibility is not sufficient. Every protected server action must use middleware, policies, gates, or explicit authorization.

---

# Phase 7 — Global UI System and Layout

Before changing individual screens, audit the entire UI implementation.

Identify:

* Tailwind usage
* Bootstrap usage
* Theme assets
* Custom styles
* Inline styles
* Internal `<style>` blocks
* Inline scripts
* Internal `<script>` blocks
* Repeated Blade markup
* Repeated form controls
* Repeated button styles
* Conflicting spacing systems
* Conflicting typography
* Duplicate icon libraries

Create a consistent UI system.

## Required Shared UI Elements

Create reusable Blade components or partials for:

* Buttons
* Text inputs
* Password inputs
* Search inputs
* Selects
* Textareas
* Checkboxes
* Radio controls
* File inputs
* Form groups
* Validation messages
* Alerts
* Cards
* Badges
* Tables
* Pagination
* Empty states
* Loading states
* Breadcrumbs
* Modals
* Confirmation dialogs
* Dropdowns
* Image placeholders

## Button Variants

Use a controlled set:

* Primary
* Secondary
* Success
* Danger
* Warning
* Ghost or text
* Icon button

Use controlled sizes:

* Small
* Medium
* Large

Do not create unique button CSS for each page.

## UI Design Requirements

The visual system should be:

* Clean
* Modern
* Professional
* Consistent
* Responsive
* Accessible
* Appropriate for a directory and discovery platform
* Suitable for both English and Persian/Dari content where relevant

Standardize:

* Typography
* Heading hierarchy
* Colors
* Border radius
* Shadows
* Form heights
* Spacing
* Containers
* Card structure
* Tables
* Status badges
* Icons
* Empty states
* Error messages
* Success feedback

Do not redesign every page at once. Establish the system first, then migrate pages gradually.

---

# Phase 8 — Public Header, Navigation, and Footer

Audit and fix:

* Logo
* Main navigation
* Search access
* Authentication controls
* User menu
* Mobile menu
* Active navigation state
* Dropdown behavior
* Keyboard accessibility
* Footer links
* Contact information
* Social links
* Copyright
* Policy links
* Broken links

Ensure the header and footer are shared rather than duplicated.

---

# Phase 9 — Home Page

Review every home-page section:

* Hero
* Main search
* Featured places
* Featured services
* Categories
* Latest content
* Verified records
* Blog posts
* Calls to action
* Empty states

Check:

* Data correctness
* Caching
* Links
* Broken images
* Long content
* Responsive layout
* Loading behavior
* Visual consistency
* Accessibility
* Performance

Improve the UI only after identifying concrete problems.

---

# Phase 10 — Place Categories

Review public and admin category workflows:

* Parent categories
* Child categories
* Listing
* Detail page
* Counts
* Slugs
* Create
* Edit
* Delete
* Active state
* Empty categories
* Categories with existing places

Ensure deletion does not create orphan records or corrupt relationships.

---

# Phase 11 — Places

Audit:

* Place listing
* Place detail
* Place creation
* Place editing
* Place deletion
* Restore behavior
* Ownership
* Category
* Location
* Address
* Coordinates
* Contact information
* Description
* Status
* Verification
* Price level
* Opening hours
* Media
* Cover image
* Reviews
* Favorites
* Related places

Test:

* Validation
* Authorization
* Empty values
* Long values
* Duplicate names
* Slug uniqueness
* Inactive visibility
* Soft-deleted visibility
* Image fallbacks
* Mobile layout

Fix all confirmed issues and add tests.

---

# Phase 12 — Service Categories

Perform the same complete audit as place categories.

Verify hierarchy, slugs, deletion rules, counts, public presentation, and admin forms.

---

# Phase 13 — Services

Perform the same systematic audit used for places.

Review:

* Listing
* Detail
* CRUD
* Ownership
* Categories
* Media
* Reviews
* Favorites
* Status
* Contact information
* Price information
* Validation
* Authorization
* Soft deletion

Reuse shared components where the place and service workflows are genuinely similar.

---

# Phase 14 — Search

Test search using:

* Exact terms
* Partial terms
* Different capitalization
* Persian/Dari text
* English text
* Place names
* Service names
* Categories
* Locations
* Descriptions
* Addresses
* Special characters
* Long input
* Empty input
* No-result input

Verify:

* Correct result types
* Ranking behavior
* Pagination
* Query persistence
* Hidden-record exclusion
* Deleted-record exclusion
* SQL safety
* Performance
* Useful empty states

---

# Phase 15 — Filters and Sorting

Test every filter separately and in combinations.

Possible filters include:

* Content type
* Category
* Location
* Status
* Price level
* Rating
* Verification
* Open now
* Search keyword

Verify:

* Mobile filters
* Desktop filters
* Clear-all
* Pagination persistence
* Invalid query parameters
* No-result combinations
* Sorting
* URL state
* Accessibility

---

# Phase 16 — Favorites

Test:

* Add place
* Remove place
* Add service
* Remove service
* Duplicate attempts
* Logged-out behavior
* Deleted records
* Inactive records
* Favorites listing
* Empty state
* Pagination
* API behavior

Enforce uniqueness at both application and database levels.

---

# Phase 17 — Reviews

Test:

* Submit
* Edit
* Delete
* Rating limits
* Text validation
* Duplicate-review rules
* Pending status
* Approval
* Rejection
* Public visibility
* Ownership
* Rating calculations

Verify that pending, rejected, deleted, or unauthorized reviews do not affect public ratings.

Test admin moderation thoroughly.

---

# Phase 18 — Suggestions

Test place and service suggestions for guests and users.

Review:

* Submission
* Validation
* Spam protection
* User association
* Status tracking
* Admin queue
* Approval
* Rejection
* Conversion to real records
* Category assignment
* Slug generation
* Duplicate handling
* Failure rollback

Ensure approval cannot partially create invalid data.

---

# Phase 19 — Opening Hours

Test:

* All seven days
* Closed days
* Opening and closing times
* Duplicate days
* Invalid ranges
* Overnight hours
* Timezone
* Editing
* Deleting
* “Open now” calculations

Add tests around boundary times.

---

# Phase 20 — Media and Image Management

Audit:

* JPG
* JPEG
* PNG
* WebP
* Unsupported files
* Oversized files
* Corrupted files
* Multiple uploads
* Cover images
* Duplicate images
* Replacement
* Deletion
* Storage cleanup
* Orphan files
* Secure filenames
* Image fallbacks
* Executable-file rejection

Confirm database and filesystem operations remain synchronized.

---

# Phase 21 — User Profile and Account Connections

Test:

* View profile
* Edit profile
* Change email
* Change password
* Avatar
* Account state
* Favorites
* Reviews
* Suggestions
* Connected Google account
* Connected Facebook account

Add a “Connected Accounts” area if appropriate.

Allow users to see whether Google or Facebook is connected.

Do not permit disconnecting the only available login method unless the user first sets a password or connects another provider.

---

# Phase 22 — Posts and Static Content

Audit:

* Post listing
* Post details
* Publication state
* Author
* Slug
* Image
* Pagination
* SEO
* Admin CRUD
* Draft visibility

Also review:

* About
* Contact
* Privacy
* Terms
* Other static pages

---

# Phase 23 — Contact Messages

Test:

* Guest submission
* User submission
* Validation
* Spam controls
* Success state
* Duplicate submission
* Admin viewing
* Read/unread behavior
* Archive or deletion
* Sensitive data handling

---

# Phase 24 — Admin Dashboard

Verify every statistic against the database.

Check:

* Users
* Places
* Services
* Reviews
* Suggestions
* Active records
* Inactive records
* Pending items
* Recent activity
* Dashboard links
* Empty state
* Query performance
* Responsive layout

---

# Phase 25 — Admin Tables and Forms

Audit all admin pages.

## Tables

Check:

* Alignment
* Search
* Filters
* Sorting
* Pagination
* Status badges
* Action buttons
* Confirmation dialogs
* Empty states
* Long text
* Responsive behavior

## Forms

Check:

* Labels
* Input alignment
* Input width
* Select controls
* File inputs
* Validation messages
* Required indicators
* Existing values
* Old input
* Save state
* Cancel action
* Loading state
* Disabled state
* Mobile layout

Fix overlapping and misaligned controls.

All admin sections must use the same shared layout and component system.

---

# Phase 26 — API Authentication and Social Authentication Compatibility

Test:

* Sanctum registration
* Sanctum login
* Token generation
* Logout
* Revoked tokens
* Missing tokens
* Invalid tokens
* Inactive users
* Throttling
* Socially created accounts using API authentication

Decide and document how a user created through Google or Facebook can authenticate through the API.

Do not automatically invent a password for socially created users.

Use a safe account-linking or password-creation flow.

---

# Phase 27 — Public API

Audit all public endpoints:

* Places
* Place details
* Services
* Service details
* Categories
* Posts
* Search
* Filters
* Pagination
* Contact
* Suggestions

Verify:

* HTTP status codes
* JSON structure
* Pagination metadata
* Validation errors
* Date formats
* Image URLs
* Null values
* Hidden fields
* Response performance

Use one consistent API response structure.

---

# Phase 28 — Owner and Protected API Actions

Test:

* Owner creates own content
* Owner edits own content
* Owner deletes own content
* Owner uploads media
* Owner updates hours
* Owner cannot alter another owner’s content
* Owner cannot access moderation
* User cannot impersonate owner
* Admin actions remain protected

Test modified UUID and ID attacks.

---

# Phase 29 — Validation

Review every Form Request and controller-level validation.

Check:

* Required values
* Length limits
* Email
* URL
* Phone
* Coordinates
* Numbers
* Rating
* Enum values
* Foreign keys
* UUIDs
* File MIME
* File size
* Conditional fields
* Unique fields
* Arrays
* Unexpected fields

Test hostile input:

* HTML
* Script tags
* SQL-like content
* Very long strings
* Unexpected arrays
* Invalid UUIDs
* Invalid file content

---

# Phase 30 — Error Handling

Test and improve:

* 403
* 404
* 419
* 422
* 429
* 500
* Missing records
* Expired session
* OAuth cancellation
* OAuth callback failure
* Database failure
* Storage failure
* Missing images
* Unauthorized action

Do not expose stack traces or sensitive data outside development.

---

# Phase 31 — Security

Audit:

* CSRF
* XSS
* SQL injection
* Mass assignment
* IDOR
* Role bypass
* Session fixation
* Login throttling
* API throttling
* OAuth state validation
* OAuth account linking
* Upload security
* Security headers
* CSP
* HTTPS readiness
* Secret management
* Debug configuration
* Git history for committed secrets

Do not weaken security headers simply to make an external script work. Configure required providers precisely.

---

# Phase 32 — CSS, JavaScript, Theme, and Asset Cleanup

Create a complete inventory of:

* CSS
* JavaScript
* Bootstrap assets
* Tailwind assets
* Theme files
* Images
* Icons
* Fonts
* Plugins

Before deleting anything, search:

* Blade templates
* Blade components
* CSS imports
* JavaScript imports
* Vite configuration
* Dynamic class names
* Controllers
* Database-generated content
* Third-party plugin initialization

Then:

* Remove confirmed unused assets
* Consolidate duplicate styles
* Move reusable inline styles into components or stylesheets
* Move reusable inline scripts into modules
* Remove obsolete theme code
* Avoid running two competing design systems without justification
* Rebuild assets
* Retest every page
* Check browser console and network failures

Provide evidence for each deleted file.

---

# Phase 33 — Responsive Design

Test at minimum:

* 320px
* 375px
* 430px
* 768px
* 1024px
* 1280px
* 1440px

Review:

* Navigation
* Search
* Filters
* Cards
* Forms
* Tables
* Modals
* Galleries
* Admin sidebar
* Footer
* Pagination
* Authentication pages
* Google/Facebook buttons

Fix horizontal overflow, overlap, unreadable text, unusable tables, and inaccessible controls.

---

# Phase 34 — Accessibility

Audit:

* Semantic HTML
* Heading order
* Form labels
* Keyboard navigation
* Focus states
* Color contrast
* Alternative text
* Button names
* Link names
* Modal focus
* Error announcements
* Table headers
* Social-login labels
* Provider icons
* Screen-reader text

Run available automated accessibility tests, but also manually test keyboard navigation.

---

# Phase 35 — SEO

Review:

* Page titles
* Meta descriptions
* Canonical URLs
* Open Graph data
* Social metadata
* Heading hierarchy
* Slugs
* Sitemap
* robots.txt
* Structured data
* Image alt text
* Pagination
* Noindex rules for private/admin pages

---

# Phase 36 — Performance

Check:

* N+1 queries
* Slow queries
* Missing indexes
* Large images
* Duplicate assets
* Large bundles
* Excessive API payloads
* Missing pagination
* Incorrect cache invalidation
* Repeated queries
* Unnecessary eager loading
* Blocking JavaScript
* Third-party OAuth script usage

Record before-and-after measurements for meaningful optimizations.

---

# Phase 37 — Automated Backend Tests

Organize and expand tests for:

* Authentication
* Google OAuth
* Facebook OAuth
* Account linking
* Roles
* Permissions
* Places
* Services
* Reviews
* Favorites
* Suggestions
* Media
* Search
* Filters
* Admin
* API
* Validation
* Security-sensitive workflows

Tests must include success, validation failure, authentication failure, authorization failure, and rollback behavior.

---

# Phase 38 — Playwright End-to-End Tests

Create or improve browser tests for:

1. Guest searches for a place.
2. User registers with email/password.
3. User logs in with email/password.
4. User starts Google login using mocked/test OAuth behavior.
5. User starts Facebook login using mocked/test OAuth behavior.
6. User adds and removes a favorite.
7. User submits a review.
8. User submits a suggestion.
9. Admin approves a review.
10. Admin approves a suggestion.
11. Admin creates a place with media.
12. Owner edits their place.
13. Unauthorized user is blocked.
14. Mobile navigation works.
15. Mobile filters work.
16. Authentication pages remain responsive.

Do not use real third-party credentials in automated tests.

---

# Phase 39 — Manual Acceptance Testing

Create a structured test report containing:

* Test ID
* Module
* User role
* Preconditions
* Steps
* Expected result
* Actual result
* Status
* Screenshot
* Notes

Use separate accounts:

* Administrator
* Owner A
* Owner B
* Normal user
* Inactive user
* Google-authenticated test user
* Facebook-authenticated test user

Record every failure and retest after repair.

---

# Phase 40 — Documentation

Prepare:

* Installation guide
* Environment configuration
* Database setup
* Seeder instructions
* Authentication setup
* Google OAuth setup
* Facebook OAuth setup
* Redirect URI configuration
* Role descriptions
* API documentation
* Testing instructions
* Asset-build instructions
* Deployment guide
* Backup guide
* Known limitations
* Troubleshooting guide

Never include real client secrets in documentation.

---

# Phase 41 — Production Readiness

Only begin after previous phases pass.

Verify:

* `APP_ENV=production`
* `APP_DEBUG=false`
* HTTPS
* Production database
* Secure OAuth callback URLs
* Google production credentials
* Facebook production credentials
* Mail
* Cache
* Queue workers
* Scheduler
* Backups
* Logging
* Monitoring
* Storage permissions
* Optimized Composer installation
* Built frontend assets
* Migration strategy
* Rollback strategy
* OAuth failure monitoring

Suggested production commands:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Do not deploy until critical workflows and regression tests pass.

---

# Current Task

Begin with **Phase 0 — Backup and Baseline** only.

Inspect the repository and provide the required phase report before modifying anything.

After completing Phase 0, stop and wait for the next instruction. Do not automatically continue to Phase 1.
