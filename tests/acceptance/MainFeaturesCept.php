<?php
/**
 * MainFeaturesCept.php
 *
 * Acceptance test for verifying that all main UI elements exist and are properly displayed.
 *
 * This test checks:
 * 1. Presence of all main UI divisions
 * 2. Chat message display functionality
 * 3. Comment form elements
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist");

// Create test post with ChatGPT interface content
$I->comment('Creating test post for main features testing');
$postContent = '<p>This is a test post for main features verification. The theme will automatically generate the chat interface with all required UI divisions.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing main features for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

$I->executeJS("clearMessages()");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

// Check for main UI divisions
$I->seeElement(AcceptanceConfig::CHAT_CONTAINER); // Everything below the adminbar
$I->seeElement(AcceptanceConfig::CHAT_SIDEBAR); // to the left
$I->seeElement(AcceptanceConfig::CHAT_MAIN); // to the right
$I->seeElement(AcceptanceConfig::POST_CONTENT); // The WordPress content
$I->seeElement(AcceptanceConfig::CHAT_MESSAGES); // The WordPress comments, correlates to the chat area
$I->seeElement(AcceptanceConfig::CHAT_INPUT); // Contains the actual comment form
$I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE); // Corresponds to user chat messages
$I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE); // Corresponds to AI messages
$I->seeElement(AcceptanceConfig::SITE_FOOTER); // Corresponds to footer found in footer.php

// Check for the submit button in the comment form (WordPress outputs input[type=submit] or button[type=submit])
$I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);

// Take a screenshot of the final state
$I->makeScreenshot('main-features');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/main-features.png' target = '_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance MainFeaturesCept.php -vvv --html"

$I->makeScreenshot('testpost');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');