<?php

$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist");
$I->amOnUrl("http://localhost");
$I->amOnPage('/test');

// Check for main UI divisions
$I->seeElement('#chat-container'); //Everything below the adminbar
$I->seeElement('#chat-sidebar'); //to the left
$I->seeElement('#chat-main'); //to the right
$I->seeElement('.post-content'); //The WordPress content. There is no corelation to Chat-GPT for this part
$I->seeElement('#chat-messages'); //The WordPress comments, corolates to the chat area of Chat-GPT
$I->seeElement('#floating-items-group'); //The WordPress comment textarea. Stays fixed near bottom of screen once any comment appears.
$I->seeElement('#chat-input'); //Contains the actual .comment-form
$I->seeElement('.interlocutor-message'); //Coresponds to Chat-GPT user chat messaages
$I->seeElement('.respondent-message'); //Corresponds to Chat-GPT ai messages
$I->seeElement('.site-footer'); //Corresponds to Chat-GPT footer found in footer.php

// Check for the submit button in the comment form (WordPress outputs input[type=submit] or button[type=submit])
$I->seeElement('input[type=submit], button[type=submit]');

$I->makeScreenshot('ScreenCaptureCept'); // The screen shot can be found at http://localhost/wp-content/themes/ai_style/tests/_output/debug/ScreenCaptureCept.png

// An examople from Chat-GPT is /var/www/html/wp-content/themes/ai_style/tests/_data/Chat-GPT_screenshot.png
// Create post object
$my_post = array(
  'post_title'    => "title",
  'post_content'  => "hi",
  'post_status'   => 'publish',
  'post_author'   => 1,
  );
  
  // Insert the post into the database
  wp_insert_post( $my_post );