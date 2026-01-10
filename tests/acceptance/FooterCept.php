<?php

$I = new AcceptanceTester($scenario);

// Load the test page
$I->amOnUrl(AcceptanceConfig::BASE_URL);



$postContent = '<p>This is a test post for FooterCept.php</p>';
$postId = $I->cUrlWP_SiteToCreatePost('FooterCept', $postContent);
//sleep(5);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
//sleep(5);
$I->loginAsAdmin();
//sleep(5);
$I->amOnPage("/");
$I->amOnPage("?p=$postId");
//$I->makeScreenshot("wtf");
//die("wtf");

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



// Configuration-driven approach: Get current device configuration
// Device type determined by breakpoint, not dynamically changed
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';


if ($deviceType === 'desktop') {
    $I->makeScreenshot("footer-visibility-29");
    $I->comment('Desktop mode: Testing full footer visibility and interaction');
    
    // On desktop, footer should be fully visible and accessible
    $I->comment('Checking if footer is visually accessible on desktop');
    

    
    // Assert that the footer is visually visible on desktop
    $I->assertTrue($isFooterVisible, 'Footer is visually obscured by another element on desktop');
    
    // Desktop should show full footer text
    if ($isFooterVisible) {
        $I->see('Cacbots can make mistakes'); // Check if footer text is visible
    }
    $I->makeScreenshot("footer-visibility-65");
}

if ($deviceType === 'mobile') {
    $I->comment('Mobile mode: Testing footer visibility with mobile-specific considerations');
    $I->comment('Checking footer accessibility on mobile');
    $I->scrollTo("#action-buttons-container");   

    $I->assertFalse($isFooterVisible, 'Footer should not be visible on mobile');
    
    if ($isFooterVisible) {
        $I->see('Cacbots can make mistakes'); // Check if footer text is visible
    }
}


$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-visibility-$deviceType.png' target = '_blank'>available here</a>");
// Take a screenshot to show the footer area in current device configuration
$I->makeScreenshot("footer-visibility-$deviceType");

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');