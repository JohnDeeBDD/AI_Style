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

## CSS and UI Architecture

### Breakpoint System
The theme uses a **single main breakpoint at 782px** to distinguish between mobile and desktop layouts:
- **Mobile**: Below 782px
- **Desktop**: 782px and above
- **Minor breakpoints**: Additional smaller breakpoints exist within the mobile and desktop ranges

### Device-Specific UI Elements
- **Admin Bar Height**: Different heights between mobile and desktop layouts
- **Footer**: Only displayed on desktop - **no footer on mobile devices**

## Key Features

### Configuration-Driven Testing Approach

The test suite now uses a **configuration-driven approach** for device and window size management, eliminating the need for dynamic zoom changes during test execution.

#### AcceptanceConfig Methods Available
- **[`AcceptanceConfig::getDeviceMode()`](tests/_support/AcceptanceConfig.php:138)** - Get current device mode (desktop, tablet_portrait, tablet_landscape, mobile_portrait, mobile_landscape)
- **[`AcceptanceConfig::getWindowSize()`](tests/_support/AcceptanceConfig.php:117)** - Get current window size from YAML configuration
- **[`AcceptanceConfig::isDesktop()`](tests/_support/AcceptanceConfig.php:167)** - Check if running in desktop mode
- **[`AcceptanceConfig::isTablet()`](tests/_support/AcceptanceConfig.php:177)** - Check if running in tablet mode (portrait or landscape)
- **[`AcceptanceConfig::isMobile()`](tests/_support/AcceptanceConfig.php:189)** - Check if running in mobile mode (portrait or landscape)

#### API Helper Methods 
- `cUrlWP_SiteToCreatePost()` - Create WordPress posts via API
- `cUrlWP_SiteToDeletePost()` - Delete WordPress posts via API
- `switchBetweenLinkedAnchorPosts()` - Navigate between linked posts

### Device Configuration Management

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

## Configuration

### Environment Setup
- **WordPress URL**: `http://localhost`
- **Admin Credentials**: Username: `Codeception`, Password: `password`
- **Browser**: Chrome with device-specific configurations

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

### Multi-Device Testing
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
