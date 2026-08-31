# Makanyab Organized TODO List

This document organizes the requested changes for the Makanyab app into clear implementation sections.

## 1. Home Page

### Search
- Add a simple search section.
- The home search should only include the essential search field and basic filter controls.
- Keep the search UI clean, simple, and easy to use.

### Hero Section
- Add 6 hero photos.
- Remove the arrow buttons used for changing hero photos.
- Keep photo changing behavior if needed, but without visible arrow controls.

### Header / Suggest Link
- Remove the `Suggest` link from the header on the home page.
- Add the suggest action at the end of the home page.
- Make the suggest action a sticky button.
- This sticky suggest button should appear only on the home page.

### Featured Sections
For each of these home page sections:
- `Featured Places`
- `Featured Services`
- `Recently Verified`
- `Latest from Makanyab`

Required behavior:
- Show content in one row.
- Add a `See More` button for each section.
- The button should redirect users to the correct full listing page.

## 2. Search Page

### Search Layout
- Put `Search in`, `Where`, and the search button in one row.
- The row must work correctly in both RTL and LTR layouts.
- The layout must be responsive and mobile friendly.

### Search Type and Categories
- Default search type should be `Places`.
- When the user switches between `Places` and `Services`, the category filters should change accordingly.
- Category filtering should be easy and clear.

### Initial Results
- Before the user filters anything, show:
  - 20 places
  - 20 services
- Add a `See More Places` button that redirects to the places page.
- Add a `See More Services` button that redirects to the services page.

## 3.imp

## 5. Posts Page

### Listing Behavior
- Show the latest posts at the top.
- Load 12 posts initially.
- Add a button to load the next 12 posts.
- Posts should be ordered newest first.
- Posts should be loaded incrementally from the database.

## 6. Static Content Pages

Write real, complete content in both Farsi and Pashto for:
- `Terms of Service`
- `Privacy Policy`
- `How to Share`
- `How to Send Posts`
- `How to Send Places`
- `How to Send Services`

Content requirements:
- Do not use placeholder text.
- Explain the process clearly for normal users.
- Include rules for submitting places, services, and posts.
- Explain draft/review behavior where relevant.
- Make sure all pages work correctly in RTL mode.

## 7. Cards and Detail Pages

### Shared Card Design
- Fix card text overflow.
- Make card descriptions readable.
- Limit card descriptions to about one line.
- Add a bold `Read More` link.
- The `Read More` link should open the full related page.
- Make the image clickable.
- Clicking the image should redirect to the related:
  - Place detail page
  - Service detail page
  - Post detail page

### Shared Card Component
- Use one shared card component for:
  - Places
  - Services
  - Posts
- Avoid having separate inconsistent card designs.

### Detail Pages
- Redesign full detail pages so content is clear and readable.
- Fix RTL layout issues on detail pages.
- Translate detail page UI text.
- Put important details in fixed, well-organized sections.
- Make page structure consistent across places, services, and posts.

## 8. About Page

- Translate the about page.
- Make sure the about page supports RTL correctly.
- Add Farsi and Pashto content where needed.

## 9. Categories Section

- Improve category cards.
- Add readable and relevant icons.
- Icons should be clear in both light and dark contexts if applicable.
- Category names should be readable and not overflow.

## 10. Language, Fonts, and Dates

### Fonts
- Add proper Farsi font support.
- Add proper Pashto font support.
- Ensure typography is readable across all RTL pages.

### Dates and Time
- Display time and dates in the Shamsi calendar.
- Apply Shamsi formatting consistently across:
  - Places
  - Services
  - Posts
  - Detail pages
  - Recently verified sections
  - Latest content sections







i'here m 
## 11. Suggest Page

### Required Form Sections
The suggest page should allow users to submit all needed information for:
- Places
- Services
- Posts

The form should include:
- Title/name
- Category
- Description
- Location/address where relevant
- Contact information where relevant
- Images
- Required details for each submission type
- Optional extra information

### Submission Behavior
- User-submitted content should be saved in draft mode.
- Draft submissions should be visible to the user after submission.
- Admin review/publishing should happen later.
- The user should be able to see the status of submitted content, such as:
  - Draft
  - Sent
  - Under review
  - Published
  - Rejected

### Validation
- Show clear validation messages.
- Require images where images are needed.
- Require all important details before submission.
- Support RTL and translated labels/messages.

## 12. Cross-Page Requirements

- All pages must support RTL and LTR correctly.
- All UI text should be translated.
- Shared components should be reused where possible.
- Database queries should avoid loading all records at once.
- Use pagination or cursor-based loading for large lists.
- Keep search and filter controls simple and modern.
- Make all clickable behavior clear and consistent.
- Test responsive layouts on desktop and mobile.

## Suggested Implementation Order

1. Fix shared language switching so route is preserved.
2. Add fonts and Shamsi date formatting.
3. Create or refactor shared card components.
4. Fix detail page RTL layout and translated labels.
5. Update home page hero, search, sticky suggest button, and featured rows.
6. Rebuild search page layout and category switching.
7. Add database-backed incremental loading for places.
8. Add database-backed incremental loading for services.
9. Add database-backed incremental loading for posts.
10. Rewrite static pages in Farsi and Pashto.
11. Rebuild suggest page with draft submission flow.
12. Final responsive and RTL/LTR testing.

## Acceptance Checklist

- [] Home page search is simple and works.
- [ ] Hero section has 6 photos.
- [ ] Hero arrows are removed.
- [ ] Home page has sticky suggest button at the end.
- [ ] Featured sections show one row and `See More` buttons.
- [ ] Search page search controls are in one row.
- [ ] Search page works in RTL and LTR.
- [ ] Search page defaults to places.
- [ ] Search categories change when switching places/services.
- [ ] Search page initially shows 20 places and 20 services.
- [ ] Places page loads first 20 records only.
- [ ] Places page can load 20 more records.
- [ ] Services page loads first 20 records only.
- [ ] Services page can load 20 more records.
- [ ] Posts page shows latest posts first.
- [ ] Posts page loads 12 posts first and 12 more on demand.
- [ ] Terms of service content exists in Farsi and Pashto.
- [ ] Privacy policy content exists in Farsi and Pashto.
- [ ] Sharing/submission guide content exists in Farsi and Pashto.
- [ ] Cards have readable one-line descriptions.
- [ ] Card images are clickable.
- [ ] Cards have bold `Read More` links.
- [ ] A shared card component is used for places, services, and posts.
- [ ] Detail pages are redesigned and RTL-safe.
- [ ] About page is translated.
- [ ] Category cards have readable icons.
- [ ] Farsi and Pashto fonts are added.
- [ ] Dates and times display in Shamsi format.
- [ ] Language switching keeps the user on the same page.
- [ ] Suggest page supports places, services, and posts.
- [ ] Suggested content is saved as draft or sent for review.
- [ ] Users can see their submitted content and status.
- [ ] Responsive testing is complete.
----------------------------
----------------------------
