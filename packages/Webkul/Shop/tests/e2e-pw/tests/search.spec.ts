import { test, expect } from "../setup";

test("should search by query", async ({ page }) => {
    await page.goto("");

    await page.getByLabel("Search article here").click();
    await page.getByLabel("Search article here").fill("arct");
    await page.getByLabel("Search article here").press("Enter");

    await expect(
        page.getByText("These are results for : arct").first()
    ).toBeVisible();
});
