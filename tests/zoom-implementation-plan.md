# Zoom Implementation Plan - 100% Desktop Default Enforcement

## Executive Summary

Based on the analysis of test support files, this plan outlines the most efficient approach to ensure all tests start with desktop 100% zoom without modifying individual test files. The strategy focuses on centralized implementation through the existing Helper/Acceptance.php infrastructure.

## Implementation Strategy

### Phase 1: Foundation Setup (Immediate - Day 1)

#### 1.1 Add Zoom Constants to AcceptanceConfig.php
```php
// Add to AcceptanceConfig.php after line 61

// Zoom Management Constants
const ZOOM_LEVEL_25 = 0.25;
const ZOOM_LEVEL_50 = 0.5;
const ZOOM_LEVEL_75 = 0.75;
const ZOOM_LEVEL_100 = 1.0;
const ZOOM_LEVEL_150 = 1.5;
const ZOOM_LEVEL_200 = 2.0;
const ZOOM_LEVEL_DEFAULT = self::ZOOM_LEVEL_100;

// Viewport configurations for zoom testing
const VIEWPORT_DESKTOP_DEFAULT = '1920x1080';
const VIEWPORT_DESKTOP_75 = '1440x810';
const VIEWPORT_DESKTOP_50 = '960x540';

// Zoom enforcement settings
const ZOOM_ENFORCEMENT_ENABLED = true;
const ZOOM_RESET_DELAY = 500; // milliseconds
```

#### 1.2 Create Core Zoom Helper Methods in Helper/Acceptance.php
```php
// Add to Helper/Acceptance.php after line 78

/**
 * Ensures the browser is set to 100% zoom level (desktop default)
 * This method should be called at the beginning of tests to ensure consistent zoom state
 */
public function ensureDesktop100Zoom() {
    if (!AcceptanceConfig::ZOOM_ENFORCEMENT_ENABLED) {
        return;
    }
    
    $this->getModule('WPWebDriver')->executeJS('
        // Reset zoom using multiple methods for maximum compatibility
        document.body.style.zoom = "1.0";
        document.body.style.transform = "scale(1.0)";
        document.documentElement.style.zoom = "1.0";
        
        // Also reset any CSS zoom that might be applied
        const allElements = document.querySelectorAll("*");
        allElements.forEach(el => {
            if (el.style.zoom && el.style.zoom !== "1" && el.style.zoom !== "1.0") {
                el.style.zoom = "1.0";
            }
        });
    ');
    
    // Wait for zoom to settle
    $this->getModule('WPWebDriver')->wait(AcceptanceConfig::ZOOM_RESET_DELAY / 1000);
}

/**
 * Sets a specific zoom level for testing
 * @param float $zoomLevel The zoom level (e.g., 0.75 for 75%, 1.5 for 150%)
 */
public function setZoomLevel($zoomLevel) {
    $this->getModule('WPWebDriver')->executeJS("
        document.body.style.zoom = '$zoomLevel';
        document.documentElement.style.zoom = '$zoomLevel';
    ");
    
    $this->getModule('WPWebDriver')->wait(AcceptanceConfig::ZOOM_RESET_DELAY / 1000);
}

/**
 * Resets zoom to default 100% level
 */
public function resetZoom() {
    $this->ensureDesktop100Zoom();
}

/**
 * Verifies the current zoom level
 * @param float $expectedZoom Expected zoom level
 */
public function verifyZoomLevel($expectedZoom) {
    $currentZoom = $this->getModule('WPWebDriver')->executeJS('
        return document.body.style.zoom || 
               getComputedStyle(document.body).zoom || 
               "1";
    ');
    
    // Convert to float for comparison
    $currentZoomFloat = floatval($currentZoom);
    $expectedZoomFloat = floatval($expectedZoom);
    
    if (abs($currentZoomFloat - $expectedZoomFloat) > 0.01) {
        throw new \Exception("Zoom level mismatch. Expected: $expectedZoom, Actual: $currentZoom");
    }
}
```

### Phase 2: Automatic Enforcement (Day 2)

#### 2.1 Modify Helper/Acceptance.php _beforeSuite Method
```php
// Update existing _beforeSuite method in Helper/Acceptance.php
public function _beforeSuite($settings = []){
    $this->hostname = shell_exec("hostname");
    
    // Initialize zoom enforcement for the entire test suite
    if (AcceptanceConfig::ZOOM_ENFORCEMENT_ENABLED) {
        // This will be called once before all tests in the suite
        $this->ensureDesktop100Zoom();
    }
}
```

#### 2.2 Add _before Method for Per-Test Enforcement
```php
// Add new method to Helper/Acceptance.php
public function _before(\Codeception\TestInterface $test) {
    // Ensure 100% zoom before each individual test
    if (AcceptanceConfig::ZOOM_ENFORCEMENT_ENABLED) {
        $this->ensureDesktop100Zoom();
    }
}
```

### Phase 3: Browser-Level Configuration (Day 3)

#### 3.1 Enhance acceptance.suite.yml Chrome Configuration
```yaml
# Update the capabilities section in acceptance.suite.yml
capabilities:
  chromeOptions:
    args:
      - "--disable-blink-features=AutomationControlled"
      - "--disable-infobars"
      - "--disable-extensions"
      - "--incognito"
      - "--disable-popup-blocking"
      - "--disable-default-apps"
      - "--force-device-scale-factor=1"
      - "--disable-features=TranslateUI"
      - "--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36"
    excludeSwitches:
      - enable-automation
    useAutomationExtension: false
    prefs:
      "profile.default_zoom_level": 0  # 0 = 100% zoom
      "profile.password_manager_enabled": false
```

### Phase 4: Testing and Validation (Day 4)

#### 4.1 Create Zoom Validation Test
```php
// Create tests/acceptance/ZoomEnforcementValidationCept.php
<?php
$I = new AcceptanceTester($scenario);

$I->wantTo('verify that zoom enforcement works correctly');

// Test should start at 100% zoom automatically
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);

// Test zoom level changes
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);

// Test zoom reset
$I->resetZoom();
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
```

## Deployment Strategy

### Immediate Benefits (Phase 1-2)
- **Zero Test File Modifications**: Existing tests remain unchanged
- **Automatic Enforcement**: Every test starts with 100% zoom
- **Centralized Control**: Single configuration point for zoom management
- **Backward Compatible**: Can be disabled via configuration

### Implementation Order

1. **Day 1 Morning**: Add constants to AcceptanceConfig.php
2. **Day 1 Afternoon**: Implement helper methods in Helper/Acceptance.php
3. **Day 2 Morning**: Add automatic enforcement hooks
4. **Day 2 Afternoon**: Test with existing test suite
5. **Day 3**: Enhance browser configuration
6. **Day 4**: Create validation tests and documentation

### Risk Mitigation

#### Rollback Plan
```php
// In AcceptanceConfig.php - can disable instantly
const ZOOM_ENFORCEMENT_ENABLED = false;
```

#### Testing Strategy
1. Run existing test suite with zoom enforcement enabled
2. Verify no tests break due to zoom changes
3. Test zoom helper methods individually
4. Validate browser-level configuration effectiveness

### Success Metrics

- [ ] All existing tests pass with zoom enforcement enabled
- [ ] New zoom helper methods work correctly
- [ ] Browser starts with 100% zoom consistently
- [ ] Zoom can be changed and reset programmatically
- [ ] No performance impact on test execution

## Advanced Features (Future Phases)

### Phase 5: Enhanced Zoom Testing (Optional)
- Zoom transition animations testing
- Multi-viewport zoom coordination
- Screenshot comparison at different zoom levels
- Automated zoom level detection and reporting

### Phase 6: Integration with Existing Tests (Optional)
- Add zoom verification to critical UI tests
- Create zoom-specific test variants
- Implement zoom-aware element location strategies

## Usage Examples

### For Test Developers
```php
// Tests automatically start at 100% zoom - no action needed

// To test at different zoom levels:
$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
// ... perform test actions ...
$I->resetZoom(); // Return to 100%

// To verify zoom level:
$I->verifyZoomLevel(AcceptanceConfig::ZOOM_LEVEL_100);
```

### For Test Maintenance
```php
// To disable zoom enforcement globally:
// In AcceptanceConfig.php: const ZOOM_ENFORCEMENT_ENABLED = false;

// To change default zoom level:
// In AcceptanceConfig.php: const ZOOM_LEVEL_DEFAULT = self::ZOOM_LEVEL_75;
```

## Conclusion

This implementation plan provides a comprehensive, non-invasive solution for zoom management that:

1. **Requires zero changes to existing test files**
2. **Automatically enforces 100% desktop zoom**
3. **Provides flexible zoom testing capabilities**
4. **Maintains backward compatibility**
5. **Can be implemented incrementally**

The approach leverages the existing Codeception infrastructure and follows established patterns in the codebase, making it maintainable and reliable for long-term use.