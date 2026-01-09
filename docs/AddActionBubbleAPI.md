# AddActionBubble Global API Documentation

## Overview

The `addActionBubble` function is now globally accessible, allowing WordPress plugins to easily add custom action buttons to the comment form interface. This API provides a simple yet powerful way to extend the comment form with plugin-specific functionality.

## Global Functions

The AI Style theme exposes three functions globally:

- `window.addActionBubble()` - Create action buttons with optional callbacks
- `window.createActionButtonsContainer()` - Get or create the action buttons container
- `window.refreshButtonVisibility()` - Refresh button visibility based on data

## Function Reference

### addActionBubble(dashiconClass, text, container, options)

Creates an action button with a dashicon and optional text, with support for callbacks and advanced options.

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `dashiconClass` | `string` | Yes | Dashicon class name (without 'dashicons-' prefix) |
| `text` | `string` | Yes | Button text to display |
| `container` | `HTMLElement` | Yes | Container element to append the button to |
| `options` | `string\|Object` | No | Button ID (backward compatibility) or options object |

#### Options Object

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `id` | `string` | `''` | Button element ID |
| `callback` | `function` | `null` | Click event handler function |
| `tooltip` | `string` | `''` | Tooltip text for accessibility |
| `disabled` | `boolean` | `false` | Initial disabled state |
| `className` | `string` | `''` | Additional CSS classes |
| `data` | `Object` | `{}` | Custom data attributes |
| `position` | `string` | `'append'` | Position: 'append' or 'prepend' |

#### Returns

`HTMLElement` - The created button element

#### Examples

**Basic Usage (Backward Compatible)**
```javascript
// Simple button creation (old signature still supported)
const button = window.addActionBubble(
    'admin-plugins', 
    'My Plugin', 
    container, 
    'my-plugin-button'
);

// Manually add event listener
button.addEventListener('click', function() {
    console.log('Button clicked');
});
```

**Enhanced Usage with Callback**
```javascript
// Button with integrated callback
const button = window.addActionBubble(
    'admin-plugins', 
    'My Plugin', 
    container, 
    {
        id: 'my-plugin-button',
        callback: function(event) {
            console.log('Button clicked:', event.target.id);
            // Your plugin functionality here
        },
        tooltip: 'Click to perform my plugin action'
    }
);
```

**Advanced Usage**
```javascript
// Button with all options
const button = window.addActionBubble(
    'database', 
    'Export Data', 
    container, 
    {
        id: 'export-button',
        callback: function(event) {
            const format = event.target.dataset.exportFormat;
            exportData(format);
        },
        tooltip: 'Export comment data',
        className: 'export-button custom-style',
        data: {
            exportFormat: 'json',
            version: '1.0'
        },
        position: 'prepend'
    }
);
```

### createActionButtonsContainer(commentForm)

Gets the existing action buttons container or creates a new one if it doesn't exist.

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `commentForm` | `HTMLElement` | Yes | The comment form element |

#### Returns

`HTMLElement` - The action buttons container element

#### Example

```javascript
const commentForm = document.getElementById('commentform');
const container = window.createActionButtonsContainer(commentForm);
```

### refreshButtonVisibility(data)

Refreshes button visibility based on provided data or window.cacbot_data.

#### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `data` | `Object` | No | Data object for visibility logic (uses window.cacbot_data if not provided) |

#### Example

```javascript
// Refresh with current data
window.refreshButtonVisibility();

// Refresh with specific data
window.refreshButtonVisibility({
    action_enabled_build_plugin: true
});
```

## Plugin Integration Guide

### Basic Plugin Integration

```javascript
(function() {
    'use strict';
    
    // Wait for AI Style theme to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Check if functions are available
        if (typeof window.addActionBubble !== 'function') {
            console.warn('AI Style theme addActionBubble not available');
            return;
        }
        
        // Get comment form
        const commentForm = document.getElementById('commentform');
        if (!commentForm) return;
        
        // Get or create action buttons container
        const container = window.createActionButtonsContainer(commentForm);
        
        // Register your plugin buttons
        registerMyPluginButtons(container);
    });
    
    function registerMyPluginButtons(container) {
        // Primary action button
        window.addActionBubble('admin-plugins', 'My Action', container, {
            id: 'my-plugin-action',
            callback: handleMyAction,
            tooltip: 'Perform my plugin action'
        });
    }
    
    function handleMyAction(event) {
        // Your plugin-specific logic here
        console.log('My plugin action executed');
    }
})();
```

### Advanced Plugin Integration

```javascript
(function() {
    'use strict';
    
    const MyPlugin = {
        buttons: [],
        
        init: function() {
            document.addEventListener('DOMContentLoaded', this.onDOMReady.bind(this));
        },
        
        onDOMReady: function() {
            if (!this.checkDependencies()) return;
            
            const container = this.getContainer();
            if (container) {
                this.registerButtons(container);
            }
        },
        
        checkDependencies: function() {
            if (typeof window.addActionBubble !== 'function') {
                console.warn('MyPlugin: AI Style theme not available');
                return false;
            }
            return true;
        },
        
        getContainer: function() {
            const commentForm = document.getElementById('commentform');
            return commentForm ? window.createActionButtonsContainer(commentForm) : null;
        },
        
        registerButtons: function(container) {
            // Primary action
            const primaryButton = window.addActionBubble('admin-plugins', 'Primary', container, {
                id: 'myplugin-primary',
                callback: this.handlePrimary.bind(this),
                tooltip: 'Primary plugin action',
                data: { action: 'primary' }
            });
            this.buttons.push(primaryButton);
            
            // Secondary action
            const secondaryButton = window.addActionBubble('admin-settings', 'Settings', container, {
                id: 'myplugin-settings',
                callback: this.handleSettings.bind(this),
                tooltip: 'Open plugin settings',
                className: 'secondary-action',
                data: { action: 'settings' }
            });
            this.buttons.push(secondaryButton);
        },
        
        handlePrimary: function(event) {
            const action = event.target.dataset.action;
            console.log('Primary action:', action);
            // Your primary action logic
        },
        
        handleSettings: function(event) {
            const action = event.target.dataset.action;
            console.log('Settings action:', action);
            // Your settings logic
        }
    };
    
    // Initialize plugin
    MyPlugin.init();
})();
```

## Available Dashicons

Common dashicons you can use (without the 'dashicons-' prefix):

- `admin-plugins` - Plugin icon
- `admin-settings` - Settings gear
- `admin-tools` - Tools/wrench
- `database` - Database icon
- `download` - Download arrow
- `upload` - Upload arrow
- `edit` - Edit pencil
- `trash` - Delete/trash
- `plus` - Add/plus sign
- `minus` - Remove/minus sign
- `hammer` - Build/hammer (used by Build Plugin button)
- `external` - External link
- `share` - Share icon

For a complete list, see the [WordPress Dashicons reference](https://developer.wordpress.org/resource/dashicons/).

## CSS Styling

### Default Button Styles

Buttons created with `addActionBubble` have the class `action-bubble` and follow this structure:

```html
<button type="button" class="action-bubble [custom-classes]" id="[button-id]">
    <span class="dashicons dashicons-[icon]"></span>
    <span class="action-bubble-text">[text]</span>
</button>
```

### Custom Styling

You can add custom styles by:

1. **Using the className option:**
```javascript
window.addActionBubble('admin-plugins', 'My Button', container, {
    className: 'my-custom-button-style'
});
```

2. **Targeting the button ID:**
```css
#my-plugin-button {
    background-color: #custom-color;
    border-radius: 8px;
}
```

3. **Targeting all action bubbles:**
```css
.action-bubble {
    /* Your custom styles */
}
```

## Best Practices

### 1. Check for Availability
Always check if the functions are available before using them:

```javascript
if (typeof window.addActionBubble === 'function') {
    // Safe to use
}
```

### 2. Use Unique IDs
Ensure your button IDs are unique to avoid conflicts:

```javascript
window.addActionBubble('admin-plugins', 'My Plugin', container, {
    id: 'myplugin-unique-action-button'
});
```

### 3. Provide Tooltips
Always provide tooltips for accessibility:

```javascript
window.addActionBubble('admin-plugins', 'My Plugin', container, {
    tooltip: 'Clear description of what this button does'
});
```

### 4. Handle Errors Gracefully
Wrap your code in try-catch blocks:

```javascript
try {
    const button = window.addActionBubble(/* ... */);
} catch (error) {
    console.error('Failed to create button:', error);
}
```

### 5. Use Semantic Data Attributes
Store relevant data in data attributes for easy access in callbacks:

```javascript
window.addActionBubble('database', 'Export', container, {
    callback: function(event) {
        const format = event.target.dataset.exportFormat;
        const includeMetadata = event.target.dataset.includeMetadata === 'true';
        // Use the data
    },
    data: {
        exportFormat: 'json',
        includeMetadata: 'true'
    }
});
```

## Troubleshooting

### Common Issues

**1. Functions not available**
- Ensure the AI Style theme is active
- Check that your code runs after DOMContentLoaded
- Verify the theme's JavaScript has loaded

**2. Buttons not appearing**
- Check that the comment form exists on the page
- Verify the container is being created correctly
- Check for JavaScript errors in the console

**3. Callbacks not executing**
- Ensure the callback is a valid function
- Check for JavaScript errors in the callback
- Verify event propagation isn't being stopped

**4. Styling issues**
- Check CSS specificity
- Verify custom classes are being applied
- Use browser dev tools to inspect the button structure

### Debug Information

The theme logs function availability to the console:

```
Comment form functions are available globally:
- resetCommentSubmitButton()
- addActionBubble(dashiconClass, text, container, options)
- createActionButtonsContainer(commentForm)
- refreshButtonVisibility(data)
```

## Changelog

### Version 1.0 (Current)
- Initial global API implementation
- Backward compatibility with old signature
- Callback support
- Advanced options (tooltip, data attributes, custom classes)
- Position control (append/prepend)
- Comprehensive documentation

## Support

For issues or questions about the `addActionBubble` API:

1. Check this documentation first
2. Review the browser console for errors
3. Test with the basic examples provided
4. Check for conflicts with other plugins

This API is designed to be simple, powerful, and extensible while maintaining backward compatibility with existing code.