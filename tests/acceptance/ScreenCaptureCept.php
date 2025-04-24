<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("Capture the appearance of the UI");

$I->amOnPage('/test');

// Take a screenshot for visual verification
$I->makeScreenshot('comment-form-chatgpt-style');

// Screen shor can be found at http://localhost/wp-content/themes/ai-style/tests/_output/debug/comment-form-chatgpt-style.png