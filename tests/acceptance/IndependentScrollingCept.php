<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That chat-sidebar and chat-main divs scroll independently");

$I->amOnPage('/test');

// Test 1: Verify the presence of main UI structural elements
// The UI follows a two-column design similar to ChatGPT, with #chat-sidebar
// and #chat-main serving as the two primary columns

$I->seeElement('#floating-items-group');
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');
// Take a screenshot for visual verification
$I->makeScreenshot('ScreenCaptureCept');

// Screen shot can be found at http://localhost/wp-content/themes/ai-style/tests/_output/debug/ScreenCaptureCept.png