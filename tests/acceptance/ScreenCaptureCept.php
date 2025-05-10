<?php

$I = new AcceptanceTester($scenario);
$I->wantToTest("Capture the appearance of the UI");
$I->amOnUrl("http://localhost");
$I->loginAsAdmin(); //This is a WordPress site
$I->amOnPage('/testpost');
$I->makeScreenshot('testpost');
// Run this test with the command: "bin/codecept run acceptance ScreenCaptureCept.php -vvv --html"
// Screen shot can be found at: http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost.png