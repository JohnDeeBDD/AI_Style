<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("Test the basic functional columns, which are the main");

$I->amOnPage('/columns-test');

// Take a screenshot for visual verification
$I->makeScreenshot('comment-form-chatgpt-style');
