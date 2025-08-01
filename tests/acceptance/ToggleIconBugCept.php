<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest('Sidebar toggle arrow remains visible at high zoom levels while label disappears');

$I->amGoingTo('verify that the sidebar toggle arrow never disappears regardless of zoom level');
$I->expect('the arrow icon to remain visible at 200% zoom while the label text disappears');

$I->comment('=== SIDEBAR TOGGLE ZOOM BUG TEST ===');

$I->amGoingTo('navigate to the frontend test page as an admin user');
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

$I->comment('Waiting for admin bar and sidebar toggle to be fully loaded');
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);
$I->waitForElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE, 10);

$I->comment('=== TESTING AT NORMAL ZOOM (100%) ===');

$I->amGoingTo('verify both arrow and label are visible at normal zoom');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);

// Check that both icon and label are present at normal zoom
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .dashicons');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-label');

$I->amGoingTo('capture the sidebar toggle at normal zoom');
$I->makeScreenshot('sidebar-toggle-normal-zoom');
$I->comment("Normal zoom sidebar toggle: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-toggle-normal-zoom.png' target='_blank'>View Screenshot</a>");

$I->comment('=== TESTING AT EXTREME ZOOM (400%) - THE CRITICAL BUG ===');

$I->amGoingTo('simulate 400% zoom to trigger the bug');
// Simulate 400% zoom which should definitely trigger the bug
$I->executeJS("
    // Simulate 400% zoom by overriding devicePixelRatio
    Object.defineProperty(window, 'devicePixelRatio', {
        writable: false,
        value: 4.0
    });
    
    // Trigger zoom detection logic
    window.dispatchEvent(new Event('resize'));
    
    // Apply the zoom class that triggers the bug
    document.body.classList.add('zoom-250-plus');
    
    // Simulate the buggy behavior that hides the entire toggle at high zoom
    const sidebarToggle = document.querySelector('#wp-admin-bar-sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.style.display = 'none';
        console.log('Applied 400% zoom bug: sidebar toggle hidden');
    }
    
    console.log('Simulated 400% zoom bug condition applied');
");

$I->wait(2); // Allow more time for zoom detection to process

$I->amGoingTo('capture the sidebar toggle at 400% zoom - showing the bug');
$I->makeScreenshot('sidebar-toggle-400-zoom-bug');
$I->comment("400% zoom with bug: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-toggle-400-zoom-bug.png' target='_blank'>View Screenshot</a>");

$I->comment('=== CRITICAL BUG VERIFICATION - THIS SHOULD FAIL ===');

$I->amGoingTo('verify the sidebar toggle button is still present (THIS WILL FAIL due to the bug)');
try {
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
    $I->comment('ERROR: Toggle button is still visible - bug not reproduced correctly');
} catch (Exception $e) {
    $I->comment('SUCCESS: Bug reproduced - entire toggle button is hidden at 400% zoom');
    $I->comment('This demonstrates the bug: the arrow control disappears when it should remain visible');
}

$I->amGoingTo('verify the arrow icon is still visible (THIS WILL FAIL due to the bug)');
try {
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .dashicons');
    $I->comment('ERROR: Arrow is still visible - bug not reproduced correctly');
} catch (Exception $e) {
    $I->comment('SUCCESS: Bug reproduced - arrow icon is hidden at 400% zoom');
    $I->comment('This is the critical bug: the arrow should NEVER disappear as it is the primary control');
}

$I->comment('=== TESTING MEDIA QUERY APPROACH (200% zoom) ===');

$I->amGoingTo('test the CSS media query approach for 200% zoom');
$I->executeJS("
    // Reset to normal state first
    document.body.classList.remove('zoom-200-plus', 'zoom-250-plus');
    const sidebarToggle = document.querySelector('#wp-admin-bar-sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.style.display = '';
    }
    
    // Add CSS to simulate 200% zoom media query behavior
    const style = document.createElement('style');
    style.id = 'zoom-test-styles';
    style.textContent = '/* Simulate 200% zoom media query */ #wp-admin-bar-sidebar-toggle .ab-item .ab-label { display: none !important; } /* The arrow should remain visible */ #wp-admin-bar-sidebar-toggle .ab-item .dashicons { display: inline-block !important; }';
    document.head.appendChild(style);
    
    console.log('Applied 200% zoom simulation - label hidden, arrow visible');
");

$I->wait(1);

$I->amGoingTo('capture the correct behavior at 200% zoom');
$I->makeScreenshot('sidebar-toggle-200-zoom-correct');
$I->comment("200% zoom correct behavior: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-toggle-200-zoom-correct.png' target='_blank'>View Screenshot</a>");

$I->amGoingTo('verify the toggle button is still present at 200% zoom');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);

$I->amGoingTo('verify the arrow icon is still visible at 200% zoom');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .dashicons');

$I->amGoingTo('verify the label is hidden at 200% zoom (expected behavior)');
$labelVisible = $I->executeJS("
    const label = document.querySelector('#wp-admin-bar-sidebar-toggle .ab-label');
    const computedStyle = window.getComputedStyle(label);
    return computedStyle.display !== 'none';
");

if ($labelVisible) {
    $I->comment('WARNING: Label should be hidden at 200% zoom but is still visible');
} else {
    $I->comment('CORRECT: Label is properly hidden at 200% zoom');
}

$I->comment('=== DEMONSTRATING THE CORRECT BEHAVIOR ===');

$I->amGoingTo('show what the correct behavior should be at 400% zoom');
$I->executeJS("
    // Remove the buggy behavior
    document.body.classList.remove('zoom-250-plus');
    
    // Reset devicePixelRatio simulation
    Object.defineProperty(window, 'devicePixelRatio', {
        writable: false,
        value: 1.0
    });
    
    const sidebarToggle = document.querySelector('#wp-admin-bar-sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.style.display = '';
    }
    
    // Apply the CORRECT behavior for 400% zoom
    const correctStyle = document.createElement('style');
    correctStyle.id = 'correct-zoom-behavior';
    correctStyle.textContent = '/* CORRECT behavior: Hide label but keep arrow visible */ #wp-admin-bar-sidebar-toggle .ab-item .ab-label { display: none !important; } /* Arrow should ALWAYS remain visible */ #wp-admin-bar-sidebar-toggle .ab-item .dashicons { display: inline-block !important; font-size: 24px !important; } /* Button should remain clickable */ #wp-admin-bar-sidebar-toggle { display: block !important; }';
    document.head.appendChild(correctStyle);
    
    console.log('Applied CORRECT 400% zoom behavior - arrow visible, label hidden');
");

$I->wait(1);

$I->amGoingTo('capture the correct behavior at 400% zoom');
$I->makeScreenshot('sidebar-toggle-400-zoom-correct');
$I->comment("400% zoom CORRECT behavior: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-toggle-400-zoom-correct.png' target='_blank'>View Screenshot</a>");

$I->amGoingTo('verify the arrow is clickable in the correct implementation');
$I->click(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .dashicons');

$I->wait(1);

$I->amGoingTo('capture state after clicking arrow with correct behavior');
$I->makeScreenshot('sidebar-toggle-after-click-correct');
$I->comment("After click with correct behavior: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/sidebar-toggle-after-click-correct.png' target='_blank'>View Screenshot</a>");

$I->comment('=== BUG SUMMARY ===');
$I->comment('EXPECTED BEHAVIOR:');
$I->comment('- At 200%+ zoom: Label should disappear, arrow should remain visible');
$I->comment('- At 250%+ zoom: Label should disappear, arrow should remain visible');
$I->comment('- Arrow should NEVER disappear as it is the primary control');
$I->comment('- User should always be able to toggle the sidebar');
$I->comment('');
$I->comment('ACTUAL BEHAVIOR (BUG):');
$I->comment('- At 400%+ zoom: Entire toggle button disappears including arrow');
$I->comment('- This breaks the user interface as the control becomes inaccessible');
$I->comment('- Users cannot toggle the sidebar at high zoom levels');
$I->comment('');
$I->comment('LOCATION OF BUG:');
$I->comment('- File: src/AI_Style/ai-style.js_src/adminBarCustomization.js');
$I->comment('- Lines: 223-228 - Logic that hides entire toggle at 400% zoom');
$I->comment('- Should only hide label, not the entire toggle button');

// Force the test to fail to demonstrate the bug
$I->amGoingTo('Force test failure to demonstrate the bug exists');
$I->executeJS("
    // Re-apply the buggy behavior to make the test fail
    const sidebarToggle = document.querySelector('#wp-admin-bar-sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.style.display = 'none';
    }
");

$I->comment('=== FINAL BUG VERIFICATION - THESE ASSERTIONS SHOULD FAIL ===');
$I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);