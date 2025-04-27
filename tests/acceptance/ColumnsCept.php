<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("UI columns functionality for ChatGPT-style interface");

$I->amOnPage('/columns-test');

$I->makeScreenshot('ai_style');