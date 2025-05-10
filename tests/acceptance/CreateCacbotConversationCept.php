<?php
/**
 * Test for the "Create Cacbot Conversation" feature
 * 
 * This test verifies that:
 * 1. An admin user can create a new Cacbot Conversation from the admin bar
 * 2. The user is redirected to the edit screen for the new post
 * 3. The post has the correct post type ('cacbot-conversation')
 */

$I = new AcceptanceTester($scenario);
$I->wantTo('Create a new Cacbot Conversation from the admin bar');

// 1. Log in as an admin
$I->amOnUrl("http://localhost");
$I->loginAsAdmin();

// 2. Navigate to the homepage where the admin bar is visible
$I->amOnPage('/testpost');

// 3. Wait for the admin bar to be fully loaded
$I->waitForElement('#wp-admin-bar-new-cacbot-conversation a', 10);

// 4. Click on the "Cacbot Conversation" link in the admin bar
$I->click('#wp-admin-bar-new-cacbot-conversation a');

// 5. Wait for the AJAX request to complete and redirect to happen
$I->waitForJqueryAjax(10);

// 6. Verify that the user is redirected to the edit screen for a new post
$I->waitForElement('#post-body', 10);
$I->seeInCurrentUrl('/wp-admin/post.php');
$I->seeInCurrentUrl('action=edit');

// 7. Confirm that the post has the correct post type ('cacbot-conversation')
$I->see('Cacbot Conversation', '#wpbody-content .wrap h1');
$I->seeElement('input[name="post_type"][value="cacbot-conversation"]');

// 8. Take a screenshot for documentation
$I->makeScreenshot('create-cacbot-conversation');

// Run this test with the command: "bin/codecept run acceptance CreateCacbotConversationCept.php -vvv --html"
// Screen shot can be found at: http://localhost/wp-content/themes/ai_style/tests/_output/debug/create-cacbot-conversation.png