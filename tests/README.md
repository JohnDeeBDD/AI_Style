**Main JS Start Point Integration File:**
```
src/AI_Style/ai-style.js_src/ai-style.js
```

All new functions must be imported and called from this main JavaScript file.

### Build Process

When any Codeception acceptance tests are executed, the JavaScript files are automatically compiled using Spack, ensuring the latest code changes are always tested. Therefore, to compile JS, just run Codeception acceptance tests.

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

### Configuration-Driven Testing Approach

The test suite now uses a **configuration-driven approach** for device and window size management, eliminating the need for dynamic zoom changes during test execution.

#### API Helper Methods 
- `cUrlWP_SiteToCreatePost()` - Create WordPress posts via API
- `cUrlWP_SiteToDeletePost()` - Delete WordPress posts via API
- `switchBetweenLinkedAnchorPosts()` - Navigate between linked posts

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
