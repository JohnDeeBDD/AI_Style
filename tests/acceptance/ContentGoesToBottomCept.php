<?php

// Initialize the Acceptance Tester
$I = new AcceptanceTester($scenario);
$I->amOnUrl('http://localhost/');

// Navigate to the test post
$I->amOnUrl('http://localhost/test-post-for-layout-verification/');

// Define all device environments from acceptance.suite.yml
$devices = [
    // Phones 📱
    'iphone_se' => ['name' => 'iPhone SE', 'size' => '375x667'],
    'iphone_xr' => ['name' => 'iPhone XR', 'size' => '414x896'],
    'iphone_12_pro' => ['name' => 'iPhone 12 Pro', 'size' => '390x844'],
    'iphone_14_pro_max' => ['name' => 'iPhone 14 Pro Max', 'size' => '430x932'],
    'pixel_7' => ['name' => 'Pixel 7', 'size' => '412x915'],
    'samsung_galaxy_s8_plus' => ['name' => 'Samsung Galaxy S8+', 'size' => '360x740'],
    'samsung_galaxy_s20_ultra' => ['name' => 'Samsung Galaxy S20 Ultra', 'size' => '412x915'],
    'galaxy_fold' => ['name' => 'Galaxy Fold', 'size' => '280x653'],
    
    // Tablets 📟
    'ipad_mini' => ['name' => 'iPad Mini', 'size' => '768x1024'],
    'ipad_air' => ['name' => 'iPad Air', 'size' => '820x1180'],
    'ipad_pro' => ['name' => 'iPad Pro', 'size' => '1024x1366'],
    'surface_pro_7' => ['name' => 'Surface Pro 7', 'size' => '912x1368'],
    'surface_duo' => ['name' => 'Surface Duo', 'size' => '540x720'],
    'nest_hub' => ['name' => 'Nest Hub', 'size' => '1024x600'],
    'nest_hub_max' => ['name' => 'Nest Hub Max', 'size' => '1280x800'],
    
    // Desktop 🖥️
    'desktop_full_hd' => ['name' => 'Desktop Full HD', 'size' => '1920x1080']
];

// Capture screenshots for each device breakpoint
foreach ($devices as $deviceKey => $deviceInfo) {
    $I->comment("🔄 Testing device: {$deviceInfo['name']} ({$deviceInfo['size']})");
    
    // Parse window size
    list($width, $height) = explode('x', $deviceInfo['size']);
    
    // Resize window to match device dimensions
    $I->resizeWindow((int)$width, (int)$height);
    
    // Wait a moment for layout to adjust
    $I->wait(1);
    
    // Take screenshot with descriptive filename
    $screenshotName = "content-goes-to-bottom-{$deviceKey}";
    $I->makeScreenshot($screenshotName);
    
    // Add comment with link to screenshot
    $I->comment("📸 Screenshot: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/{$screenshotName}.png' target='_blank'>{$deviceInfo['name']} ({$deviceInfo['size']})</a>");
}

// Reset to default desktop size for any subsequent tests
$I->resizeWindow(1920, 1080);
$I->comment("✅ Content goes to bottom test completed for all device breakpoints");
