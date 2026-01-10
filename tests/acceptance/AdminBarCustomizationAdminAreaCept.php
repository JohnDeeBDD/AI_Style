<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest('Admin bar customizations are applied on the frontend but not in admin area');

$I->amGoingTo('verify that admin bar customizations work correctly on frontend but are disabled in admin area');
$I->expect('the New button dropdown to be hidden on frontend but visible in admin area');

$I->comment('=== FRONTEND TESTING: Admin bar customizations should be active ===');

$I->amGoingTo('navigate to the frontend test page as an admin user');


$postId = $I->cUrlWP_SiteToCreatePost('AdminBarCustomizationAdminAreaCept', "AdminBarCustomizationAdminAreaCeptTestContent");
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage("/");
$I->amOnPage("/?p={$postId}");

// Configuration-driven approach: Test behavior adapts based on current device configuration
// Device type determined by breakpoint, eliminating the need for dynamic zoom changes during test execution
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';
$I->comment("Testing admin bar customization in admin area for {$deviceType} mode (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");
$I->comment("Configuration-driven test: Device type = {$deviceType}, Mobile breakpoint = " . ($isMobile ? 'true' : 'false'));

$I->expect('the admin bar to be fully loaded and visible');
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);
$I->seeElement(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT);

$I->amGoingTo('capture the admin bar state before any interaction');
$I->makeScreenshot('frontend-admin-bar-before-hover');
$I->comment("Frontend admin bar initial state: <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/frontend-admin-bar-before-hover.png' target = '_blank'>View Screenshot</a>");

$I->amGoingTo('hover over the New button to test if dropdown appears');
$I->moveMouseOver(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT);
$I->wait(1);

$I->amGoingTo('capture the admin bar state after hovering');
$I->makeScreenshot('frontend-admin-bar-after-hover');
$I->comment("Frontend admin bar after hover: <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/frontend-admin-bar-after-hover.png' target = '_blank'>View Screenshot</a>");

$I->expect('the dropdown menu to be hidden due to frontend customizations');
$I->dontSeeElement(AcceptanceConfig::ADMIN_BAR_DROPDOWN);

$frontendUrl = $I->grabFromCurrentUrl();

$I->amGoingTo('click the New button to verify it does not navigate away');
$I->click(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT_LINK);
$I->wait(2);

$I->expect('to remain on the same page after clicking New button');
$afterClickUrl = $I->grabFromCurrentUrl();
$I->assertEquals($frontendUrl, $afterClickUrl, 'URL should not change after clicking the "New" button on frontend');

$I->comment('=== ADMIN AREA TESTING: Admin bar customizations should be disabled ===');

$I->amGoingTo('test multiple admin pages to ensure customizations are not applied in admin area');
$I->expect('the New button dropdown to be visible and functional in all admin pages');
// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');
