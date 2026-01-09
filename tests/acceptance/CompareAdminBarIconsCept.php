<?php
/**
 * CompareAdminBarIconsCept.php
 * 
 * Acceptance test for comparing the sidebar toggle icon with other WordPress admin bar icons
 * to identify visual differences that might make it appear smaller.
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Compare sidebar toggle icon with other admin bar icons');

// Create test post for admin bar icon comparison testing
$I->comment('Creating test post for admin bar icon comparison testing');
$postContent = '<p>This is a test post for comparing admin bar icons. The theme will automatically generate the chat interface with admin bar customizations.</p>';
$postId = $I->cUrlWP_SiteToCreatePost('CompareAdminBarIconsCept', $postContent);
$I->comment('✓ Test post created with ID: ' . $postId);

$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage("/"); // Go to the homepage first
$I->amOnPage("/?p={$postId}");

// Configuration-driven approach: Test behavior adapts based on current device configuration
// Device type determined by breakpoint, eliminating the need for dynamic zoom changes during test execution
$isMobile = $I->isMobileBreakpoint();
$deviceType = $isMobile ? 'mobile' : 'desktop';
$I->comment("Comparing admin bar icons for {$deviceType} mode (breakpoint: " . ($isMobile ? '<784px' : '>=784px') . ")");
$I->comment("Configuration-driven test: Device type = {$deviceType}, Mobile breakpoint = " . ($isMobile ? 'true' : 'false'));

// Wait for the admin bar to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);

// Take a screenshot to see the current state
$I->makeScreenshot('admin-bar-icons-comparison');
$I->comment("Screenshot of admin bar icons: <a href='http://localhost/wp-content/themes/ai_style/tests/_output/debug/admin-bar-icons-comparison.png' target='_blank'>available here</a>");

// Compare sidebar toggle icon with other admin bar icons
$I->comment('Comparing sidebar toggle icon with other WordPress admin bar icons');

// Get styles for sidebar toggle icon
$sidebarToggleStyles = $I->executeJS("
    const element = document.querySelector('#wp-admin-bar-sidebar-toggle .dashicons');
    const styles = window.getComputedStyle(element);
    return {
        fontSize: styles.fontSize,
        width: styles.width,
        height: styles.height,
        lineHeight: styles.lineHeight,
        verticalAlign: styles.verticalAlign,
        display: styles.display,
        fontFamily: styles.fontFamily,
        fontWeight: styles.fontWeight,
        transform: styles.transform,
        padding: styles.padding,
        margin: styles.margin
    };
");

$I->comment("Sidebar toggle icon styles:");
foreach ($sidebarToggleStyles as $property => $value) {
    $I->comment("  $property: $value");
}

// Get styles for "New" button icon (if it has one)
$newButtonStyles = $I->executeJS("
    const element = document.querySelector('#wp-admin-bar-new-content .dashicons');
    if (!element) return null;
    const styles = window.getComputedStyle(element);
    return {
        fontSize: styles.fontSize,
        width: styles.width,
        height: styles.height,
        lineHeight: styles.lineHeight,
        verticalAlign: styles.verticalAlign,
        display: styles.display,
        fontFamily: styles.fontFamily,
        fontWeight: styles.fontWeight,
        transform: styles.transform,
        padding: styles.padding,
        margin: styles.margin
    };
");

if ($newButtonStyles) {
    $I->comment("New button icon styles:");
    foreach ($newButtonStyles as $property => $value) {
        $I->comment("  $property: $value");
    }
} else {
    $I->comment("New button does not have a dashicons element");
}

// Get styles for "Edit" button icon (if it has one)
$editButtonStyles = $I->executeJS("
    const element = document.querySelector('#wp-admin-bar-edit .dashicons');
    if (!element) return null;
    const styles = window.getComputedStyle(element);
    return {
        fontSize: styles.fontSize,
        width: styles.width,
        height: styles.height,
        lineHeight: styles.lineHeight,
        verticalAlign: styles.verticalAlign,
        display: styles.display,
        fontFamily: styles.fontFamily,
        fontWeight: styles.fontWeight,
        transform: styles.transform,
        padding: styles.padding,
        margin: styles.margin
    };
");

if ($editButtonStyles) {
    $I->comment("Edit button icon styles:");
    foreach ($editButtonStyles as $property => $value) {
        $I->comment("  $property: $value");
    }
} else {
    $I->comment("Edit button does not have a dashicons element");
}

// Get styles for user account icon
$userAccountStyles = $I->executeJS("
    const element = document.querySelector('#wp-admin-bar-my-account .dashicons');
    if (!element) return null;
    const styles = window.getComputedStyle(element);
    return {
        fontSize: styles.fontSize,
        width: styles.width,
        height: styles.height,
        lineHeight: styles.lineHeight,
        verticalAlign: styles.verticalAlign,
        display: styles.display,
        fontFamily: styles.fontFamily,
        fontWeight: styles.fontWeight,
        transform: styles.transform,
        padding: styles.padding,
        margin: styles.margin
    };
");

if ($userAccountStyles) {
    $I->comment("User account icon styles:");
    foreach ($userAccountStyles as $property => $value) {
        $I->comment("  $property: $value");
    }
} else {
    $I->comment("User account does not have a dashicons element");
}

// Check what dashicon classes are being used
$I->comment('Checking dashicon classes used by different admin bar elements');

$dashiconClasses = $I->executeJS("
    const elements = document.querySelectorAll('#wpadminbar .dashicons');
    const results = [];
    elements.forEach((el, index) => {
        const parent = el.closest('[id*=\"wp-admin-bar-\"]');
        const parentId = parent ? parent.id : 'unknown';
        results.push({
            parentId: parentId,
            classes: el.className,
            innerHTML: el.innerHTML,
            textContent: el.textContent
        });
    });
    return results;
");

$I->comment("Dashicon elements found in admin bar:");
foreach ($dashiconClasses as $index => $info) {
    $I->comment("  Element $index:");
    $I->comment("    Parent ID: " . $info['parentId']);
    $I->comment("    Classes: " . $info['classes']);
    $I->comment("    Content: " . ($info['textContent'] ?: 'empty'));
}

// Check if there are any WordPress core admin bar icon styles that might be different
$coreAdminBarStyles = $I->executeJS("
    // Look for any admin bar icons that might have different styling
    const adminBarItems = document.querySelectorAll('#wpadminbar [id*=\"wp-admin-bar-\"] .ab-item');
    const results = [];
    adminBarItems.forEach((item) => {
        const parent = item.closest('[id*=\"wp-admin-bar-\"]');
        const parentId = parent ? parent.id : 'unknown';
        const icon = item.querySelector('.dashicons');
        if (icon) {
            const styles = window.getComputedStyle(icon);
            results.push({
                parentId: parentId,
                fontSize: styles.fontSize,
                width: styles.width,
                height: styles.height,
                classes: icon.className
            });
        }
    });
    return results;
");

$I->comment("All admin bar icons with their styles:");
foreach ($coreAdminBarStyles as $iconInfo) {
    $I->comment("  " . $iconInfo['parentId'] . ":");
    $I->comment("    Font-size: " . $iconInfo['fontSize']);
    $I->comment("    Width: " . $iconInfo['width']);
    $I->comment("    Height: " . $iconInfo['height']);
    $I->comment("    Classes: " . $iconInfo['classes']);
}

// Cleanup test data
$I->comment('Cleaning up test post');
$I->cUrlWP_SiteToDeletePost($postId);
$I->comment('✓ Test post deleted successfully');