<?php


$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist");
$I->amOnUrl("http://localhost");
$I->loginAsAdmin();
$I->amOnPage('/testpost');


$I->executeJS("clearMessages()");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

$I->executeJS("addInterlocutorMessage('Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum Lorum ipsum ')");
$I->executeJS("addRespondentMessage('This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");

// Check for main UI divisions
$I->seeElement('#chat-container'); //Everything below the adminbar
$I->seeElement('#chat-sidebar'); //to the left
$I->seeElement('#chat-main'); //to the right
$I->seeElement('.post-content'); //The WordPress content. There is no corelation to Chat-GPT for this part
$I->seeElement('#chat-messages'); //The WordPress comments, corolates to the chat area of Chat-GPT
$I->seeElement('#chat-input'); //Contains the actual .comment-form
$I->seeElement('.interlocutor-message'); //Coresponds to Chat-GPT user chat messaages
$I->seeElement('.respondent-message'); //Corresponds to Chat-GPT ai messages
$I->seeElement('.site-footer'); //Corresponds to Chat-GPT footer found in footer.php

// Check for the submit button in the comment form (WordPress outputs input[type=submit] or button[type=submit])
$I->seeElement('input[type=submit], button[type=submit]');

$I->makeScreenshot('testpost');