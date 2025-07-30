<?php

$I = new AcceptanceTester($scenario);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// REQUIRED: Enforce 100% zoom after navigation
$I->ensureDesktop100Zoom();

// /testpost/ Contains 8 comments
// If cacbotData.comment_count > 0 then the #scrollable-content should be scrolled to the bottom for the user upon page load.