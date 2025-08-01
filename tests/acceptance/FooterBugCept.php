<?php
/**
 * Footer Bug Test - SEPT
 *
 * This test demonstrates the footer bug where the footer line does not extend
 * to the left when the sidebar is closed. The footer should dynamically adjust
 * its left position when the sidebar visibility changes.
 *
 * Bug Description:
 * - Footer has a line above the text "Cacbots can make mistakes. Check important info."
 * - When sidebar is visible, footer starts at left: 377px (sidebar width)
 * - When sidebar is hidden, footer should extend to left: 0, but it doesn't
 * - This creates a visual gap where the footer line doesn't extend into the former sidebar area
 */

$I = new AcceptanceTester($scenario);

$I->wantTo('demonstrate the footer bug where footer line does not extend when sidebar is closed');

// Setup: Navigate to test page and login
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Get current device mode and window size
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Running footer bug test in $deviceMode mode with window size: $windowSize");

// Device-specific setup: Different devices may have different footer positioning expectations
$isDesktop = strpos($deviceMode, 'desktop') !== false;
$isTablet = strpos($deviceMode, 'tablet') !== false;
$isMobile = strpos($deviceMode, 'mobile') !== false;

// Wait for zoom and layout to stabilize (configuration-driven, no manual zoom changes)
$I->wait(1);

// Wait for page to fully load
$I->waitForElement(AcceptanceConfig::CHAT_CONTAINER, 10);
$I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);
$I->waitForElement(AcceptanceConfig::SITE_FOOTER, 10);

// Verify initial state: sidebar should be visible
$I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
$I->comment('Initial state: Sidebar is visible');

// Get initial footer left position - expectations vary by device mode
$initialFooterLeft = $I->executeJS('
    const footer = document.querySelector("' . AcceptanceConfig::SITE_FOOTER . '");
    const computedStyle = window.getComputedStyle(footer);
    return computedStyle.left;
');
$I->comment('Initial footer left position: ' . $initialFooterLeft);

// Device-specific footer positioning expectations
if ($isDesktop) {
    // Desktop: Footer should start at sidebar width (377px) when sidebar is visible
    $expectedInitialLeft = '377px';
    $I->comment('Desktop mode: Expecting footer to start at sidebar width (377px)');
} elseif ($isTablet) {
    // Tablet: Footer positioning may differ due to responsive design
    $expectedInitialLeft = $initialFooterLeft; // Accept current position for tablets
    $I->comment('Tablet mode: Recording initial footer position for comparison');
} else { // Mobile
    // Mobile: Sidebar behavior may be different (overlay, hidden by default, etc.)
    $expectedInitialLeft = $initialFooterLeft; // Accept current position for mobile
    $I->comment('Mobile mode: Recording initial footer position for comparison');
}

// Verify initial footer position based on device mode
if ($isDesktop) {
    $I->assertEquals($expectedInitialLeft, $initialFooterLeft, 'Footer should initially start at sidebar width on desktop');
} else {
    $I->comment("Non-desktop mode: Initial footer position recorded as: $initialFooterLeft");
}

// Take screenshot of initial state
$I->makeScreenshot('footer-bug-sidebar-visible');

// Action: Hide the sidebar by clicking the toggle button
$I->comment('Hiding sidebar via admin bar toggle');
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);

// Wait for sidebar animation to complete (300ms according to toggleSidebarVisible.js)
$I->wait(1);

// Verify sidebar is now hidden
$sidebarWidth = $I->executeJS('
    const sidebar = document.getElementById("chat-sidebar");
    return window.getComputedStyle(sidebar).width;
');
$I->comment('Sidebar width after hiding: ' . $sidebarWidth);
$I->assertEquals('0px', $sidebarWidth, 'Sidebar should be hidden (width: 0px)');

// BUG DEMONSTRATION: Get footer left position after sidebar is hidden
$footerLeftAfterHide = $I->executeJS('
    const footer = document.querySelector("' . AcceptanceConfig::SITE_FOOTER . '");
    const computedStyle = window.getComputedStyle(footer);
    return computedStyle.left;
');
$I->comment('Footer left position after hiding sidebar: ' . $footerLeftAfterHide);

// Take screenshot showing the bug
$I->makeScreenshot('footer-bug-sidebar-hidden');

// Device-specific bug demonstration and expectations
if ($isDesktop) {
    // Desktop: THIS IS THE FAILING ASSERTION - demonstrates the bug
    // Footer should extend to left: 0 when sidebar is hidden, but it doesn't
    $I->comment('DESKTOP MODE - EXPECTED: Footer should extend to left: 0px when sidebar is hidden');
    $I->comment('DESKTOP MODE - ACTUAL: Footer remains at left: 377px, creating a visual gap');
    
    // This assertion will FAIL on desktop, demonstrating the bug
    $I->assertEquals('0px', $footerLeftAfterHide, 'FAILING TEST (Desktop): Footer should extend to left: 0px when sidebar is hidden, but it stays at 377px');
} elseif ($isTablet) {
    // Tablet: Footer behavior may be different due to responsive design
    $I->comment('TABLET MODE: Checking if footer adjusts properly when sidebar is hidden');
    $I->comment("Tablet footer position after hiding sidebar: $footerLeftAfterHide");
    
    // For tablets, we may expect different behavior - document the actual behavior
    if ($footerLeftAfterHide === '0px') {
        $I->comment('TABLET MODE: Footer correctly extends to left edge when sidebar is hidden');
    } else {
        $I->comment('TABLET MODE: Footer positioning may differ from desktop - recording behavior');
    }
} else { // Mobile
    // Mobile: Sidebar may behave as overlay or be hidden by default
    $I->comment('MOBILE MODE: Checking footer behavior when sidebar state changes');
    $I->comment("Mobile footer position after hiding sidebar: $footerLeftAfterHide");
    
    // Mobile devices may have different sidebar/footer interaction patterns
    $I->comment('MOBILE MODE: Footer behavior may differ due to responsive design patterns');
}

// Additional verification: Show the sidebar again to test the reverse
$I->comment('Showing sidebar again to test reverse behavior');
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
$I->wait(1);

// Get footer position after showing sidebar again
$footerLeftAfterShow = $I->executeJS('
    const footer = document.querySelector("' . AcceptanceConfig::SITE_FOOTER . '");
    const computedStyle = window.getComputedStyle(footer);
    return computedStyle.left;
');
$I->comment('Footer left position after showing sidebar again: ' . $footerLeftAfterShow);

// Take final screenshot
$I->makeScreenshot('footer-bug-sidebar-visible-again');

// Device-specific verification of footer returning to original position
if ($isDesktop) {
    // Desktop: Footer should return to original sidebar width position
    $I->assertEquals('377px', $footerLeftAfterShow, 'Desktop: Footer should return to 377px when sidebar is shown again');
} elseif ($isTablet) {
    // Tablet: Compare with initial position recorded earlier
    $I->comment('Tablet: Verifying footer returns to initial position');
    if ($footerLeftAfterShow === $expectedInitialLeft) {
        $I->comment('Tablet: Footer correctly returned to initial position');
    } else {
        $I->comment("Tablet: Footer position changed - Initial: $expectedInitialLeft, Final: $footerLeftAfterShow");
    }
} else { // Mobile
    // Mobile: Compare with initial position
    $I->comment('Mobile: Verifying footer returns to initial position');
    if ($footerLeftAfterShow === $expectedInitialLeft) {
        $I->comment('Mobile: Footer correctly returned to initial position');
    } else {
        $I->comment("Mobile: Footer position changed - Initial: $expectedInitialLeft, Final: $footerLeftAfterShow");
    }
}

// Configuration-aware test completion summary
$I->comment("Test completed in $deviceMode mode. Footer behavior analysis:");
if ($isDesktop) {
    $I->comment('DESKTOP: Bug demonstrated - Footer line does not extend when sidebar is closed.');
} elseif ($isTablet) {
    $I->comment('TABLET: Footer behavior documented for responsive design analysis.');
} else {
    $I->comment('MOBILE: Footer behavior documented for mobile-specific interaction patterns.');
}

$I->comment("Screenshots available for $deviceMode mode:");
$I->comment("- Initial state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-visible.png' target='_blank'>Sidebar Visible</a>");
$I->comment("- Bug/behavior state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-hidden.png' target='_blank'>Sidebar Hidden</a>");
$I->comment("- Final state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-visible-again.png' target='_blank'>Sidebar Visible Again</a>");

// Configuration-driven approach: No manual zoom reset needed
// Window size and zoom are managed by suite configuration
$I->comment("Test completed using configuration-driven approach - no manual zoom management required.");
