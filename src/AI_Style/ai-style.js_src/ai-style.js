/* 
To compile use this command: 
npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
*/
/* global cacbot_data */

import commentBoxStyle from "./CommentFormStyle";
import justifyOneLiner from "./justifyOneLiner";
import chatMessages from "./chatMessages";
import adminBarCustomization from "./adminBarCustomization";
import cacbotData from "./cacbotData";
import fetchPost, { updatePostUI } from "./fetchPost";
import sidebarClick, { initSidebarClickListeners } from "./sidebarClick";


// Make the chat message functions available globally
window.addInterlocutorMessage = chatMessages.addInterlocutorMessage;
window.addRespondentMessage = chatMessages.addRespondentMessage;
window.fetchPost = fetchPost;
window.updatePostUI = updatePostUI;


document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  justifyOneLiner();
  
  console.log("PHP cacbot_data:");
  console.log(window.cacbot_data);
  // Initialize cacbotData with the global cacbot_data object
  try {
    cacbotData.initialize(window.cacbot_data || {});
    console.log(cacbotData.getAll());
  } catch (error) {
    console.error("Failed to initialize cacbotData:", error);
  }
  
  // Log that the chat message functions are available
  console.log('Chat message functions are available globally:');
  console.log('- addInterlocutorMessage(message)');
  console.log('- addRespondentMessage(message)');
  adminBarCustomization();
  
  // Set up event listeners for post navigation
  setupPostNavigation();
  initSidebarClickListeners();
});

/**
 * Sets up event listeners for post navigation without page refresh
 */
function setupPostNavigation() {
  // Listen for clicks on post links
  document.addEventListener('click', function(event) {
    // Check if the clicked element is a link to a post
    const link = event.target.closest('a');
    if (!link) return;
    
    // Check if this is an internal post link
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('http') || href.indexOf('wp-admin') !== -1) {
      return; // Not a post link or external link
    }
    
    // Try to extract post ID from the URL
    const postIdMatch = href.match(/\/(\d+)\/?$/);
    if (!postIdMatch) return; // Not a post URL with ID
    
    const postId = parseInt(postIdMatch[1], 10);
    if (isNaN(postId)) return;
    
    // Prevent default link behavior
    event.preventDefault();
    
    // Show loading indicator
    const contentArea = document.querySelector('.entry-content');
    if (contentArea) {
      contentArea.innerHTML = '<div class="loading">Loading post content...</div>';
    }
    
    // Fetch the post data
    fetchPost(postId)
      .then(postData => {
        // Update the UI with the fetched data
        updatePostUI(postData);
        
        // Scroll to top
        window.scrollTo(0, 0);
      })
      .catch(error => {
        console.error('Error navigating to post:', error);
        if (contentArea) {
          contentArea.innerHTML = `<div class="error">Error loading post: ${error.message}</div>`;
        }
      });
  });
  
  // Handle browser back/forward navigation
  window.addEventListener('popstate', function(event) {
    if (event.state && event.state.postId) {
      fetchPost(event.state.postId)
        .then(updatePostUI)
        .catch(error => {
          console.error('Error handling history navigation:', error);
        });
    }
  });
}