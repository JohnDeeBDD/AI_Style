<?php
/**
 * ScrollbarPositionCept.php
 *
 * Acceptance test for verifying the scrollbar position in the UI.
 *
 * This test checks:
 * 1. That the scrollbar is positioned correctly at the right edge of the viewport
 * 2. That the scrollbar alignment matches the expected design
 */

$I = new AcceptanceTester($scenario);
$I->wantToTest("Scrollbar position alignment");
$I->expect("The scrollbar should be positioned at the right edge of the viewport");
$I->expectTo("See the scrollbar aligned with the right side of the screen");

// Create test post with ChatGPT interface content
$I->comment('Creating test post for scrollbar position testing');
$postContent = '<p>This is a test post for scrollbar position verification. The theme will automatically generate the chat interface with proper scrollbar positioning.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->makeScreenshot('testpost-position');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost-position.png' target = '_blank'>available here</a>");

// Wait for the page to load completely
$I->waitForElementVisible(AcceptanceConfig::CHAT_MESSAGES, 10);
$I->waitForElementVisible(AcceptanceConfig::POST_CONTENT, 10);
$I->waitForElementVisible(AcceptanceConfig::FIXED_COMMENT_BOX, 10);

// Test scrollbar position at the right edge of viewport
$I->amGoingTo('Test if the scrollbar is positioned at the right edge of the viewport');
$I->expect('The scrollbar to be aligned with the right edge of the viewport');

// Get the scrollbar position and viewport width
$scrollbarPosition = $I->executeJS("
    const scrollableContent = document.querySelector('" . AcceptanceConfig::SCROLLABLE_CONTENT . "');
    if (!scrollableContent) return null;
    
    // We can't select pseudo-elements with querySelector
    // Just use the scrollableContent element's bounding rectangle
    const rect = scrollableContent.getBoundingClientRect();
    return {
        scrollbarRight: rect.right,
        viewportWidth: window.innerWidth
    };
");

// Verify the scrollbar is positioned at the viewport edge
$I->assertTrue(
    $scrollbarPosition['scrollbarRight'] == $scrollbarPosition['viewportWidth'],
    'Scrollbar should be positioned at the right edge of the viewport'
);
$I->makeScreenshot('scrollbar-position');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollbar-position.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

// Run this test with the command: "bin/codecept run acceptance ScrollbarPositionCept.php -vvv --html"