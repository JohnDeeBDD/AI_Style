<?php
/**
 * InspectAdminBarStructureCept.php
 * 
 * Acceptance test for inspecting the HTML structure and styling of WordPress admin bar elements
 * to understand how they achieve consistent visual sizing.
 */

$I = new AcceptanceTester($scenario);

$I->wantToTest('Inspect WordPress admin bar structure and styling');
$I->amOnUrl(AcceptanceConfig::BASE_URL);
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Configuration-driven approach: Test behavior adapts based on current device configuration
// The window size and device mode are determined by the suite configuration in acceptance.suite.yml
// This eliminates the need for dynamic zoom changes during test execution
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();
$I->comment("Inspecting admin bar structure for {$deviceMode} mode ({$windowSize})");
$I->comment("Configuration-driven test: Device mode = {$deviceMode}, Window size = {$windowSize}");

// Wait for the admin bar to be fully loaded
$I->waitForElement(AcceptanceConfig::ADMIN_BAR, 10);

// Get the HTML structure of various admin bar elements
$I->comment('Inspecting HTML structure of admin bar elements');

$adminBarHTML = $I->executeJS("
    const adminBar = document.querySelector('#wpadminbar');
    const items = adminBar.querySelectorAll('[id*=\"wp-admin-bar-\"]');
    const results = [];
    
    items.forEach(item => {
        if (item.id && item.offsetWidth > 0) { // Only visible items
            results.push({
                id: item.id,
                innerHTML: item.innerHTML,
                outerHTML: item.outerHTML.substring(0, 500) // Truncate for readability
            });
        }
    });
    
    return results;
");

foreach ($adminBarHTML as $item) {
    $I->comment("=== " . $item['id'] . " ===");
    $I->comment("HTML: " . substr($item['outerHTML'], 0, 200) . "...");
}

// Get computed styles for the ab-item elements (the clickable parts)
$I->comment('Checking styles of .ab-item elements in admin bar');

$abItemStyles = $I->executeJS("
    const items = document.querySelectorAll('#wpadminbar .ab-item');
    const results = [];
    
    items.forEach((item, index) => {
        const parent = item.closest('[id*=\"wp-admin-bar-\"]');
        const parentId = parent ? parent.id : 'unknown-' + index;
        const styles = window.getComputedStyle(item);
        
        results.push({
            parentId: parentId,
            fontSize: styles.fontSize,
            lineHeight: styles.lineHeight,
            padding: styles.padding,
            height: styles.height,
            display: styles.display,
            alignItems: styles.alignItems,
            textContent: item.textContent.trim(),
            hasIcon: !!item.querySelector('.dashicons, .ab-icon')
        });
    });
    
    return results;
");

foreach ($abItemStyles as $item) {
    $I->comment("--- " . $item['parentId'] . " ---");
    $I->comment("  Text: '" . $item['textContent'] . "'");
    $I->comment("  Font-size: " . $item['fontSize']);
    $I->comment("  Line-height: " . $item['lineHeight']);
    $I->comment("  Padding: " . $item['padding']);
    $I->comment("  Height: " . $item['height']);
    $I->comment("  Display: " . $item['display']);
    $I->comment("  Align-items: " . $item['alignItems']);
    $I->comment("  Has icon: " . ($item['hasIcon'] ? 'yes' : 'no'));
}

// Check if there are any WordPress core CSS rules that might affect icon sizing
$I->comment('Checking for WordPress core admin bar CSS rules');

$coreStyles = $I->executeJS("
    // Get all stylesheets and look for admin bar related rules
    const stylesheets = Array.from(document.styleSheets);
    const adminBarRules = [];
    
    stylesheets.forEach(sheet => {
        try {
            const rules = Array.from(sheet.cssRules || sheet.rules || []);
            rules.forEach(rule => {
                if (rule.selectorText && (
                    rule.selectorText.includes('#wpadminbar') ||
                    rule.selectorText.includes('.ab-item') ||
                    rule.selectorText.includes('.dashicons')
                )) {
                    adminBarRules.push({
                        selector: rule.selectorText,
                        cssText: rule.cssText,
                        href: sheet.href || 'inline'
                    });
                }
            });
        } catch (e) {
            // Cross-origin stylesheets might throw errors
        }
    });
    
    return adminBarRules.slice(0, 20); // Limit to first 20 rules
");

$I->comment('WordPress core admin bar CSS rules (first 20):');
foreach ($coreStyles as $rule) {
    $I->comment("Selector: " . $rule['selector']);
    $I->comment("Source: " . basename($rule['href']));
    $I->comment("CSS: " . substr($rule['cssText'], 0, 200) . "...");
    $I->comment("---");
}