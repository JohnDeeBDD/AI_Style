<?php
/**
 * FooterPositioningCept.php
 *
 * Acceptance test for verifying proper positioning of the comment box relative to the footer
 * in desktop mode and relative to the viewport bottom in mobile mode.
 *
 * This test framework:
 * 1. Automatically detects the current breakpoint from the test suite configuration
 * 2. Executes breakpoint-specific test logic (mobile or desktop)
 * 3. Tests desktop functionality: comment box should sit on top of the footer
 * 4. Tests mobile functionality: comment box should sit at the bottom of the viewport with no space
 * 5. Validates that there is no footer visible in mobile mode when sidebar is closed
 * 6. Takes breakpoint-specific screenshots for visual verification
 *
 * Expected Behavior:
 * - Desktop: Comment box positioned correctly on top of footer with proper spacing
 * - Mobile: Comment box positioned at bottom of viewport with no gap, no footer visible
 *
 * Current Bug (this test should FAIL):
 * - Mobile: There is unwanted space between comment box and bottom of viewport
 *
 * Breakpoint Support:
 * - Desktop: Comment box sits on footer (window width >= 768px)
 * - Mobile: Comment box sits at viewport bottom, no footer (window width < 768px)
 *
 * Usage Examples:
 * - Desktop: bin/codecept run acceptance FooterPositioningCept.php --env desktop_full_hd -vvv --html --xml
 * - Mobile: bin/codecept run acceptance FooterPositioningCept.php --env iphone_se -vvv --html --xml
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("Footer positioning behavior across desktop and mobile breakpoints");

// Create test post for footer positioning testing
$I->comment('Creating test post for footer positioning testing');
$postContent = '<p>This is a test post for footer positioning verification. The theme will automatically generate the chat interface with comment box positioning tests.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('footer-positioning-test', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current breakpoint configuration
$windowSize = $I->getWindowSize();
$I->comment("Testing footer positioning with window size: {$windowSize}");

// Determine breakpoint based on window width (mobile < 768px, desktop >= 768px)
$isMobile = isMobileBreakpoint($I);
$breakpoint = $isMobile ? 'mobile' : 'desktop';
$I->comment("Detected breakpoint: {$breakpoint}");

// Execute breakpoint-specific test logic
if ($isMobile) {
    $I->comment("Executing mobile footer positioning tests");
    executeMobileFooterPositioningTests($I);
} else {
    $I->comment("Executing desktop footer positioning tests");
    executeDesktopFooterPositioningTests($I);
}

// Take a breakpoint-specific screenshot
$screenshotName = 'footer-positioning-' . $breakpoint;
$I->makeScreenshot($screenshotName);
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

/**
 * Helper functions for breakpoint detection
 */

/**
 * Determine if we're in mobile breakpoint (window width < 768px)
 * @param AcceptanceTester $I
 * @return bool
 */
function isMobileBreakpoint($I) {
    try {
        $windowWidth = $I->executeJS("return window.innerWidth;");
        $I->comment("Window width: {$windowWidth}px");
        return $windowWidth < 768;
    } catch (Exception $e) {
        $I->comment("Failed to detect window width, assuming desktop: " . $e->getMessage());
        return false;
    }
}

/**
 * Execute desktop footer positioning tests (window width >= 768px)
 * Tests that comment box is properly positioned on top of the footer
 * @param AcceptanceTester $I
 */
function executeDesktopFooterPositioningTests($I) {
    $I->comment("Running desktop footer positioning tests");
    
    // Ensure sidebar is closed using the global function
    $I->executeJS("
        // Check if sidebar is currently visible and hide it if needed
        if (typeof isSidebarVisible === 'function' && typeof toggleSidebarVisibility === 'function') {
            if (isSidebarVisible()) {
                toggleSidebarVisibility();
                console.log('Sidebar hidden for desktop footer positioning test using global function');
            } else {
                console.log('Sidebar already hidden for desktop footer positioning test');
            }
        } else {
            console.warn('Global sidebar functions not available, falling back to direct manipulation');
            // Fallback: direct manipulation if global functions aren't available
            const sidebar = document.getElementById('chat-sidebar');
            if (sidebar) {
                sidebar.style.width = '0';
                sidebar.style.minWidth = '0';
                sidebar.style.paddingLeft = '0';
                sidebar.style.paddingRight = '0';
                sidebar.classList.add('sidebar-hidden');
                sidebar.style.overflow = 'hidden';
                console.log('Sidebar hidden using fallback method');
            }
        }
    ");
    
    $I->wait(3); // Wait for animation
    
    // Verify that both footer and comment box are visible in desktop mode
    $I->seeElement(AcceptanceConfig::SITE_FOOTER);
    $I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX);
    
    // Test that comment box is positioned correctly relative to footer
    $commentBoxBottom = $I->executeJS("
        const commentBox = document.getElementById('fixed-comment-box');
        if (!commentBox) return null;
        const rect = commentBox.getBoundingClientRect();
        return rect.bottom;
    ");
    
    $footerTop = $I->executeJS("
        const footer = document.querySelector('.site-footer');
        if (!footer) return null;
        const rect = footer.getBoundingClientRect();
        return rect.top;
    ");
    
    $I->comment("Comment box bottom: {$commentBoxBottom}px, Footer top: {$footerTop}px");
    
    // In desktop mode, comment box should be positioned on top of footer
    // The comment box bottom should be at or very close to the footer top
    $tolerance = 5; // Allow 5px tolerance for proper positioning
    $I->assertTrue(
        abs($commentBoxBottom - $footerTop) <= $tolerance,
        "Desktop: Comment box should be positioned on top of footer. Comment box bottom: {$commentBoxBottom}px, Footer top: {$footerTop}px"
    );
    
    $I->comment("✓ Desktop footer positioning test passed - comment box correctly positioned on footer");
}

/**
 * Execute mobile footer positioning tests (window width < 768px)
 * Tests that comment box is positioned at bottom of viewport with no space, and no footer is visible
 * @param AcceptanceTester $I
 */
function executeMobileFooterPositioningTests($I) {
    $I->comment("Running mobile footer positioning tests");
    
    // Ensure sidebar is closed using the global function
    $I->executeJS("
        // Check if sidebar is currently visible and hide it if needed
        if (typeof isSidebarVisible === 'function' && typeof toggleSidebarVisibility === 'function') {
            if (isSidebarVisible()) {
                toggleSidebarVisibility();
                console.log('Sidebar hidden for mobile footer positioning test using global function');
            } else {
                console.log('Sidebar already hidden for mobile footer positioning test');
            }
        } else {
            console.warn('Global sidebar functions not available, falling back to direct manipulation');
            // Fallback: direct manipulation if global functions aren't available
            const sidebar = document.getElementById('chat-sidebar');
            if (sidebar) {
                // Mobile: Use overlay behavior
                sidebar.classList.remove('sidebar-visible');
                sidebar.style.left = '-100%';
                sidebar.style.overflow = 'hidden';
                
                // Update element visibility for mobile portrait mode
                const commentForm = document.getElementById('fixed-comment-box');
                const footer = document.querySelector('.site-footer');
                const isHighZoomOrMobilePortrait = window.innerWidth <= 480 && window.innerHeight > window.innerWidth;
                
                if (isHighZoomOrMobilePortrait) {
                    if (commentForm) commentForm.style.display = 'block';
                    if (footer) footer.style.display = 'none';
                } else {
                    if (commentForm) commentForm.style.display = 'block';
                    if (footer) footer.style.display = 'block';
                }
                
                console.log('Sidebar hidden using fallback method');
            }
        }
    ");
    
    $I->wait(1); // Wait for animation
    
    // Verify that footer is NOT visible in mobile mode
    $I->dontSeeElement(AcceptanceConfig::SITE_FOOTER);
    
    // Verify that comment box IS visible in mobile mode
    $I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX);
    
    // Test that comment box is positioned at the bottom of the viewport with no space
    $commentBoxBottom = $I->executeJS("
        const commentBox = document.getElementById('fixed-comment-box');
        if (!commentBox) return null;
        const rect = commentBox.getBoundingClientRect();
        return rect.bottom;
    ");
    
    $viewportHeight = $I->executeJS("return window.innerHeight;");
    
    $I->comment("Comment box bottom: {$commentBoxBottom}px, Viewport height: {$viewportHeight}px");
    
    // In mobile mode, comment box should be positioned at the very bottom of viewport
    // The comment box bottom should match the viewport height (no space)
    $tolerance = 2; // Allow 2px tolerance for proper positioning
    $gapFromBottom = $viewportHeight - $commentBoxBottom;
    
    $I->comment("Gap from viewport bottom: {$gapFromBottom}px (should be 0 or very close to 0)");
    
    // THIS TEST SHOULD CURRENTLY FAIL because there's a bug with spacing
    $I->assertTrue(
        $gapFromBottom <= $tolerance,
        "Mobile: Comment box should be positioned at bottom of viewport with no space. Gap from bottom: {$gapFromBottom}px (tolerance: {$tolerance}px)"
    );
    
    $I->comment("✓ Mobile footer positioning test passed - comment box correctly positioned at viewport bottom");
}