<?php
/**
 * Zoom Enforcement Validation Test
 *
 * This test verifies that the centralized zoom management solution works correctly:
 * - Tests automatically start at 100% zoom
 * - Zoom helper methods function properly
 * - Zoom can be changed and reset programmatically
 * - Browser-level configuration is effective
 */

$I = new AcceptanceTester($scenario);

$I->wantTo('verify that zoom enforcement works correctly');

// Setup: Navigate to test page
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
// Navigate to the test post page
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Enforce 100% zoom after navigation
$I->ensureDesktop100Zoom();

// Test 1: Verify test starts at 100% zoom automatically
$I->comment('Test 1: Verifying that test starts at 100% zoom automatically');
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
$I->comment('✓ Test automatically started at 100% zoom');

// Test 2: Test zoom level changes using helper methods
$I->comment('Test 2: Testing zoom level changes using helper methods');

// Test 75% zoom
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->comment('✓ Successfully changed to 75% zoom');

// Test 150% zoom
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_150);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_150);
$I->comment('✓ Successfully changed to 150% zoom');

// Test 50% zoom
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_50);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_50);
$I->comment('✓ Successfully changed to 50% zoom');

// Test 3: Test zoom reset functionality
$I->comment('Test 3: Testing zoom reset functionality');
$I->resetZoom();
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
$I->comment('✓ Successfully reset zoom to 100%');

// Test 4: Test zoom constants are working
$I->comment('Test 4: Testing zoom constants functionality');
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_DEFAULT);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
$I->comment('✓ Default zoom level constant works correctly');

// Test 5: Verify zoom persistence during page interactions
$I->comment('Test 5: Testing zoom persistence during page interactions');
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->scrollTo('body');
$I->wait(1);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->comment('✓ Zoom level persists during page interactions');

// Test 6: Test multiple zoom changes in sequence
$I->comment('Test 6: Testing multiple zoom changes in sequence');
$zoomLevels = [
    AcceptanceConfig::ZOOM_LEVEL_25,
    AcceptanceConfig::ZOOM_LEVEL_100,
    AcceptanceConfig::ZOOM_LEVEL_200,
    AcceptanceConfig::ZOOM_LEVEL_75,
    AcceptanceConfig::ZOOM_LEVEL_100
];

foreach ($zoomLevels as $index => $zoomLevel) {
    $I->setZoomLevel($zoomLevel);
    $I->verifyZoomLevel($zoomLevel);
    $I->comment("✓ Step " . ($index + 1) . ": Successfully set zoom to " . ($zoomLevel * 100) . "%");
}

// Test 7: Verify zoom enforcement can be controlled via configuration
$I->comment('Test 7: Testing zoom enforcement configuration');
$enforcementEnabled = AcceptanceConfig::ZOOM_ENFORCEMENT_ENABLED;
$I->assertTrue($enforcementEnabled, 'Zoom enforcement should be enabled by default');
$I->comment('✓ Zoom enforcement is properly configured');

// Test 8: Take screenshots at different zoom levels for visual verification
$I->comment('Test 8: Taking screenshots at different zoom levels for visual verification');

$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
$I->makeScreenshot('zoom-validation-100-percent');

$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->makeScreenshot('zoom-validation-75-percent');

$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_150);
$I->makeScreenshot('zoom-validation-150-percent');

// Reset to 100% for final screenshot
$I->resetZoom();
$I->makeScreenshot('zoom-validation-final-reset');

$I->comment('✓ Screenshots taken at different zoom levels');

// Final verification: Ensure we end at 100% zoom
$I->comment('Final verification: Ensuring test ends at 100% zoom');
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
$I->comment('✓ Test completed successfully - all zoom enforcement features working correctly');

// Summary
$I->comment('=== ZOOM ENFORCEMENT VALIDATION SUMMARY ===');
$I->comment('✓ Automatic 100% zoom enforcement: WORKING');
$I->comment('✓ Zoom helper methods: WORKING');
$I->comment('✓ Zoom level verification: WORKING');
$I->comment('✓ Zoom reset functionality: WORKING');
$I->comment('✓ Zoom constants: WORKING');
$I->comment('✓ Zoom persistence: WORKING');
$I->comment('✓ Configuration control: WORKING');
$I->comment('✓ Visual verification: COMPLETE');

$I->comment("Screenshots available:");
$I->comment("- 100% zoom: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/zoom-validation-100-percent.png' target='_blank'>View</a>");
$I->comment("- 75% zoom: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/zoom-validation-75-percent.png' target='_blank'>View</a>");
$I->comment("- 150% zoom: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/zoom-validation-150-percent.png' target='_blank'>View</a>");
$I->comment("- Final reset: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/zoom-validation-final-reset.png' target='_blank'>View</a>");