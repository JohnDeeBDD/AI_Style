/**
 * Sets up the column layout for the ChatGPT-style UI
 * Ensures that #chat-messages div maintains the same dimensions as #respond div
 * Handles dynamic resizing when the window is resized
 */
export default function setupColumns() {
    console.log("Setting up columns for ChatGPT-style UI");
    
    // Function to adjust the dimensions of chat-messages to match respond
    function adjustChatMessagesDimensions() {
        const chatMessages = document.getElementById('chat-messages');
        const respond = document.querySelector('.comment-respond');
        
        if (!chatMessages || !respond) {
            console.log("Required elements not found");
            return;
        }
        
        // Get the dimensions of the respond div
        const respondRect = respond.getBoundingClientRect();
        
        // Set the width and height of chat-messages to match respond
        chatMessages.style.maxWidth = `${respondRect.width}px`;
        chatMessages.style.width = '100%';
        chatMessages.style.height = `${respondRect.height}px`;
        chatMessages.style.minHeight = `${respondRect.height}px`;
        
        // Ensure proper positioning of message containers
        const respondentMessages = document.querySelectorAll('.respondent-message');
        const interlocutorMessages = document.querySelectorAll('.interlocutor-message');
        
        // Ensure respondent messages conform to container width
        respondentMessages.forEach(message => {
            message.style.width = '100%';
        });
        
        // Ensure interlocutor messages are right-justified and have proper width
        interlocutorMessages.forEach(message => {
            message.style.width = '79%';
            message.style.maxWidth = '79%';
            message.style.marginLeft = 'auto';
            
            // Ensure parent container has proper alignment
            const parent = message.parentElement;
            if (parent && parent.classList.contains('message')) {
                parent.style.textAlign = 'right';
            }
        });
        
        console.log("Chat messages dimensions adjusted to match respond div");
    }
    
    // Initial adjustment
    adjustChatMessagesDimensions();
    
    // Handle window resize events
    window.addEventListener('resize', function() {
        adjustChatMessagesDimensions();
    });
    
    // Also adjust when content changes (e.g., new messages added)
    const chatMain = document.getElementById('chat-main');
    if (chatMain) {
        const observer = new MutationObserver(function(mutations) {
            adjustChatMessagesDimensions();
        });
        
        observer.observe(chatMain, {
            childList: true,
            subtree: true
        });
    }
}