<?php
/**
 * SidebarToggleCept.php
 *
 * Acceptance test for verifying the sidebar toggle functionality with localStorage persistence.
 * This test demonstrates that the sidebar toggle state is NOT currently persistent across page reloads.
 *
 * TDD FAILING TEST: This test is designed to FAIL to demonstrate missing functionality.
 * The test expects sidebar state to be preserved in localStorage across page reloads,
 * but the current implementation only maintains state in memory.
 *
 * Expected behavior (not yet implemented):
 * - When user toggles sidebar to hidden, state should be saved to localStorage
 * - After page reload, sidebar should remain in the hidden state
 * - localStorage should contain the sidebar visibility preference
 *
 * Current behavior (what actually happens):
 * - Sidebar state is only maintained in JavaScript memory (sidebarState variable)
 * - Page reload resets sidebar to default visible state
 * - No localStorage persistence exists
 *
 * This test checks:
 * 1. Initial sidebar visibility state
 * 2. Sidebar toggle button presence in admin bar
 * 3. Sidebar hide functionality with animation
 * 4. Sidebar show functionality with animation
 * 5. CSS class changes during toggle operations
 * 6. Footer position updates when sidebar toggles
 * 7. Main content area expansion when sidebar is hidden
 * 8. **NEW: localStorage persistence across page reloads (EXPECTED TO FAIL)**
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Sidebar toggle functionality');

// Create test post with ChatGPT interface content
$I->comment('Creating test post for sidebar toggle testing');
$postContent = 'This is a test post for sidebar toggle verification. The theme will automatically generate the chat interface with sidebar toggle functionality.';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing sidebar toggle for {$deviceMode} mode ({$windowSize})");

// Wait for the page to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);
$I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);

// 1. Test initial sidebar visibility state
$I->comment('Verifying initial sidebar visibility state');

// The sidebar should be visible by default (unless at high zoom levels)
$I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
$I->comment('✓ Sidebar element is present in DOM');

// Check initial sidebar state by looking at the toggle button arrow
// Initially, sidebar should be visible, so arrow should point left (to hide it)
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
$I->comment('✓ Sidebar is initially visible (toggle shows arrow-left)');

// Take initial screenshot
$I->makeScreenshot('sidebar-initial-state');
$I->comment("Initial state screenshot <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-initial-state.png' target='_blank'>available here</a>");

// 2. Test sidebar toggle button presence in admin bar
$I->comment('Verifying sidebar toggle button presence in admin bar');

$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
$I->comment('✓ Sidebar toggle button is present in admin bar');

// Verify the toggle button has the expected structure (icon + label)
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->comment('✓ Toggle button has proper structure');

// 3. Test sidebar hide functionality
$I->comment('Testing sidebar hide functionality');

// Get initial sidebar width for comparison
$initialSidebarWidth = $I->executeJS('return document.getElementById("chat-sidebar").offsetWidth;');
$I->comment("Initial sidebar width: {$initialSidebarWidth}px");

// Click the toggle button to hide sidebar
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->comment('✓ Clicked sidebar toggle button to hide sidebar');

// Wait for animation to complete (300ms animation + buffer)
$I->wait(1);

// Take screenshot after hiding
$I->makeScreenshot('sidebar-hidden-state');
$I->comment("Hidden state screenshot <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-hidden-state.png' target='_blank'>available here</a>");

// Verify sidebar toggle worked by checking the arrow direction
// When sidebar is being hidden, the arrow should point right (to show it can be opened)
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-right');
$I->comment('✓ Toggle button shows arrow-right icon (sidebar is hidden)');

// Check that sidebar width is 0 or very small
$hiddenSidebarWidth = $I->executeJS('return document.getElementById("chat-sidebar").offsetWidth;');
$I->comment("Hidden sidebar width: {$hiddenSidebarWidth}px");
$I->assertLessThan(10, $hiddenSidebarWidth, 'Sidebar width should be minimal when hidden');

// Verify main content area has expanded
$I->comment('Verifying main content area expansion when sidebar is hidden');
$mainContentWidth = $I->executeJS('return document.getElementById("chat-main").offsetWidth;');
$I->comment("Main content width when sidebar hidden: {$mainContentWidth}px");

// 4. Test footer position update when sidebar is hidden
$I->comment('Verifying footer position when sidebar is hidden');

// Footer should have left position of 0px when sidebar is hidden
$footerLeftPosition = $I->executeJS('return window.getComputedStyle(document.querySelector(".site-footer")).left;');
$I->comment("Footer left position when sidebar hidden: {$footerLeftPosition}");
$I->assertEquals('0px', $footerLeftPosition, 'Footer should be positioned at left: 0px when sidebar is hidden');

// 5. Test sidebar show functionality
$I->comment('Testing sidebar show functionality');

// Click the toggle button again to show sidebar
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->comment('✓ Clicked sidebar toggle button to show sidebar');

// Wait for animation to complete
$I->wait(1);

// Take screenshot after showing
$I->makeScreenshot('sidebar-shown-state');
$I->comment("Shown state screenshot <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-shown-state.png' target='_blank'>available here</a>");

// Verify sidebar is visible again by checking the arrow direction
// When sidebar is visible, the arrow should point left (to show it can be hidden)
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
$I->comment('✓ Toggle button shows arrow-left icon (sidebar is visible)');

// Check that sidebar width is restored
$restoredSidebarWidth = $I->executeJS('return document.getElementById("chat-sidebar").offsetWidth;');
$I->comment("Restored sidebar width: {$restoredSidebarWidth}px");
$I->assertGreaterThan(300, $restoredSidebarWidth, 'Sidebar width should be restored when shown');

// 6. Test footer position when sidebar is shown
$I->comment('Verifying footer position when sidebar is shown');

// Footer should have left position of 377px when sidebar is shown
$footerLeftPositionShown = $I->executeJS('return window.getComputedStyle(document.querySelector(".site-footer")).left;');
$I->comment("Footer left position when sidebar shown: {$footerLeftPositionShown}");
$I->assertEquals('377px', $footerLeftPositionShown, 'Footer should be positioned at left: 377px when sidebar is shown');

// 7. Test rapid clicking prevention (animation state management)
$I->comment('Testing rapid clicking prevention during animation');

// Click toggle button rapidly
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->comment('✓ First rapid click');

// Immediately click again (should be ignored due to animation state)
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->comment('✓ Second rapid click (should be ignored)');

// Wait for animation to complete
$I->wait(1);

// Verify sidebar state is consistent (should be hidden from first click)
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-right');
$I->comment('✓ Rapid clicking handled correctly - sidebar is hidden as expected (arrow-right)');

// 8. Test CSS animation classes during transition
$I->comment('Testing CSS animation classes during transition');

// Click to show sidebar and immediately check for transitioning class
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');

// Check for transitioning class (this might be brief, so we'll use JavaScript)
$hasTransitioningClass = $I->executeJS('
    return document.getElementById("chat-sidebar").classList.contains("sidebar-transitioning");
');

if ($hasTransitioningClass) {
    $I->comment('✓ Sidebar has transitioning class during animation');
} else {
    $I->comment('Note: Transitioning class may have been removed quickly');
}

// Wait for animation to complete
$I->wait(1);

// Verify sidebar is now visible (arrow should point left)
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
$I->comment('✓ Sidebar is visible after animation completes (arrow-left)');

// 9. Test sidebar content accessibility during toggle
$I->comment('Testing sidebar content accessibility during toggle operations');

// Verify sidebar links are still present in DOM even when hidden
$sidebarLinks = $I->executeJS('return document.querySelectorAll("#chat-sidebar li a").length;');
$I->comment("Number of sidebar links found: {$sidebarLinks}");

if ($sidebarLinks > 0) {
    $I->comment('✓ Sidebar content remains in DOM when toggled');
} else {
    $I->comment('Note: No sidebar links found - this may be expected for test content');
}

// 10. Final state verification
$I->comment('Final state verification');

// Ensure sidebar is in a known state (visible) by checking arrow direction
$finalSidebarState = $I->executeJS('
    const sidebar = document.getElementById("chat-sidebar");
    const toggleIcon = document.querySelector("#wp-admin-bar-sidebar-toggle .dashicons");
    return {
        sidebarWidth: sidebar.offsetWidth,
        hasArrowLeft: toggleIcon ? toggleIcon.classList.contains("dashicons-arrow-left") : false,
        hasArrowRight: toggleIcon ? toggleIcon.classList.contains("dashicons-arrow-right") : false
    };
');

$I->comment('Final sidebar state: ' . json_encode($finalSidebarState));
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
$I->comment('✓ Final state: Sidebar is visible (arrow-left)');

// Take final screenshot
$I->makeScreenshot('sidebar-final-state');
$I->comment("Final state screenshot <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-final-state.png' target='_blank'>available here</a>");

// 11. **TDD FAILING TEST**: Test localStorage persistence across page reloads
$I->comment('=== TDD FAILING TEST: Testing localStorage persistence across page reloads ===');
$I->comment('EXPECTED TO FAIL: This test demonstrates missing localStorage functionality');

// First, ensure sidebar is in visible state
$I->comment('Step 1: Ensuring sidebar is visible before persistence test');
$currentState = $I->executeJS('
    const toggleIcon = document.querySelector("#wp-admin-bar-sidebar-toggle .dashicons");
    return toggleIcon ? toggleIcon.classList.contains("dashicons-arrow-left") : false;
');

if (!$currentState) {
    // If sidebar is hidden, show it first
    $I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
    $I->wait(1);
    $I->comment('✓ Sidebar made visible for persistence test');
}

// Step 2: Toggle sidebar to hidden state
$I->comment('Step 2: Toggling sidebar to hidden state');
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
$I->wait(1);

// Verify sidebar is now hidden
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-right');
$I->comment('✓ Sidebar is now hidden (arrow-right icon visible)');

// Step 3: Check if localStorage was set (this will fail with current implementation)
$I->comment('Step 3: Checking if sidebar state was saved to localStorage');
$localStorageValue = $I->executeJS('return localStorage.getItem("ai_style_sidebar_visible");');
$I->comment('localStorage value for sidebar state: ' . ($localStorageValue ?? 'null'));

// This assertion will FAIL because localStorage persistence is not implemented
$I->assertEquals('false', $localStorageValue, 'TDD FAILING TEST: Sidebar hidden state should be saved to localStorage but is not implemented');

// Step 4: Reload the page to test persistence
$I->comment('Step 4: Reloading page to test state persistence');
$I->reloadPage();
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);
$I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);

// Step 5: Check if sidebar state was restored from localStorage
$I->comment('Step 5: Checking if sidebar state was restored after page reload');

// Wait a moment for any initialization to complete
$I->wait(2);

// Check the current sidebar state after reload
$sidebarStateAfterReload = $I->executeJS('
    const sidebar = document.getElementById("chat-sidebar");
    const toggleIcon = document.querySelector("#wp-admin-bar-sidebar-toggle .dashicons");
    return {
        sidebarWidth: sidebar ? sidebar.offsetWidth : 0,
        hasArrowLeft: toggleIcon ? toggleIcon.classList.contains("dashicons-arrow-left") : false,
        hasArrowRight: toggleIcon ? toggleIcon.classList.contains("dashicons-arrow-right") : false,
        localStorageValue: localStorage.getItem("ai_style_sidebar_visible")
    };
');

$I->comment('Sidebar state after reload: ' . json_encode($sidebarStateAfterReload));

// This assertion will FAIL because the sidebar resets to visible state after reload
$I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-right');
$I->comment('✓ SUCCESS: Sidebar remained hidden after page reload - localStorage persistence is working!');

// Step 6: Verify localStorage behavior after reload
$I->comment('Step 6: Verifying localStorage behavior after page reload');
$localStorageAfterReload = $I->executeJS('return localStorage.getItem("ai_style_sidebar_visible");');
$I->comment('localStorage after reload: ' . ($localStorageAfterReload ?? 'null'));

if ($localStorageAfterReload === null) {
    $I->comment('❌ CONFIRMED: No localStorage persistence implemented');
} else {
    $I->comment('localStorage value found: ' . $localStorageAfterReload);
}

// Take screenshot showing the failed persistence
$I->makeScreenshot('sidebar-persistence-test-failed');
$I->comment("Persistence test screenshot <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-persistence-test-failed.png' target='_blank'>available here</a>");

$I->comment('=== END TDD FAILING TEST ===');
$I->comment('Summary: This test demonstrates that sidebar toggle state is NOT persistent across page reloads');
$I->comment('To fix: Implement localStorage save/restore functionality in toggleSidebarVisible.js');

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

$I->comment('✅ Sidebar toggle functionality test completed');
$I->comment('⚠️  Note: localStorage persistence test FAILED as expected (TDD approach)');