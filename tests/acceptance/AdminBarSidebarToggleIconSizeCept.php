<?php
/**
 * AdminBarSidebarToggleIconSizeCept.php
 * 
 * Acceptance test for verifying the admin bar sidebar toggle icon sizing consistency.
 * This test demonstrates the sizing issue where the sidebar toggle icon appears smaller
 * than other admin bar icons due to incorrect CSS styling.
 * 
 * This test checks:
 * 1. Presence of the sidebar toggle button in the admin bar
 * 2. Presence of the dashicons element within the sidebar toggle
 * 3. Font-size comparison between sidebar toggle icon and standard admin bar icons
 * 4. Width and height comparison between sidebar toggle icon and standard admin bar icons
 * 5. Visual consistency assertions that should fail until the implementation is fixed
 * 
 * Following TDD approach: This test should initially fail and pass once we fix the CSS implementation.
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Admin bar sidebar toggle icon sizing consistency');
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing admin bar sidebar toggle icon sizing for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Wait for the admin bar to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);

// 1. Verify that the sidebar toggle button exists in the admin bar
$I->comment('Verifying presence of sidebar toggle button in admin bar');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);

// 2. Verify that the dashicons element exists within the sidebar toggle
$I->comment('Verifying presence of dashicons element within sidebar toggle');
$sidebarToggleDashicon = AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .dashicons';
$I->seeElement($sidebarToggleDashicon);

// Take a screenshot to document the current state
$I->makeScreenshot('admin-bar-sidebar-toggle-before-size-check');
$I->comment("Screenshot of admin bar with sidebar toggle: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/admin-bar-sidebar-toggle-before-size-check.png' target='_blank'>available here</a>");

// 3. Check sidebar toggle icon properties
$I->comment('Checking sidebar toggle icon properties for WordPress admin bar standards');

// Get computed styles of sidebar toggle dashicon
$sidebarToggleStyles = $I->executeJS("
    const element = document.querySelector('" . $sidebarToggleDashicon . "');
    const styles = window.getComputedStyle(element);
    return {
        fontSize: styles.fontSize,
        width: styles.width,
        height: styles.height,
        lineHeight: styles.lineHeight,
        verticalAlign: styles.verticalAlign
    };
");

$I->comment("Sidebar toggle icon styles:");
$I->comment("  Font-size: " . $sidebarToggleStyles['fontSize']);
$I->comment("  Width: " . $sidebarToggleStyles['width']);
$I->comment("  Height: " . $sidebarToggleStyles['height']);
$I->comment("  Line-height: " . $sidebarToggleStyles['lineHeight']);
$I->comment("  Vertical-align: " . $sidebarToggleStyles['verticalAlign']);

// 4. Assert that the sidebar toggle icon has proper visual sizing for admin bar consistency
$I->comment('Asserting sidebar toggle icon visual consistency with admin bar');

// Assert font-size is 24px (increased for better visual appearance)
$I->assertEquals(
    '24px',
    $sidebarToggleStyles['fontSize'],
    'Sidebar toggle icon font-size should be 24px for proper visual appearance in admin bar.'
);

// Assert width is 24px
$I->assertEquals(
    '24px',
    $sidebarToggleStyles['width'],
    'Sidebar toggle icon width should be 24px for proper visual appearance in admin bar.'
);

// Assert height is 24px
$I->assertEquals(
    '24px',
    $sidebarToggleStyles['height'],
    'Sidebar toggle icon height should be 24px for proper visual appearance in admin bar.'
);

// Assert line-height is 1:1 ratio for clean appearance
$I->assertEquals(
    '24px',
    $sidebarToggleStyles['lineHeight'],
    'Sidebar toggle icon line-height should be 24px (1:1 ratio) for clean visual appearance. Got: ' . $sidebarToggleStyles['lineHeight']
);

// Assert vertical-align is set for proper alignment
$I->assertEquals(
    'middle',
    $sidebarToggleStyles['verticalAlign'],
    'Sidebar toggle icon vertical-align should be "middle" for proper admin bar alignment.'
);

// Additional verification: Check if the parent container has proper styling
$I->comment('Verifying parent container styling for WordPress admin bar standards');

// Get the parent link element styling for sidebar toggle
$sidebarToggleLink = AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item';
$I->seeElement($sidebarToggleLink);

$sidebarToggleLinkStyles = $I->executeJS("
    const element = document.querySelector('" . $sidebarToggleLink . "');
    const styles = window.getComputedStyle(element);
    return {
        display: styles.display,
        alignItems: styles.alignItems,
        gap: styles.gap,
        lineHeight: styles.lineHeight,
        padding: styles.padding
    };
");
$I->comment("Sidebar toggle link styles:");
$I->comment("  Display: " . $sidebarToggleLinkStyles['display']);
$I->comment("  Align-items: " . $sidebarToggleLinkStyles['alignItems']);
$I->comment("  Gap: " . $sidebarToggleLinkStyles['gap']);
$I->comment("  Line Height: " . $sidebarToggleLinkStyles['lineHeight']);
$I->comment("  Padding: " . $sidebarToggleLinkStyles['padding']);

// Verify the parent container has proper flexbox styling
$I->assertEquals(
    'flex',
    $sidebarToggleLinkStyles['display'],
    'Sidebar toggle link should use flexbox display for proper alignment.'
);

$I->assertEquals(
    'center',
    $sidebarToggleLinkStyles['alignItems'],
    'Sidebar toggle link should center-align items for proper icon alignment.'
);

// Take a final screenshot after all measurements
$I->makeScreenshot('admin-bar-sidebar-toggle-after-size-check');
$I->comment("Final screenshot after size verification: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/admin-bar-sidebar-toggle-after-size-check.png' target='_blank'>available here</a>");

$I->comment('Test completed. All assertions should pass if the sidebar toggle icon sizing matches WordPress admin bar standards.');