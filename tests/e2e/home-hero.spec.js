import { expect, test } from '@playwright/test';

test.describe('home hero', () => {
    test('slider dots and simplified search form work without console errors', async ({ page }) => {
        const issues = [];

        page.on('console', (message) => {
            if (message.type() === 'error') {
                issues.push(message.text());
            }
        });

        await setLocale(page, 'en');
        await page.goto('/', { waitUntil: 'networkidle' });

        const hero = page.locator('[data-home-hero]');
        await expect(hero).toBeVisible();
        await expect(page.locator('[data-hero-slide]')).toHaveCount(6);
        await expect(page.locator('[data-hero-slide].is-active')).toContainText('Find trusted places across Afghanistan');

        await expect(page.locator('[data-hero-prev]')).toHaveCount(0);
        await expect(page.locator('[data-hero-next]')).toHaveCount(0);

        await page.getByRole('button', { name: 'Show slide 2' }).click();
        await expect(page.locator('[data-hero-slide].is-active')).toContainText('Discover services that are ready to help');

        await page.getByRole('button', { name: 'Show slide 1' }).click();
        await expect(page.locator('[data-hero-slide].is-active')).toContainText('Find trusted places across Afghanistan');

        await page.getByLabel('Keyword').fill('restaurant');
        await page.getByLabel('Choose a location').selectOption({ label: 'Kabul' }).catch(async () => {});
        await page.getByRole('button', { name: /^Search$/ }).click();

        await expect(page).toHaveURL(/\/search\?/);
        await expect(page).toHaveURL(/search=restaurant/);
        expect(issues).toEqual([]);
    });
});

async function setLocale(page, locale) {
    await page.goto('/login', { waitUntil: 'domcontentloaded' });
    const localeSelect = page.locator('select[name="locale"]').first();

    if (await localeSelect.count()) {
        await localeSelect.selectOption(locale);
        await page.waitForLoadState('networkidle');
    }
}
