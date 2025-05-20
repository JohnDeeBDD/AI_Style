<?php

$I = new AcceptanceTester($scenario);
$I->wantToTest("Scrollbar position issue");
$I->expect("The scrollbar should be positioned at the right edge of the viewport");
$I->expectTo("See the scrollbar aligned with the right side of the screen");

$I->amOnUrl("http://localhost");
$I->loginAsAdmin(); //This is a WordPress site
$I->amOnPage('/testpost');
$I->makeScreenshot('testpost-position');

// Wait for the page to load completely
$I->waitForElementVisible('#chat-messages', 10);
$I->waitForElementVisible('#post-content-1', 10);
$I->waitForElementVisible('#fixed-comment-box', 10);

// Test Bug #2: Scrollbar position is not at the right edge of viewport
$I->amGoingTo('Test if the scrollbar is positioned at the right edge of the viewport');
$I->expect('The scrollbar to be aligned with the right edge of the viewport');

// Get the scrollbar position and viewport width
$scrollbarPosition = $I->executeJS("
    const scrollableContent = document.querySelector('#scrollable-content');
    if (!scrollableContent) return null;
    
    // We can't select pseudo-elements with querySelector
    // Just use the scrollableContent element's bounding rectangle
    const rect = scrollableContent.getBoundingClientRect();
    return {
        scrollbarRight: rect.right,
        viewportWidth: window.innerWidth
    };
");

// This should fail because the scrollbar is not positioned at the viewport edge
$I->assertTrue(
    $scrollbarPosition['scrollbarRight'] == $scrollbarPosition['viewportWidth'],
    'Scrollbar should be positioned at the right edge of the viewport'
);
$I->makeScreenshot('scrollbar-position-bug');