<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest('Sidebar toggle functionality - Desktop visible initially, Mobile hidden initially');

// Create test post with ChatGPT interface content
$I->comment('Creating test post for sidebar toggle testing');
$postContent = 'This is a test post for sidebar toggle verification. The theme will automatically generate the chat interface with sidebar toggle functionality.';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
// Clear localStorage to prevent data bleeding from other tests
$I->comment('Clearing localStorage to ensure clean test state');
$I->executeJS('localStorage.clear();');
$I->comment('✓ localStorage cleared');
$I->wait(2);
$I->amOnPage("/?p=" . $postId);

// Determine device mode using simplified breakpoint approach
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';

$I->comment("Testing sidebar toggle for {$deviceType} mode (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");

// Wait for the page to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);



// Define the toggle button selector based on device mode
$toggleButtonSelector = $isMobile
    ? AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER . ' .ab-item'
    : AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item';

$I->comment("Using toggle button selector: {$toggleButtonSelector}");

// Wait for sidebar and toggle button
if ($deviceType === 'desktop') {
    $I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);
} else {
    // On mobile, sidebar might be hidden initially, so just wait for page load
    $I->wait(2);
}

$I->waitForElement($toggleButtonSelector, 10);

// === CUSTOM FUNCTIONS AVAILABILITY CHECK ===
$I->comment('=== Verifying custom JavaScript functions are available ===');

// Check if custom functions are available
$functionsAvailable = $I->executeJS('
    return {
        isSidebarVisible: typeof window.isSidebarVisible === "function",
        toggleSidebarVisibility: typeof window.toggleSidebarVisibility === "function",
        showSidebar: typeof window.showSidebar === "function",
        hideSidebar: typeof window.hideSidebar === "function"
    };
');

$I->comment('Custom function availability: ' . json_encode($functionsAvailable));

// Verify all required functions are available
$I->assertTrue($functionsAvailable['isSidebarVisible'], 'isSidebarVisible function should be available');
$I->assertTrue($functionsAvailable['toggleSidebarVisibility'], 'toggleSidebarVisibility function should be available');
$I->assertTrue($functionsAvailable['showSidebar'], 'showSidebar function should be available');
$I->assertTrue($functionsAvailable['hideSidebar'], 'hideSidebar function should be available');
$I->comment('✓ All required custom functions are available');

// === TEST 1: INITIAL SIDEBAR VISIBILITY STATE ===
$I->comment('=== TEST 1: Verifying initial sidebar visibility state ===');

// Verify sidebar element exists in DOM
$sidebarExists = $I->executeJS('return document.getElementById("chat-sidebar") !== null;');
$I->assertTrue($sidebarExists, 'Sidebar element should be present in DOM');
$I->comment('✓ Sidebar element is present in DOM');

// Check initial sidebar state using custom function
$initialSidebarVisible = $I->executeJS('
    if (typeof window.isSidebarVisible !== "function") {
        throw new Error("isSidebarVisible function is not available");
    }
    return window.isSidebarVisible();
');
$I->comment("Initial sidebar visibility state: " . ($initialSidebarVisible ? 'visible' : 'hidden'));

// Check initial sidebar state based on device mode
if ($deviceType === 'desktop') {
    sleep(2);
    // EXPECTED: Desktop sidebar should be VISIBLE initially (arrow points left to hide it)
    $I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
    $I->comment('✓ DESKTOP: Sidebar is initially VISIBLE (toggle shows arrow-left)');
    
    // Verify sidebar visibility using custom function
    $I->assertTrue($initialSidebarVisible, 'Desktop sidebar should be visible initially');
    $I->comment('✓ DESKTOP: Custom function confirms sidebar is visible');
    
} else {
    // EXPECTED: Mobile sidebar should be HIDDEN initially
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER);
    $I->comment('✓ MOBILE: Hamburger menu is present');
    
    // Verify sidebar is hidden using custom function
    $I->assertFalse($initialSidebarVisible, 'MOBILE sidebar should start HIDDEN');
    $I->comment('✓ MOBILE: Custom function confirms sidebar is hidden');
}

// Take initial screenshot
$I->makeScreenshot('sidebar-initial-state');
$I->comment("Initial state screenshot available");

// === TEST 2: TOGGLE BUTTON PRESENCE ===
$I->comment('=== TEST 2: Verifying toggle button presence ===');

if ($deviceType === 'desktop') {
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE . ' .ab-item');
    $I->comment('✓ Desktop: Sidebar toggle button is present and properly structured');
} else {
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER);
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_MOBILE_HAMBURGER . ' .ab-item');
    $I->comment('✓ Mobile: Hamburger menu button is present and properly structured');
}

// === TEST 3: TOGGLE FUNCTIONALITY ===
$I->comment('=== TEST 3: Testing sidebar toggle functionality ===');

if ($deviceType === 'desktop') {
    // Desktop: Start with visible sidebar, test hiding it
    $I->comment('Testing sidebar HIDE functionality (Desktop)');
    
    // Get initial sidebar state using custom function
    $initialSidebarVisible = $I->executeJS('return window.isSidebarVisible ? window.isSidebarVisible() : null;');
    $I->comment("Initial sidebar state: " . ($initialSidebarVisible ? 'visible' : 'hidden'));
    
    // Use custom toggle function instead of clicking button directly
    $I->executeJS('
        if (typeof window.toggleSidebarVisibility !== "function") {
            throw new Error("toggleSidebarVisibility function is not available");
        }
        window.toggleSidebarVisibility();
    ');
    $I->comment('✓ Used custom toggleSidebarVisibility() function to hide sidebar');
    
    // Wait for animation to complete
    $I->wait(1);
    
    // Verify sidebar is now hidden using custom function
    $hiddenSidebarVisible = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($hiddenSidebarVisible, 'Sidebar should be hidden after toggle');
    $I->comment('✓ Custom function confirms sidebar is now hidden');
    
    // Verify toggle button shows correct icon
    $I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-right');
    $I->comment('✓ Toggle button shows arrow-right icon (sidebar is hidden)');
    
    // Test showing sidebar again using custom function
    $I->comment('Testing sidebar SHOW functionality (Desktop)');
    $I->executeJS('
        if (typeof window.toggleSidebarVisibility !== "function") {
            throw new Error("toggleSidebarVisibility function is not available");
        }
        window.toggleSidebarVisibility();
    ');
    $I->comment('✓ Used custom toggleSidebarVisibility() function to show sidebar');
    $I->wait(1);
    
    // Verify sidebar is visible again using custom function
    $restoredSidebarVisible = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertTrue($restoredSidebarVisible, 'Sidebar should be visible after toggle');
    $I->comment('✓ Custom function confirms sidebar is now visible');
    
    // Verify toggle button shows correct icon
    $I->seeElement('#wp-admin-bar-sidebar-toggle .dashicons-arrow-left');
    $I->comment('✓ Toggle button shows arrow-left icon (sidebar is visible)');
    
} else {
    // Mobile: Start with hidden sidebar, test showing it
    $I->comment('Testing sidebar SHOW functionality (Mobile)');
    
    // Get initial sidebar state using custom function (should be hidden)
    $initialSidebarVisible = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($initialSidebarVisible, 'Mobile sidebar should start hidden');
    $I->comment("Initial sidebar state: " . ($initialSidebarVisible ? 'visible' : 'hidden'));
    
    // Use custom toggle function to show sidebar
    $I->executeJS('
        if (typeof window.toggleSidebarVisibility !== "function") {
            throw new Error("toggleSidebarVisibility function is not available");
        }
        window.toggleSidebarVisibility();
    ');
    $I->comment('✓ Used custom toggleSidebarVisibility() function to show sidebar');
    
    // Wait for animation to complete
    $I->wait(1);
    
    // Verify sidebar is now visible using custom function
    $shownSidebarVisible = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertTrue($shownSidebarVisible, 'Mobile sidebar should be visible when shown');
    $I->comment('✓ Custom function confirms sidebar is now visible');
    
    // Test hiding sidebar again using custom function
    $I->comment('Testing sidebar HIDE functionality (Mobile)');
    $I->executeJS('
        if (typeof window.toggleSidebarVisibility !== "function") {
            throw new Error("toggleSidebarVisibility function is not available");
        }
        window.toggleSidebarVisibility();
    ');
    $I->comment('✓ Used custom toggleSidebarVisibility() function to hide sidebar');
    $I->wait(1);
    
    // Verify sidebar is hidden again using custom function
    $hiddenSidebarVisible = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($hiddenSidebarVisible, 'Mobile sidebar should be hidden');
    $I->comment('✓ Custom function confirms sidebar is now hidden');
}

// === TEST 4: RAPID CLICKING PREVENTION ===
$I->comment('=== TEST 4: Testing rapid clicking prevention ===');

// Get initial state using custom function
$initialState = $I->executeJS('
    if (typeof window.isSidebarVisible !== "function") {
        throw new Error("isSidebarVisible function is not available");
    }
    return window.isSidebarVisible();
');
$I->comment("Initial state before rapid clicking: " . ($initialState ? 'visible' : 'hidden'));

// Use custom toggle function rapidly
$I->executeJS('
    if (typeof window.toggleSidebarVisibility !== "function") {
        throw new Error("toggleSidebarVisibility function is not available");
    }
    window.toggleSidebarVisibility();
');
$I->comment('✓ First rapid toggle using custom function');

// Immediately toggle again (should be ignored due to animation state)
$I->executeJS('
    if (typeof window.toggleSidebarVisibility !== "function") {
        throw new Error("toggleSidebarVisibility function is not available");
    }
    window.toggleSidebarVisibility();
');
$I->comment('✓ Second rapid toggle using custom function (should be ignored)');

// Wait for animation to complete
$I->wait(1);

// Verify sidebar state is consistent using custom function
$finalState = $I->executeJS('
    if (typeof window.isSidebarVisible !== "function") {
        throw new Error("isSidebarVisible function is not available");
    }
    return window.isSidebarVisible();
');
$expectedState = !$initialState; // Should be opposite of initial state
$I->assertEquals($expectedState, $finalState, 'Sidebar state should change only once despite rapid clicking');
$I->comment("Final state after rapid clicking: " . ($finalState ? 'visible' : 'hidden'));
$I->comment('✓ Rapid clicking handled correctly - sidebar state is consistent');

// === TEST 5: LOCALSTORAGE PERSISTENCE ===
$I->comment('=== TEST 5: localStorage persistence ===');
$I->comment('Testing localStorage persistence functionality');

if ($deviceType === 'desktop') {
    // Ensure sidebar is visible, then hide it using custom functions
    $I->comment('Step 1: Ensuring sidebar is visible, then hiding it');
    
    $currentState = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->comment("Current sidebar state: " . ($currentState ? 'visible' : 'hidden'));
    
    if (!$currentState) {
        // If sidebar is hidden, show it first using custom function
        $I->executeJS('
            if (typeof window.showSidebar !== "function") {
                throw new Error("showSidebar function is not available");
            }
            window.showSidebar();
        ');
        $I->wait(1);
        $I->comment('✓ Sidebar made visible for persistence test using custom function');
    }
    
    // Hide the sidebar using custom function
    $I->executeJS('
        if (typeof window.hideSidebar !== "function") {
            throw new Error("hideSidebar function is not available");
        }
        window.hideSidebar();
    ');
    $I->wait(1);
    
    // Verify sidebar is now hidden using custom function
    $hiddenState = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($hiddenState, 'Sidebar should be hidden');
    $I->comment('✓ Custom function confirms sidebar is now hidden');
    
    // Check if localStorage was set by the custom functions
    $I->comment('Step 2: Checking if sidebar state was saved to localStorage by custom functions');
    $localStorageValue = $I->executeJS('return localStorage.getItem("ai_style_sidebar_visible");');
    $I->comment('localStorage value for sidebar state: ' . ($localStorageValue ?? 'null'));
    
    // Verify localStorage persistence is working
    $I->assertEquals('false', $localStorageValue, 'Sidebar hidden state should be saved to localStorage by custom functions');
    $I->comment('✓ SUCCESS: localStorage persistence is working with custom functions!');
    
    // Reload the page to test persistence
    $I->comment('Step 3: Reloading page to test state persistence');
    $I->reloadPage();
    $I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);
    $I->waitForElement(AcceptanceConfig::CHAT_SIDEBAR, 10);
    $I->wait(2);
    
    // Check if sidebar state was restored using custom function
    $I->comment('Step 4: Checking if sidebar state was restored after page reload');
    $restoredState = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available after page reload");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($restoredState, 'Sidebar should remain hidden after page reload');
    $I->comment('✓ SUCCESS: Custom function confirms sidebar remained hidden after page reload - localStorage persistence is working!');
}

// Take final screenshot
$I->makeScreenshot('sidebar-final-state');
$I->comment("Final state screenshot available");

// === FINAL STATE VERIFICATION ===
$I->comment('=== FINAL STATE VERIFICATION ===');

// Ensure sidebar ends in expected initial state for device mode using custom functions
if ($deviceType === 'desktop') {
    // Desktop should end with sidebar visible (as it started)
    $finalState = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    
    if (!$finalState) {
        // Reset to visible state using custom function
        $I->executeJS('
            if (typeof window.showSidebar !== "function") {
                throw new Error("showSidebar function is not available");
            }
            window.showSidebar();
        ');
        $I->wait(1);
        $I->comment('✓ Reset desktop sidebar to visible state using custom function');
    }
    
    // Verify final state using custom function
    $finalStateConfirmed = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertTrue($finalStateConfirmed, 'Desktop sidebar should be visible at the end');
    $I->comment('✓ Final state: Desktop sidebar is VISIBLE (confirmed by custom function)');
    
} else {
    // Mobile should end with sidebar hidden (as it started)
    $finalState = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    
    if ($finalState) {
        // Reset to hidden state using custom function
        $I->executeJS('
            if (typeof window.hideSidebar !== "function") {
                throw new Error("hideSidebar function is not available");
            }
            window.hideSidebar();
        ');
        $I->wait(1);
        $I->comment('✓ Reset mobile sidebar to hidden state using custom function');
    }
    
    // Verify final state using custom function
    $finalStateConfirmed = $I->executeJS('
        if (typeof window.isSidebarVisible !== "function") {
            throw new Error("isSidebarVisible function is not available");
        }
        return window.isSidebarVisible();
    ');
    $I->assertFalse($finalStateConfirmed, 'Mobile sidebar should be hidden at the end');
    $I->comment('✓ Final state: Mobile sidebar is HIDDEN (confirmed by custom function)');
}

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

$I->comment('✅ Sidebar toggle functionality test completed');
$I->comment('✅ CONFIRMED: Desktop sidebar visible initially, Mobile sidebar hidden initially');
$I->comment('✅ localStorage persistence functionality verified and working');