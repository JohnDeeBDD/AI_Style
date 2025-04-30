<?php

$I = new AcceptanceTester($scenario);
$I->loginAsAdmin();

$I->wantToTest("That chat-sidebar and chat-main divs scroll independently");

$I->amOnPage('/test');

// Test 1: Verify the presence of main UI structural elements
// The UI follows a two-column design similar to ChatGPT, with #chat-sidebar
// and #chat-main serving as the two primary columns
$I->comment('Verifying presence of main UI structural elements');
$I->seeElement('#floating-items-group');
$I->seeElement('#chat-container');
$I->seeElement('#chat-sidebar');
$I->seeElement('#chat-main');
$I->seeElement('#chat-messages');
$I->seeElement('#chat-input');

// Take a screenshot for visual verification
$I->makeScreenshot('IndependentScrollingInitial');
$I->comment('Screenshot saved as IndependentScrollingInitial.png');

// Test 2: Verify CSS properties for independent scrolling
// Both chat-sidebar and chat-main should have specific CSS properties
// that enable them to scroll independently
$I->comment('Verifying CSS properties for independent scrolling');

// Check that body has overflow: hidden to suppress browser's default scrollbar
$bodyOverflow = $I->executeJS("return window.getComputedStyle(document.body).overflow;");
$I->assertEquals('hidden', $bodyOverflow, 'Body should have overflow: hidden');

// Check chat-sidebar CSS properties
$sidebarOverflowY = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-sidebar')).overflowY;");
$sidebarHeight = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-sidebar')).height;");
$I->assertEquals('auto', $sidebarOverflowY, 'chat-sidebar should have overflowY: auto');
$I->assertEquals('100%', $sidebarHeight, 'chat-sidebar should have height: 100%');

// Check chat-main CSS properties
$mainOverflowY = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-main')).overflowY;");
$mainHeight = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-main')).height;");
$I->assertEquals('auto', $mainOverflowY, 'chat-main should have overflowY: auto');
$I->assertEquals('100%', $mainHeight, 'chat-main should have height: 100%');

// Test 3: Verify that wheel event listeners are attached
// The JavaScript should attach wheel event listeners to both elements
$I->comment('Verifying wheel event listeners are attached');
$sidebarHasListener = $I->executeJS("return document.getElementById('chat-sidebar').__wheelListenerAttached === true;");
$mainHasListener = $I->executeJS("return document.getElementById('chat-main').__wheelListenerAttached === true;");
$I->assertTrue($sidebarHasListener, 'chat-sidebar should have a wheel event listener attached');
$I->assertTrue($mainHasListener, 'chat-main should have a wheel event listener attached');

// Test 4: Verify chat-messages area is scrollable
$I->comment('Verifying chat-messages area is scrollable');
$messagesOverflowY = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-messages')).overflowY;");
$messagesFlex = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-messages')).flex;");
$I->assertEquals('auto', $messagesOverflowY, 'chat-messages should have overflowY: auto');
$I->assertTrue(strpos($messagesFlex, '1 1 auto') !== false, 'chat-messages should have flex: 1 1 auto');

// Test 5: Verify chat-input stays fixed at the bottom
$I->comment('Verifying chat-input stays fixed at the bottom');
$inputPosition = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-input')).position;");
$inputBottom = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-input')).bottom;");
$inputZIndex = $I->executeJS("return window.getComputedStyle(document.getElementById('chat-input')).zIndex;");
$I->assertEquals('sticky', $inputPosition, 'chat-input should have position: sticky');
$I->assertEquals('0px', $inputBottom, 'chat-input should have bottom: 0');
$I->assertTrue($inputZIndex > 0, 'chat-input should have a positive z-index');

// Test 6: Verify floating-items-group behavior with no content
// When there are no comments or content, #floating-items-group should be 
// in the middle of the screen vertically
$I->comment('Verifying floating-items-group behavior with no content');

// First, ensure we have no content by clearing any existing content
$I->executeJS("
    const messagesElement = document.getElementById('chat-messages');
    if (messagesElement) {
        messagesElement.innerHTML = '';
    }
");

// Get the position of the floating-items-group and the viewport height
$floatingItemsRect = $I->executeJS("return document.getElementById('floating-items-group').getBoundingClientRect();");
$viewportHeight = $I->executeJS("return window.innerHeight;");

// Calculate the vertical center of the floating-items-group
$floatingItemsCenter = $floatingItemsRect['top'] + ($floatingItemsRect['height'] / 2);
$viewportCenter = $viewportHeight / 2;

// Check if the floating-items-group is approximately in the middle of the screen
// Allow for a 20% margin of error to account for other UI elements
$marginOfError = $viewportHeight * 0.2;
$I->assertTrue(
    abs($floatingItemsCenter - $viewportCenter) <= $marginOfError,
    'With no content, floating-items-group should be approximately in the middle of the screen vertically'
);

// Test 7: Verify floating-items-group behavior with content
// When there are comments or content, #floating-items-group should be 
// fixed near the bottom of the screen
$I->comment('Verifying floating-items-group behavior with content');

// Add some content to the chat-messages
$I->executeJS("
    const messagesElement = document.getElementById('chat-messages');
    if (messagesElement) {
        // Add enough content to make the area scrollable
        let content = '';
        for (let i = 0; i < 20; i++) {
            content += '<div style=\"height: 100px; margin: 10px; background-color: #f0f0f0;\">Test content ' + i + '</div>';
        }
        messagesElement.innerHTML = content;
    }
");

// Get the updated position of the floating-items-group
$floatingItemsRect = $I->executeJS("return document.getElementById('floating-items-group').getBoundingClientRect();");

// Check if the floating-items-group is near the bottom of the screen
// The bottom of the floating-items-group should be close to the bottom of the viewport
$distanceFromBottom = $viewportHeight - $floatingItemsRect['bottom'];
$I->assertTrue(
    $distanceFromBottom <= $viewportHeight * 0.3,
    'With content, floating-items-group should be fixed near the bottom of the screen'
);

// Test 8: Verify independent scrolling behavior
// Simulate scrolling in chat-sidebar and ensure it doesn't affect chat-main
$I->comment('Verifying independent scrolling behavior');

// First, get initial scroll positions
$initialSidebarScroll = $I->executeJS("return document.getElementById('chat-sidebar').scrollTop;");
$initialMainScroll = $I->executeJS("return document.getElementById('chat-main').scrollTop;");

// Simulate scrolling in chat-sidebar
$I->executeJS("document.getElementById('chat-sidebar').scrollTop = 100;");

// Get updated scroll positions
$updatedSidebarScroll = $I->executeJS("return document.getElementById('chat-sidebar').scrollTop;");
$updatedMainScroll = $I->executeJS("return document.getElementById('chat-main').scrollTop;");

// Verify that chat-sidebar scrolled but chat-main didn't
$I->assertTrue($updatedSidebarScroll > $initialSidebarScroll, 'chat-sidebar should have scrolled');
$I->assertEquals($initialMainScroll, $updatedMainScroll, 'chat-main scroll position should remain unchanged');

// Now simulate scrolling in chat-main
$I->executeJS("document.getElementById('chat-main').scrollTop = 100;");

// Get updated scroll positions again
$finalSidebarScroll = $I->executeJS("return document.getElementById('chat-sidebar').scrollTop;");
$finalMainScroll = $I->executeJS("return document.getElementById('chat-main').scrollTop;");

// Verify that chat-main scrolled but chat-sidebar didn't change from its previous position
$I->assertEquals($updatedSidebarScroll, $finalSidebarScroll, 'chat-sidebar scroll position should remain unchanged');
$I->assertTrue($finalMainScroll > $initialMainScroll, 'chat-main should have scrolled');

// Take a final screenshot with content for visual verification
$I->makeScreenshot('IndependentScrollingWithContent');
$I->comment('Screenshot saved as IndependentScrollingWithContent.png');
