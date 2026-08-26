import { expect, test } from '@playwright/test';

const adminEmail = process.env.PLAYWRIGHT_ADMIN_EMAIL;
const adminPassword = process.env.PLAYWRIGHT_ADMIN_PASSWORD;

const viewports = [
    { name: 'desktop', width: 1440, height: 1000 },
    { name: 'tablet', width: 1024, height: 900 },
    { name: 'mobile', width: 390, height: 844 },
];

const publicPaths = [
    '/',
    '/about',
    '/contact',
    '/search',
    '/places',
    '/categories',
    '/service-categories',
    '/posts',
    '/privacy-policy',
    '/terms-of-service',
    '/login',
];

const adminPaths = [
    '/admin',
    '/admin/dashboard',
    '/admin/places',
    '/admin/places/create',
    '/admin/users',
    '/admin/users/create',
    '/admin/reviews',
    '/admin/contact-messages',
    '/admin/services',
    '/admin/services/create',
    '/admin/categories',
    '/admin/service-categories',
    '/admin/place-suggestions',
    '/admin/service-suggestions',
    '/admin/posts',
    '/admin/posts/create',
];

test.describe('frontend browser audit', () => {
    test.beforeEach(async ({ page }) => {
        const issues = {
            consoleErrors: [],
            failedRequests: [],
            failedResponses: [],
        };

        page.auditIssues = issues;

        page.on('console', (message) => {
            if (message.type() === 'error') {
                issues.consoleErrors.push(message.text());
            }
        });

        page.on('requestfailed', (request) => {
            if (isSameOrigin(request.url())) {
                issues.failedRequests.push(`${request.method()} ${request.url()} ${request.failure()?.errorText || ''}`.trim());
            }
        });

        page.on('response', (response) => {
            const status = response.status();
            if (status >= 400 && isSameOrigin(response.url())) {
                issues.failedResponses.push(`${status} ${response.url()}`);
            }
        });

        await setLocale(page, 'en');
    });

    for (const viewport of viewports) {
        test(`public pages have stable assets and scripts at ${viewport.name}`, async ({ page }) => {
            await page.setViewportSize({ width: viewport.width, height: viewport.height });

            for (const path of publicPaths) {
                await page.goto(path, { waitUntil: 'networkidle' });
                await expect(page.locator('body')).toBeVisible();
            }

            expect(page.auditIssues).toEqual({
                consoleErrors: [],
                failedRequests: [],
                failedResponses: [],
            });
        });

        test(`admin pages have stable assets and scripts at ${viewport.name}`, async ({ page }) => {
            test.skip(!adminEmail || !adminPassword, 'Set PLAYWRIGHT_ADMIN_EMAIL and PLAYWRIGHT_ADMIN_PASSWORD.');

            await page.setViewportSize({ width: viewport.width, height: viewport.height });
            await loginAsAdmin(page);

            for (const path of adminPaths) {
                await page.goto(path, { waitUntil: 'networkidle' });
                await expect(page.locator('body')).toBeVisible();
            }

            expect(page.auditIssues).toEqual({
                consoleErrors: [],
                failedRequests: [],
                failedResponses: [],
            });
        });
    }
});

async function loginAsAdmin(page) {
    await page.goto('/login', { waitUntil: 'networkidle' });
    await page.locator('input[name="email"]').fill(adminEmail);
    await page.locator('input[name="password"]').fill(adminPassword);
    await page.locator('button[type="submit"]').click();
    await page.waitForURL(/\/admin|\/dashboard/, { timeout: 15_000 });
}

async function setLocale(page, locale) {
    await page.goto('/login', { waitUntil: 'domcontentloaded' });
    const localeSelect = page.locator('select[name="locale"]').first();

    if (await localeSelect.count()) {
        await localeSelect.selectOption(locale);
        await page.waitForLoadState('networkidle');
    }
}

function isSameOrigin(url) {
    try {
        const parsedUrl = new URL(url);
        return ['127.0.0.1', 'localhost'].includes(parsedUrl.hostname);
    } catch {
        return false;
    }
}
