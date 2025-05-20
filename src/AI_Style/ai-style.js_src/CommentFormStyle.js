/**
 * Enhances the WordPress comment form to look like modern LLM interfaces
 * such as ChatGPT, Gemini, and Grok
 */

import { createActionButtonsContainer } from './CommentFormButtons.js';

/**
 * Main function to apply modern LLM-style to the comment form
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