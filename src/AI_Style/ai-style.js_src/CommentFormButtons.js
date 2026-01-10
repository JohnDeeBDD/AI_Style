/**
 * Functions for creating and managing action buttons for the comment form
 */

import cacbotData from "./cacbotData";

/**
 * Creates a container for action buttons below the textarea
 * @param {HTMLElement} commentForm - The comment form element
 * @returns {HTMLElement} - The created container element
 */
export function createActionButtonsContainer(commentForm) {
    // Check if container already exists
    if (document.getElementById('action-buttons-container')) {
        return document.getElementById('action-buttons-container');
    }
    
    // Create container for action buttons
    const actionButtonsContainer = document.createElement('div');
    actionButtonsContainer.id = 'action-buttons-container';
    actionButtonsContainer.className = 'action-buttons-container';
    
    // Insert container after the textarea but before the submit button
    const commentTextareaParent = document.querySelector('.comment-form-comment');
    if (commentTextareaParent) {
        commentForm.insertBefore(actionButtonsContainer, commentTextareaParent.nextSibling);
        
        // Always create the Build Plugin button (initially hidden)
        addActionBubble('dashicons-hammer', 'Build Plugin', actionButtonsContainer, 'action-button-build-plugin');
        
        // Hide the button initially - it will be shown/hidden based on dynamic data
        const buildPluginButton = document.getElementById('action-button-build-plugin');
        if (buildPluginButton) {
            buildPluginButton.style.display = 'none';
        }
        
        // Set up listener for dynamic data updates from app.ts
        setupDataListener();
        
        // Also check initial state from window.cacbot_data if available
        updateButtonVisibility(window.cacbot_data);
    }
    
    return actionButtonsContainer;
}

/**
 * Sets up event listener for cacbot data updates from app.ts
 */
function setupDataListener() {
    // Listen for the custom event dispatched by app.ts DataService
    document.addEventListener('cacbot-data-updated', (event) => {
        console.log('CommentFormButtons: Received updated data from app.ts:', event.detail);
        updateButtonVisibility(event.detail);
    });
}

/**
 * Updates button visibility based on the provided data
 * @param {Object} data - The data object containing action settings
 */
function updateButtonVisibility(data) {
    if (!data) {
        console.log('CommentFormButtons: No data provided, keeping buttons hidden');
        return;
    }
    
    const buildPluginButton = document.getElementById('action-button-build-plugin');
    if (!buildPluginButton) {
        console.log('CommentFormButtons: Build Plugin button not found');
        return;
    }
    
    // Check for the build plugin action setting in the data
    // Handle both possible property names for flexibility
    const buildPluginEnabled = data._cacbot_action_enabled_build_plugin === "1" || 
                              data.action_enabled_build_plugin === true ||
                              data.action_enabled_create_new_linked_post === true;
    
    if (buildPluginEnabled) {
        console.log('CommentFormButtons: Showing Build Plugin button');
        buildPluginButton.style.display = '';
    } else {
        console.log('CommentFormButtons: Hiding Build Plugin button');
        buildPluginButton.style.display = 'none';
    }
}

/**
 * Adds an action button/bubble with a dashicon and optional text
 * Enhanced version with callback support and additional options
 *
 * @param {string} dashiconClass - The dashicon class name (without 'dashicons')
 * @param {string} text - The text to display next to the icon
 * @param {HTMLElement} container - The container to add the button to
 * @param {string|Object} options - Button ID (backward compatibility) or options object
 * @param {string} [options.id=''] - Button ID
 * @param {Function} [options.callback=null] - Click handler function
 * @param {string} [options.tooltip=''] - Tooltip text for accessibility
 * @param {boolean} [options.disabled=false] - Initial disabled state
 * @param {string} [options.className=''] - Additional CSS classes
 * @param {Object} [options.data={}] - Custom data attributes
 * @param {string} [options.position='append'] - Position: 'append', 'prepend', or 'before:elementId'
 * @returns {HTMLElement} - The created button element
 */
export function addActionBubble(dashiconClass, text, container, options = {}) {
    // Handle backward compatibility - if options is a string, treat as ID
    if (typeof options === 'string') {
        options = { id: options };
    }
    
    const {
        id = '',
        callback = null,
        tooltip = '',
        disabled = false,
        className = '',
        data = {},
        position = 'append'
    } = options;
    
    const button = document.createElement('button');
    button.type = 'button'; // Prevent form submission
    button.className = `action-bubble${className ? ' ' + className : ''}`;
    button.disabled = disabled;
    
    if (id) {
        button.id = id;
    }
    
    if (tooltip) {
        button.title = tooltip;
        button.setAttribute('aria-label', tooltip);
    }
    
    // Add custom data attributes
    Object.entries(data).forEach(([key, value]) => {
        button.dataset[key] = value;
    });
    
    // Create dashicon
    const icon = document.createElement('span');
    icon.className = `dashicons ${dashiconClass}`;
    button.appendChild(icon);
    
    // Add text if provided
    if (text) {
        const textSpan = document.createElement('span');
        textSpan.className = 'action-bubble-text';
        textSpan.textContent = text;
        button.appendChild(textSpan);
    }
    
    // Add callback if provided
    if (callback && typeof callback === 'function') {
        button.addEventListener('click', callback);
    }
    
    // Handle positioning
    switch (position) {
        case 'prepend':
            container.insertBefore(button, container.firstChild);
            break;
        case 'append':
        default:
            container.appendChild(button);
            break;
    }
    
    return button;
}

/**
 * Public function to manually trigger button visibility update
 * Useful for external scripts that want to force a refresh
 * @param {Object} data - Optional data object, will use window.cacbot_data if not provided
 */
export function refreshButtonVisibility(data = null) {
    updateButtonVisibility(data || window.cacbot_data);
}