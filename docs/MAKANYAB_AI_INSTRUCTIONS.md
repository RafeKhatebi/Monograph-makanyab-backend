Makanyab AI Project Instructions

Purpose

Before doing any task for this project, first read the full Makanyab Project Context Report provided with this file or in the conversation.

Do not start implementation, refactoring, database changes, UI changes, or recommendations until you understand the existing architecture and intended product flow.

Core Product Idea

Makanyab is a Laravel-based discovery platform for Afghanistan.

The main user flow is:

Guest → Search/Discover → View Place/Service → Login when interaction is required

Logged-in User → Search → Save/Favorite → Rate/Review → Suggest Place/Service → Upload Photos → Track Submission Status

Admin → Review Suggestions → Approve/Reject → Manage Listings, Users, Categories, Reviews, Media, Posts, and Other Admin Data

The application already has these roles:

user

owner

admin

Do not add or recommend a manager role unless I explicitly ask for one.

Product Direction

Homepage

The homepage must stay simple and discovery-focused.

Do not show all available data.

Prefer small sections such as:

Top Places

Popular Places

Recently Updated

Categories

Services

Recommended Listings

Each section should normally show about 6 items and provide a View More link to the relevant full page.

Search should remain one of the main ways users discover content.

Search and Filters

Keep search useful and simple.

Filters should only contain filters that provide real value to users.

Avoid overloading the filter UI with unnecessary controls.

Always verify that frontend filters match backend query logic.

User Features

A logged-in user should be able to:

Register/login by email.

Verify email.

Search places and services.

Save/favorite listings.

Rate/review listings.

Suggest a place.

Suggest a service.

Upload photos with suggestions.

See all submitted suggestions.

See submission status:

pending

approved

rejected

View and manage profile information.

Do not duplicate features that already exist.

Suggestion Workflow

The intended submission flow is:

User submits → Pending → Admin reviews → Approved or Rejected

When reviewing this feature, always check:

Form fields.

Validation.

User ownership.

Photo/media upload.

Media storage.

Approval process.

Media transfer to the final Place/Service.

Rejection notes.

User submission-status visibility.

The form should use appropriate field types.

Long descriptions should use a proper textarea or Markdown-capable editor where appropriate.

Admin

The existing admin role is the administrative role.

Admin should be able to manage:

Users.

User roles.

Active/inactive users.

Places.

Services.

Place categories.

Service categories.

Suggestions.

Reviews.

Media.

Posts.

Contact messages.

Relevant site/business settings where implemented.

Always check security before expanding admin permissions.

Do not create unnecessary new roles.

Localization

The application supports:

English (en)

Farsi (fa)

Pashto (ps)

When modifying any page or backend response:

Check for hardcoded English.

Use translation keys where appropriate.

Check all three language files.

Check RTL behavior for Farsi and Pashto.

Do not use ucfirst() or English formatting for translated status/price labels.

Check backend validation and error messages too.

Also remember that some database content is currently single-language, so distinguish UI localization from multilingual database content.

Frontend Rules

Keep the UI consistent.

Before creating a new component:

Check whether a shared component already exists.

Reuse shared cards, grids, badges, buttons, forms, and layout components where possible.

Avoid unnecessary inline CSS.

Avoid duplicated card/list markup.

Keep card sizes consistent.

Long text must not make one card much taller than others.

Truncate or line-clamp preview text.

Show full content on the detail page or through a clear View/View More action.

Use icons that match the content and action.

Place and service pages should progressively move toward one consistent modern design system.

Backend / API Rules

For every backend or API change, check the full flow:

Database → Model → Request Validation → Policy/Middleware → Controller → Service → API Response → Frontend

Always verify:

Authentication.

Authorization.

Ownership.

is_active.

Role checks.

Validation.

Media handling.

Error localization.

API response consistency.

Frontend/backend field consistency.

Do not accept fields in validation and then ignore them in the controller.

Do not expose unnecessary internal model fields through APIs.

Database Rules

Before changing the database:

Read the existing migration.

Read the model.

Check relationships.

Check validation rules.

Check controllers/services using the field.

Check admin forms.

Check public frontend usage.

Check API usage.

Check localization implications.

Check backward compatibility.

Do not create duplicate fields or tables when the existing schema can support the feature.

Important Existing Areas

Before changing related functionality, inspect the relevant existing files for:

Users

Places

Services

Place categories

Service categories

Place suggestions

Service suggestions

Reviews

Favorites

Media

Posts

Contact messages

Social accounts

Web routes

API routes

Policies

Middleware

Services

Frontend Blade views

Admin Blade views

Localization files

Priority Order

P0 — Critical

Focus first on:

Authentication/security inconsistencies.

Inactive-user handling.

Broken frontend/backend integration.

Broken or missing media flows.

Suggestion submission tracking.

Suggestion photo upload.

Suggestion approval media transfer.

Permission problems.

Validation/localization problems affecting core flows.

P1 — Important

Then focus on:

Homepage simplification.

6-item discovery sections with View More.

Real Top/Popular/Recommended ranking.

Search/filter simplification.

Complete English/Farsi/Pashto localization.

Multilingual content architecture.

Consistent place/service detail pages.

Shared frontend components.

P2 — Improvements

Then consider:

Removing inline CSS.

API resources/DTOs.

Markdown editor.

Better maps/geocoding.

Integration-status pages.

Site/business settings.

Code cleanup and component refactoring.

How to Handle Every New Task

Whenever I give you a task:

Step 1 — Read Context

Read the Makanyab Project Context Report and this instruction file first.

Step 2 — Inspect Existing Implementation

Find the exact:

Database tables/migrations.

Models.

Routes.

Controllers.

Services.

Policies/middleware.

Frontend components/views.

Admin implementation.

Translation files.

API endpoints.

Step 3 — Compare

Determine:

Current implementation → Intended product behavior → Gap

Step 4 — Avoid Unnecessary Changes

Do not rebuild something that already works.

Prefer extending or correcting the existing architecture.

Step 5 — Explain Before Major Changes

For significant tasks, first tell me briefly:

What currently exists.

What is wrong/missing.

What should change.

Which files are affected.

Whether DB/API/frontend changes are required.

Keep this explanation concise and easy to understand.

Step 6 — Implement Consistently

If I ask you to implement, make the change across every affected layer, not only the visible frontend.

Step 7 — Recheck Integration

After implementation, verify:

Database ↔ Backend ↔ API ↔ Frontend ↔ Admin ↔ Localization

Required Behavior

Do not invent functionality.

Do not guess when code can be inspected.

Do not add a manager role.

Do not unnecessarily rewrite existing working features.

Distinguish verified facts from recommendations.

Mention exact files/classes/routes when possible.

Preserve existing architecture unless there is a strong reason to change it.

Keep the user journey simple.

Keep explanations short and practical.

If something cannot be verified, say Not verified.

Treat the Project Context Report as the main architectural reference, but always check the current code because implementation may have changed since the report was generated.

Short Instruction for Every Task

Use this rule before every Makanyab task:

Read the Makanyab Project Context Report and MAKANYAB_AI_INSTRUCTIONS.md first. Then inspect the current code related to my request. Stay consistent with the existing architecture and intended product flow. do not duplicate working features, and check the full Database → Backend → API → Frontend → Admin → Localization flow before recommending or implementing changes.