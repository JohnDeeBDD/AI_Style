
/**
 * Enhances the WordPress comment form to look like modern LLM interfaces
 * such as ChatGPT, Gemini, and Grok
 */
export default function commentBoxStyle() {
    console.log("Applying modern LLM-style to comment form");
    
    const commentTextarea = document.getElementById('comment');
    if (commentTextarea) {
        // Set initial height to one row
        commentTextarea.rows = 1;
        commentTextarea.style.height = 'auto';
        
        // Set placeholder text
        if (commentTextarea.getAttribute('placeholder') === '') {
            commentTextarea.setAttribute('placeholder', 'Ask anything');
        }
        
        // Auto-resize the textarea as the user types
        commentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Trigger initial resize
        commentTextarea.style.height = (commentTextarea.scrollHeight) + 'px';
    }
    
    // Add action buttons (similar to modern LLM interfaces)
    const commentForm = document.getElementById('commentform');
    if (commentForm) {
        // Focus the textarea when clicking anywhere in the form
        commentForm.addEventListener('click', function(e) {
            if (e.target === commentForm) {
                commentTextarea.focus();
            }
        });
        
        // Create action buttons container
        createActionButtonsContainer(commentForm);
        
        // Adjust submit button position
        adjustSubmitButtonPosition();
    }
}

/**
 * Adjusts the submit button position to align with the textarea
 */
function adjustSubmitButtonPosition() {
    const submitButton = document.querySelector('.form-submit input[type="submit"]');
    const commentTextarea = document.getElementById('comment');
    
    if (submitButton && commentTextarea) {
        // Ensure the submit button is vertically centered with the textarea
        const formSubmit = document.querySelector('.form-submit');
        if (formSubmit) {
            formSubmit.style.display = 'flex';
            formSubmit.style.alignItems = 'center';
        }
        
        // Add a disabled state to the submit button when textarea is empty
        commentTextarea.addEventListener('input', function() {
            if (this.value.trim() === '') {
                submitButton.style.opacity = '0.6';
                submitButton.style.cursor = 'default';
            } else {
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
            }
        });
        
        // Initial state
        if (commentTextarea.value.trim() === '') {
            submitButton.style.opacity = '0.6';
            submitButton.style.cursor = 'default';
        }
    }
}

/**
 * Creates a container for action buttons below the textarea
 * @param {HTMLElement} commentForm - The comment form element
 */
function createActionButtonsContainer(commentForm) {
    // Check if container already exists
    if (document.getElementById('action-buttons-container')) {
        return;
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
        const plusButton = addActionBubble('dashicons-plus', '', actionButtonsContainer); // Plus button like ChatGPT
        addActionBubble('dashicons-upload', 'Attach', actionButtonsContainer);
        addActionBubble('dashicons-editor-code', 'Code', actionButtonsContainer);
        addActionBubble('dashicons-format-image', 'Image', actionButtonsContainer);
        
        // Add tooltip to plus button
        if (plusButton) {
            plusButton.title = "Add attachment";
        }
    }
}

/**
 * Adds an action button/bubble with a dashicon and optional text
 * @param {string} dashiconClass - The dashicon class name (without 'dashicons')
 * @param {string} text - The text to display next to the icon
 * @param {HTMLElement} container - The container to add the button to
 * @returns {HTMLElement} - The created button element
 */
function addActionBubble(dashiconClass, text, container) {
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