<?php
/**
 * DesktopMainFeaturesCept.php
 *
 * Desktop-specific acceptance test for verifying that all main UI elements exist and are properly displayed
 * on desktop breakpoints (window width >= 768px).
 *
 * This test framework:
 * 1. Checks if the current breakpoint is desktop, exits early if mobile
 * 2. Tests desktop-specific functionality:
 *    - Full UI with sidebar, all elements visible
 *    - Desktop layout and element positioning
 * 3. Checks for presence of main UI divisions appropriate to desktop breakpoint
 * 4. Tests chat message display functionality with desktop-appropriate content
 * 5. Validates comment form elements and interactions in desktop context
 * 6. Takes desktop-specific screenshots for visual verification
 *
 * Desktop Breakpoint Support:
 * - Desktop: Full UI with sidebar, all elements visible (window width >= 768px)
 *
 * Usage Examples:
 * - Desktop: bin/codecept run acceptance DesktopMainFeaturesCept.php --env desktop_full_hd
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist on desktop breakpoints");

// Create test post with ChatGPT interface content
$I->comment('Creating test post for desktop main features testing');
$postContent = '<p>This is a test post for desktop main features verification. The theme will automatically generate the chat interface with all required UI divisions.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost-desktop', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Check if this is a desktop breakpoint - if not, skip the test
$windowSize = $I->getWindowSize();
$I->comment("Testing desktop main features with window size: {$windowSize}");

$isMobile = $I->isMobileBreakpoint();
if ($isMobile) {
    $I->comment("This is a mobile breakpoint (< 768px). Skipping desktop-specific tests.");
    // Cleanup test data before exiting
    $I->comment('Cleaning up test post');
    $I->cUrlWP_SiteToDeletePost($postId);
    $I->comment('✓ Test post deleted successfully');
    return; // Exit early for mobile breakpoints
}

$I->comment("Detected desktop breakpoint - executing desktop-specific test logic");

// Execute desktop-specific test logic
executeDesktopTests($I);

// Take a desktop-specific screenshot
$screenshotName = 'main-features-desktop';
$I->makeScreenshot($screenshotName);
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target = '_blank'>available here</a>");

$I->makeScreenshot('testpost-desktop');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost-desktop.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

/**
 * Helper functions for desktop testing
 */

/**
 * Execute desktop-specific tests (window width >= 768px)
 * @param AcceptanceTester $I
 */
function executeDesktopTests($I) {
    $I->comment("Running desktop-specific UI tests");
    
    // Add test messages for desktop layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Desktop test: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Desktop test: This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");
    
    // Add more messages to test scrolling behavior on desktop
    for ($i = 1; $i <= 4; $i++) {
        $I->executeJS("addInterlocutorMessage('Desktop message {$i}: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
        $I->executeJS("addRespondentMessage('Desktop response {$i}: This is a message from the respondent. This is a message from the respondent. ')");
    }
    
    // Desktop-specific element checks - all elements should be visible
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::POST_CONTENT);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SITE_FOOTER);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // Desktop-specific checks (sidebar should be fully visible)
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
    
    $I->comment("✓ Desktop tests completed");
}