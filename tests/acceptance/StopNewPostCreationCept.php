<?php
/**
 * StopNewPostCreationCept.php
 * 
 * Acceptance test for verifying that clicking the "New" post icon on the admin bar
 * while on the `/testpost` page does not redirect the user and only shows a console.log message.
 * 
 * This test checks:
 * 1. Navigation to the /testpost page
 * 2. Presence of the "New" button in the admin bar
 * 3. That clicking the "New" button does not redirect to /wp-admin/post-new.php
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Prevention of new post creation from test post page');

// Create test post for testing new post creation prevention
$I->comment('Creating test post for new post creation prevention testing');
$postContent = '<p>This is a test post for verifying that the "New" button in the admin bar does not redirect when clicked from a test post page.</p>';
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
$I->comment("Testing stop new post creation for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Wait for the admin bar to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);

// Verify the "New" button is present in the admin bar
$I->comment('Verifying the "New" button is present');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT);

// Take a screenshot before clicking
$I->makeScreenshot('new-button-before-click');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/new-button-before-click.png' target = '_blank'>available here</a>");

// Store the current URL before clicking
$currentUrl = $I->grabFromCurrentUrl();
$I->comment("Current URL before clicking: $currentUrl");

// Click the "New" button
$I->comment('Clicking the "New" button');
$I->click(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT_LINK);

// Wait a moment to ensure any navigation would have occurred
$I->wait(2);

// Verify we're still on the same page by checking the URL hasn't changed
$afterClickUrl = $I->grabFromCurrentUrl();
$I->comment("URL after clicking: $afterClickUrl");
$I->assertEquals($currentUrl, $afterClickUrl, 'URL should not change after clicking the "New" button');

// Verify we're not redirected to /wp-admin/post-new.php
$I->dontSeeInCurrentUrl(AcceptanceConfig::ADMIN_NEW_POST);

// Take a screenshot after clicking to show we're still on the same page
$I->makeScreenshot('new-button-after-click');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/new-button-after-click.png' target = '_blank'>available here</a>");

// Verify we can still see elements that would be gone if we navigated away
$I->seeElement(AcceptanceConfig::ADMIN_BAR_NEW_CONTENT);

// Add a comment to explain console log verification limitation
$I->comment('Note: Console log verification requires manual inspection or browser extension');

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

// Run this test with the command: "bin/codecept run acceptance StopNewPostCreationCept.php -vvv --html"