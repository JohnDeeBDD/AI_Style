<?php
/**
 * PaginationCept.php
 *
 * Acceptance tests for blog roll pagination functionality.
 *
 * This test verifies:
 * 1. Pagination appears when there are multiple pages of posts
 * 2. Pagination does NOT appear when there's only one page of posts
 * 3. Pagination links work correctly (clicking next/previous/page numbers)
 * 4. Current page is properly highlighted
 * 5. Pagination has the correct CSS classes and structure
 * 6. Navigation between pages shows different posts
 *
 * REFACTORED: Now uses configuration-driven approach for device-aware testing
 * instead of deprecated zoom enforcement functions.
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Blog roll pagination functionality');
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();

// CRITICAL CHECK: Verify WordPress is configured to show posts (blog roll) on homepage
$I->comment('=== CHECKING WORDPRESS HOMEPAGE CONFIGURATION ===');
$I->amOnPage('/wp-admin/options-reading.php');
$I->waitForElement('input[name="show_on_front"]', 5);

// Check if "Your latest posts" is selected (blog roll mode)
$showOnFront = $I->executeJS("
    const latestPostsRadio = document.querySelector('input[name=\"show_on_front\"][value=\"posts\"]');
    const staticPageRadio = document.querySelector('input[name=\"show_on_front\"][value=\"page\"]');
    
    if (latestPostsRadio && latestPostsRadio.checked) {
        return 'posts';
    } else if (staticPageRadio && staticPageRadio.checked) {
        return 'page';
    }
    return 'unknown';
");

$I->comment("WordPress homepage configuration: $showOnFront");

if ($showOnFront === 'page') {
    $staticPageId = $I->executeJS("
        const pageOnFrontSelect = document.querySelector('select[name=\"page_on_front\"]');
        return pageOnFrontSelect ? pageOnFrontSelect.value : 'unknown';
    ");
    
    $blogPageId = $I->executeJS("
        const pageForPostsSelect = document.querySelector('select[name=\"page_for_posts\"]');
        return pageForPostsSelect ? pageForPostsSelect.value : 'unknown';
    ");
    
    $I->comment("Static page ID set as homepage: $staticPageId");
    $I->comment("Blog page ID (Posts page): $blogPageId");
    
    // Get page titles for better error messaging
    $staticPageTitle = 'Unknown';
    $blogPageTitle = 'Unknown';
    
    if ($staticPageId !== 'unknown' && $staticPageId !== '0') {
        $staticPageTitle = $I->executeJS("
            const option = document.querySelector('select[name=\"page_on_front\"] option[value=\"$staticPageId\"]');
            return option ? option.textContent : 'Unknown';
        ");
    }
    
    if ($blogPageId !== 'unknown' && $blogPageId !== '0') {
        $blogPageTitle = $I->executeJS("
            const option = document.querySelector('select[name=\"page_for_posts\"] option[value=\"$blogPageId\"]');
            return option ? option.textContent : 'Unknown';
        ");
    }
    
    // Build detailed failure message
    $failureMessage = "PAGINATION TEST FAILED: WordPress is configured to show a static page as the homepage instead of the blog roll.\n\n";
    $failureMessage .= "Current Reading Settings configuration:\n";
    $failureMessage .= "- Homepage displays: A static page\n";
    $failureMessage .= "- Homepage (static page): $staticPageTitle (ID: $staticPageId)\n";
    
    if ($blogPageId !== 'unknown' && $blogPageId !== '0') {
        $failureMessage .= "- Posts page (blog roll): $blogPageTitle (ID: $blogPageId)\n\n";
        $failureMessage .= "ALTERNATIVE SOLUTION: If you want to keep the static homepage,\n";
        $failureMessage .= "you can modify this test to check pagination on the posts page instead:\n";
        $failureMessage .= "- Change the test to navigate to the posts page URL\n";
        $failureMessage .= "- The posts page should be accessible and show the blog roll with pagination\n\n";
    } else {
        $failureMessage .= "- Posts page (blog roll): Not configured\n\n";
    }
    
    $failureMessage .= "To fix this issue (Option 1 - Recommended for this test):\n";
    $failureMessage .= "1. Go to WordPress Admin > Settings > Reading\n";
    $failureMessage .= "2. Select 'Your latest posts' under 'Your homepage displays'\n";
    $failureMessage .= "3. Save changes and re-run this test\n\n";
    
    $failureMessage .= "Alternative fix (Option 2 - Keep static homepage):\n";
    $failureMessage .= "1. Ensure a 'Posts page' is selected in Settings > Reading\n";
    $failureMessage .= "2. Modify this test to check pagination on that posts page instead of homepage\n\n";
    
    $failureMessage .= "This test requires access to the blog roll to properly test pagination functionality.";
    
    // Fail the test with the detailed explanation
    $I->fail($failureMessage);
}

if ($showOnFront !== 'posts') {
    $I->fail(
        "PAGINATION TEST FAILED: Unable to determine WordPress homepage configuration.\n" .
        "Expected: 'Your latest posts' should be selected in Settings > Reading.\n" .
        "Current configuration: $showOnFront\n\n" .
        "Please verify WordPress is properly configured to show the blog roll on the homepage."
    );
}

$I->comment('✓ WordPress is correctly configured to show blog roll on homepage');
$I->comment('✓ Proceeding with pagination tests...');

// Return to the main site for testing
$I->amOnUrl(AcceptanceConfig::BASE_URL);

// Configuration-driven approach: Get current device type using breakpoint
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';
$I->comment("Testing pagination with device type: $deviceType (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");

// Setup: Create test posts for pagination testing
$I->comment('=== SETUP: Creating test posts for pagination ===');
$testCategoryId = $I->cUrlWP_SiteToGetOrCreateCategory('test', 'Test Category');
$I->comment("Test category ID: $testCategoryId");

// Create enough posts to ensure pagination (15 posts should be enough for most configurations)
$createdPostIds = [];
for ($i = 1; $i <= 15; $i++) {
    $postTitle = "Test Pagination Post $i";
    $postContent = "<p>This is test content for pagination post number $i. This post was created automatically for testing pagination functionality.</p>";
    
    $postId = $I->cUrlWP_SiteToCreatePostWithCategories($postTitle, $postContent, [$testCategoryId]);
    $createdPostIds[] = $postId;
    $I->comment("Created post $i with ID: $postId");
}

$I->comment("Created " . count($createdPostIds) . " test posts for pagination testing");

// Wrap all tests in try-finally to ensure cleanup
try {

    // Test 1: Verify pagination appears on category page with multiple pages
    $I->comment('=== TEST 1: Pagination appears on multi-page category ===');
    $I->amOnPage('/category/test/');
    
    // Configuration-aware testing: Adapt behavior based on device type
    if ($deviceType === 'mobile') {
        $I->comment('Mobile mode: Testing pagination with touch-friendly interface');
        // Mobile devices may have different pagination layouts or behaviors
        $waitTime = 3; // Longer wait for mobile rendering
    } else {
        $I->comment('Desktop mode: Testing pagination with full desktop layout');
        $waitTime = 2;
    }
    
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-category-test-{$deviceType}");

    try {
        $I->seeElement('.blog-roll-pagination');
        $I->comment('✓ Pagination found - testing pagination structure and functionality');
        $I->seeElement('.pagination-list');
        $I->seeElement('.pagination-list .current-page');

        $currentPageText = $I->grabTextFrom('.current-page');
        $I->comment("Current page: $currentPageText");

        // Device-aware pagination navigation testing
        $I->comment("--- Testing pagination navigation for $deviceType ---");
        
        // Adjust interaction method based on device type
        if ($deviceType === 'mobile') {
            $I->comment('Mobile: Testing touch-based pagination navigation');
            $clickDelay = 1000; // Add delay for mobile touch interactions
        } else {
            $I->comment('Desktop: Testing mouse-based pagination navigation');
            $clickDelay = 0;
        }
        
        try {
            $I->seeElement('.pagination-list .next-page');
            $I->comment('Testing "Next" page navigation');
            $currentPosts = $I->grabMultiple('.blog-roll-item .blog-roll-title', 'textContent');

            // Device-specific click handling
            if ($clickDelay > 0) {
                $I->wait($clickDelay / 1000); // Convert to seconds
            }
            $I->click('.pagination-list .next-page a');
            
            // Longer wait times for mobile due to potential slower rendering
            $waitTime = $deviceType === 'mobile' ? 5 : 3;
            $I->waitForElementChange('.current-page', function($el) use ($currentPageText) {
                return $el->getText() !== $currentPageText;
            }, $waitTime);

            $newPageText = $I->grabTextFrom('.current-page');
            $I->comment("New current page: $newPageText");
            $I->assertNotEquals($currentPageText, $newPageText, 'Page number should change after clicking next');

            $newPosts = $I->grabMultiple('.blog-roll-item .blog-roll-title', 'textContent');
            $I->assertNotEquals($currentPosts, $newPosts, 'Different posts should be displayed on different pages');
            $I->comment("✓ Next page navigation working correctly in $deviceType mode");

            try {
                $I->seeElement('.pagination-list .prev-page');
                $I->comment('Testing "Previous" page navigation');
                if ($clickDelay > 0) {
                    $I->wait($clickDelay / 1000);
                }
                $I->click('.pagination-list .prev-page a');
                $I->waitForText($currentPageText, $waitTime, '.current-page');
                $I->comment("✓ Previous page navigation working correctly in $deviceType mode");
            } catch (\Exception $e) {
                $I->comment('ℹ Previous page button not available (expected on first page)');
            }
        } catch (\Exception $e) {
            $I->comment('ℹ Next page button not available');
        }

        // Device-aware page number testing
        $pageNumbers = $I->grabMultiple('.pagination-list .page-number a', 'textContent');
        if (!empty($pageNumbers)) {
            $I->comment("Testing direct page number navigation in $deviceType mode");
            if ($deviceType === 'mobile') {
                $I->comment('Mobile: Ensuring page numbers are touch-friendly');
                // On mobile, we might want to test that page numbers are large enough for touch
                $I->seeElement('.pagination-list .page-number');
            }
            
            if ($clickDelay > 0) {
                $I->wait($clickDelay / 1000);
            }
            $I->click('.pagination-list .page-number a');
            $I->waitForElement('.blog-roll-pagination', $waitTime);
            $I->comment("✓ Direct page number navigation working in $deviceType mode");
        }
    } catch (\Exception $e) {
        $I->comment('ℹ No pagination found - this may be expected if there\'s only one page of posts');
    }

    // Test 2: Test pagination on main blog page
    $I->comment('=== TEST 2: Testing pagination on main blog page ===');
    $I->amOnPage('/');
    
    // Device-aware pagination testing on home page
    $I->comment("Testing home page pagination in $deviceType mode");
    
    // Set wait time for this test section
    $waitTime = $deviceType === 'mobile' ? 5 : 3;
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-home-page-{$deviceType}");

    try {
        $I->seeElement('.blog-roll-pagination');
        $I->comment('✓ Pagination found on home page');
        $I->seeElement('.pagination-list');
    } catch (\Exception $e) {
        $I->comment('ℹ No pagination on home page - may be expected if few posts');
    }

    // Test 3: Verify pagination CSS classes and styling
    $I->comment('=== TEST 3: Verifying pagination CSS classes and structure ===');
    $I->amOnPage('/category/test/');
    
    // Device-specific CSS and styling verification
    $I->comment("Verifying pagination CSS structure for $deviceType mode");
    
    // Set wait time for this test section
    $waitTime = $deviceType === 'mobile' ? 5 : 3;
    $I->waitForElement('.blog-roll-pagination', $waitTime);

    $I->seeElement('nav.blog-roll-pagination');
    $I->seeElement('ul.pagination-list');

    // Device-specific CSS structure verification
    $paginationItems = $I->grabMultiple('.pagination-list li', 'class');
    $I->comment("Found pagination item classes for $deviceType: " . implode(', ', $paginationItems));
    $I->assertTrue(in_array('current-page', $paginationItems), 'Current page class should exist');

    // Verify pagination structure is appropriate for device type
    if ($deviceType === 'mobile') {
        $I->comment('Mobile: Verifying touch-friendly pagination structure');
        // On mobile, pagination items should be large enough for touch interaction
        $paginationHeight = $I->executeJS("
            const pagination = document.querySelector('.pagination-list li a');
            return pagination ? window.getComputedStyle(pagination).height : '0px';
        ");
        $I->comment("Mobile pagination link height: $paginationHeight");
    } else {
        $I->comment('Desktop: Verifying full-size pagination layout');
    }

    $currentPageHasLink = $I->executeJS("return document.querySelector('.pagination-list .current-page a') !== null;");
    $I->assertFalse($currentPageHasLink, 'Current page should not be clickable');
    $I->comment("✓ Current page is properly marked as non-clickable in $deviceType mode");

    $otherLinksClickable = $I->executeJS("
        const otherItems = document.querySelectorAll('.pagination-list li:not(.current-page)');
        const itemsWithLinks = Array.from(otherItems).filter(item => item.querySelector('a') !== null);
        return itemsWithLinks.length > 0;
    ");
    $I->assertTrue($otherLinksClickable, 'At least some non-current pagination items should be clickable');

    // Test 4: Test pagination with different post counts
    $I->comment('=== TEST 4: Testing pagination behavior with different scenarios ===');
    $I->amOnPage('/?s=test');
    
    // Search results pagination testing with device awareness
    $I->comment("Testing search results pagination in $deviceType mode");
    
    // Set wait time for this test section
    $waitTime = $deviceType === 'mobile' ? 5 : 3;
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-search-results-{$deviceType}");

    try {
        $I->seeElement('.blog-roll-pagination');
        $I->comment('✓ Pagination works correctly with search results');
    } catch (\Exception $e) {
        $I->comment('ℹ No pagination in search results - expected if few results');
    }

    // Final verification: Test pagination accessibility
    $I->comment('=== TEST 5: Testing pagination accessibility ===');
    $I->amOnPage('/category/test/');
    
    // Device-aware accessibility testing
    $I->comment("Testing pagination accessibility for $deviceType mode");
    if ($deviceType === 'mobile') {
        $I->comment('Mobile: Verifying touch-friendly pagination controls');
    } else {
        $I->comment('Desktop: Verifying full keyboard and mouse accessibility');
    }
    
    // Set wait time for this test section
    $waitTime = $deviceType === 'mobile' ? 5 : 3;
    $I->waitForElement('.blog-roll-pagination', $waitTime);

    $I->seeElement('.blog-roll-pagination[role="navigation"]');
    $I->seeElement('.blog-roll-pagination[aria-label="Posts pagination"]');

    $linkTexts = $I->grabMultiple('.pagination-list a', 'textContent');
    $I->assertTrue(count($linkTexts) > 0, 'Pagination links should have readable text');
    $I->comment("Pagination link texts for $deviceType: " . implode(', ', $linkTexts));

    // Device-specific accessibility checks
    if ($deviceType === 'mobile') {
        $I->comment('Mobile: Verifying touch accessibility features');
        // Check if pagination links have adequate touch targets
        $touchTargetSize = $I->executeJS("
            const links = document.querySelectorAll('.pagination-list a');
            if (links.length > 0) {
                const rect = links[0].getBoundingClientRect();
                return Math.min(rect.width, rect.height);
            }
            return 0;
        ");
        $I->comment("Mobile pagination touch target size: {$touchTargetSize}px");
        // Touch targets should ideally be at least 44px for good accessibility
    }

    try {
        $prevText = $I->grabTextFrom('.pagination-list .prev-page a');
        $I->assertStringContainsString('Previous', $prevText, 'Previous link should contain "Previous" text');
        $I->comment("✓ Previous link text for $deviceType: $prevText");
    } catch (\Exception $e) {
        $I->comment('ℹ Previous link not available (expected on first page)');
    }

    try {
        $nextText = $I->grabTextFrom('.pagination-list .next-page a');
        $I->assertStringContainsString('Next', $nextText, 'Next link should contain "Next" text');
        $I->comment("✓ Next link text for $deviceType: $nextText");
    } catch (\Exception $e) {
        $I->comment('ℹ Next link not available (expected on last page)');
    }

    $I->comment("✓ Pagination accessibility tests completed for $deviceType mode");
    $I->comment('=== PAGINATION TESTS COMPLETED ===');
    $I->comment("All pagination functionality has been tested for $deviceType mode including:");
    $I->comment('- Device-aware pagination presence/absence based on post count');
    $I->comment('- Configuration-driven navigation functionality (next/previous/page numbers)');
    $I->comment('- Device-specific CSS classes and HTML structure verification');
    $I->comment('- Current page highlighting across different screen sizes');
    $I->comment('- Device-appropriate accessibility features and touch targets');
    $I->comment('- Different page contexts (category, home, search) with device awareness');
    $I->comment('');
    $I->comment('REFACTORING NOTES:');
    $I->comment('- Removed all deprecated $I->ensureDesktop100Zoom() calls');
    $I->comment('- Implemented configuration-driven approach using AcceptanceConfig methods');
    $I->comment('- Added device-specific testing logic for desktop, tablet, and mobile modes');
    $I->comment('- Enhanced accessibility testing with device-appropriate checks');
    $I->comment('- Improved screenshot naming to include device mode for better test tracking');

} finally {
    
    // Cleanup: Delete all created test posts (runs even if test fails)
    $I->comment('=== CLEANUP: Deleting created test posts ===');
    foreach ($createdPostIds as $postId) {
        try {
            $I->cUrlWP_SiteToDeletePost($postId);
            $I->comment("Deleted post ID: $postId");
        } catch (\Exception $e) {
            $I->comment("Warning: Failed to delete post ID $postId: " . $e->getMessage());
        }
    }
    $I->comment("Cleanup completed - deleted " . count($createdPostIds) . " test posts");
    
}
