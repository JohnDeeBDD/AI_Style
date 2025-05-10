<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest("Chat message functions in chatMessages.js");
$I->loginAsAdmin();
$I->amOnUrl("http://localhost");
$I->amOnPage('/testpost');

// Test 1: Clear any existing messages first
$I->comment("Testing clearMessages() function");
$I->executeJS("import('/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js').then(module => module.clearMessages())");
$I->wait(1); // Wait for the DOM to update
$I->dontSeeElement('.message'); // Verify no messages are present

// Test 2: Add an interlocutor message and verify it appears
$I->comment("Testing addInterlocutorMessage() function");
$testMessage1 = "This is a test interlocutor message";
$I->executeJS("import('/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js').then(module => module.addInterlocutorMessage('$testMessage1'))");
$I->wait(1); // Wait for the DOM to update
$I->seeElement('.interlocutor-message'); // Verify the message element exists
$I->see($testMessage1, '.interlocutor-message .message-content'); // Verify the message content

// Test 3: Add a respondent message and verify it appears
$I->comment("Testing addRespondentMessage() function");
$testMessage2 = "This is a test respondent message";
$I->executeJS("import('/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js').then(module => module.addRespondentMessage('$testMessage2'))");
$I->wait(1); // Wait for the DOM to update
$I->seeElement('.respondent-message'); // Verify the message element exists
$I->see($testMessage2, '.respondent-message .message-content'); // Verify the message content

// Test 4: Clear all messages and verify they are removed
$I->comment("Testing clearMessages() function again");
$I->executeJS("import('/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js').then(module => module.clearMessages())");
$I->wait(1); // Wait for the DOM to update
$I->dontSeeElement('.message'); // Verify all messages are removed

// Take a screenshot of the final state
$I->makeScreenshot('chat-messages-test');

// Run this test with the command: "bin/codecept run acceptance ChatMessagesCept.php -vvv --html"
// The screen shot can be found at: http://localhost/wp-content/themes/ai_style/tests/_output/debug/chat-messages-test.png