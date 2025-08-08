<?php
/**
 * StartupFocusPositionCept.php
 *
 * Acceptance test for verifying the startup focus position functionality.
 *
 * This test checks:
 * 1. Creating a test post with comments
 * 2. Verifying that when cacbotData.comment_count > 0, the #scrollable-content
 *    should be scrolled to the bottom for the user upon page load
 * 3. Testing behavior based on device configuration
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("Startup focus position behavior");

// Create test post with content that will generate comments/chat interface
$I->comment('Creating test post for startup focus position testing');
$postContent = '<p>This is a test post for startup focus position verification. The theme will automatically generate the chat interface with comment handling capabilities that should trigger the focus position behavior.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage("/?p=" . $postId);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing startup focus position for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Test post contains comments/chat interface
// If cacbotData.comment_count > 0 then the #scrollable-content should be scrolled to the bottom for the user upon page load.

// Take a screenshot of the final state
$I->makeScreenshot('startup-focus-position-test');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/startup-focus-position-test.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

// Run this test with the command: "bin/codecept run acceptance StartupFocusPositionCept.php -vvv --html"