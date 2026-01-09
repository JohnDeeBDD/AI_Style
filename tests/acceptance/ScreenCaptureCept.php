<?php
/**
 * ScreenCaptureCept.php
 *
 * Acceptance test for capturing screenshots of the UI appearance.
 *
 * This test:
 * 1. Navigates to the test post page
 * 2. Captures a screenshot of the UI for visual inspection and documentation
 * 3. Provides a reference for UI appearance across test runs
 */

$I = new AcceptanceTester($scenario);
$I->wantToTest("Capture the appearance of the UI");
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// Device type determined by breakpoint, eliminating the need for dynamic zoom changes during test execution
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';
$I->comment("Taking screenshot for {$deviceType} mode (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");
$I->comment("Configuration-driven test: Device type = {$deviceType}, Mobile breakpoint = " . ($isMobile ? 'true' : 'false'));

// Take a screenshot of the current UI state
$I->makeScreenshot('testpost');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost.png' target = '_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance ScreenCaptureCept.php -vvv --html"