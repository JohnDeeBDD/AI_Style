# AI Style Theme - Test Suite

This directory contains the Codeception test suite for the AI Style WordPress theme, a ChatGPT-inspired WordPress theme built using Test Driven Development (TDD) methodology.

## Project Overview

The AI Style WordPress theme is inspired by the ChatGPT user interface and follows a strict Test Driven Development approach where every feature begins with test creation before implementation.

## Development Workflow

### Test Driven Development Process

The development process follows TDD methodology with this workflow:

1. **Test Creation First** - For each new feature, development begins by creating a Codeception acceptance test
2. **JavaScript Implementation** - Features are implemented as small, atomic JavaScript files
3. **Integration** - All functions are imported and called from the main JavaScript file
4. **Automated Build** - JavaScript files are automatically compiled using Spack when tests run

### Feature Development Structure

**Test Location:**
```
/var/www/html/wp-content/themes/ai_style/tests/acceptance/ScreenCaptureCept.php
```

The `ScreenCaptureCept.php` test serves dual purposes:
- Testing feature functionality
- Capturing visual snapshots of the UI during development

**JavaScript Template:**
```
/var/www/html/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/template.js
```

**Main JS Start Point Integration File:**
```
/var/www/html/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/ai-style.js
```

All new functions must be imported and called from this main JavaScript file.

### Build Process

When any Codeception acceptance test is executed, the JavaScript files are automatically compiled using Spack, ensuring the latest code changes are always tested.

## Quick Start

1. **Run all acceptance tests:**
   ```bash
   ./bin/codecept run acceptance
   ```

2. **Run a specific test:**
   ```bash
   ./bin/codecept run acceptance ZoomEnforcementValidationCept
   ```

3. **Run tests with debug output:**
   ```bash
   ./bin/codecept run acceptance --debug
   ```

## Test Structure

```
tests/
├── acceptance/                 # Acceptance tests (browser-based)
├── _support/                   # Test support files
│   ├── AcceptanceConfig.php   # Test configuration constants
│   └── Helper/
│       └── Acceptance.php     # Custom helper methods
├── _data/                     # Test data and screenshots
├── acceptance.suite.yml       # Codeception configuration
└── codeception.yml           # Main Codeception config
```
## Key Features

### Configuration-Driven Testing Approach

The test suite now uses a **configuration-driven approach** for device and window size management, eliminating the need for dynamic zoom changes during test execution.

#### AcceptanceConfig Methods Available
- **[`AcceptanceConfig::getDeviceMode()`](tests/_support/AcceptanceConfig.php:138)** - Get current device mode (desktop, tablet_portrait, tablet_landscape, mobile_portrait, mobile_landscape)
- **[`AcceptanceConfig::getWindowSize()`](tests/_support/AcceptanceConfig.php:117)** - Get current window size from YAML configuration
- **[`AcceptanceConfig::isDesktop()`](tests/_support/AcceptanceConfig.php:167)** - Check if running in desktop mode
- **[`AcceptanceConfig::isTablet()`](tests/_support/AcceptanceConfig.php:177)** - Check if running in tablet mode (portrait or landscape)
- **[`AcceptanceConfig::isMobile()`](tests/_support/AcceptanceConfig.php:189)** - Check if running in mobile mode (portrait or landscape)

#### Legacy Helper Methods (Deprecated)
- ~~`ensureDesktop100Zoom()`~~ - **DEPRECATED**: Use configuration-driven approach instead
- ~~`setZoomLevel($level)`~~ - **DEPRECATED**: Use static YAML configuration instead
- ~~`resetZoom()`~~ - **DEPRECATED**: No longer needed with static configuration
- ~~`verifyZoomLevel($expected)`~~ - **DEPRECATED**: Use AcceptanceConfig methods instead

#### API Helper Methods (Still Available)
- `cUrlWP_SiteToCreatePost()` - Create WordPress posts via API
- `cUrlWP_SiteToDeletePost()` - Delete WordPress posts via API
- `switchBetweenLinkedAnchorPosts()` - Navigate between linked posts

### Device Configuration Management

#### Supported Device Modes
Configure different device modes in [`acceptance.suite.yml`](tests/acceptance.suite.yml):

| Device Mode | Window Size | Configuration |
|-------------|-------------|---------------|
| `desktop` | 1920x1080 | `window_size: 1920x1080`<br>`device_mode: desktop` |
| `tablet_portrait` | 768x1024 | `window_size: 768x1024`<br>`device_mode: tablet_portrait` |
| `tablet_landscape` | 1024x768 | `window_size: 1024x768`<br>`device_mode: tablet_landscape` |
| `mobile_portrait` | 375x667 | `window_size: 375x667`<br>`device_mode: mobile_portrait` |
| `mobile_landscape` | 667x375 | `window_size: 667x375`<br>`device_mode: mobile_landscape` |

### TDD Test Development Pattern

Following the project's TDD methodology, new feature tests should:

1. **Start with acceptance test creation** before any implementation
2. **Use configuration-driven device detection** instead of dynamic zoom changes
3. **Test both functionality and visual appearance** (using screenshots)
4. **Be atomic and focused** on single feature aspects
5. **Follow the established naming conventions** and file structure
6. **Implement device-specific logic** using AcceptanceConfig methods

### Modern Test Template (Configuration-Driven)

```php
<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('describe what this test does');

// Navigate to test page
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->loginAsAdmin(); // if needed

// NEW APPROACH: Get current configuration
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();

// Device-specific test logic
if (AcceptanceConfig::isDesktop()) {
    // Desktop-specific test logic
    $I->see('Desktop-specific content');
} elseif (AcceptanceConfig::isTablet()) {
    // Tablet-specific test logic
    $I->see('Tablet-specific content');
} elseif (AcceptanceConfig::isMobile()) {
    // Mobile-specific test logic
    $I->see('Mobile-specific content');
}

// Common test logic
$I->see('Expected content');
$I->click('Some element');
$I->see('Expected result');
```

### Legacy Test Template (Deprecated)

```php
<?php
// OLD APPROACH - DO NOT USE IN NEW TESTS
$I = new AcceptanceTester($scenario);
$I->wantTo('describe what this test does');

$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->loginAsAdmin();

// DEPRECATED: These methods are no longer used
// $I->ensureDesktop100Zoom();
// $I->setZoomLevel(1.5);
// $I->resetZoom();

// Use AcceptanceConfig methods instead
```

## Configuration

### Environment Setup
- **WordPress URL**: `http://localhost`
- **Admin Credentials**: Username: `Codeception`, Password: `password`
- **Test Database**: `wordpress_unit_test`
- **Browser**: Chrome with device-specific configurations

### Device Configuration (New Approach)
Configure device modes and window sizes in [`acceptance.suite.yml`](tests/acceptance.suite.yml):

```yaml
modules:
  config:
    WPWebDriver:
      window_size: 1920x1080    # Static window size
      device_mode: desktop      # Device mode identifier
```

#### Available Device Configurations:
- **Desktop**: `window_size: 1920x1080`, `device_mode: desktop`
- **Tablet Portrait**: `window_size: 768x1024`, `device_mode: tablet_portrait`
- **Tablet Landscape**: `window_size: 1024x768`, `device_mode: tablet_landscape`
- **Mobile Portrait**: `window_size: 375x667`, `device_mode: mobile_portrait`
- **Mobile Landscape**: `window_size: 667x375`, `device_mode: mobile_landscape`

### Legacy Zoom Configuration (Deprecated)
- ~~**Default Zoom**: 100% (enforced via browser settings and helper methods)~~
- ~~**Available Levels**: 25%, 50%, 75%, 100%, 150%, 200%~~
- ~~**Enforcement**: Manual (must call helper methods after navigation)~~

**Note**: Zoom management is now handled statically via browser configuration. Dynamic zoom changes during test execution are no longer supported.

## Common Test Scenarios

### Basic Page Testing (Configuration-Driven)
```php
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);

// Get current device configuration
$deviceMode = AcceptanceConfig::getDeviceMode();
$I->see('Expected content');

// Device-specific assertions
if (AcceptanceConfig::isDesktop()) {
    $I->see('Desktop-specific element');
}
```

### Admin Area Testing (Configuration-Driven)
```php
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::ADMIN_POSTS);

// Adapt test based on current device mode
if (AcceptanceConfig::isMobile()) {
    $I->see('Mobile admin interface');
} else {
    $I->see('Posts');
}
```

### Multi-Device Testing (New Approach)
```php
$I->amOnPage('/responsive-page');

// Test adapts automatically based on YAML configuration
$deviceMode = AcceptanceConfig::getDeviceMode();
$windowSize = AcceptanceConfig::getWindowSize();

switch ($deviceMode) {
    case AcceptanceConfig::DEVICE_MODE_DESKTOP:
        $I->see('Desktop layout');
        break;
    case AcceptanceConfig::DEVICE_MODE_TABLET_PORTRAIT:
        $I->see('Tablet portrait layout');
        break;
    case AcceptanceConfig::DEVICE_MODE_MOBILE_PORTRAIT:
        $I->see('Mobile layout');
        break;
}
```

### Legacy Multi-Zoom Testing (Deprecated)
```php
// OLD APPROACH - DO NOT USE
// $I->amOnPage('/responsive-page');
// $I->ensureDesktop100Zoom();
// $I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
// $I->see('Content at 75%');
// $I->resetZoom();
```

## Troubleshooting

### Common Issues

1. **Configuration not detected properly**
   - **Cause**: YAML configuration not properly set in [`acceptance.suite.yml`](tests/acceptance.suite.yml)
   - **Solution**: Verify `window_size` and `device_mode` are correctly configured

2. **Device-specific tests failing**
   - **Cause**: Test logic not adapted for current device mode
   - **Solution**: Use [`AcceptanceConfig::isDesktop()`](tests/_support/AcceptanceConfig.php:167), [`isTablet()`](tests/_support/AcceptanceConfig.php:177), or [`isMobile()`](tests/_support/AcceptanceConfig.php:189) methods

3. **Legacy zoom methods causing errors**
   - **Cause**: Using deprecated zoom helper methods
   - **Solution**: Remove calls to `ensureDesktop100Zoom()`, `resetZoom()`, `setZoomLevel()` and use configuration-driven approach

### Legacy Issues (No Longer Applicable)
- ~~**"WebDriver not available" errors**: Zoom methods before navigation~~
- ~~**Inconsistent test results**: Tests not starting at consistent zoom~~
- ~~**Zoom not taking effect**: Insufficient wait time~~

**Note**: These issues have been eliminated by the configuration-driven approach.

### Debug Mode
Run tests with `--debug` flag to see detailed output including configuration detection messages:

```bash
./bin/codecept run acceptance --debug
```

## Screenshots and Visual Testing

Tests can capture screenshots for visual verification:

```php
$I->makeScreenshot('test-description');
```

Screenshots are saved to `tests/_output/debug/` and can be viewed in browser.

## API Testing Helpers

The test suite includes helpers for WordPress API operations:

- **Create posts**: `$I->cUrlWP_SiteToCreatePost($title, $content)`
- **Delete posts**: `$I->cUrlWP_SiteToDeletePost($postId)`
- **Manage categories**: `$I->cUrlWP_SiteToGetOrCreateCategory($slug, $name)`

These require `localhost_wordpress_api_config.json` configuration file.

## Contributing

When adding new tests following the TDD methodology:

1. **Create tests first** before implementing features
2. **Use configuration-driven device detection** instead of dynamic zoom changes
3. Use configuration constants from [`AcceptanceConfig.php`](tests/_support/AcceptanceConfig.php)
4. Add appropriate comments and documentation
5. **Test across multiple device configurations** if UI-related
6. **Implement device-specific logic** using AcceptanceConfig methods
7. **Implement JavaScript features as atomic files** in the `ai-style.js_src` directory
8. **Import and integrate** all new functions in the main `ai-style.js` file

### Configuration-Driven Test Development Guidelines

- Use [`AcceptanceConfig::getDeviceMode()`](tests/_support/AcceptanceConfig.php:138) to get current device mode
- Use [`AcceptanceConfig::isDesktop()`](tests/_support/AcceptanceConfig.php:167), [`isTablet()`](tests/_support/AcceptanceConfig.php:177), [`isMobile()`](tests/_support/AcceptanceConfig.php:189) for device-specific logic
- Configure different device modes in [`acceptance.suite.yml`](tests/acceptance.suite.yml)
- **DO NOT** use deprecated zoom helper methods (`ensureDesktop100Zoom()`, `resetZoom()`, `setZoomLevel()`)

For detailed information about the refactoring approach, see:
- [RefactoringGuide.md](tests/RefactoringGuide.md) - Implementation guidelines
- [REFACTORING_SUMMARY.md](tests/REFACTORING_SUMMARY.md) - Complete refactoring overview
- [FINAL_REFACTORING_COMPLIANCE_REPORT.md](tests/FINAL_REFACTORING_COMPLIANCE_REPORT.md) - Compliance verification