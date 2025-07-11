/**
 * Sets focus to the last comment on the page
 */
export default function focusLastComment() {
  // WordPress comment selectors + chat message selectors from chatMessages.js
  const commentSelectors = [
    // Traditional WordPress comment selectors
    '.comment',
    '.comment-body',
    '.commentlist li',
    '#comments .comment',
    '.wp-block-comment',
    '[id^="comment-"]',
    // Chat message selectors from chatMessages.js
    '.message',
    '.interlocutor-message',
    '.respondent-message',
    '.message-content'
  ];
  
  let lastComment = null;
  
  // Try each selector to find comments
  for (const selector of commentSelectors) {
    const comments = document.querySelectorAll(selector);
    if (comments.length > 0) {
      lastComment = comments[comments.length - 1];
      break;
    }
  }
  
  // If we found a comment, set focus to it
  if (lastComment) {
    // Make the element focusable if it isn't already
    if (!lastComment.hasAttribute('tabindex')) {
      lastComment.setAttribute('tabindex', '-1');
    }
    
    // Set focus and scroll to bottom of content
    lastComment.focus();
    
    // Scroll to the bottom of the scrollable content container
    const scrollableContent = document.getElementById('scrollable-content');
    if (scrollableContent) {
      scrollableContent.scrollTo({
        top: scrollableContent.scrollHeight,
        behavior: 'smooth'
      });
    }
    
    console.log('Focus set to last comment:', lastComment);
  } else {
    console.log('No comments found on the page');
  }
}