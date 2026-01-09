/**
 * Enhances the WordPress comment form to look like modern LLM interfaces
 * such as ChatGPT, Gemini, and Grok
 */

import { createActionButtonsContainer } from './CommentFormButtons.js';

/**
 * Main function to apply modern LLM-style to the comment form
 */
export default function commentBoxStyle() {

    
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
    
    if (submitButton) {
        // Ensure the submit button is vertically centered with the textarea
        const formSubmit = document.querySelector('.form-submit');
        if (formSubmit) {
            formSubmit.style.display = 'flex';
            formSubmit.style.alignItems = 'center';
        }
        
        // Add form submission handler for loading state
        setupFormSubmissionHandler(submitButton);
    }
}


/**
 * Sets up form submission handler with loading state
 */
function setupFormSubmissionHandler(submitButton) {
    const commentForm = document.getElementById('commentform');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            // Set submitting state
            setSubmitButtonLoadingState(submitButton, true);
        });
    }
}

/**
 * Sets the submit button to loading state or resets it
 */
function setSubmitButtonLoadingState(submitButton, isLoading) {
    if (isLoading) {
        // Store original text
        if (!submitButton.dataset.originalText) {
            submitButton.dataset.originalText = submitButton.value;
        }
        
        // Set loading state
        submitButton.dataset.submitting = 'true';
        submitButton.disabled = true;
        submitButton.style.opacity = '0.7';
        submitButton.style.cursor = 'not-allowed';
        
        // Add loading animation
        submitButton.value = 'Submitting...';
        submitButton.style.position = 'relative';
        
        // Create and add spinner
        createLoadingSpinner(submitButton);
        
    } else {
        // Reset to normal state
        submitButton.dataset.submitting = 'false';
        submitButton.disabled = false;
        submitButton.style.opacity = '1';
        submitButton.style.cursor = 'pointer';
        
        // Restore original text
        if (submitButton.dataset.originalText) {
            submitButton.value = submitButton.dataset.originalText;
        }
        
        // Remove spinner
        removeLoadingSpinner(submitButton);
    }
}

/**
 * Creates a loading spinner animation for the submit button
 */
function createLoadingSpinner(submitButton) {
    // Remove existing spinner if any
    removeLoadingSpinner(submitButton);
    
    const spinner = document.createElement('span');
    spinner.className = 'comment-submit-spinner';
    spinner.innerHTML = '⟳';
    
    // Style the spinner
    spinner.style.cssText = `
        display: inline-block;
        margin-left: 8px;
        animation: spin 1s linear infinite;
        font-size: 14px;
    `;
    
    // Add CSS animation if not already present
    if (!document.querySelector('#comment-spinner-styles')) {
        const style = document.createElement('style');
        style.id = 'comment-spinner-styles';
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Insert spinner after the button
    submitButton.parentNode.insertBefore(spinner, submitButton.nextSibling);
}

/**
 * Removes the loading spinner
 */
function removeLoadingSpinner(submitButton) {
    const spinner = submitButton.parentNode.querySelector('.comment-submit-spinner');
    if (spinner) {
        spinner.remove();
    }
}

/**
 * Public function to reset submit button state (for future AJAX implementations)
 */
export function resetCommentSubmitButton() {
    const submitButton = document.querySelector('.form-submit input[type="submit"]');
    if (submitButton) {
        setSubmitButtonLoadingState(submitButton, false);
    }
}