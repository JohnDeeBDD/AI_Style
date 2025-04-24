<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That main divisions of the UI interface exist");
$I->amOnPage('/test');

// Check for main UI divisions
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');
$I->seeElement('#chat-messages');
$I->seeElement('#chat-input');

// Check for the submit button in the comment form (WordPress outputs input[type=submit] or button[type=submit])
$I->seeElement('input[type=submit], button[type=submit]');

/*
 Output of the screen shot can be seen at:
  /var/www/html/wp-content/themes/ai_style/tests/_output/debug/comment-form-chatgpt-style.png
*/