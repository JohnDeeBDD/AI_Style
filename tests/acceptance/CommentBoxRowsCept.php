<?php
/**
 * CommentBoxRowsCept.php
 *
 * Acceptance test for verifying the comment box has two distinct rows:
 * 1. Text input row - where users type their messages
 * 2. Tools/icons row - containing plus icon, tools button, microphone, and submit arrow
 *
 * This test verifies the ChatGPT-style comment box layout with proper
 * separation between input area and action controls.
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("Comment box has two distinct rows like ChatGPT interface");
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Test 1: Verify the comment box container exists
$I->comment("Testing that comment box container is present");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX);

// Test 2: Verify the first row (text input row) exists
$I->comment("Testing that text input row exists");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row');
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row textarea, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row input[type="text"]');

// Test 3: Verify the second row (tools/icons row) exists
$I->comment("Testing that tools/icons row exists");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row');

// Test 4: Verify the tools row contains expected elements
$I->comment("Testing that tools row contains plus icon");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .plus-icon, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .add-button');

$I->comment("Testing that tools row contains tools button/text");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .tools-button, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .tools-text');

$I->comment("Testing that tools row contains submit button/arrow");
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .submit-arrow, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .submit-button');

// Test 5: Verify the rows are visually separated (different containers)
$I->comment("Testing that input row and tools row are separate elements");
$I->dontSeeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row .comment-tools-row');
$I->dontSeeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row .comment-input-row');

// Test 6: Verify the text input is functional
$I->comment("Testing that text input accepts user input");
$testMessage = "Testing the comment box input functionality";
$I->fillField(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row textarea, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row input[type="text"]', $testMessage);
$I->seeInField(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row textarea, ' . AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row input[type="text"]', $testMessage);

// Test 7: Verify the layout structure matches ChatGPT style
$I->comment("Testing that comment box has proper ChatGPT-style layout");
$I->executeJS("
    const commentBox = document.querySelector('" . AcceptanceConfig::FIXED_COMMENT_BOX . "');
    const commentBoxInner = commentBox.querySelector('.comment-box-inner');
    const inputRow = commentBox.querySelector('.comment-input-row');
    const toolsRow = commentBox.querySelector('.comment-tools-row');
    
    // Verify the unified inner wrapper exists
    if (!commentBoxInner) {
        throw new Error('Missing comment-box-inner wrapper for unified styling');
    }
    
    // Verify both rows exist
    if (!inputRow || !toolsRow) {
        throw new Error('Missing required comment box rows');
    }
    
    // Verify input row comes before tools row in DOM within the inner wrapper
    const inputRowPosition = Array.from(commentBoxInner.children).indexOf(inputRow);
    const toolsRowPosition = Array.from(commentBoxInner.children).indexOf(toolsRow);
    
    if (inputRowPosition >= toolsRowPosition) {
        throw new Error('Input row should come before tools row');
    }
    
    return true;
");

// Test 8: Verify responsive behavior of the two-row layout
$I->comment("Testing that two-row layout is maintained at different screen sizes");
$I->resizeWindow(1200, 800);
$I->wait(1);
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row');
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row');

$I->resizeWindow(768, 600);
$I->wait(1);
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-input-row');
$I->seeElement(AcceptanceConfig::FIXED_COMMENT_BOX . ' .comment-tools-row');

// Reset window size to default after testing responsive behavior
$I->resizeWindow(1920, 1080);
$I->wait(1);

// Take a screenshot of the final state
$I->makeScreenshot('comment-box-rows-test');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/comment-box-rows-test.png' target = '_blank'>available here</a>");

// Take a final comprehensive screenshot showing the complete ChatGPT-style comment box layout
$I->comment("Taking final screenshot to document the complete two-row comment box implementation");
$I->makeScreenshot('comment-box-rows-final-state');
$I->comment("Final test screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/comment-box-rows-final-state.png' target='_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance CommentBoxRowsCept.php -vvv --html --xml"