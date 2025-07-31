<?php
/**
 * ChatMessagesCept.php
 *
 * Acceptance test for verifying the chat message functionality.
 *
 * This test checks:
 * 1. Clearing existing messages
 * 2. Adding interlocutor messages
 * 3. Adding respondent messages
 * 4. Verifying message content and styling
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("Chat message functions in chatMessages.js");
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();
$I->comment("Testing chat messages for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Test 1: Clear any existing messages first
$I->comment("Testing clearMessages() function");
$I->executeJS("clearMessages();");
$I->wait(1); // Wait for the DOM to update
$I->dontSeeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE); // Verify no messages are present
$I->dontSeeElement(AcceptanceConfig::RESPONDENT_MESSAGE);

// Test 2: Add an interlocutor message and verify it appears
$I->comment("Testing addInterlocutorMessage() function");
$testMessage1 = "This is a test interlocutor message";
$I->executeJS("addInterlocutorMessage('$testMessage1');");
$I->wait(1); // Wait for the DOM to update
$I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE); // Verify the message element exists
$I->see($testMessage1, AcceptanceConfig::INTERLOCUTOR_MESSAGE . ' .message-content'); // Verify the message content

// Test 3: Add a respondent message and verify it appears
$I->comment("Testing addRespondentMessage() function");
$testMessage2 = "This is a test respondent message";
$I->executeJS("addRespondentMessage('$testMessage2');");
$I->wait(1); // Wait for the DOM to update
$I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE); // Verify the message element exists
$I->see($testMessage2, AcceptanceConfig::RESPONDENT_MESSAGE . ' .message-content'); // Verify the message content

// Test 4: Clear all messages and verify they are removed
$I->comment("Testing clearMessages() function again");
$I->executeJS("clearMessages();");
$I->wait(1); // Wait for the DOM to update
$I->dontSeeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE); // Verify all messages are removed
$I->dontSeeElement(AcceptanceConfig::RESPONDENT_MESSAGE);

// Take a screenshot of the final state
$I->makeScreenshot('chat-messages-test');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/chat-messages-test.png' target = '_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance ChatMessagesCept.php -vvv --html"