<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('create a post with three comments and verify they are visible on the frontend');

// Create a test post
$postTitle = 'Test Post for Comments';
$postContent = 'This is a test post that will receive comments for testing purposes.';

$postId = $I->cUrlWP_SiteToCreatePost($postTitle, $postContent);
$I->assertNotEmpty($postId, 'Post should be created successfully');

// Add first comment
$comment1Data = [
    'content' => 'This is the first test comment on this post.',
    'author_name' => 'Test User 1',
    'author_email' => 'testuser1@example.com'
];
$comment1Id = $I->cUrlWP_SiteToAddComment($postId, $comment1Data);
$I->assertNotEmpty($comment1Id, 'First comment should be created successfully');

// Add second comment
$comment2Data = [
    'content' => 'This is the second test comment with different content.',
    'author_name' => 'Test User 2', 
    'author_email' => 'testuser2@example.com'
];
$comment2Id = $I->cUrlWP_SiteToAddComment($postId, $comment2Data);
$I->assertNotEmpty($comment2Id, 'Second comment should be created successfully');

// Add third comment
$comment3Data = [
    'content' => 'This is the third and final test comment for this post.',
    'author_name' => 'Test User 3',
    'author_email' => 'testuser3@example.com'
];
$comment3Id = $I->cUrlWP_SiteToAddComment($postId, $comment3Data);
$I->assertNotEmpty($comment3Id, 'Third comment should be created successfully');

// Navigate to the post to verify comments are visible
$I->amOnPage("/?p=$postId");
//$I->waitForPageLoad();

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing comment visibility for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Verify the post title and content are displayed
$I->see($postContent);

// Verify all three comments are visible on the post
$I->see($comment1Data['content']);
$I->see($comment2Data['content']);
$I->see($comment3Data['content']);

//The Comments should not be visible on this page!
$I->amOnPage("/category/uncategorized/");

// Configuration-driven approach: Verify comments are not visible on category pages
// Test behavior adapts to current device configuration without dynamic zoom changes
$I->comment("Verifying comments are not displayed on category pages for {$deviceMode} mode");

$I->dontSee($comment1Data['content']);
$I->dontSee($comment2Data['content']);
$I->dontSee($comment3Data['content']);

// Clean up - delete the test post (this will also delete associated comments)
$deleteResult = $I->cUrlWP_SiteToDeletePost($postId);
$I->assertTrue($deleteResult['deleted'], 'Test post should be deleted successfully');