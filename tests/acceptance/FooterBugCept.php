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

// Set zoom level to 100% to ensure consistent behavior
$I->executeJS('document.body.style.zoom = "100%";');

// Wait for page to fully load
$I->waitForElement(AcceptanceConfig::CHAT_CONTAINER, 10);
$I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);
$I->waitForElement(AcceptanceConfig::SITE_FOOTER, 10);

// Verify initial state: sidebar should be visible
$I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
$I->comment('Initial state: Sidebar is visible');

// Get initial footer left position (should be 377px - sidebar width)
$initialFooterLeft = $I->executeJS('
    const footer = document.querySelector("' . AcceptanceConfig::SITE_FOOTER . '");
    const computedStyle = window.getComputedStyle(footer);
    return computedStyle.left;
');
$I->comment('Initial footer left position: ' . $initialFooterLeft);

// Verify footer starts at sidebar width (377px)
$I->assertEquals('377px', $initialFooterLeft, 'Footer should initially start at sidebar width (377px)');

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

// THIS IS THE FAILING ASSERTION - demonstrates the bug
// Footer should extend to left: 0 when sidebar is hidden, but it doesn't
$I->comment('EXPECTED: Footer should extend to left: 0px when sidebar is hidden');
$I->comment('ACTUAL: Footer remains at left: 377px, creating a visual gap');

// This assertion will FAIL, demonstrating the bug
// BUG: Footer should extend to left: 0px when sidebar is hidden, but it stays at 377px
$I->assertEquals('0px', $footerLeftAfterHide, 'FAILING TEST: Footer should extend to left: 0px when sidebar is hidden, but it stays at 377px');

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

// Verify footer returns to original position (this should work)
$I->assertEquals('377px', $footerLeftAfterShow, 'Footer should return to 377px when sidebar is shown again');

$I->comment('Test completed. Bug demonstrated: Footer line does not extend when sidebar is closed.');
$I->comment("Screenshots available:");
$I->comment("- Initial state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-visible.png' target='_blank'>Sidebar Visible</a>");
$I->comment("- Bug state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-hidden.png' target='_blank'>Sidebar Hidden (Bug)</a>");
$I->comment("- Final state: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-bug-sidebar-visible-again.png' target='_blank'>Sidebar Visible Again</a>");
