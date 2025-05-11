<?php
/**
 * AdminBarCustomizationAdminAreaCept.php
 * 
 * Acceptance test for verifying that admin bar customizations only work on the frontend
 * and not in the WordPress admin area.
 * 
 * This test checks:
 * 1. Frontend: Admin bar customizations are applied
 * 2. Admin Area: Admin bar customizations are NOT applied
 */

$I = new AcceptanceTester($scenario);

// PART 1: Test frontend behavior - customizations SHOULD be applied
$I->wantToTest('Admin bar customizations are applied on the frontend but not in admin area');

// Test on frontend
$I->comment('TESTING FRONTEND: Customizations should be applied');
$I->amOnUrl('http://localhost');
$I->loginAsAdmin();
$I->amOnPage('/testpost');

// Wait for the admin bar to be fully loaded
$I->waitForElement('#wpadminbar', 10);

// Verify the "New" button is present
$I->seeElement('#wp-admin-bar-new-content');

// Take a screenshot before hovering
$I->makeScreenshot('frontend-admin-bar-before-hover');

// Hover over the "New" button
$I->moveMouseOver('#wp-admin-bar-new-content');

// Wait a moment for any potential dropdown to appear
$I->wait(1);

// Take a screenshot after hovering
$I->makeScreenshot('frontend-admin-bar-after-hover');

// Verify the dropdown menu doesn't appear (customization IS applied)
$I->dontSeeElement('#wp-admin-bar-new-content .ab-sub-wrapper:not([style*="display: none"])');

// Store the current URL before clicking
$frontendUrl = $I->grabFromCurrentUrl();

// Click the "New" button
$I->click('#wp-admin-bar-new-content a.ab-item');

// Wait a moment to ensure any navigation would have occurred
$I->wait(2);

// Verify we're still on the same page (customization IS applied)
$afterClickUrl = $I->grabFromCurrentUrl();
$I->assertEquals($frontendUrl, $afterClickUrl, 'URL should not change after clicking the "New" button on frontend');

// PART 2: Test admin area behavior - customizations should NOT be applied
$I->comment('TESTING ADMIN AREA: Customizations should NOT be applied');

// Test multiple admin pages to be thorough
$adminPages = [
    '/wp-admin/',              // Dashboard
    '/wp-admin/edit.php',      // Posts
    '/wp-admin/post-new.php',  // Add New Post
    '/wp-admin/edit.php?post_type=page', // Pages
    '/wp-admin/themes.php'     // Themes
];

foreach ($adminPages as $index => $adminPage) {
    $I->comment("Testing admin page: $adminPage");
    $I->amOnPage($adminPage);

    // Wait for the admin bar to be fully loaded
    $I->waitForElement('#wpadminbar', 10);

    // Verify the "New" button is present
    $I->seeElement('#wp-admin-bar-new-content');

    // Take a screenshot before hovering
    $I->makeScreenshot("admin-area-$index-before-hover");

    // Hover over the "New" button
    $I->moveMouseOver('#wp-admin-bar-new-content');

    // Wait a moment for any potential dropdown to appear
    $I->wait(1);

    // Take a screenshot after hovering
    $I->makeScreenshot("admin-area-$index-after-hover");

    // Verify the dropdown menu DOES appear (customization is NOT applied)
    // This is the key test - if this fails, it means the customizations are being applied in the admin area
    $I->seeElement('#wp-admin-bar-new-content .ab-sub-wrapper:not([style*="display: none"])');
    
    // We'll only test clicking on the dashboard page to avoid too many redirects
    if ($adminPage === '/wp-admin/') {
        // Store the current URL before clicking
        $adminUrl = $I->grabFromCurrentUrl();

        // Click the "New" button
        $I->click('#wp-admin-bar-new-content a.ab-item');

        // Wait a moment to ensure navigation occurs
        $I->wait(2);

        // Verify we're redirected to post-new.php (customization is NOT applied)
        $I->seeInCurrentUrl('/wp-admin/post-new.php');
    }
}

// Run this test with the command: "bin/codecept run acceptance AdminBarCustomizationAdminAreaCept.php -vvv --html"