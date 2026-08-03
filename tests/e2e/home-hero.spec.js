import { expect, test } from '@playwright/test';

test.describe('home hero', () => {
    test('slider controls and search form work without console errors', async ({ page }) => {
        const issues = [];

        page.on('console', (message) => {
            if (message.type() === 'error') {
                issues.push(message.text());
            }
        });

        await page.goto('/', { waitUntil: 'networkidle' });

        const hero = page.locator('[data-home-hero]');
        await expect(hero).toBeVisible();
        await expect(page.locator('[data-hero-slide].is-active')).toContainText('Find trusted places across Afghanistan');

        await page.getByRole('button', { name: 'Show next Makanyab highlight' }).click();
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
