/**
 * Functions to add messages to the chat UI
 */

/**
 * Process markdown content using the mmd plugin if available
 * @param {string} content - The content to process
 * @returns {string} The processed content or original content if mmd is not available
 */
function processMarkdown(content) {
    // Check if mmd function is available globally (from the mmd plugin)
    if (typeof window.mmd === 'function') {
        try {
            return window.mmd(content);
        } catch (error) {
            console.warn('Error processing markdown with mmd:', error);
            return content;
        }
    }
    
    // Check if MarkupMarkdown class is available
    if (typeof window.MarkupMarkdown === 'function') {
        try {
            const markdown = new window.MarkupMarkdown();
            if (typeof markdown.transform === 'function') {
                return markdown.transform(content);
            }
        } catch (error) {
            console.warn('Error processing markdown with MarkupMarkdown class:', error);
            return content;
        }
    }
    
    // Return original content if no markdown processor is available
    return content;
}

/**
 * Check if markdown processing is available
 * @returns {boolean} True if markdown processing is available
 */
function isMarkdownAvailable() {
    return (typeof window.mmd === 'function') ||
           (typeof window.MarkupMarkdown === 'function');
}

/**
 * Adds a message from the interlocutor (user) to the chat
 * @param {string} message - The message content
 */
export function addInterlocutorMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error('Chat messages container not found');
        return;
    }

    // Create a unique ID for the message
    const messageId = 'message-' + Date.now();
    
    // Create the message element
    const messageElement = document.createElement('div');
    messageElement.className = 'message interlocutor-message';
    messageElement.id = messageId;
    
    // Create the message content element
    const messageContentElement = document.createElement('div');
    messageContentElement.className = 'message-content';
    messageContentElement.id = 'message-content-' + messageId;
    
    // Process markdown if available
    const processedMessage = processMarkdown(message);
    messageContentElement.innerHTML = processedMessage;
    
    // Append the content to the message
    messageElement.appendChild(messageContentElement);
    
    // Append the message to the chat
    chatMessages.appendChild(messageElement);
    
    // Scroll to the bottom of the chat
    scrollToBottom();
}

/**
 * Adds a message from the respondent (AI/bot) to the chat
 * @param {string} message - The message content
 */
export function addRespondentMessage(message) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error('Chat messages container not found');
        return;
    }

    // Create a unique ID for the message
    const messageId = 'message-' + Date.now();
    
    // Create the message element
    const messageElement = document.createElement('div');
    messageElement.className = 'message respondent-message';
    messageElement.id = messageId;
    
    // Create the message content element
    const messageContentElement = document.createElement('div');
    messageContentElement.className = 'message-content';
    messageContentElement.id = 'message-content-' + messageId;
    
    // Process markdown if available
    const processedMessage = processMarkdown(message);
    messageContentElement.innerHTML = processedMessage;
    
    // Append the content to the message
    messageElement.appendChild(messageContentElement);
    
    // Append the message to the chat
    chatMessages.appendChild(messageElement);
    
    // Scroll to the bottom of the chat
    scrollToBottom();
}

/**
 * Helper function to scroll to the bottom of the chat
 */
function scrollToBottom() {
    const scrollableContent = document.querySelector('#scrollable-content');
    if (scrollableContent) {
        scrollableContent.scrollTop = scrollableContent.scrollHeight;
    }
}

/**
 * Clears all messages from the chat UI
 */
export function clearMessages() {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error('Chat messages container not found');
        return;
    }
    
    // Remove all child elements (messages) from the container
    while (chatMessages.firstChild) {
        chatMessages.removeChild(chatMessages.firstChild);
    }
}

// Export functions as a default object for easier importing
export default {
    addInterlocutorMessage,
    addRespondentMessage,
    clearMessages,
    processMarkdown,
    isMarkdownAvailable
};