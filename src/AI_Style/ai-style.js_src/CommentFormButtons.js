/**
 * Functions for creating and managing action buttons for the comment form
 */

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
        
        // Add example action buttons - similar to commercial implementations
        const plusButton = 
        addActionBubble('dashicons-plus', 'Attach', actionButtonsContainer); // Plus button like ChatGPT
        addActionBubble('dashicons-hammer', 'Act', actionButtonsContainer);
       // addActionBubble('dashicons-format-image', 'Create Image', actionButtonsContainer);
        
        // Add tooltip to plus button
        if (plusButton) {
            plusButton.title = "Add attachment";
        }
    }
    
    return actionButtonsContainer;
}

/**
 * Adds an action button/bubble with a dashicon and optional text
 * @param {string} dashiconClass - The dashicon class name (without 'dashicons')
 * @param {string} text - The text to display next to the icon
 * @param {HTMLElement} container - The container to add the button to
 * @returns {HTMLElement} - The created button element
 */
export function addActionBubble(dashiconClass, text, container) {
    const button = document.createElement('button');
    button.type = 'button'; // Prevent form submission
    button.className = 'action-bubble';
    
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
    
    // Add click event listener
    button.addEventListener('click', function(e) {
        e.preventDefault();
        console.log(`Action button clicked: ${text}`);
        // Here you would implement the actual functionality
    });
    
    // Add to container
    container.appendChild(button);
    return button;
}