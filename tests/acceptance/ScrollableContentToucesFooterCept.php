<?php

/**
 * @group ScrollableContentTouchesFooter
 * @group UI
 * @group Desktop
 * @group Scrolling
 */

// Initialize the Acceptance Tester
$I = new AcceptanceTester($scenario);

$I->comment("Concept: Desktop UI should display scrollable content that requires scrolling to reach the footer");
$I->comment("🎯 Test: Scrollable Content Touches Footer Verification");
$I->comment("📋 Objective: Verify that content with sufficient length requires scrolling to see the footer on desktop breakpoints");
$I->expect("Desktop layout should display content that extends beyond viewport height, requiring scroll to reach footer");

$I->comment("🚀 Starting scrollable content touches footer test setup");

// Initialize variables for cleanup
$postId = null;

try {
    $I->comment("🔧 Setting up test data with extensive content");
    
    // Create extensive Lorem ipsum content that will definitely require scrolling
    $loremIpsum = "
    <h2>Lorem Ipsum Dolor Sit Amet</h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    
    <h3>Sed ut perspiciatis unde omnis</h3>
    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
    
    <h3>Neque porro quisquam est</h3>
    <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?</p>
    
    <h3>At vero eos et accusamus</h3>
    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
    
    <h3>Et harum quidem rerum</h3>
    <p>Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.</p>
    
    <h3>Temporibus autem quibusdam</h3>
    <p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
    
    <h3>Lorem ipsum dolor sit amet</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
    
    <h3>Excepteur sint occaecat</h3>
    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    
    <h3>Nemo enim ipsam voluptatem</h3>
    <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.</p>
    
    <h3>Ut enim ad minima veniam</h3>
    <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
    
    <h3>At vero eos et accusamus et iusto</h3>
    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
    
    <h3>Nam libero tempore</h3>
    <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae.</p>
    
    <h3>Final Section - More Content</h3>
    <p>This is additional content to ensure we have enough text to require scrolling on desktop viewports. The footer should only be visible after scrolling down through all this content. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    
    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
    
    <p>This should be enough content to test scrollable behavior and footer visibility on desktop breakpoints.</p>
    ";
    
    // Create test post using WP-CLI for better reliability
    $I->comment("🔨 Creating test post with extensive content for scrollable footer testing");
    $postId = $I->cUrlWP_SiteToCreatePost('testpost-scrollable-content', $loremIpsum);
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
        $I->comment("⚠️ This is a mobile breakpoint (< 782px). Skipping desktop-specific scrollable content tests.");
        $I->makeScreenshot("mobile-breakpoint-detected-scrollable");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/mobile-breakpoint-detected-scrollable.png' target='_blank'>Mobile breakpoint state</a>");
        return; // Exit early for mobile breakpoints - cleanup will happen in finally block
    }
    
    $I->comment("✅ Detected desktop breakpoint - proceeding with scrollable content tests");
    
    $I->comment("📝 Testing scrollable content and footer visibility");
    executeScrollableContentTests($I);
    
    $I->comment("📸 Taking scrollable content screenshots");
    $I->makeScreenshot("scrollable-content-initial");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-content-initial.png' target='_blank'>Initial scrollable content state</a>");
    
    $I->comment("✅ Scrollable content touches footer test completed successfully");
    
} catch (Exception $e) {
    $I->comment("❌ Error during test execution: " . $e->getMessage());
    $I->makeScreenshot("scrollable-content-test-error");
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-content-test-error.png' target='_blank'>Error state</a>");
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
            $I->makeScreenshot("scrollable-cleanup-error");
            $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-cleanup-error.png' target='_blank'>Cleanup error state</a>");
        }
    }
    $I->comment("✅ Cleanup complete");
}

/**
 * Execute scrollable content tests for desktop (window width >= 782px)
 * @param AcceptanceTester $I
 */
function executeScrollableContentTests($I) {
    $I->comment("🚀 Running scrollable content and footer visibility tests");
    
    try {
        $I->comment("🔧 Setting up initial scroll position and checking content visibility");
        
        // Scroll to top to ensure we start from the beginning
        $I->executeJS("window.scrollTo(0, 0);");
        $I->wait(1); // Allow scroll to complete
        
        $I->comment("📏 Measuring initial viewport and content dimensions");
        
        // Get viewport height and document height
        $viewportHeight = $I->executeJS("return window.innerHeight;");
        $documentHeight = $I->executeJS("return document.documentElement.scrollHeight;");
        $currentScrollTop = $I->executeJS("return window.pageYOffset || document.documentElement.scrollTop;");
        
        $I->comment("ℹ️ Viewport height: {$viewportHeight}px");
        $I->comment("ℹ️ Document height: {$documentHeight}px");
        $I->comment("ℹ️ Current scroll position: {$currentScrollTop}px");
        
        // Verify that content is longer than viewport (requires scrolling)
        $I->expect("Document height should be greater than viewport height to require scrolling");
        if ($documentHeight <= $viewportHeight) {
            throw new Exception("Content is not long enough to require scrolling. Document height: {$documentHeight}px, Viewport height: {$viewportHeight}px");
        }
        $I->comment("✅ Content is longer than viewport - scrolling is required");
        
        $I->comment("🔍 Checking initial footer visibility");
        
        // Check if footer is initially visible (it shouldn't be for scrollable content)
        $footerVisible = $I->executeJS("
            const footer = document.querySelector('" . AcceptanceConfig::SITE_FOOTER . "');
            if (!footer) return false;
            
            const rect = footer.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            
            // Footer is visible if any part of it is within the viewport
            return rect.top < viewportHeight && rect.bottom > 0;
        ");
        
        if ($footerVisible) {
            $I->comment("⚠️ Footer is initially visible - content may not be long enough");
        } else {
            $I->comment("✅ Footer is not initially visible - content extends beyond viewport as expected");
        }
        
        $I->comment("📜 Testing scroll behavior to reach footer");
        
        // Scroll down to make footer visible
        $I->executeJS("window.scrollTo(0, document.documentElement.scrollHeight);");
        $I->wait(1); // Allow scroll to complete
        
        $I->comment("🔍 Verifying footer visibility after scrolling to bottom");
        
        // Check that footer is now visible
        $footerVisibleAfterScroll = $I->executeJS("
            const footer = document.querySelector('" . AcceptanceConfig::SITE_FOOTER . "');
            if (!footer) return false;
            
            const rect = footer.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            
            // Footer should be visible after scrolling to bottom
            return rect.top < viewportHeight && rect.bottom > 0;
        ");
        
        $I->expect("Footer should be visible after scrolling to bottom");
        if (!$footerVisibleAfterScroll) {
            throw new Exception("Footer is not visible after scrolling to bottom");
        }
        $I->comment("✅ Footer is visible after scrolling to bottom");
        
        $I->comment("📸 Taking screenshot at bottom of page");
        $I->makeScreenshot("scrollable-content-bottom");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-content-bottom.png' target='_blank'>Bottom of scrollable content</a>");
        
        $I->comment("🔄 Testing scroll back to top");
        
        // Scroll back to top
        $I->executeJS("window.scrollTo(0, 0);");
        $I->wait(1); // Allow scroll to complete
        
        // Verify we're back at the top
        $finalScrollTop = $I->executeJS("return window.pageYOffset || document.documentElement.scrollTop;");
        $I->comment("ℹ️ Final scroll position after returning to top: {$finalScrollTop}px");
        
        if ($finalScrollTop > 10) { // Allow for small rounding errors
            $I->comment("⚠️ Warning: Scroll position is not exactly at top ({$finalScrollTop}px)");
        } else {
            $I->comment("✅ Successfully scrolled back to top");
        }
        
        $I->comment("📸 Taking final screenshot at top");
        $I->makeScreenshot("scrollable-content-top-final");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-content-top-final.png' target='_blank'>Final top position</a>");
        
        $I->comment("✅ All scrollable content and footer visibility tests passed");
        
    } catch (Exception $e) {
        $I->comment("❌ Error during scrollable content verification: " . $e->getMessage());
        $I->comment("🐛 Debug info - Current URL: " . ($I->grabFromCurrentUrl() ?? 'Unknown'));
        $I->makeScreenshot("scrollable-content-verification-error");
        $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/scrollable-content-verification-error.png' target='_blank'>Scrollable content verification error</a>");
        throw $e;
    }
    
    $I->comment("✅ Scrollable content tests completed successfully");
}