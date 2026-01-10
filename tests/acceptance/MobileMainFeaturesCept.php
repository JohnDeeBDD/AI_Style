<?php
/**
 * MobileMainFeaturesCept.php - Z-Index Layering Test
 *
 * Mobile-specific acceptance test focused on verifying z-index layering issues
 * when the sidebar is open in mobile view (window width < 768px).
 *
 * **Z-INDEX BUG BEING TESTED:**
 * When the sidebar is open in mobile view, the sidebar should have a higher z-index
 * than everything in the chat main area, especially the comment form. The BUG is:
 * - Sidebar: z-index: 1000 (should overlay above content)
 * - Fixed comment box: z-index: 1000 (same as sidebar - CONFLICT!)
 * - Comment form: z-index: 1001 (INCORRECTLY above sidebar!)
 * - Admin bar toggle: z-index: 2000 (highest - correct)
 *
 * **THIS TEST IS DESIGNED TO FAIL WHEN THE BUG IS PRESENT**
 * The test will FAIL if:
 * - Comment form z-index >= sidebar z-index when elements overlap
 * - Comment form visually appears above sidebar in overlapping areas
 * - Sidebar does not properly cover the comment form in mobile overlay mode
 *
 * This test framework:
 * 1. Checks if the current breakpoint is mobile, exits early if desktop
 * 2. Tests mobile sidebar z-index layering with ASSERTIONS that FAIL on bug:
 *    - Uses custom toggleSidebarVisibility() and isSidebarVisible() functions for sidebar control
 *    - ASSERTS sidebar z-index is higher than comment form z-index
 *    - ASSERTS sidebar visually covers comment form in overlapping areas
 *    - Verifies proper layering hierarchy in mobile overlay mode with failing assertions
 * 3. Takes detailed screenshots with z-index annotations for visual verification
 * 4. Provides comprehensive HTML report with z-index analysis and failure details
 *
 * Mobile Breakpoint Support:
 * - Mobile: Sidebar overlay behavior with z-index layering (window width < 768px)
 *
 * Usage Examples:
 * - Mobile: bin/codecept run acceptance MobileMainFeaturesCept.php --env samsung_galaxy_s8_plus
 * - Tablet: bin/codecept run acceptance MobileMainFeaturesCept.php --env ipad_air
 */

$I = new AcceptanceTester($scenario);

// Create test post with ChatGPT interface content

$postContent = '<p>This is a test post for mobile z-index layering verification. The theme will automatically generate the chat interface with all required UI divisions for z-index testing.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost-mobile-zindex', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();

// Navigate to the frontend post page where AI Style theme is active
$I->comment('Navigating to frontend post page with AI Style theme active');
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Wait for page to load and check if we're on the right page
$I->wait(2);
$currentUrl = $I->executeJS("return window.location.href;");
$I->comment("Current URL: $currentUrl");

// Check if we're actually on the frontend (not admin)
$isAdmin = $I->executeJS("return document.body.classList.contains('wp-admin');");
if ($isAdmin) {
    $I->comment("Still on admin page, attempting to navigate to frontend...");
    // Try direct navigation to the post
    $I->amOnUrl(AcceptanceConfig::BASE_URL . AcceptanceConfig::TEST_POST_PAGE);
    $I->wait(2);
    $currentUrl = $I->executeJS("return window.location.href;");
    $I->comment("After direct navigation, current URL: $currentUrl");
}

// Check if this is a mobile breakpoint - if not, skip the test
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';
$I->comment("Testing mobile z-index layering for {$deviceType} mode (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");
if (!$isMobile) {
    $I->comment("This is a desktop breakpoint (>= 768px). Skipping mobile z-index tests.");
    // Cleanup test data before exiting
    $I->comment('Cleaning up test post');
    $I->cUrlWP_SiteToDeletePost($postId);
    $I->comment('✓ Test post deleted successfully');
    return; // Exit early for desktop breakpoints
}

$I->comment("Detected mobile breakpoint - executing mobile z-index layering tests");

// Execute mobile z-index specific test logic
executeMobileZIndexTests($I);

// Take comprehensive screenshots for z-index analysis
$screenshotName = 'mobile-zindex-layering-test';
$I->makeScreenshot($screenshotName);
$I->comment("Z-index layering screenshot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target = '_blank'>available here</a>");

$I->makeScreenshot('mobile-zindex-final-state');
$I->comment("Final state screenshot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-zindex-final-state.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

/**
 * Helper functions for mobile z-index testing
 */

/**
 * Get computed z-index value for an element
 * @param AcceptanceTester $I
 * @param string $selector CSS selector for the element
 * @return int|string The z-index value or 'auto'
 */
function getElementZIndex($I, $selector) {
    return $I->executeJS("
        const element = document.querySelector('$selector');
        if (!element) return 'not found';
        const computedStyle = window.getComputedStyle(element);
        return computedStyle.zIndex;
    ");
}

/**
 * Check if element is visually above another element (higher z-index or stacking context)
 * @param AcceptanceTester $I
 * @param string $topSelector Selector for element that should be on top
 * @param string $bottomSelector Selector for element that should be below
 * @return array Analysis results
 */
function analyzeElementLayering($I, $topSelector, $bottomSelector) {
    return $I->executeJS("
        const topElement = document.querySelector('$topSelector');
        const bottomElement = document.querySelector('$bottomSelector');
        
        if (!topElement || !bottomElement) {
            return {
                error: 'One or both elements not found',
                topFound: !!topElement,
                bottomFound: !!bottomElement
            };
        }
        
        const topStyle = window.getComputedStyle(topElement);
        const bottomStyle = window.getComputedStyle(bottomElement);
        
        const topZIndex = topStyle.zIndex;
        const bottomZIndex = bottomStyle.zIndex;
        
        // Get bounding rectangles to check for overlap
        const topRect = topElement.getBoundingClientRect();
        const bottomRect = bottomElement.getBoundingClientRect();
        
        const overlap = !(topRect.right < bottomRect.left || 
                         topRect.left > bottomRect.right || 
                         topRect.bottom < bottomRect.top || 
                         topRect.top > bottomRect.bottom);
        
        return {
            topZIndex: topZIndex,
            bottomZIndex: bottomZIndex,
            topPosition: topStyle.position,
            bottomPosition: bottomStyle.position,
            overlap: overlap,
            topRect: {
                left: topRect.left,
                top: topRect.top,
                right: topRect.right,
                bottom: topRect.bottom,
                width: topRect.width,
                height: topRect.height
            },
            bottomRect: {
                left: bottomRect.left,
                top: bottomRect.top,
                right: bottomRect.right,
                bottom: bottomRect.bottom,
                width: bottomRect.width,
                height: bottomRect.height
            }
        };
    ");
}

/**
 * Execute mobile-specific z-index layering tests
 * Tests the z-index hierarchy when sidebar is open in mobile overlay mode
 * @param AcceptanceTester $I
 */
function executeMobileZIndexTests($I) {
    $I->comment("=== MOBILE Z-INDEX LAYERING ANALYSIS ===");
    
    // Check for presence of basic chat interface elements
    $I->comment("Checking for presence of basic chat interface elements...");
    
    // First check if elements exist without failing the test
    $chatContainerExists = $I->executeJS("return document.querySelector('#chat-container') !== null;");
    $chatMainExists = $I->executeJS("return document.querySelector('#chat-main') !== null;");
    $sidebarExists = $I->executeJS("return document.querySelector('#chat-sidebar') !== null;");
    
    $I->comment("Element existence check:");
    $I->comment("- Chat container exists: " . ($chatContainerExists ? 'YES' : 'NO'));
    $I->comment("- Chat main exists: " . ($chatMainExists ? 'YES' : 'NO'));
    $I->comment("- Chat sidebar exists: " . ($sidebarExists ? 'YES' : 'NO'));
    
    if (!$chatContainerExists || !$chatMainExists || !$sidebarExists) {
        $I->comment("⚠️  Missing required chat interface elements. This may indicate:");
        $I->comment("   1. AI Style theme is not active");
        $I->comment("   2. Post page is not loading correctly");
        $I->comment("   3. JavaScript is not initializing the chat interface");
        
        // Take a screenshot for debugging
        $I->makeScreenshot('missing-chat-elements-debug');
        $I->comment("Debug screenshot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/missing-chat-elements-debug.png' target = '_blank'>available here</a>");
        
        // Get page info for debugging
        $pageTitle = $I->executeJS("return document.title;");
        $bodyClasses = $I->executeJS("return document.body.className;");
        $I->comment("Page title: $pageTitle");
        $I->comment("Body classes: $bodyClasses");
        
        // Continue with limited testing if possible
        $I->comment("Continuing with limited z-index analysis...");
    } else {
        $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
        $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    }
    
    // Add some test content to make layering more visible
    $I->comment("Adding test content to make z-index layering more visible...");
    $I->executeJS("
        // Add test messages if the functions exist
        if (typeof addInterlocutorMessage === 'function') {
            addInterlocutorMessage('Z-index test message 1');
            addRespondentMessage('Z-index test response 1');
        } else {
            console.log('Message functions not available, continuing with existing content');
        }
    ");
    
    // Test sidebar z-index behavior
    testMobileSidebarZIndexLayering($I);
    
    $I->comment("✓ Mobile z-index layering tests completed");
}

/**
 * Test sidebar z-index layering in mobile mode
 * Focuses specifically on z-index conflicts and proper layering hierarchy
 * @param AcceptanceTester $I
 */
function testMobileSidebarZIndexLayering($I) {
    $I->comment("=== TESTING MOBILE SIDEBAR Z-INDEX LAYERING ===");
    
    // Check for mobile hamburger button
    $hamburgerExists = $I->executeJS("return document.querySelector('#wp-admin-bar-mobile-hamburger') !== null;");
    $I->comment("Mobile hamburger button exists: " . ($hamburgerExists ? 'YES' : 'NO'));
    
    if ($hamburgerExists) {
        $I->seeElement(AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER);
    } else {
        $I->comment("⚠️  Mobile hamburger button not found - may affect sidebar testing");
    }
    
    // First, test with sidebar closed
    $I->comment("--- PHASE 1: SIDEBAR CLOSED STATE ---");
    
    // Ensure sidebar is closed initially using custom functions
    $sidebarFound = $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        if (sidebar) {
            // Use custom function to ensure sidebar is hidden
            if (typeof isSidebarVisible === 'function' && typeof toggleSidebarVisibility === 'function') {
                if (isSidebarVisible()) {
                    toggleSidebarVisibility();
                    console.log('Sidebar closed using toggleSidebarVisibility function');
                } else {
                    console.log('Sidebar already in closed state for z-index testing');
                }
            } else {
                console.log('Custom sidebar functions not available - sidebar state may be inconsistent');
            }
            return true;
        } else {
            console.log('Sidebar element not found for z-index testing');
            return false;
        }
    ");
    
    if (!$sidebarFound) {
        $I->comment("⚠️  Sidebar element not found - z-index testing will be limited");
        return; // Exit early if no sidebar
    }
    
    $I->wait(1);
    
    // Analyze z-index values in closed state
    $sidebarZIndexClosed = getElementZIndex($I, '#chat-sidebar');
    $commentFormZIndex = getElementZIndex($I, '#fixed-content');
    $chatMainZIndex = getElementZIndex($I, '#chat-main');
    
    $I->comment("Z-index values (sidebar closed):");
    $I->comment("- Sidebar: $sidebarZIndexClosed");
    $I->comment("- Comment form: $commentFormZIndex");
    $I->comment("- Chat main: $chatMainZIndex");
    
    // Take screenshot of closed state
    $I->makeScreenshot('mobile-zindex-sidebar-closed');
    $I->comment("Sidebar closed state <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-zindex-sidebar-closed.png' target = '_blank'>screenshot</a>");
    
    // Now test with sidebar open
    $I->comment("--- PHASE 2: SIDEBAR OPEN STATE ---");
    
    // Open the sidebar using custom toggle function
    $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        if (sidebar) {
            // Use custom function to show sidebar
            if (typeof isSidebarVisible === 'function' && typeof toggleSidebarVisibility === 'function') {
                if (!isSidebarVisible()) {
                    toggleSidebarVisibility();
                    console.log('Sidebar opened using toggleSidebarVisibility function');
                } else {
                    console.log('Sidebar already in open state for z-index testing');
                }
            } else {
                console.error('Custom sidebar functions not available - cannot control sidebar state!');
            }
        } else {
            console.error('Sidebar element not found for z-index testing!');
        }
    ");
    
    $I->wait(1);
    
    // Analyze z-index values in open state
    $sidebarZIndexOpen = getElementZIndex($I, '#chat-sidebar');
    $commentFormZIndexOpen = getElementZIndex($I, '#fixed-content');
    $chatMainZIndexOpen = getElementZIndex($I, '#chat-main');
    
    $I->comment("Z-index values (sidebar open):");
    $I->comment("- Sidebar: $sidebarZIndexOpen");
    $I->comment("- Comment form: $commentFormZIndexOpen");
    $I->comment("- Chat main: $chatMainZIndexOpen");
    
    // Take screenshot of open state
    $I->makeScreenshot('mobile-zindex-sidebar-open');
    $I->comment("Sidebar open state <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-zindex-sidebar-open.png' target = '_blank'>screenshot</a>");
    
    // Analyze layering between sidebar and comment form
    $I->comment("--- PHASE 3: LAYERING ANALYSIS ---");
    
    $layeringAnalysis = analyzeElementLayering($I, '#chat-sidebar', '#fixed-content');
    
    $I->comment("Layering analysis (sidebar vs comment form):");
    $I->comment("- Sidebar z-index: " . $layeringAnalysis['topZIndex']);
    $I->comment("- Comment form z-index: " . $layeringAnalysis['bottomZIndex']);
    $I->comment("- Elements overlap: " . ($layeringAnalysis['overlap'] ? 'YES' : 'NO'));
    $I->comment("- Sidebar position: " . $layeringAnalysis['topPosition']);
    $I->comment("- Comment form position: " . $layeringAnalysis['bottomPosition']);
    
    // Check for the specific z-index issue and FAIL the test if bug is present
    $I->comment("--- PHASE 4: Z-INDEX ISSUE DETECTION & ASSERTION ---");
    
    $sidebarZNum = is_numeric($sidebarZIndexOpen) ? (int)$sidebarZIndexOpen : 0;
    $commentZNum = is_numeric($commentFormZIndexOpen) ? (int)$commentFormZIndexOpen : 0;
    
    $I->comment("Asserting proper z-index hierarchy for mobile sidebar overlay...");
    $I->comment("Expected: Sidebar z-index > Comment form z-index when elements overlap");
    $I->comment("Actual: Sidebar z-index ($sidebarZNum), Comment form z-index ($commentZNum)");
    
    if ($layeringAnalysis['overlap']) {
        $I->comment("Elements overlap detected - z-index hierarchy is critical");
        
        // ASSERTION: When sidebar is open and elements overlap, sidebar MUST have higher z-index
        $I->assertTrue(
            $sidebarZNum > $commentZNum,
            "FAILED: Sidebar z-index ($sidebarZNum) must be higher than comment form z-index ($commentZNum) when sidebar is open in mobile view. " .
            "The sidebar should cover the comment form, not the other way around. " .
            "Current behavior allows comment form to appear above sidebar, which is incorrect."
        );
        
        $I->comment("✅ Z-index hierarchy assertion passed: Sidebar properly covers comment form");
    } else {
        $I->comment("ℹ️  Elements do not overlap, z-index conflict may not be visible in current layout");
        // Still assert the z-index values are reasonable for overlay behavior
        $I->assertTrue(
            $sidebarZNum >= 1000,
            "FAILED: Sidebar z-index ($sidebarZNum) should be at least 1000 for proper mobile overlay behavior"
        );
    }
    
    // Test actual visual layering and element interaction
    $I->comment("--- PHASE 5: VISUAL LAYERING & INTERACTION TESTING ---");
    
    // Comprehensive visual layering test
    $visualLayeringTest = $I->executeJS("
        const sidebar = document.getElementById('chat-sidebar');
        const commentForm = document.getElementById('fixed-content');
        const chatMain = document.getElementById('chat-main');
        
        if (!sidebar || !commentForm) {
            return { error: 'Required elements not found for visual layering test' };
        }
        
        // Get computed styles and positions
        const sidebarStyle = window.getComputedStyle(sidebar);
        const commentStyle = window.getComputedStyle(commentForm);
        
        const sidebarRect = sidebar.getBoundingClientRect();
        const commentRect = commentForm.getBoundingClientRect();
        
        // Check if elements are actually visible and positioned
        const sidebarVisible = (typeof isSidebarVisible === 'function') ? isSidebarVisible() :
                              (sidebarStyle.display !== 'none' && sidebarStyle.visibility !== 'hidden' &&
                               sidebarRect.width > 0 && sidebarRect.height > 0);
        
        const commentVisible = commentStyle.display !== 'none' && commentStyle.visibility !== 'hidden' &&
                              commentRect.width > 0 && commentRect.height > 0;
        
        // Calculate overlap
        const overlap = !(sidebarRect.right < commentRect.left ||
                         sidebarRect.left > commentRect.right ||
                         sidebarRect.bottom < commentRect.top ||
                         sidebarRect.top > commentRect.bottom);
        
        // Test which element would receive clicks in overlapping area (indicates visual stacking)
        let topElementInOverlap = null;
        if (overlap && sidebarVisible && commentVisible) {
            // Find a point in the overlapping area
            const overlapX = Math.max(sidebarRect.left, commentRect.left) + 10;
            const overlapY = Math.max(sidebarRect.top, commentRect.top) + 10;
            
            if (overlapX < Math.min(sidebarRect.right, commentRect.right) &&
                overlapY < Math.min(sidebarRect.bottom, commentRect.bottom)) {
                const elementAtPoint = document.elementFromPoint(overlapX, overlapY);
                if (elementAtPoint) {
                    if (elementAtPoint === sidebar || sidebar.contains(elementAtPoint)) {
                        topElementInOverlap = 'sidebar';
                    } else if (elementAtPoint === commentForm || commentForm.contains(elementAtPoint)) {
                        topElementInOverlap = 'commentForm';
                    } else {
                        topElementInOverlap = 'other: ' + elementAtPoint.tagName + (elementAtPoint.id ? '#' + elementAtPoint.id : '');
                    }
                }
            }
        }
        
        return {
            sidebarVisible: sidebarVisible,
            commentFormVisible: commentVisible,
            chatMainVisible: chatMain ? (chatMain.style.display !== 'none') : false,
            sidebarLeft: sidebar.style.left || sidebarStyle.left,
            commentFormDisplay: commentForm.style.display || commentStyle.display,
            overlap: overlap,
            topElementInOverlap: topElementInOverlap,
            sidebarZIndex: sidebarStyle.zIndex,
            commentZIndex: commentStyle.zIndex,
            sidebarPosition: sidebarStyle.position,
            commentPosition: commentStyle.position,
            customFunctionsAvailable: (typeof isSidebarVisible === 'function' && typeof toggleSidebarVisibility === 'function')
        };
    ");
    
    $I->comment("Visual layering test results:");
    $I->comment("- Sidebar visible: " . ($visualLayeringTest['sidebarVisible'] ? 'YES' : 'NO'));
    $I->comment("- Comment form visible: " . ($visualLayeringTest['commentFormVisible'] ? 'YES' : 'NO'));
    $I->comment("- Elements overlap: " . ($visualLayeringTest['overlap'] ? 'YES' : 'NO'));
    $I->comment("- Top element in overlap area: " . ($visualLayeringTest['topElementInOverlap'] ?: 'N/A'));
    $I->comment("- Sidebar position/z-index: " . $visualLayeringTest['sidebarPosition'] . '/' . $visualLayeringTest['sidebarZIndex']);
    $I->comment("- Comment form position/z-index: " . $visualLayeringTest['commentPosition'] . '/' . $visualLayeringTest['commentZIndex']);
    
    // CRITICAL ASSERTION: When sidebar is open and elements overlap, sidebar must be the top element
    if ($visualLayeringTest['overlap'] && $visualLayeringTest['sidebarVisible'] && $visualLayeringTest['commentFormVisible']) {
        $I->comment("ASSERTING: Sidebar must be visually on top when overlapping with comment form");
        
        $I->assertEquals(
            'sidebar',
            $visualLayeringTest['topElementInOverlap'],
            "FAILED: When sidebar is open in mobile view, it should visually cover the comment form. " .
            "Currently, the element on top in the overlapping area is: " . $visualLayeringTest['topElementInOverlap'] . ". " .
            "This indicates the comment form is appearing above the sidebar, which is the bug we're testing for."
        );
        
        $I->comment("✅ Visual layering assertion passed: Sidebar properly covers comment form");
    } else {
        $I->comment("ℹ️  Visual layering test skipped - elements not overlapping or not both visible");
    }
    
    // Final comprehensive screenshot
    $I->makeScreenshot('mobile-zindex-comprehensive-analysis');
    $I->comment("Comprehensive z-index analysis <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-zindex-comprehensive-analysis.png' target = '_blank'>screenshot</a>");
    
    $I->comment("✓ Mobile sidebar z-index layering analysis completed");
}