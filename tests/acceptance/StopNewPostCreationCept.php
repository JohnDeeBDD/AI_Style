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

$I->wantToTest('Prevention of new post creation from /testpost page');
$I->amOnUrl('http://localhost');
$I->loginAsAdmin();
$I->amOnPage('/testpost');

// Wait for the admin bar to be fully loaded
$I->waitForElement('#wpadminbar', 10);

// Verify the "New" button is present in the admin bar
$I->comment('Verifying the "New" button is present');
$I->seeElement('#wp-admin-bar-new-content');

// Take a screenshot before clicking
$I->makeScreenshot('new-button-before-click');

// Store the current URL before clicking
$currentUrl = $I->grabFromCurrentUrl();
$I->comment("Current URL before clicking: $currentUrl");

// Click the "New" button
$I->comment('Clicking the "New" button');
$I->click('#wp-admin-bar-new-content a.ab-item');

// Wait a moment to ensure any navigation would have occurred
$I->wait(2);

// Verify we're still on the same page by checking the URL hasn't changed
$afterClickUrl = $I->grabFromCurrentUrl();
$I->comment("URL after clicking: $afterClickUrl");
$I->assertEquals($currentUrl, $afterClickUrl, 'URL should not change after clicking the "New" button');

// Verify we're not redirected to /wp-admin/post-new.php
$I->dontSeeInCurrentUrl('/wp-admin/post-new.php');

// Take a screenshot after clicking to show we're still on the same page
$I->makeScreenshot('new-button-after-click');

// Verify we can still see elements that would be gone if we navigated away
$I->seeElement('#wp-admin-bar-new-content');

// Add a comment to explain console log verification limitation
$I->comment('Note: Console log verification requires manual inspection or browser extension');

// Run this test with the command: "bin/codecept run acceptance StopNewPostCreationCept.php -vvv --html"