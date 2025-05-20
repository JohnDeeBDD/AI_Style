<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest('Fixed comment box alignment issue with scrollable content');
$I->comment('Bug: The fixed comment box should be centered the same way as chat messages, but is centered relative to the entire viewport instead');

$I->amOnUrl('http://localhost');
$I->loginAsAdmin();
$I->amOnPage('/testpost');

// Wait for the page to load completely
$I->waitForElementVisible('#chat-messages', 10);
$I->waitForElementVisible('#fixed-comment-box', 10);
$I->waitForElementVisible('#scrollable-content', 10);

// Execute JavaScript to clear existing messages and add lorem ipsum chat messages
$I->executeJS("
    // Functions from chatMessages.js will be automatically compiled and imported, and are available globally

    // Clear existing messages
    clearMessages();
    
    // Add interlocutor and respondent messages with lorem ipsum content
    addInterlocutorMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Proin vel ante a orci tempus eleifend ut et magna. Sed quis laoreet est, non venenatis quam.');
    
    addRespondentMessage('Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.');
    
    addInterlocutorMessage('Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.');
    
    addRespondentMessage('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris.');
    
    addInterlocutorMessage('Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra. Donec posuere vulputate arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci.');
");

// Wait for messages to be rendered
$I->wait(1);

// Take a screenshot of the current state
$I->makeScreenshot('centered_items_before_test');

// Get the positions and dimensions of the elements
$positions = $I->executeJS("
    const scrollableContent = document.querySelector('#scrollable-content');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    const chatMessages = document.querySelector('#chat-messages');
    
    if (!scrollableContent || !fixedCommentBox || !chatMessages) {
        return { error: 'One or more elements not found' };
    }
    
    const scrollableRect = scrollableContent.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    
    return {
        scrollable: {
            left: scrollableRect.left,
            right: scrollableRect.right,
            width: scrollableRect.width
        },
        fixedComment: {
            left: fixedCommentRect.left,
            right: fixedCommentRect.right,
            width: fixedCommentRect.width
        },
        chatMessages: {
            left: chatMessagesRect.left,
            right: chatMessagesRect.right,
            width: chatMessagesRect.width
        },
        viewport: {
            width: window.innerWidth
        }
    };
");

// Output the positions for debugging
$I->comment('Element positions: ' . json_encode($positions));

// Test for the bug: The fixed comment box should be aligned with the scrollable content
// This test should fail, demonstrating the bug
$I->comment('Testing alignment of fixed comment box with scrollable content (expected to fail)');
$I->executeJS("
    const chatMessages = document.querySelector('#chat-messages');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    
    if (!chatMessages || !fixedCommentBox) {
        return false;
    }
    
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    
    // Add visual indicators to show the alignment
    const indicator1 = document.createElement('div');
    indicator1.style.position = 'fixed';
    indicator1.style.top = '0';
    indicator1.style.left = chatMessagesRect.left + 'px';
    indicator1.style.width = '2px';
    indicator1.style.height = '100%';
    indicator1.style.backgroundColor = 'red';
    indicator1.style.zIndex = '9999';
    document.body.appendChild(indicator1);
    
    const indicator2 = document.createElement('div');
    indicator2.style.position = 'fixed';
    indicator2.style.top = '0';
    indicator2.style.left = fixedCommentRect.left + 'px';
    indicator2.style.width = '2px';
    indicator2.style.height = '100%';
    indicator2.style.backgroundColor = 'blue';
    indicator2.style.zIndex = '9999';
    document.body.appendChild(indicator2);
    
    // Check if the left positions match (with a small tolerance)
    const tolerance = 5; // 5px tolerance
    return Math.abs(chatMessagesRect.left - fixedCommentRect.left) <= tolerance;
");

// Take a screenshot showing the misalignment with the visual indicators
$I->makeScreenshot('centered_items_alignment_issue');

// Add assertions that will fail, demonstrating the bug
$alignmentCorrect = $I->executeJS("
    const chatMessages = document.querySelector('#chat-messages');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    
    if (!chatMessages || !fixedCommentBox) {
        return false;
    }
    
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    
    // Check if the left positions match (with a small tolerance)
    const tolerance = 5; // 5px tolerance
    return Math.abs(chatMessagesRect.left - fixedCommentRect.left) <= tolerance;
");

// This assertion should fail, demonstrating the bug
$I->assertTrue($alignmentCorrect, 'Fixed comment box should be aligned with chat messages');

// Add a comment explaining the expected behavior
$I->comment('Expected: The fixed comment box should be aligned with the chat messages');
$I->comment('Actual: The fixed comment box is now properly aligned with the chat messages');
