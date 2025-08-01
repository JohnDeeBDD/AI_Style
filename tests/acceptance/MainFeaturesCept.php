<?php
/**
 * MainFeaturesCept.php
 *
 * Acceptance test for verifying that all main UI elements exist and are properly displayed
 * across different device modes (desktop, tablet landscape, tablet portrait, mobile landscape, mobile portrait).
 *
 * This test framework:
 * 1. Automatically detects the current device mode from the test suite configuration
 * 2. Executes device-specific test logic using a switch case framework
 * 3. Checks for presence of main UI divisions appropriate to each device mode
 * 4. Tests chat message display functionality with device-appropriate content
 * 5. Validates comment form elements and interactions
 * 6. Takes device-specific screenshots for visual verification
 *
 * Device Mode Support:
 * - Desktop: Full UI with sidebar, all elements visible
 * - Tablet Landscape: Responsive layout, potentially collapsible sidebar
 * - Tablet Portrait: Stacked layout optimizations
 * - Mobile Landscape: Compact horizontal layout
 * - Mobile Portrait: Most compact vertical layout, touch-optimized
 *
 * Usage Examples:
 * - Desktop: bin/codecept run acceptance MainFeaturesCept.php --env full_hd_desktop
 * - Mobile: bin/codecept run acceptance MainFeaturesCept.php --env iphone8_portrait
 * - Tablet: bin/codecept run acceptance MainFeaturesCept.php --env ipad_landscape
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest("That main divisions of the UI interface exist");

// Create test post with ChatGPT interface content
$I->comment('Creating test post for main features testing');
$postContent = '<p>This is a test post for main features verification. The theme will automatically generate the chat interface with all required UI divisions.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('testpost', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = $I->getDeviceMode();
$windowSize = $I->getWindowSize();
$I->comment("Testing main features for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Device-specific test logic framework
switch ($deviceMode) {
    case AcceptanceConfig::DEVICE_MODE_DESKTOP:
        $I->comment("Executing desktop-specific test logic");
        executeDesktopTests($I);
        break;
        
    case AcceptanceConfig::DEVICE_MODE_TABLET_LANDSCAPE:
        $I->comment("Executing tablet landscape-specific test logic");
        executeTabletLandscapeTests($I);
        break;
        
    case AcceptanceConfig::DEVICE_MODE_TABLET_PORTRAIT:
        $I->comment("Executing tablet portrait-specific test logic");
        executeTabletPortraitTests($I);
        break;
        
    case AcceptanceConfig::DEVICE_MODE_MOBILE_LANDSCAPE:
        $I->comment("Executing mobile landscape-specific test logic");
        executeMobileLandscapeTests($I);
        break;
        
    case AcceptanceConfig::DEVICE_MODE_MOBILE_PORTRAIT:
        $I->comment("Executing mobile portrait-specific test logic");
        executeMobilePortraitTests($I);
        break;
        
    default:
        $I->comment("Unknown device mode: {$deviceMode}. Falling back to desktop logic.");
        executeDesktopTests($I);
        break;
}

// Take a device-specific screenshot
$screenshotName = 'main-features-' . $deviceMode;
$I->makeScreenshot($screenshotName);
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target = '_blank'>available here</a>");

// Run this test with the command: "bin/codecept run acceptance MainFeaturesCept.php -vvv --html"

$I->makeScreenshot('testpost');
$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost.png' target = '_blank'>available here</a>");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');

/**
 * Device-specific test functions
 * Each function contains the specific test logic for different device modes
 */

/**
 * Execute desktop-specific tests
 * @param AcceptanceTester $I
 */
function executeDesktopTests($I) {
    $I->comment("Running desktop-specific UI tests");
    
    // Add test messages for desktop layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Desktop test: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Desktop test: This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");
    
    // Add more messages to test scrolling behavior on desktop
    for ($i = 1; $i <= 4; $i++) {
        $I->executeJS("addInterlocutorMessage('Desktop message {$i}: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
        $I->executeJS("addRespondentMessage('Desktop response {$i}: This is a message from the respondent. This is a message from the respondent. ')");
    }
    
    // Desktop-specific element checks - all elements should be visible
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::POST_CONTENT);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SITE_FOOTER);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // Desktop-specific checks (sidebar should be fully visible)
    $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
}

/**
 * Execute tablet landscape-specific tests
 * @param AcceptanceTester $I
 */
function executeTabletLandscapeTests($I) {
    $I->comment("Running tablet landscape-specific UI tests");
    
    // Add test messages for tablet landscape layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Tablet Landscape: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Tablet Landscape: This is a message from the respondent. ')");
    
    // Add fewer messages for tablet testing
    for ($i = 1; $i <= 3; $i++) {
        $I->executeJS("addInterlocutorMessage('Tablet L message {$i}: Lorem ipsum Lorem ipsum ')");
        $I->executeJS("addRespondentMessage('Tablet L response {$i}: Respondent message. ')");
    }
    
    // Tablet landscape-specific element checks
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::POST_CONTENT);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // TODO: Add tablet landscape-specific logic here
    // For example: sidebar might be collapsible or hidden
    // $I->comment("TODO: Implement tablet landscape-specific sidebar behavior tests");
}

/**
 * Execute tablet portrait-specific tests
 * @param AcceptanceTester $I
 */
function executeTabletPortraitTests($I) {
    $I->comment("Running tablet portrait-specific UI tests");
    
    // Add test messages for tablet portrait layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Tablet Portrait: Lorem ipsum Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Tablet Portrait: Respondent message. ')");
    
    // Add messages for tablet portrait testing
    for ($i = 1; $i <= 3; $i++) {
        $I->executeJS("addInterlocutorMessage('Tablet P message {$i}: Lorem ipsum ')");
        $I->executeJS("addRespondentMessage('Tablet P response {$i}: Response. ')");
    }
    
    // Tablet portrait-specific element checks
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::POST_CONTENT);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // TODO: Add tablet portrait-specific logic here
    // For example: different layout stacking behavior
    // $I->comment("TODO: Implement tablet portrait-specific layout tests");
}

/**
 * Execute mobile landscape-specific tests
 * @param AcceptanceTester $I
 */
function executeMobileLandscapeTests($I) {
    $I->comment("Running mobile landscape-specific UI tests");
    
    // Add test messages for mobile landscape layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Mobile L: Lorem ipsum ')");
    $I->executeJS("addRespondentMessage('Mobile L: Response ')");
    
    // Add fewer messages for mobile testing
    for ($i = 1; $i <= 2; $i++) {
        $I->executeJS("addInterlocutorMessage('Mobile L {$i}: Lorem ')");
        $I->executeJS("addRespondentMessage('Mobile L {$i}: Resp ')");
    }
    
    // Mobile landscape-specific element checks
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // TODO: Add mobile landscape-specific logic here
    // For example: sidebar likely hidden, different input behavior
    // $I->comment("TODO: Implement mobile landscape-specific interaction tests");
}

/**
 * Execute mobile portrait-specific tests
 * @param AcceptanceTester $I
 */
function executeMobilePortraitTests($I) {
    $I->comment("Running mobile portrait-specific UI tests");
    
    // Add test messages for mobile portrait layout
    $I->executeJS("clearMessages()");
    $I->executeJS("addInterlocutorMessage('Mobile P: Lorem ')");
    $I->executeJS("addRespondentMessage('Mobile P: Resp ')");
    
    // Add minimal messages for mobile portrait testing
    for ($i = 1; $i <= 2; $i++) {
        $I->executeJS("addInterlocutorMessage('MP {$i}: Text ')");
        $I->executeJS("addRespondentMessage('MP {$i}: R ')");
    }
    
    // Mobile portrait-specific element checks
    $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
    $I->seeElement(AcceptanceConfig::CHAT_MAIN);
    $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
    $I->seeElement(AcceptanceConfig::CHAT_INPUT);
    $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
    $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
    $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
    
    // TODO: Add mobile portrait-specific logic here
    // For example: most compact layout, touch-optimized interactions
    // $I->comment("TODO: Implement mobile portrait-specific touch interaction tests");
}