<?php

$I = new AcceptanceTester($scenario);

// Load the test page
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Get current device configuration
// Window size and device mode are set via YAML configuration, not dynamically changed
$deviceMode = AcceptanceConfig::getDeviceMode();

$windowSize = AcceptanceConfig::getWindowSize();

$I->comment("Testing footer visibility in $deviceMode mode with window size: $windowSize");
$I->makeScreenshot('testpost');
$I->makeScreenshot("footer-visibility-1");

// Check for footer visibility
$I->comment('Verifying footer visibility');
$I->makeScreenshot("footer-visibility-22");
//$I->scrollTo('#comment'); // Scroll to the footer
$I->makeScreenshot("footer-visibility-24");
$I->seeElement('#colophon'); // Check if footer element exists

// Device-specific footer behavior testing
// Footer behavior may differ based on device configuration
    $I->makeScreenshot("footer-visibility-27");
if (AcceptanceConfig::isDesktop()) {
        $I->makeScreenshot("footer-visibility-29");
    $I->comment('Desktop mode: Testing full footer visibility and interaction');
    
    // On desktop, footer should be fully visible and accessible
    $I->comment('Checking if footer is visually accessible on desktop');
    
    $isFooterVisible = $I->executeJS('
        function isElementVisuallyObscured(selector) {
            const element = document.querySelector(selector);
            if (!element) return false;
            
            // Get the element\'s bounding rectangle
            const rect = element.getBoundingClientRect();
            
            // Check if element is in viewport
            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return false;
            }
            
            // Get the center point of the element
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            
            // Check what element is at this point
            const elementAtPoint = document.elementFromPoint(centerX, centerY);
            
            // If the element at point is null or not the element itself or its descendant,
            // then the element is obscured
            return elementAtPoint && (element === elementAtPoint || element.contains(elementAtPoint));
        }
        return isElementVisuallyObscured("#colophon");
    ');
    
    // Assert that the footer is visually visible on desktop
    $I->assertTrue($isFooterVisible, 'Footer is visually obscured by another element on desktop');
    
    // Desktop should show full footer text
    if ($isFooterVisible) {
        $I->see('Cacbots can make mistakes'); // Check if footer text is visible
    }
    $I->makeScreenshot("footer-visibility-65");
} elseif (AcceptanceConfig::isTablet()) {
    $I->comment('Tablet mode: Testing footer visibility with potential layout adjustments');
    
    // On tablet, footer should still be accessible but layout may be different
    $I->comment('Checking footer accessibility on tablet');
    
    $isFooterVisible = $I->executeJS('
        function isElementVisuallyObscured(selector) {
            const element = document.querySelector(selector);
            if (!element) return false;
            
            const rect = element.getBoundingClientRect();
            
            // Check if element is in viewport
            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return false;
            }
            
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const elementAtPoint = document.elementFromPoint(centerX, centerY);
            
            return elementAtPoint && (element === elementAtPoint || element.contains(elementAtPoint));
        }
        return isElementVisuallyObscured("#colophon");
    ');
    
    // Footer should be accessible on tablet, but may have different styling
    $I->assertTrue($isFooterVisible, 'Footer is visually obscured by another element on tablet');
    
    if ($isFooterVisible) {
        $I->see('Cacbots can make mistakes'); // Footer text should still be visible
    }
    
} elseif (AcceptanceConfig::isMobile()) {
    $I->comment('Mobile mode: Testing footer visibility with mobile-specific considerations');
    
    // On mobile, footer behavior may be different due to space constraints
    $I->comment('Checking footer accessibility on mobile');
    $isFooterVisible = $I->executeJS('
        function isElementVisuallyObscured(selector) {
            const element = document.querySelector(selector);
            if (!element) return false;
            
            const rect = element.getBoundingClientRect();
            
            // Check if element is in viewport
            if (rect.bottom < 0 || rect.top > window.innerHeight) {
                return false;
            }
            
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;
            const elementAtPoint = document.elementFromPoint(centerX, centerY);
            
            return elementAtPoint && (element === elementAtPoint || element.contains(elementAtPoint));
        }
        return isElementVisuallyObscured("#colophon");
    ');
    
    // Footer should be accessible on mobile, though layout may be more compact
    $I->assertTrue($isFooterVisible, 'Footer is visually obscured by another element on mobile');
    
    if ($isFooterVisible) {
        // On mobile, footer text might be abbreviated or styled differently
        $I->see('Cacbots can make mistakes'); // Check if footer text is visible
    }
}

//$I->seeInSource('<footer id="colophon" class="site-footer">'); // Check if footer HTML is in the source

$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-visibility-$deviceMode.png' target = '_blank'>available here</a>");
// Take a screenshot to show the footer area in current device configuration
$I->makeScreenshot("footer-visibility-$deviceMode");