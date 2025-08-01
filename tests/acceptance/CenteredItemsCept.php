<?php
/**
 * CenteredItemsCept.php
 *
 * Acceptance test for verifying the alignment of the fixed comment box with scrollable content.
 *
 * This test validates that the fixed comment box at the bottom of the page maintains
 * proper horizontal alignment with the chat messages container, ensuring a consistent
 * visual layout regardless of content length or scrolling behavior.
 *
 * Test Objectives:
 * 1. Verify the fixed comment box is horizontally aligned with chat messages
 * 2. Confirm alignment remains consistent with different content lengths
 * 3. Document any misalignment issues with visual evidence
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Fixed comment box alignment with scrollable content');
$I->comment('=== STARTING ALIGNMENT TEST ===');
$I->comment('This test verifies that the fixed comment box maintains proper horizontal alignment with the chat messages container');

$I->comment('STEP 1: Create test post with ChatGPT interface content');
$I->comment('Creating a test post that will render the ChatGPT-like interface');
$postContent = '<p>This is a test post for the ChatGPT-like interface. The theme will automatically generate the chat container, sidebar, and messaging interface around this content.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->comment('STEP 2: Navigate to test environment and authenticate');
$I->comment('Loading the base URL and logging in as admin to access test content');
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->comment('Successfully authenticated as admin user');

$I->comment('STEP 3: Navigate to the test post page');
$I->comment('Accessing the specific post page that contains the chat interface');
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing centered items for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

$I->comment('STEP 4: Wait for critical UI elements to load');
$I->comment('Ensuring all required elements are present before proceeding with alignment tests');
$I->waitForElementVisible(AcceptanceConfig::CHAT_MESSAGES, 10);
$I->comment('✓ Chat messages container is visible and ready');
$I->waitForElementVisible(AcceptanceConfig::FIXED_COMMENT_BOX, 10);
$I->comment('✓ Fixed comment box is visible and ready');
$I->waitForElementVisible(AcceptanceConfig::SCROLLABLE_CONTENT, 10);
$I->comment('✓ Scrollable content container is visible and ready');

$I->comment('STEP 5: Prepare test data by populating chat messages');
$I->comment('Clearing any existing messages and adding standardized lorem ipsum content to test alignment');
$I->comment('This ensures consistent test conditions regardless of previous page state');

$I->executeJS("
    // Clear any existing chat messages to start with a clean slate
    clearMessages();
");
$I->comment('✓ Existing messages cleared successfully');

$I->comment('Adding interlocutor messages (left-aligned messages from the conversation partner)');
$I->executeJS("
    addInterlocutorMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Proin vel ante a orci tempus eleifend ut et magna. Sed quis laoreet est, non venenatis quam.');
");

$I->executeJS("
    addInterlocutorMessage('Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.');
");

$I->executeJS("
    addInterlocutorMessage('Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra. Donec posuere vulputate arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci.');
");
$I->comment('✓ Added 3 interlocutor messages with varying lengths');

$I->comment('Adding respondent messages (right-aligned messages from the current user)');
$I->executeJS("
    addRespondentMessage('Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.');
");

$I->executeJS("
    addRespondentMessage('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum. Sed aliquam ultrices mauris. Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris.');
");
$I->comment('✓ Added 2 respondent messages with varying lengths');

$I->comment('Allowing time for DOM updates and message rendering to complete');
$I->wait(1);
$I->comment('✓ Message rendering completed');

$I->comment('STEP 6: Capture baseline screenshot for visual reference');
$I->comment('Taking a screenshot of the current state before performing alignment analysis');
$I->makeScreenshot('centered_items_before_test');
$I->comment("📸 Baseline screenshot captured");
$I->comment("View screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/centered_items_before_test.png' target='_blank'>centered_items_before_test.png</a>");

$I->comment('STEP 7: Measure element positions and dimensions');
$I->comment('Collecting precise positioning data for all relevant UI elements to analyze alignment');

$positions = $I->executeJS("
    // Get references to the three key elements we need to analyze
    const scrollableContent = document.querySelector('#scrollable-content');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    const chatMessages = document.querySelector('#chat-messages');
    
    // Verify all elements exist before proceeding
    if (!scrollableContent || !fixedCommentBox || !chatMessages) {
        return { error: 'One or more critical elements not found in DOM' };
    }
    
    // Get bounding rectangles for precise positioning data
    const scrollableRect = scrollableContent.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    
    // Return structured data for analysis
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

$I->comment('✓ Element positioning data collected successfully');
$I->comment('📊 Raw positioning data: ' . json_encode($positions, JSON_PRETTY_PRINT));

// Validate that we got valid positioning data
if (isset($positions['error'])) {
    $I->comment('❌ ERROR: ' . $positions['error']);
    $I->fail('Could not locate required DOM elements for alignment testing');
}

$I->comment('STEP 8: Create visual alignment indicators for debugging');
$I->comment('Adding colored vertical lines to visually demonstrate the alignment (or misalignment) of elements');
$I->comment('Red line = Chat messages left edge | Blue line = Fixed comment box left edge');

$alignmentTestResult = $I->executeJS("
    // Get fresh references to the elements
    const chatMessages = document.querySelector('#chat-messages');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    
    if (!chatMessages || !fixedCommentBox) {
        return { success: false, error: 'Required elements not found' };
    }
    
    // Get current positioning
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    
    // Create visual indicator for chat messages left edge (RED LINE)
    const chatMessagesIndicator = document.createElement('div');
    chatMessagesIndicator.id = 'chat-messages-alignment-indicator';
    chatMessagesIndicator.style.position = 'fixed';
    chatMessagesIndicator.style.top = '0';
    chatMessagesIndicator.style.left = chatMessagesRect.left + 'px';
    chatMessagesIndicator.style.width = '3px';
    chatMessagesIndicator.style.height = '100%';
    chatMessagesIndicator.style.backgroundColor = 'red';
    chatMessagesIndicator.style.zIndex = '9999';
    chatMessagesIndicator.style.opacity = '0.8';
    document.body.appendChild(chatMessagesIndicator);
    
    // Create visual indicator for fixed comment box left edge (BLUE LINE)
    const fixedCommentIndicator = document.createElement('div');
    fixedCommentIndicator.id = 'fixed-comment-alignment-indicator';
    fixedCommentIndicator.style.position = 'fixed';
    fixedCommentIndicator.style.top = '0';
    fixedCommentIndicator.style.left = fixedCommentRect.left + 'px';
    fixedCommentIndicator.style.width = '3px';
    fixedCommentIndicator.style.height = '100%';
    fixedCommentIndicator.style.backgroundColor = 'blue';
    fixedCommentIndicator.style.zIndex = '9998';
    fixedCommentIndicator.style.opacity = '0.8';
    document.body.appendChild(fixedCommentIndicator);
    
    // Calculate alignment difference
    const alignmentDifference = Math.abs(chatMessagesRect.left - fixedCommentRect.left);
    const tolerance = 10; // 10px tolerance for acceptable alignment
    const isAligned = alignmentDifference <= tolerance;
    
    return {
        success: true,
        chatMessagesLeft: chatMessagesRect.left,
        fixedCommentLeft: fixedCommentRect.left,
        alignmentDifference: alignmentDifference,
        tolerance: tolerance,
        isAligned: isAligned
    };
");

if ($alignmentTestResult['success']) {
    $I->comment('✓ Visual alignment indicators created successfully');
    $I->comment('📏 Chat messages left position: ' . $alignmentTestResult['chatMessagesLeft'] . 'px');
    $I->comment('📏 Fixed comment box left position: ' . $alignmentTestResult['fixedCommentLeft'] . 'px');
    $I->comment('📏 Alignment difference: ' . $alignmentTestResult['alignmentDifference'] . 'px');
    $I->comment('📏 Tolerance threshold: ' . $alignmentTestResult['tolerance'] . 'px');
    
    if ($alignmentTestResult['isAligned']) {
        $I->comment('✅ Elements appear to be properly aligned (within tolerance)');
    } else {
        $I->comment('❌ Elements appear to be misaligned (exceeds tolerance)');
    }
} else {
    $I->comment('❌ Failed to create visual indicators: ' . $alignmentTestResult['error']);
}

$I->comment('STEP 9: Capture screenshot with visual alignment indicators');
$I->comment('Taking a screenshot that shows the colored alignment lines for visual verification');
$I->makeScreenshot('centered_items_alignment_analysis');
$I->comment("📸 Alignment analysis screenshot captured");
$I->comment("View screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/centered_items_alignment_analysis.png' target='_blank'>centered_items_alignment_analysis.png</a>");
$I->comment("🔍 Look for red and blue vertical lines - they should overlap if elements are properly aligned");

$I->comment('STEP 10: Perform final alignment verification');
$I->comment('Executing the definitive alignment test to determine pass/fail status');

$finalAlignmentCheck = $I->executeJS("
    // Get fresh element references for final verification
    const chatMessages = document.querySelector('#chat-messages');
    const fixedCommentBox = document.querySelector('#fixed-comment-box');
    
    if (!chatMessages || !fixedCommentBox) {
        return {
            success: false,
            error: 'Critical elements missing from DOM'
        };
    }
    
    // Get current positioning data
    const chatMessagesRect = chatMessages.getBoundingClientRect();
    const fixedCommentRect = fixedCommentBox.getBoundingClientRect();
    
    // Calculate precise alignment metrics
    const leftPositionDifference = Math.abs(chatMessagesRect.left - fixedCommentRect.left);
    const tolerance = 10; // 10px tolerance for acceptable alignment
    const isProperlyAligned = leftPositionDifference <= tolerance;
    
    return {
        success: true,
        chatMessagesLeft: chatMessagesRect.left,
        fixedCommentLeft: fixedCommentRect.left,
        difference: leftPositionDifference,
        tolerance: tolerance,
        aligned: isProperlyAligned,
        details: {
            chatMessagesWidth: chatMessagesRect.width,
            fixedCommentWidth: fixedCommentRect.width,
            viewportWidth: window.innerWidth
        }
    };
");

if ($finalAlignmentCheck['success']) {
    $alignmentCorrect = $finalAlignmentCheck['aligned'];
    
    $I->comment('📊 FINAL ALIGNMENT ANALYSIS:');
    $I->comment('   • Chat messages left edge: ' . $finalAlignmentCheck['chatMessagesLeft'] . 'px');
    $I->comment('   • Fixed comment box left edge: ' . $finalAlignmentCheck['fixedCommentLeft'] . 'px');
    $I->comment('   • Difference: ' . $finalAlignmentCheck['difference'] . 'px');
    $I->comment('   • Tolerance: ' . $finalAlignmentCheck['tolerance'] . 'px');
    $I->comment('   • Result: ' . ($alignmentCorrect ? 'ALIGNED ✅' : 'MISALIGNED ❌'));
    
    // Perform the assertion with detailed error message
    $I->assertTrue(
        $alignmentCorrect, 
        sprintf(
            'Fixed comment box alignment failed: Expected difference ≤ %dpx, but got %dpx difference (Chat: %dpx, Fixed: %dpx)',
            $finalAlignmentCheck['tolerance'],
            $finalAlignmentCheck['difference'],
            $finalAlignmentCheck['chatMessagesLeft'],
            $finalAlignmentCheck['fixedCommentLeft']
        )
    );
    
    if ($alignmentCorrect) {
        $I->comment('🎉 SUCCESS: The fixed comment box is properly aligned with the chat messages container');
        $I->comment('The horizontal positioning meets the specified tolerance requirements');
    }
} else {
    $I->comment('❌ CRITICAL ERROR: ' . $finalAlignmentCheck['error']);
    $I->fail('Unable to complete alignment verification due to missing DOM elements');
}

$I->comment('STEP 11: Cleanup test data');
$I->comment('Removing the test post to clean up after the test');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

$I->comment('=== ALIGNMENT TEST COMPLETED ===');
