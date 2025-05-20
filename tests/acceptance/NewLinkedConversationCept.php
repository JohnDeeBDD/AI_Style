<?php

//Setup test (this might take a minute):
chdir("/var/www/html/wp-content/plugins/cacbot");
exec("bin/codecept run acceptance AnchorPostCept.php -vvv --html --xml");

$I = new AcceptanceTester($scenario);
$I->amOnUrl("http://localhost");
$I->loginAsAdmin();
$I->amOnPage("/testpost");
$I->see("Who was the President of the United States in 2003?");
$I->click("New");
$I->dontSee("Who was the President of the United States in 2003?");
$I->reloadPage();

$I->expect("the previous API call to unlink the conversation. There should not be any comments.");
//Fails:
$I->dontSee("Who was the President of the United States in 2003?");