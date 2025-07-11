<?php
/**
 * ScrollbarVisableCept.php
 *
 * Acceptance test for verifying the scrollbar visibility behavior.
 *
 * This test checks:
 * 1. That the scrollbar is only visible when content requires scrolling
 * 2. That the scrollbar is hidden when content is small enough
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("Scrollbar visibility behavior");
$I->expect("The scrollbar should only be visible when content requires scrolling");
$I->expectTo("Not see a scrollbar when content is small enough");

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->makeScreenshot('testpost-visibility');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost-visibility.png' target = '_blank'>available here</a>");

$I->switchBetweenLinkedAnchorPosts($I);
// Wait for the page to load completely
$I->waitForElementVisible(AcceptanceConfig::CHAT_MESSAGES, 10);
$I->waitForElementVisible(AcceptanceConfig::POST_CONTENT, 10);
$I->waitForElementVisible(AcceptanceConfig::FIXED_COMMENT_BOX, 10);

// Test scrollbar visibility behavior
$I->amGoingTo('Test if the scrollbar is hidden when content is small enough not to need scrolling');
$I->expect('The scrollbar to be hidden when content does not require scrolling');

$I->executeJS("
    // Clear existing messages and add minimal content to simulate small content
    clearMessages();
    
    // Add a single small message to have minimal content
    addInterlocutorMessage('Small test message');
    
    return true;
");
$I->wait(1); // Wait for DOM updates

// Take a screenshot to visually verify the state
$I->makeScreenshot('scrollbar-minimal-content');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollbar-minimal-content.png' target = '_blank'>available here</a>");

// Check if scrollbar is visible by comparing scrollHeight and clientHeight
// If scrollHeight > clientHeight, a scrollbar is needed
// If they're equal, no scrollbar should be visible
$scrollbarCheck = $I->executeJS("
    const scrollableContent = document.querySelector('" . AcceptanceConfig::SCROLLABLE_CONTENT . "');
    if (!scrollableContent) {
        return { error: 'Scrollable content element not found' };
    }
    
    return {
        scrollHeight: scrollableContent.scrollHeight,
        clientHeight: scrollableContent.clientHeight,
        hasVerticalScrollbar: scrollableContent.scrollHeight > scrollableContent.clientHeight,
        computedStyle: window.getComputedStyle(scrollableContent).overflowY
    };
");

// Output detailed information about the scrollbar state for debugging
$I->comment('Scrollbar check results: ' . json_encode($scrollbarCheck));

// Check the computed style - should be 'auto' to allow scrollbar when needed
$I->assertEquals('auto', $scrollbarCheck['computedStyle'],
    'The scrollable content should have overflow-y: auto to allow scrollbar when needed');

// Even though overflow-y is auto, there should be no scrollbar visible when content is minimal

// Check if scrollHeight and clientHeight indicate a scrollbar is needed
$I->assertFalse($scrollbarCheck['hasVerticalScrollbar'],
    'A vertical scrollbar is present when content is minimal (scrollHeight > clientHeight)');

// Take another screenshot after the checks
$I->makeScreenshot('scrollbar-visibility-after-checks');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollbar-visibility-after-checks.png' target = '_blank'>available here</a>");

// Now test with long content that should show a scrollbar
$I->amGoingTo('Test if the scrollbar is visible when content is long enough to need scrolling');
$I->expect('The scrollbar to be visible when content requires scrolling');

$I->executeJS("
    // Clear existing messages and add a lot of content to simulate long content
    clearMessages();
    
    // Add multiple messages to create long content
    for (let i = 0; i < 20; i++) {
        addInterlocutorMessage('This is message ' + i + ' to create long content that requires scrolling. Adding more text to make it even longer.');
        addRespondentMessage('This is a response to message ' + i + '. Adding more text to ensure the content is long enough to require scrolling.');
    }
    
    return true;
");
$I->wait(1); // Wait for DOM updates

// Take a screenshot to visually verify the state
$I->makeScreenshot('scrollbar-long-content');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollbar-long-content.png' target = '_blank'>available here</a>");

// Check if scrollbar is visible by comparing scrollHeight and clientHeight
$scrollbarCheck = $I->executeJS("
    const scrollableContent = document.querySelector('" . AcceptanceConfig::SCROLLABLE_CONTENT . "');
    if (!scrollableContent) {
        return { error: 'Scrollable content element not found' };
    }
    
    return {
        scrollHeight: scrollableContent.scrollHeight,
        clientHeight: scrollableContent.clientHeight,
        hasVerticalScrollbar: scrollableContent.scrollHeight > scrollableContent.clientHeight,
        computedStyle: window.getComputedStyle(scrollableContent).overflowY
    };
");

// Output detailed information about the scrollbar state for debugging
$I->comment('Scrollbar check results for long content: ' . json_encode($scrollbarCheck));

// Check the computed style - should be 'scroll' or 'auto' when content is long
$I->assertTrue(
    in_array($scrollbarCheck['computedStyle'], ['auto', 'scroll']),
    'The scrollable content should have overflow-y: auto or scroll with long content'
);

// Check if scrollHeight and clientHeight indicate a scrollbar is needed
$I->assertTrue($scrollbarCheck['hasVerticalScrollbar'],
    'A vertical scrollbar should be present when content is long (scrollHeight > clientHeight)');

// Take another screenshot after the checks
$I->makeScreenshot('scrollbar-visibility-long-content-after-checks');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollbar-visibility-long-content-after-checks.png' target = '_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance ScrollbarVisableCept.php -vvv --html"