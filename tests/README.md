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

### Helper Methods Available
- `ensureDesktop100Zoom()` - Enforce 100% zoom (required after navigation)
- `setZoomLevel($level)` - Set specific zoom level
- `resetZoom()` - Reset to 100% zoom
- `verifyZoomLevel($expected)` - Verify current zoom level
- `cUrlWP_SiteToCreatePost()` - Create WordPress posts via API
- `cUrlWP_SiteToDeletePost()` - Delete WordPress posts via API
- `switchBetweenLinkedAnchorPosts()` - Navigate between linked posts

### TDD Test Development Pattern

Following the project's TDD methodology, new feature tests should:

1. **Start with acceptance test creation** before any implementation
2. **Test both functionality and visual appearance** (using screenshots)
3. **Be atomic and focused** on single feature aspects
4. **Follow the established naming conventions** and file structure

### Test Template

```php
<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('describe what this test does');

// Navigate to test page
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->loginAsAdmin(); // if needed

// REQUIRED: Enforce zoom after navigation
$I->ensureDesktop100Zoom();

// Test logic here
$I->see('Expected content');
$I->click('Some element');
$I->see('Expected result');

// Clean up if zoom was changed
$I->resetZoom();
```

## Configuration

### Environment Setup
- **WordPress URL**: `http://localhost`
- **Admin Credentials**: Username: `Codeception`, Password: `password`
- **Test Database**: `wordpress_unit_test`
- **Browser**: Chrome with specific zoom-related configurations

### Zoom Configuration
- **Default Zoom**: 100% (enforced via browser settings and helper methods)
- **Available Levels**: 25%, 50%, 75%, 100%, 150%, 200%
- **Enforcement**: Manual (must call helper methods after navigation)

## Common Test Scenarios

### Basic Page Testing
```php
$I->amOnPage(AcceptanceConfig::TEST_POST_PAGE);
$I->ensureDesktop100Zoom();
$I->see('Expected content');
```

### Admin Area Testing
```php
$I->loginAsAdmin();
$I->amOnPage(AcceptanceConfig::ADMIN_POSTS);
$I->ensureDesktop100Zoom();
$I->see('Posts');
```

### Multi-Zoom Testing
```php
$I->amOnPage('/responsive-page');
$I->ensureDesktop100Zoom();

$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_75);
$I->see('Content at 75%');

$I->setZoomLevel(AcceptanceConfig::ZOOM_LEVEL_150);
$I->see('Content at 150%');

$I->resetZoom();
```

## Troubleshooting

### Common Issues

1. **"WebDriver not available" errors**
   - **Cause**: Calling zoom methods before navigation
   - **Solution**: Always call zoom methods after `amOnPage()` or `loginAsAdmin()`

2. **Inconsistent test results**
   - **Cause**: Tests not starting at consistent zoom
   - **Solution**: Ensure `ensureDesktop100Zoom()` is called after navigation

3. **Zoom not taking effect**
   - **Cause**: Insufficient wait time
   - **Solution**: Increase `ZOOM_RESET_DELAY` in AcceptanceConfig.php

### Debug Mode
Run tests with `--debug` flag to see detailed output including zoom enforcement messages:

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
2. Follow the zoom enforcement pattern
3. Use configuration constants from `AcceptanceConfig.php`
4. Add appropriate comments and documentation
5. Test at multiple zoom levels if UI-related
6. Clean up any zoom changes before test completion
7. **Implement JavaScript features as atomic files** in the `ai-style.js_src` directory
8. **Import and integrate** all new functions in the main `ai-style.js` file

For questions about zoom enforcement, see the [Zoom Enforcement Guide](ZOOM_ENFORCEMENT_GUIDE.md).