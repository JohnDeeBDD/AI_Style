6+<?php
/**
 * ChatMessagesCept.php
 *
 * Acceptance test for verifying the chat message functionality.
 *
 * This test follows a comprehensive flow:
 * 1. Setup test data (post and user authentication)
 * 2. Create a test post with chat interface
 * 3. Add interlocutor message via form submission
 * 4. Add respondent message via cURL API
 * 5. Verify both messages display correctly
 * 6. Clean up test data
 */

$I = new AcceptanceTester($scenario);

$I->comment("🎯 Test: Chat Messages Functionality");
$I->comment("📋 Objective: Verify chat interface can handle interlocutor and respondent messages");
$I->expect("Chat messages should display correctly with proper user attribution");

try {
    // Setup phase
    $I->comment("🚀 Starting test setup");
    $I->makeScreenshot("initial-state");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/initial-state.png' target='_blank'>Initial test state</a>");

    // Get application password for API authentication
    $I->comment("🔧 Retrieving application password for API access");
    $app_password = json_decode(file_get_contents("http://localhost/wp-json/cacbot-tester/v1/app-password/?username=Codeception"), true);
    $app_password = $app_password['application_password'];
    $I->comment("✅ Application password retrieved successfully");

    // Create test post with ChatGPT interface content
    $I->comment("🔧 Setting up test data");
    $I->comment("📝 Creating test post for chat messages testing");
    $postContent = '<p>This is a test post for chat message functionality verification. The theme will automatically generate the chat interface with message handling capabilities.</p>';
    $postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
    $I->comment("✅ Test post created with ID: " . $postId);

    // Set post meta data for cacbot interlocutor user ID
    $I->comment("🔧 Setting post meta data: _cacbot_interlocutor_user_id = 1");
    $I->cUrlWP_SiteToSetCacbotMeta("http://localhost", "Codeception", $app_password, $postId, "_cacbot_interlocutor_user_id", "1");
    $I->comment("✅ Post meta data configured successfully");

    // Navigation phase
    $I->comment("📍 Step 1: Navigate to test post");
    $I->amOnUrl(AcceptanceConfig::BASE_URL);
    $I->loginAsAdmin();
    $I->amOnPage("/?p=" . $postId);
    $I->comment("✅ Successfully navigated to test post");
    
    $I->makeScreenshot("post-loaded");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/post-loaded.png' target='_blank'>Test post loaded</a>");

    // Interlocutor message submission
    $I->comment("✏️ Step 2: Submit interlocutor message");
    $I->expect("Comment form should be available for message input");
    
    $I->waitForElement("#comment", 10);
    $I->fillField("#comment", "Initial interlocutor message to start the chat.");
    $I->makeScreenshot("comment-form-filled");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/comment-form-filled.png' target='_blank'>Comment form filled</a>");
    
    $I->comment("🔘 Submitting comment form");
    $I->click("#submit");
    
    // Wait for form submission to complete
    $I->comment("⏳ Waiting for comment submission to process");
    $I->waitForText("Initial interlocutor message to start the chat.", 15);
    $I->comment("✅ Interlocutor message submitted and displayed");
    
    $I->makeScreenshot("interlocutor-message-posted");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/interlocutor-message-posted.png' target='_blank'>Interlocutor message posted</a>");

    // Respondent message via API
    $I->comment("🔧 Step 3: Add respondent message via cURL API");
    $I->expect("API should accept respondent message with proper user attribution");
    
    $respondentCommentData = [
        'content' => 'This is the respondent message added via cURL in response to the interlocutor message.',
        'author_name' => 'Assistant',
        'author_email' => 'assistant@cacbot.com',
        'user_id' => 2  // Set as AI/bot user
    ];
    $respondentCommentId = $I->cUrlWP_SiteToAddComment($postId, $respondentCommentData);
    $I->comment("✅ Respondent comment created with ID: " . $respondentCommentId);

    // Verification phase
    $I->comment("🔍 Step 4: Verify both messages display correctly");
    $I->comment("⏳ Waiting for comment monitoring system to process new comments");
    
    // Wait for the comment monitoring system to process the new comments
    $I->waitForText("This is the respondent message added via cURL in response to the interlocutor message.", 15);
    $I->comment("✅ Respondent message is visible on page");

    // Comprehensive verification
    $I->expect("Both interlocutor and respondent messages should be visible");
    $I->see("Initial interlocutor message to start the chat.");
    $I->see("This is the respondent message added via cURL in response to the interlocutor message.");
    $I->comment("✅ Both messages verified successfully");

    $I->makeScreenshot("chat-messages-complete");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/chat-messages-complete.png' target='_blank'>Complete chat conversation</a>");

    // Additional verification for message structure
    $I->comment("🔍 Verifying message structure and attribution");
    $I->expect("Messages should have proper HTML structure and user attribution");
    
    // Check for message structure (using correct selectors based on theme)
    $I->seeElement('.message');
    $I->comment("✅ Message elements found with proper structure");
    
    // Verify specific message types exist
    $I->seeElement('.interlocutor-message');
    $I->comment("✅ Interlocutor message element found");
    
    $I->seeElement('.respondent-message');
    $I->comment("✅ Respondent message element found");
    
    // Verify message content containers
    $I->seeElement('.message-content');
    $I->comment("✅ Message content containers found");

    $I->comment("✅ Test completed successfully");

} catch (Exception $e) {
    $I->comment("❌ Test failed with exception: " . $e->getMessage());
    $I->makeScreenshot("error-state");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/error-state.png' target='_blank'>Error state for debugging</a>");
    
    // Additional debug information
    $I->comment("🐛 Debug information:");
    $I->comment("📄 Current URL: " . $I->grabFromCurrentUrl());
    
    throw $e;
} finally {
    // Cleanup phase
    $I->comment("🧹 Cleaning up test data");
    
    // Remove test post and associated comments
    if (isset($postId)) {
        shell_exec("wp post delete {$postId} --force --path=/var/www/html");
        $I->comment("✅ Test post and comments cleaned up");
    }
    
    $I->comment("✅ Cleanup complete");
}