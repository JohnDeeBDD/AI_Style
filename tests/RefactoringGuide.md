# Refactoring Guide: Zoom and Window Size Testing

## Overview

This document outlines the new approach for handling zoom and window size testing in our acceptance test suite. We are moving away from helper functions that dynamically resize screens and reset zoom levels within tests, towards a configuration-driven approach.

## Previous Approach (Deprecated)

Previously, we used helper functions within tests to:
- Resize the screen dynamically
- Reset zoom levels
- Ensure desktop 100% zoom
- Manually adjust window dimensions during test execution

**This approach is now deprecated and should be refactored.**

## New Approach

### Configuration-Driven Window Sizing

1. **YAML Configuration**: Set screen size in the acceptance suite YAML via the WP WebDriver config using the `window_size` parameter.

2. **Device and Orientation Simulation**: Configure different window sizes in the YAML file to simulate each device type and orientation.

3. **Static Window Sizes**: Window sizes are set once at the suite level and remain constant throughout test execution.

### Test Implementation Guidelines

#### For Zoom/Window Size Aware Tests

When a test needs to be aware of the current zoom level or window size:

1. **Get Configuration**: Retrieve the current configuration from within the test
2. **Determine Current Configuration**: Identify the current window size configuration that is active
3. **Conditional Testing**: Implement test logic that adapts based on the current window size configuration

#### Key Principles

- **No Dynamic Resizing**: Tests should NOT change window size during execution
- **External Configuration**: All window size changes are handled via YAML configuration outside of the test
- **Configuration-Aware Logic**: Tests should query the current configuration to determine appropriate behavior

### Test Runner Strategy

- **Multi-Size Execution**: Implement a runner that can execute the entire acceptance suite at each configured window size
- **Comprehensive Coverage**: Each test suite run covers one specific window size/device configuration
- **Parallel Execution**: Consider running different window size configurations in parallel for efficiency

## Implementation Steps

### 1. Update YAML Configuration

```yaml
# Example acceptance.suite.yml configuration
modules:
  enabled:
    - WebDriver:
        window_size: 1920x1080  # Desktop
        # OR
        window_size: 768x1024   # Tablet Portrait
        # OR  
        window_size: 375x667    # Mobile
```

### 2. Refactor Existing Tests

For tests that currently use helper functions:

```php
// OLD APPROACH (Remove)
$I->resizeWindow(1920, 1080);
$I->resetZoom();
$I->ensureDesktopZoom();

// NEW APPROACH (Implement)
$currentConfig = $I->getAcceptanceConfig();
$windowSize = $currentConfig->getWindowSize();
$deviceMode = $currentConfig->getDeviceMode();

if ($deviceMode === 'desktop') {
    // Desktop-specific test logic
} elseif ($deviceMode === 'tablet') {
    // Tablet-specific test logic
} elseif ($deviceMode === 'mobile') {
    // Mobile-specific test logic
}
```


## Configuration Examples

### Desktop Configuration
```yaml
window_size: 1920x1080
device_mode: desktop
```

### Tablet Portrait Configuration
```yaml
window_size: 768x1024
device_mode: tablet_portrait
```

### Tablet Landscape Configuration
```yaml
window_size: 1024x768
device_mode: tablet_landscape
```

### Mobile Portrait Configuration
```yaml
window_size: 375x667
device_mode: mobile_portrait
```

### Mobile Landscape Configuration
```yaml
window_size: 667x375
device_mode: mobile_landscape
```