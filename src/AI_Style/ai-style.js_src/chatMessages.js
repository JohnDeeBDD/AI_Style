/**
 * Functions to add messages to the chat UI
 */

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
    messageContentElement.innerHTML = message;
    
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
    messageContentElement.innerHTML = message;
    
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
    const scrollableContent = document.querySelector('.scrollable-content');
    if (scrollableContent) {
        scrollableContent.scrollTop = scrollableContent.scrollHeight;
    }
}

// Export both functions as a default object for easier importing
export default {
    addInterlocutorMessage,
    addRespondentMessage
};