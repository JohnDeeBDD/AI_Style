<?php

$I = new AcceptanceTester($scenario);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();
$I->comment("Testing startup focus position for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// /testpost/ Contains 8 comments
// If cacbotData.comment_count > 0 then the #scrollable-content should be scrolled to the bottom for the user upon page load.