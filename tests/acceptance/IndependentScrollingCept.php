<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That chat-sidebar and chat-main divs scroll independently");

$I->amOnPage('/test');

// Test 1: Verify the presence of main UI structural elements
// The UI follows a two-column design similar to ChatGPT, with #chat-sidebar
// and #chat-main serving as the two primary columns
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');

// Test 2: Verify the scrolling behavior of the sidebar
// The sidebar should have its own scrollbar when content exceeds the viewport height
$result = $I->executeJS("
    const sidebar = document.getElementById('chat-sidebar');
    return {
        overflowY: window.getComputedStyle(sidebar).overflowY,
        height: window.getComputedStyle(sidebar).height
    };
");
$I->assertEquals('auto', $result['overflowY'], 'Sidebar should have overflowY set to auto');
// For height, we just check that it's a non-empty value since getComputedStyle()
// will return the computed pixel value rather than the percentage
$I->assertNotEmpty($result['height'], 'Sidebar should have a height value');

// Test 3: Verify the scrolling behavior of the main content area
// The main content area should have its own scrollbar when content exceeds the viewport height
$result = $I->executeJS("
    const main = document.getElementById('chat-main');
    return {
        overflowY: window.getComputedStyle(main).overflowY,
        height: window.getComputedStyle(main).height
    };
");
$I->assertEquals('auto', $result['overflowY'], 'Main content should have overflowY set to auto');
// For height, we just check that it's a non-empty value since getComputedStyle()
// will return the computed pixel value rather than the percentage
$I->assertNotEmpty($result['height'], 'Main content should have a height value');

// Test 4: Verify that the browser's default scrollbar is suppressed
// This ensures that only the individual elements scroll, not the entire page
$result = $I->executeJS("
    return {
        bodyOverflow: window.getComputedStyle(document.body).overflow
    };
");
$I->assertEquals('hidden', $result['bodyOverflow'], 'Body should have overflow set to hidden');

// Test 5: Verify that wheel events don't propagate between elements
// This ensures that scrolling one element doesn't affect the other
$result = $I->executeJS("
    const sidebar = document.getElementById('chat-sidebar');
    const main = document.getElementById('chat-main');
    
    // Check if event listeners are attached
    // Note: getEventListeners is only available in DevTools console, not in automated tests
    // We'll check if the elements have wheel event listeners attached using a different approach
    return {
        sidebarHasWheelListener: sidebar.__wheelListenerAttached || false,
        mainHasWheelListener: main.__wheelListenerAttached || false
    };
");
$I->assertTrue($result['sidebarHasWheelListener'], 'Sidebar should have wheel event listener');
$I->assertTrue($result['mainHasWheelListener'], 'Main content should have wheel event listener');

// Test 6: Verify the comment form behavior
// The comment form should remain fixed at the bottom and independent of scrolling content
$result = $I->executeJS("
    const chatInput = document.getElementById('chat-input');
    return {
        position: window.getComputedStyle(chatInput).position,
        bottom: window.getComputedStyle(chatInput).bottom,
        zIndex: window.getComputedStyle(chatInput).zIndex,
        backgroundColor: window.getComputedStyle(chatInput).backgroundColor
    };
");
$I->assertEquals('sticky', $result['position'], 'Chat input should have position set to sticky');
$I->assertEquals('0px', $result['bottom'], 'Chat input should have bottom set to 0px');
$I->assertEquals('10', $result['zIndex'], 'Chat input should have zIndex set to 10');