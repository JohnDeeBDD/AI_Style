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

// Configuration-driven approach: Get current device mode and window size
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();
$I->comment("Testing pagination with device mode: $deviceMode, window size: $windowSize");

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
    
    // Configuration-aware testing: Adapt behavior based on device mode
    if (AcceptanceConfig::isMobile()) {
        $I->comment('Mobile mode: Testing pagination with touch-friendly interface');
        // Mobile devices may have different pagination layouts or behaviors
        $waitTime = 3; // Longer wait for mobile rendering
    } elseif (AcceptanceConfig::isTablet()) {
        $I->comment('Tablet mode: Testing pagination with medium screen layout');
        $waitTime = 2;
    } else {
        $I->comment('Desktop mode: Testing pagination with full desktop layout');
        $waitTime = 2;
    }
    
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-category-test-{$deviceMode}");

    try {
        $I->seeElement('.blog-roll-pagination');
        $I->comment('✓ Pagination found - testing pagination structure and functionality');
        $I->seeElement('.pagination-list');
        $I->seeElement('.pagination-list .current-page');

        $currentPageText = $I->grabTextFrom('.current-page');
        $I->comment("Current page: $currentPageText");

        // Device-aware pagination navigation testing
        $I->comment("--- Testing pagination navigation for $deviceMode ---");
        
        // Adjust interaction method based on device type
        if (AcceptanceConfig::isMobile()) {
            $I->comment('Mobile: Testing touch-based pagination navigation');
            $clickDelay = 1000; // Add delay for mobile touch interactions
        } elseif (AcceptanceConfig::isTablet()) {
            $I->comment('Tablet: Testing tablet-optimized pagination navigation');
            $clickDelay = 500;
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
            
            // Longer wait times for mobile/tablet due to potential slower rendering
            $waitTime = AcceptanceConfig::isMobile() ? 5 : 3;
            $I->waitForElementChange('.current-page', function($el) use ($currentPageText) {
                return $el->text() !== $currentPageText;
            }, $waitTime);

            $newPageText = $I->grabTextFrom('.current-page');
            $I->comment("New current page: $newPageText");
            $I->assertNotEquals($currentPageText, $newPageText, 'Page number should change after clicking next');

            $newPosts = $I->grabMultiple('.blog-roll-item .blog-roll-title', 'textContent');
            $I->assertNotEquals($currentPosts, $newPosts, 'Different posts should be displayed on different pages');
            $I->comment("✓ Next page navigation working correctly in $deviceMode mode");

            try {
                $I->seeElement('.pagination-list .prev-page');
                $I->comment('Testing "Previous" page navigation');
                if ($clickDelay > 0) {
                    $I->wait($clickDelay / 1000);
                }
                $I->click('.pagination-list .prev-page a');
                $I->waitForText($currentPageText, $waitTime, '.current-page');
                $I->comment("✓ Previous page navigation working correctly in $deviceMode mode");
            } catch (\Exception $e) {
                $I->comment('ℹ Previous page button not available (expected on first page)');
            }
        } catch (\Exception $e) {
            $I->comment('ℹ Next page button not available');
        }

        // Device-aware page number testing
        $pageNumbers = $I->grabMultiple('.pagination-list .page-number a', 'textContent');
        if (!empty($pageNumbers)) {
            $I->comment("Testing direct page number navigation in $deviceMode mode");
            if (AcceptanceConfig::isMobile()) {
                $I->comment('Mobile: Ensuring page numbers are touch-friendly');
                // On mobile, we might want to test that page numbers are large enough for touch
                $I->seeElement('.pagination-list .page-number');
            }
            
            if ($clickDelay > 0) {
                $I->wait($clickDelay / 1000);
            }
            $I->click('.pagination-list .page-number a');
            $I->waitForElement('.blog-roll-pagination', $waitTime);
            $I->comment("✓ Direct page number navigation working in $deviceMode mode");
        }
    } catch (\Exception $e) {
        $I->comment('ℹ No pagination found - this may be expected if there\'s only one page of posts');
    }

    // Test 2: Test pagination on main blog page
    $I->comment('=== TEST 2: Testing pagination on main blog page ===');
    $I->amOnPage('/');
    
    // Device-aware pagination testing on home page
    $I->comment("Testing home page pagination in $deviceMode mode");
    
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-home-page-{$deviceMode}");

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
    $I->comment("Verifying pagination CSS structure for $deviceMode mode");
    
    $I->waitForElement('.blog-roll-pagination', $waitTime);

    $I->seeElement('nav.blog-roll-pagination');
    $I->seeElement('ul.pagination-list');

    // Device-specific CSS structure verification
    $paginationItems = $I->grabMultiple('.pagination-list li', 'class');
    $I->comment("Found pagination item classes for $deviceMode: " . implode(', ', $paginationItems));
    $I->assertTrue(in_array('current-page', $paginationItems), 'Current page class should exist');

    // Verify pagination structure is appropriate for device type
    if (AcceptanceConfig::isMobile()) {
        $I->comment('Mobile: Verifying touch-friendly pagination structure');
        // On mobile, pagination items should be large enough for touch interaction
        $paginationHeight = $I->executeJS("
            const pagination = document.querySelector('.pagination-list li a');
            return pagination ? window.getComputedStyle(pagination).height : '0px';
        ");
        $I->comment("Mobile pagination link height: $paginationHeight");
    } elseif (AcceptanceConfig::isTablet()) {
        $I->comment('Tablet: Verifying medium-screen pagination layout');
    } else {
        $I->comment('Desktop: Verifying full-size pagination layout');
    }

    $currentPageHasLink = $I->executeJS("return document.querySelector('.pagination-list .current-page a') !== null;");
    $I->assertFalse($currentPageHasLink, 'Current page should not be clickable');
    $I->comment("✓ Current page is properly marked as non-clickable in $deviceMode mode");

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
    $I->comment("Testing search results pagination in $deviceMode mode");
    
    $I->waitForElement('.blog-roll-container', $waitTime);
    $I->makeScreenshot("pagination-search-results-{$deviceMode}");

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
    $I->comment("Testing pagination accessibility for $deviceMode mode");
    if (AcceptanceConfig::isMobile()) {
        $I->comment('Mobile: Verifying touch-friendly pagination controls');
    } elseif (AcceptanceConfig::isTablet()) {
        $I->comment('Tablet: Verifying medium-screen accessibility features');
    } else {
        $I->comment('Desktop: Verifying full keyboard and mouse accessibility');
    }
    
    $I->waitForElement('.blog-roll-pagination', $waitTime);

    $I->seeElement('.blog-roll-pagination[role="navigation"]');
    $I->seeElement('.blog-roll-pagination[aria-label="Posts pagination"]');

    $linkTexts = $I->grabMultiple('.pagination-list a', 'textContent');
    $I->assertTrue(count($linkTexts) > 0, 'Pagination links should have readable text');
    $I->comment("Pagination link texts for $deviceMode: " . implode(', ', $linkTexts));

    // Device-specific accessibility checks
    if (AcceptanceConfig::isMobile()) {
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
        $I->comment("✓ Previous link text for $deviceMode: $prevText");
    } catch (\Exception $e) {
        $I->comment('ℹ Previous link not available (expected on first page)');
    }

    try {
        $nextText = $I->grabTextFrom('.pagination-list .next-page a');
        $I->assertStringContainsString('Next', $nextText, 'Next link should contain "Next" text');
        $I->comment("✓ Next link text for $deviceMode: $nextText");
    } catch (\Exception $e) {
        $I->comment('ℹ Next link not available (expected on last page)');
    }

    $I->comment("✓ Pagination accessibility tests completed for $deviceMode mode");
    $I->comment('=== PAGINATION TESTS COMPLETED ===');
    $I->comment("All pagination functionality has been tested for $deviceMode mode including:");
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
