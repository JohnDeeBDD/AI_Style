<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That main UI divisions have correct relative layout (floats/positions)");

$I->amOnPage('/test');

// Check for main UI divisions
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');
$I->seeElement('#chat-messages');
$I->seeElement('#chat-input');

// Check that #chat-sidebar is to the left of #chat-main
$sidebarRect = $I->executeJS("return document.querySelector('#chat-sidebar').getBoundingClientRect();");
$mainRect = $I->executeJS("return document.querySelector('#chat-main').getBoundingClientRect();");
$I->assertTrue($sidebarRect['right'] <= $mainRect['left'], '#chat-sidebar should be to the left of #chat-main');

// Check that #chat-main is to the right of #chat-sidebar
$I->assertTrue($mainRect['left'] >= $sidebarRect['right'], '#chat-main should be to the right of #chat-sidebar');

// Check that #chat-messages is inside #chat-main
$messagesRect = $I->executeJS("return document.querySelector('#chat-messages').getBoundingClientRect();");
$I->assertTrue(
    $messagesRect['left'] >= $mainRect['left'] &&
    $messagesRect['right'] <= $mainRect['right'] &&
    $messagesRect['top'] >= $mainRect['top'] &&
    $messagesRect['bottom'] <= $mainRect['bottom'],
    '#chat-messages should be inside #chat-main'
);

// Check that #chat-input is below #chat-messages
$inputRect = $I->executeJS("return document.querySelector('#chat-input').getBoundingClientRect();");
$I->assertTrue($inputRect['top'] >= $messagesRect['bottom'], '#chat-input should be below #chat-messages');