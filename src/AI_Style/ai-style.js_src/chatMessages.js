/**
 * Functions to add messages to the chat UI
 */

/**
 * Comment tracking and management
 */
let currentComments = new Map(); // comment_ID -> comment object
let isInitialLoad = true;

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
 * Initialize comment monitoring
 */
export function initializeCommentMonitoring() {
  console.log('ChatMessages: Initializing comment monitoring...');
  console.log('ChatMessages: window.cacbotData available:', !!window.cacbotData);
  console.log('ChatMessages: subscribeToComments function available:',
    window.cacbotData && typeof window.cacbotData.subscribeToComments === 'function');
  
  // Subscribe to cacbot data changes
  if (window.cacbotData && typeof window.cacbotData.subscribeToComments === 'function') {
    window.cacbotData.subscribeToComments(handleCommentUpdate);
    console.log('ChatMessages: Successfully subscribed to comment updates');
  } else {
    console.warn('ChatMessages: cacbotData not available for comment monitoring');
    console.warn('ChatMessages: Available window properties:', Object.keys(window).filter(k => k.includes('cacbot')));
  }
  
  // Also listen for the cacbot-data-updated event (like CommentFormButtons.js does)
  setupCacbotDataListener();
}

/**
 * Handle comment updates from cacbotData
 * @param {Array} comments - Array of comment objects
 * @param {number} commentCount - Total comment count
 */
function handleCommentUpdate(comments, commentCount) {
  console.log('ChatMessages: Received comment update', {
    commentCount,
    commentsLength: comments ? comments.length : 0,
    isInitialLoad
  });

  if (!comments || !Array.isArray(comments)) {
    console.warn('ChatMessages: Invalid comments data received');
    return;
  }

  // Skip initial load to prevent duplicate rendering
  if (isInitialLoad) {
    updateCommentTracking(comments);
    isInitialLoad = false;
    return;
  }

  // Process comment changes
  processCommentChanges(comments);
  updateCommentTracking(comments);
}

/**
 * Process comment changes and update UI accordingly
 * @param {Array} newComments - Array of new comment objects
 */
function processCommentChanges(newComments) {
  const newCommentMap = new Map(newComments.map(comment => [comment.comment_ID, comment]));
  
  // Find new comments
  const addedComments = newComments.filter(comment => !currentComments.has(comment.comment_ID));
  
  // Find removed comments
  const removedCommentIds = [...currentComments.keys()].filter(id => !newCommentMap.has(id));
  
  // Process additions
  addedComments.forEach(comment => {
    addCommentToUI(comment);
  });
  
  // Process removals
  removedCommentIds.forEach(commentId => {
    removeCommentFromUI(commentId);
  });
  
  console.log('ChatMessages: Processed comment changes', {
    added: addedComments.length,
    removed: removedCommentIds.length
  });
}

/**
 * Add a single comment to the UI
 * @param {Object} comment - The comment object to add
 */
function addCommentToUI(comment) {
  const messageType = determineMessageType(comment);
  const messageContent = comment.comment_content;
  
  // Create message element with comment ID for tracking
  const messageId = `comment-${comment.comment_ID}`;
  
  if (messageType === 'interlocutor') {
    addInterlocutorMessage(messageContent, messageId);
  } else {
    addRespondentMessage(messageContent, messageId);
  }
  
  console.log('ChatMessages: Added comment to UI', {
    commentId: comment.comment_ID,
    messageType,
    messageId
  });
}

/**
 * Remove a comment from the UI
 * @param {string} commentId - The comment ID to remove
 */
function removeCommentFromUI(commentId) {
  const messageElement = document.getElementById(`comment-${commentId}`);
  if (messageElement) {
    messageElement.remove();
    console.log('ChatMessages: Removed comment from UI', { commentId });
  }
}

/**
 * Determine message type based on comment metadata
 * @param {Object} comment - The comment object
 * @returns {string} Either 'interlocutor' or 'respondent'
 */
function determineMessageType(comment) {
  // Logic to determine if comment is from user or AI
  // user_id === '0' or 0 means anonymous/AI (respondent)
  // user_id > 0 means logged in user (interlocutor)
  
  console.log('ChatMessages: Determining message type for comment', {
    commentId: comment.comment_ID,
    userId: comment.user_id,
    userIdType: typeof comment.user_id,
    authorName: comment.comment_author || 'N/A',
    authorEmail: comment.comment_author_email || 'N/A'
  });
  
  const userId = parseInt(comment.user_id) || 0;
  
  // DIAGNOSTIC: Check if this is the test respondent comment
  const isTestRespondent = comment.comment_author === 'Assistant' ||
                          comment.comment_author_email === 'assistant@cacbot.com';
  
  console.log('ChatMessages: User ID analysis', {
    commentId: comment.comment_ID,
    originalUserId: comment.user_id,
    parsedUserId: userId,
    isTestRespondent: isTestRespondent,
    shouldBeRespondent: userId === 0 || isTestRespondent
  });
  
  // For now, treat test assistant comments as respondent regardless of user_id
  const messageType = (userId === 0 || isTestRespondent) ? 'respondent' : 'interlocutor';
  
  console.log('ChatMessages: Final message type determination', {
    commentId: comment.comment_ID,
    userId: userId,
    messageType: messageType,
    reason: userId === 0 ? 'user_id is 0' : isTestRespondent ? 'test assistant comment' : 'logged in user'
  });
  
  return messageType;
}

/**
 * Update comment tracking data
 * @param {Array} comments - Array of comment objects
 */
function updateCommentTracking(comments) {
  currentComments = new Map(comments.map(comment => [comment.comment_ID, comment]));
}

/**
 * Adds a message from the interlocutor (user) to the chat
 * @param {string} message - The message content
 * @param {string|null} customId - Optional custom ID for the message
 */
export function addInterlocutorMessage(message, customId = null) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error('Chat messages container not found');
        return;
    }

    // Use custom ID if provided, otherwise generate timestamp-based ID
    const messageId = customId || 'message-' + Date.now();
    
    // Create the message element
    const messageElement = document.createElement('div');
    messageElement.className = 'message interlocutor-message';
    messageElement.id = messageId;
    
    // Add animation class for new messages (not initial load)
    if (!isInitialLoad && !customId) {
        messageElement.classList.add('message-fade-in');
    }
    
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
 * @param {string|null} customId - Optional custom ID for the message
 */
export function addRespondentMessage(message, customId = null) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error('Chat messages container not found');
        return;
    }

    // Use custom ID if provided, otherwise generate timestamp-based ID
    const messageId = customId || 'message-' + Date.now();
    
    // Create the message element
    const messageElement = document.createElement('div');
    messageElement.className = 'message respondent-message';
    messageElement.id = messageId;
    
    // Add animation class for new messages (not initial load)
    if (!isInitialLoad && !customId) {
        messageElement.classList.add('message-fade-in');
    }
    
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

/**
 * Sets up event listener for cacbot-data-updated events
 * This mirrors the approach used in CommentFormButtons.js
 */
function setupCacbotDataListener() {
  // Listen for the custom event dispatched by app.ts DataService
  document.addEventListener('cacbot-data-updated', (event) => {
    console.log('ChatMessages: Received cacbot-data-updated event:', event.detail);
    
    // Extract comments from the event data
    if (event.detail && event.detail.comments) {
      const comments = event.detail.comments;
      const commentCount = comments.length;
      
      console.log('ChatMessages: Processing cacbot-data-updated with', commentCount, 'comments');
      handleCommentUpdate(comments, commentCount);
    } else {
      console.warn('ChatMessages: cacbot-data-updated event received but no comments data found');
    }
  });
  
  console.log('ChatMessages: Successfully set up cacbot-data-updated event listener');
}

// Auto-initialize comment monitoring when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    initializeCommentMonitoring();
  });
} else {
  initializeCommentMonitoring();
}

// Export functions as a default object for easier importing
export default {
    addInterlocutorMessage,
    addRespondentMessage,
    clearMessages,
    processMarkdown,
    isMarkdownAvailable,
    initializeCommentMonitoring
};