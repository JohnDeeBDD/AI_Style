# AI Style WordPress Theme Development Guide

## Overview

This project involves building a WordPress theme that is inspired by the ChatGPT user interface.

## Development Process

The development process follows Test Driven Development (TDD) methodology.

## Feature Development Workflow

### 1. Test Creation

For each new feature, development begins by creating a Codeception acceptance test to verify the implementation.

**Example test location:**
```
/var/www/html/wp-content/themes/ai_style/tests/acceptance/ScreenCaptureCept.php
```

The `ScreenCaptureCept.php` test serves dual purposes:
- Testing feature functionality
- Capturing visual snapshots of the UI during development

### 2. JavaScript Development

Each new JavaScript feature should be implemented as a small, atomic file.

**Template location:**
```
/var/www/html/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/template.js
```

### 3. Function Integration

All new functions must be imported and called from the main JavaScript file:
```
/var/www/html/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/ai-style.js
```

## Build Process

When any Codeception acceptance test is executed, the JavaScript files are automatically compiled using Spack.