<?php

$I = new AcceptanceTester($scenario);
$I->wantToTest("Scrollbar visibility issue");
$I->expect("The scrollbar should only be visible when content requires scrolling");
$I->expectTo("Not see a scrollbar when content is small enough");

$I->amOnUrl("http://localhost");
$I->loginAsAdmin(); //This is a WordPress site
$I->amOnPage('/testpost');
$I->makeScreenshot('testpost-visibility');

// Wait for the page to load completely
$I->waitForElementVisible('#chat-messages', 10);
$I->waitForElementVisible('#post-content-1', 10);
$I->waitForElementVisible('#fixed-comment-box', 10);

// Test Bug #1: Scrollbar is visible even when content doesn't need scrolling
$I->amGoingTo('Test if the scrollbar is hidden when content is small enough not to need scrolling');
$I->expect('The scrollbar to be hidden when content does not require scrolling');

$I->executeJS("
    // Temporarily reduce the content height to simulate small content
    document.querySelector('#chat-messages').style.height = '50px';
    document.querySelector('#post-content-1').style.height = '50px';
    return true;
");
$I->wait(1); // Wait for DOM updates

// This should fail because the scrollbar is always visible regardless of content size
$I->dontSeeElement('.scrollbar-visible', ['css' => 'overflow-y: scroll']);
$I->makeScreenshot('scrollbar-always-visible-bug');

// Reset content height
$I->executeJS("
    document.querySelector('#chat-messages').style.height = '';
    document.querySelector('#post-content-1').style.height = '';
    return true;
");