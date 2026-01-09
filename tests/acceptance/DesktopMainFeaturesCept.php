<?php

/**
 * @group DesktopMainFeatures
 * @group UI
 * @group Desktop
 */

// Initialize the Acceptance Tester
$I = new AcceptanceTester($scenario);

$I->comment("Concept: Desktop UI layout system displays all main interface elements correctly when viewport width is >= 768px");
$I->comment("🎯 Test: Desktop Main Features Verification");
$I->comment("📋 Objective: Verify that all main UI divisions exist and are properly displayed on desktop breakpoints");
$I->expect("Desktop layout should display full UI with sidebar, chat interface, and all interactive elements");

$I->comment("🚀 Starting desktop main features test setup");

// Initialize variables for cleanup
$postId = null;

try {
    $I->comment("🔧 Setting up test data");
    
    // Create test post using WP-CLI for better reliability
    $I->comment("🔨 Creating test post for desktop main features testing");
    $postContent = '<p>This is a test post for desktop main features verification. The theme will automatically generate the chat interface with all required UI divisions.</p>';
    $postId = $I->cUrlWP_SiteToCreatePost('testpost-desktop', $postContent);
    $I->comment("✅ Test post created with ID: " . $postId);
    
    $I->comment("📍 Navigating to WordPress admin and test page");
    $I->amOnUrl(AcceptanceConfig::BASE_URL);
    $I->loginAsAdmin();
    $I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
    
    // Wait for page to load completely
    $I->comment("⏳ Waiting for page to load completely");
    $I->waitForElement('body', 10);
    
    $I->comment("🔍 Checking device breakpoint");
    $isMobile = $I->isMobileBreakpoint();
    $deviceType = $isMobile ? 'mobile' : 'desktop';
    $I->comment("ℹ️ Detected device type: {$deviceType}");
    
    if ($isMobile) {
        $I->comment("⚠️ This is a mobile breakpoint (< 768px). Skipping desktop-specific tests.");
        $I->makeScreenshot("mobile-breakpoint-detected");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-breakpoint-detected.png' target='_blank'>Mobile breakpoint state</a>");
        return; // Exit early for mobile breakpoints - cleanup will happen in finally block
    }
    
    $I->comment("✅ Detected desktop breakpoint - proceeding with desktop-specific tests");
    
    $I->comment("📝 Testing desktop UI layout and elements");
    executeDesktopTests($I);
    
    $I->comment("📸 Taking desktop-specific screenshots");
    $I->makeScreenshot("main-features-desktop");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/main-features-desktop.png' target='_blank'>Desktop main features state</a>");
    
    $I->makeScreenshot("testpost-desktop");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/testpost-desktop.png' target='_blank'>Test post desktop view</a>");
    
    $I->comment("✅ Desktop main features test completed successfully");
    
} catch (Exception $e) {
    $I->comment("❌ Error during test execution: " . $e->getMessage());
    $I->makeScreenshot("desktop-test-error");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/desktop-test-error.png' target='_blank'>Error state</a>");
    throw $e;
} finally {
    // Cleanup test data
    if ($postId) {
        $I->comment("🧹 Cleaning up test data");
        try {
            $I->cUrlWP_SiteToDeletePost($postId);
            $I->comment("✅ Test post deleted successfully");
        } catch (Exception $e) {
            $I->comment("⚠️ Warning during cleanup: " . $e->getMessage());
            $I->makeScreenshot("cleanup-error");
            $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/cleanup-error.png' target='_blank'>Cleanup error state</a>");
        }
    }
    $I->comment("✅ Cleanup complete");
}

/**
 * Execute desktop-specific tests (window width >= 768px)
 * @param AcceptanceTester $I
 */
function executeDesktopTests($I) {
    $I->comment("🚀 Running desktop-specific UI tests");
    
    try {
        $I->comment("🔧 Setting up test messages for desktop layout verification");
        
        // Clear existing messages and add test content
        $I->executeJS("clearMessages()");
        $I->comment("🧹 Cleared existing messages");
        
        $I->comment("💬 Adding test messages for desktop layout testing");
        $I->executeJS("addInterlocutorMessage('Desktop test: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
        $I->executeJS("addRespondentMessage('Desktop test: This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. This is a message from the respondent. ')");
        
        // Add multiple messages to test scrolling behavior on desktop
        $I->comment("📝 Adding multiple messages to test desktop scrolling behavior");
        for ($i = 1; $i <= 4; $i++) {
            $I->executeJS("addInterlocutorMessage('Desktop message {$i}: Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum ')");
            $I->executeJS("addRespondentMessage('Desktop response {$i}: This is a message from the respondent. This is a message from the respondent. ')");
        }
        $I->comment("✅ Test messages added successfully");
        
        $I->comment("🔍 Verifying desktop-specific UI elements");
        $I->expect("All main UI elements should be visible on desktop layout");
        
        // Desktop-specific element checks - all elements should be visible
        $I->comment("📋 Checking main container elements");
        $I->waitForElement(AcceptanceConfig::CHAT_CONTAINER, 10);
        $I->seeElement(AcceptanceConfig::CHAT_CONTAINER);
        $I->seeElement(AcceptanceConfig::CHAT_SIDEBAR);
        $I->seeElement(AcceptanceConfig::CHAT_MAIN);
        $I->seeElement(AcceptanceConfig::POST_CONTENT);
        
        $I->comment("💬 Checking chat interface elements");
        $I->seeElement(AcceptanceConfig::CHAT_MESSAGES);
        $I->seeElement(AcceptanceConfig::CHAT_INPUT);
        $I->seeElement(AcceptanceConfig::INTERLOCUTOR_MESSAGE);
        $I->seeElement(AcceptanceConfig::RESPONDENT_MESSAGE);
        
        $I->comment("🔘 Checking interactive elements");
        $I->seeElement(AcceptanceConfig::SUBMIT_BUTTON);
        
        $I->comment("🌐 Checking page structure elements");
        $I->seeElement(AcceptanceConfig::SITE_FOOTER);
        
        // Desktop-specific checks (sidebar should be fully visible)
        $I->comment("📱 Checking desktop-specific sidebar elements");
        $I->seeElement(AcceptanceConfig::ADMIN_BAR_SIDEBAR_TOGGLE);
        
        $I->comment("✅ All desktop UI elements verified successfully");
        
    } catch (Exception $e) {
        $I->comment("❌ Error during desktop UI verification: " . $e->getMessage());
        $I->comment("🐛 Debug info - Current URL: " . ($I->grabFromCurrentUrl() ?? 'Unknown'));
        $I->makeScreenshot("desktop-ui-verification-error");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/desktop-ui-verification-error.png' target='_blank'>Desktop UI verification error</a>");
        throw $e;
    }
    
    $I->comment("✅ Desktop-specific tests completed successfully");
}