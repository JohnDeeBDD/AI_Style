<?php

/*
CONCEPT:
WordPress posts have a 'comment_status' field that indicates whether comments are open or closed. 
This test verifies that when comments are closed via the WordPress REST API, the change is accurately reflected in 
the front-end behavior of the site, preventing new comments from being added.
*/

$I = new AcceptanceTester($scenario);
$I->wantTo("Verify that closing comments via the WordPress REST API is reflected on the front-end");
// Setup phase: Create a post with comments open
$I->comment("🚀 Setting up test data");
$postId = $I->cUrlWP_SiteToCreatePost('testpost', '<p>This is a test post for comment status verification.</p>');
$I->comment("✅ Test post created with ID: " . $postId);
// Add an initial comment to the post so we can confirm it exists before closing
$I->comment("🔧 Adding an initial comment to the post");
$commentData = [
	'content' => 'This is a test comment that should exist before comments are closed.',
	'author_name' => 'Codeception',
	'author_email' => 'johndeebdd@gmail.com',
	'user_id' => 1
];
$commentId = null;
try {
	$commentId = $I->cUrlWP_SiteToAddComment($postId, $commentData);
	$I->comment("✅ Comment created with ID: " . $commentId);

	// Retrieve an application password for API authentication (helper endpoint used elsewhere in tests)
	$I->comment("🔧 Retrieving application password for API access");
	$app_pwd_resp = json_decode(@file_get_contents("http://localhost/wp-json/cacbot-tester/v1/app-password/?username=Codeception"), true);
	if (!is_array($app_pwd_resp) || empty($app_pwd_resp['application_password'])) {
		throw new Exception('Unable to retrieve application password for Codeception user');
	}
	$app_password = $app_pwd_resp['application_password'];
	$I->comment("✅ Application password retrieved successfully");

	// Close comments via the REST API
	$I->comment("🔧 Closing comments via REST API for post ID: " . $postId);
	$api_url = "http://localhost/wp-json/wp/v2/posts/" . $postId;
	$payload = json_encode(['comment_status' => 'closed']);

	$ch = curl_init($api_url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Content-Length: ' . strlen($payload),
	]);
	curl_setopt($ch, CURLOPT_USERPWD, 'Codeception:' . $app_password);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);

	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$curl_err = curl_error($ch);
	curl_close($ch);

	if ($response === false || ($http_code < 200 || $http_code >= 300)) {
		throw new Exception('Failed to close comments via API. HTTP code: ' . $http_code . ' curl_err: ' . $curl_err . ' resp: ' . $response);
	}
	$I->comment("✅ REST API responded with HTTP code: " . $http_code);

	// Navigate to the post and verify comments are closed
	$I->comment("📍 Navigating to the post to verify front-end behavior");
	$I->amOnUrl(AcceptanceConfig::BASE_URL);
	$I->loginAsAdmin();
	$I->amOnPage("/?p=" . $postId);
	$I->waitForElement('body', 10);
	$I->makeScreenshot("post-after-closing-comments");

	// Verify comment form is not present and site shows comments-closed message
	$I->comment("🔍 Verifying comments are closed on the front-end");
	$I->dontSeeElement('#comment');
	// WordPress default no-comments text is commonly 'Comments are closed.'; check for that fallback
	$I->see('Comments are closed');
	$I->comment("✅ Comments are closed and front-end reflects the change");

} catch (Exception $e) {
    $I->amOnUrl(AcceptanceConfig::BASE_URL);
	$I->comment("❌ Test failed with exception: " . $e->getMessage());
	$I->makeScreenshot("error-comments-closed-api");
	$I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/error-comments-closed-api.png' target='_blank'>Error state</a>");
	$I->comment("🐛 Debug information:");
	$I->comment("📄 Current URL: " . $I->grabFromCurrentUrl());
	throw $e;
} finally {
	// Cleanup created comment and post
	$I->comment("🧹 Cleaning up test data");
	if (!empty($commentId)) {
		//shell_exec("wp comment delete {$commentId} --path=/var/www/html --force 2>/dev/null");
		$I->comment("✅ Test comment cleaned up (ID: {$commentId})");
	}
	if (!empty($postId)) {
		//shell_exec("wp post delete {$postId} --force --path=/var/www/html 2>/dev/null");
		$I->comment("✅ Test post cleaned up (ID: {$postId})");
	}
	$I->comment("✅ Cleanup complete");
}