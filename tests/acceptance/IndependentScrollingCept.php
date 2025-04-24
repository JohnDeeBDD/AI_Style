<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That chat-sidebar and chat-main divs scroll independently");

$I->amOnPage('/test');

// Check for main UI divisions
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');

// Correct behavior:
// Our UI is designed to be similiar to ChatGPTs. There are two columns in ChatGPT. In our UI the #chat-sidebar and #chat-main are the two columns. The normal scroll bar on the right of the browser should be supressed, and if the size of the content exceeds the size of the viewport vertically, scroll bars should appear in #chat-sidebar and #chat-main. The comment form itself should be independent of the scroll. The comment form should hover with a higher z index above the content of #chat-main, and should reside near the bottom of the usable viewport: as dipicted in the screen shot of ChatGPT.