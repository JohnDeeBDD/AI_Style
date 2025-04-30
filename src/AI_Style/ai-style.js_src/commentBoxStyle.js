
/**
 * Enhances the WordPress comment form to look like the ChatGPT interface
 */
export default function commentBoxStyle() {
    console.log("Applying ChatGPT-style to comment form");
    
    // Set the placeholder text to "Ask anything"
    const commentTextarea = document.getElementById('comment');
    if (commentTextarea) {
        //commentTextarea.placeholder = 'Ask anything';
        
        // Set initial height to one row
        commentTextarea.rows = 1;
        commentTextarea.style.height = 'auto';
        
        // Auto-resize the textarea as the user types
        commentTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // Add action buttons (similar to ChatGPT interface)
    const commentForm = document.getElementById('commentform');
    if (commentForm) {
        // Focus the textarea when clicking anywhere in the form
        commentForm.addEventListener('click', function(e) {
            if (e.target === commentForm) {
                commentTextarea.focus();
            }
        });
        
        // Change the submit button text to be empty (icon only)
        const submitButton = document.querySelector('.form-submit input[type="submit"]');
        if (submitButton) {
            submitButton.value = 'SUBMIT';
        }
    }
}