<?php

$I = new AcceptanceTester($scenario);
$I->wantToTest("Capture the appearance of the UI");
$I->amOnPage('http://localhost/test');
$I->makeScreenshot('ScreenCaptureCept');

// Screen shot can be found at http://localhost/wp-content/themes/ai_style/tests/_output/debug/ScreenCaptureCept.png