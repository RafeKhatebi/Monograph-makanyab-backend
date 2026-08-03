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