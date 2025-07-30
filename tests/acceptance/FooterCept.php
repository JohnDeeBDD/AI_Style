<?php

$I = new AcceptanceTester($scenario);

// Load the test page
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// REQUIRED: Enforce 100% zoom after navigation
$I->ensureDesktop100Zoom();

// Ensure 100% zoom for consistent behavior (handled automatically by Helper/Acceptance.php)
$I->resetZoom();
$I->wait(1); // Wait for zoom to settle
$I->makeScreenshot('testpost');

// Check for footer visibility
$I->comment('Verifying footer visibility');
$I->scrollTo('#colophon'); // Scroll to the footer
$I->seeElement('#colophon'); // Check if footer element exists

// Check if the footer is actually visible (not obscured by other elements)
$I->comment('Checking if footer is visually accessible');
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

// Assert that the footer is visually visible
$I->assertTrue($isFooterVisible, 'Footer is visually obscured by another element');

// Only check for text if the footer is visually accessible
if ($isFooterVisible) {
    $I->see('Cacbots can make mistakes'); // Check if footer text is visible
}

//$I->seeInSource('<footer id="colophon" class="site-footer">'); // Check if footer HTML is in the source

$I->comment("Screen shot <a href = 'http://localhost/wp-content/themes/ai_style/tests/_output/debug/footer-visibility.png' target = '_blank'>available here</a>");
// Take a screenshot to show the footer area (zoom already at 100%)
$I->makeScreenshot('footer-visibility');

// Reset zoom at end of test for consistency
$I->resetZoom();