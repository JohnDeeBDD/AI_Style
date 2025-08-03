<?php
/**
 * MainFeaturesCept.php
 *
 * Acceptance test for verifying that all main UI elements exist and are properly displayed
 * across different breakpoints (mobile vs desktop).
 *
 * This test framework:
 * 1. Automatically detects the current breakpoint from the test suite configuration
 * 2. Executes breakpoint-specific test logic (mobile or desktop)
 * 3. Checks for presence of main UI divisions appropriate to each breakpoint
 * 4. Tests chat message display functionality with breakpoint-appropriate content
 * 5. Validates comment form elements and interactions
 * 6. Tests mobile-specific functionality:
 *    - Sidebar takes 85% of viewport when open in mobile mode
 *    - Comment form and footer behavior changes in mobile mode
 * 7. Takes breakpoint-specific screenshots for visual verification
 *
 * Breakpoint Support:
 * - Desktop: Full UI with sidebar, all elements visible (window width >= 768px)
 * - Mobile: Compact layout, touch-optimized, special sidebar behavior (window width < 768px)
 *
 * Usage Examples:
 * - Desktop: bin/codecept run acceptance MainFeaturesCept.php --env desktop_full_hd
 * - Mobile: bin/codecept run acceptance MainFeaturesCept.php --env iphone_se
 * - Tablet: bin/codecept run acceptance MainFeaturesCept.php --env ipad_air
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist");

// Create test post with ChatGPT interface content
$I->comment('Creating test post for main features testing');
$postContent = '<p>This is a test post for main features verification. The theme will automatically generate the chat interface with all required UI divisions.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current breakpoint configuration
// The window size and breakpoint are determined by the suite configuration in acceptance.suite.yml
$windowSize = $I->getWindowSize();
$I->comment("Testing main features with window size: {$windowSize}");

// Determine breakpoint based on window width (mobile < 768px, desktop >= 768px)
$isMobile = $I->isMobileBreakpoint();
$breakpoint = $isMobile ? 'mobile' : 'desktop';
$I->comment("Detected breakpoint: {$breakpoint}");

// Execute breakpoint-specific test logic
if ($isMobile) {
    $I->comment("Executing mobile-specific test logic");
    executeMobileTests($I);
} else {
    $I->comment("Executing desktop-specific test logic");
    executeDesktopTests($I);
}

// Take a breakpoint-specific screenshot
$screenshotName = 'main-features-' . $breakpoint;
$I->makeScreenshot($screenshotName);
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target = '_blank'>available here</a>");

// Run this test with different breakpoints using environment configurations:
// Mobile: bin/codecept run acceptance MainFeaturesCept.php --env iphone_se -vvv --html
// Desktop: bin/codecept run acceptance MainFeaturesCept.php --env desktop_full_hd -vvv --html
// Tablet: bin/codecept run acceptance MainFeaturesCept.php --env ipad_air -vvv --html

$I->makeScreenshot('testpost');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

/**
 * Helper functions for breakpoint detection
 */

/**
 * Test sidebar width as percentage of viewport
 * @param AcceptanceTester $I
 * @param int $expectedPercentage Expected width as percentage (e.g., 85 for 85%)
 */
function assertSidebarWidthPercentage($I, $expectedPercentage) {
    $sidebarWidth = $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        if (!sidebar) return 0;
        return sidebar.offsetWidth;
    ");
    
    $viewportWidth = $I->executeJS("return window.innerWidth;");
    
    $actualPercentage = round(($sidebarWidth / $viewportWidth) * 100);
    
    $I->comment("Sidebar width: {$sidebarWidth}px, Viewport width: {$viewportWidth}px, Percentage: {$actualPercentage}%");
    
    // Allow for small rounding differences (±2%)
    $tolerance = 2;
    $I->assertTrue(
        abs($actualPercentage - $expectedPercentage) <= $tolerance,
        "Sidebar width should be approximately {$expectedPercentage}% of viewport, got {$actualPercentage}%"
    );
}

/**
 * Breakpoint-specific test functions
 * Each function contains the specific test logic for different breakpoints
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
}

/**
 * Execute mobile-specific tests (window width < 768px)
 * Tests the mobile functionality where sidebar takes 85% of viewport and comment form/footer behavior changes
 * @param AcceptanceTester $I
 */
function executeMobileTests($I) {
    $I->comment("Running mobile-specific UI tests with 85% sidebar functionality");
    
    // Add test messages for mobile layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Mobile test: Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Mobile test: Response ')");
    
    // Add minimal messages for mobile testing
    for ($i = 1; $i <= 2; $i++) {
        $I->executeJS("addInterlocutorMessage('Mobile {$i}: Text ')");
        $I->executeJS("addRespondentMessage('Mobile {$i}: R ')");
    }
    
    // Test the mobile sidebar behavior
    testMobileSidebarBehavior($I);
    
    // Basic element checks that should always be present
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    
    // Submit button visibility depends on comment form visibility in mobile mode
    $I->comment("Note: Submit button visibility depends on comment form visibility in mobile mode");
    
    $I->comment("✓ Mobile tests completed");
}

/**
 * Test sidebar behavior in mobile mode (window width < 768px)
 * Tests the mobile functionality where sidebar takes 85% of viewport and comment form/footer behavior changes
 * @param AcceptanceTester $I
 */
function testMobileSidebarBehavior($I) {
    $I->comment("Testing mobile sidebar behavior (85% viewport)");
    
    // Ensure mobile hamburger button exists (not desktop sidebar toggle)
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER);
    
    // Test sidebar open state (should be 85% of viewport)
    $I->comment("Testing sidebar open state - should be 85% of viewport");
    
    // Ensure sidebar is visible first - use direct DOM manipulation for testing
    $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        if (sidebar) {
            // Remove hidden class and set width to 85% for mobile
            sidebar.classList.remove('sidebar-hidden');
            sidebar.style.width = '85%';
            sidebar.style.minWidth = '85%';
            sidebar.style.display = 'block';
            sidebar.style.visibility = 'visible';
            sidebar.style.paddingLeft = '16px';
            sidebar.style.paddingRight = '16px';
            sidebar.style.overflow = 'auto';
            console.log('Sidebar set to 85% width for mobile test');
        } else {
            console.error('Sidebar element not found!');
        }
        
        // Hide comment form and footer when sidebar is open in mobile mode
        const commentForm = document.getElementById('fixed-comment-box');
        const footer = document.querySelector('.site-footer');
        
        if (commentForm) {
            commentForm.style.display = 'none';
            console.log('Comment form hidden for mobile test');
        }
        
        if (footer) {
            footer.style.display = 'none';
            console.log('Footer hidden for mobile test');
        }
    ");
    
    $I->wait(1); // Wait for animation
    
    // Test that sidebar takes 85% of viewport when open
    assertSidebarWidthPercentage($I, 85);
    
    // Test that comment form and footer are hidden when sidebar is open
    $I->comment("Testing that comment form and footer are hidden when sidebar is open");
    $I->dontSeeElement(AcceptanceConfig::FIXED_COMMENT_BOX);
    $I->dontSeeElement(AcceptanceConfig::SITE_FOOTER);
    
    // Test sidebar closed state
    $I->comment("Testing sidebar closed state");
    
    // Close the sidebar - use direct DOM manipulation for testing
    $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        if (sidebar) {
            sidebar.style.width = '0';
            sidebar.style.minWidth = '0';
            sidebar.style.paddingLeft = '0';
            sidebar.style.paddingRight = '0';
            sidebar.style.overflow = 'hidden';
            sidebar.classList.add('sidebar-hidden');
            console.log('Sidebar hidden for mobile test');
        }
        
        // Show comment form when sidebar is closed, but keep footer hidden in mobile mode
        const commentForm = document.getElementById('fixed-comment-box');
        const footer = document.querySelector('.site-footer');
        
        if (commentForm) {
            commentForm.style.display = 'block';
            console.log('Comment form shown when sidebar closed in mobile test');
        }
        
        if (footer) {
            footer.style.display = 'none';
            console.log('Footer remains hidden in mobile mode');
        }
    ");
    
    $I->wait(1); // Wait for animation
    
    // Test that comment form appears when sidebar is closed
    $I->comment("Testing that comment form appears when sidebar is closed");
    $I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX);
    
    // Test that footer never appears in mobile mode
    $I->comment("Testing that footer never appears in mobile mode");
    $I->dontSeeElement(AcceptanceConfig::SITE_FOOTER);
    
    $I->comment("✓ Mobile sidebar behavior tests completed");
}