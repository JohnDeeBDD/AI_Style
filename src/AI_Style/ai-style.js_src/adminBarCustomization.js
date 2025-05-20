/**
 * Admin Bar Customization
 *
 * This file contains JavaScript to override the default behavior of the WordPress admin bar's "New" button.
 * It prevents the hover behavior (no expanded menu) and overrides the click behavior to redirect with model=archive
 * and nonce parameters.
 *
 * @package AI_Style
 */

import cacbotData from './cacbotData';
import { clearMessages } from './chatMessages';
import fetchCacbotLinkAPI from './fetchCacbotLinkAPI';

/**
 * Customizes the WordPress admin bar "New" button behavior
 * - Prevents hover behavior (no expanded menu)
 * - Overrides click behavior to redirect with model=archive and nonce parameters
 */
export default function adminBarCustomization() {
  // Only run on the frontend, not in the WordPress admin area
  if (document.body.classList.contains('wp-admin')) {
    return;
  }

  console.log(AIStyleSettings);
  console.log('Customizing admin bar "New" button behavior');
  
  // Get the "New" button in the admin bar
  const newButton = document.getElementById('wp-admin-bar-new-content');
  
  if (!newButton) {
    console.warn('Admin bar "New" button not found');
    return;
  }
  
  // Override hover behavior - prevent the dropdown menu from appearing
  // This returns the cloned button that replaced the original
  const newButtonClone = overrideHoverBehavior(newButton);
  
  // Override click behavior - just log a message
  // Use the cloned button that's actually in the DOM
  overrideClickBehavior(newButtonClone);
}

/**
 * Prevents the dropdown menu from appearing when hovering over the "New" button
 * 
 * @param {HTMLElement} newButton - The "New" button element
 */
function overrideHoverBehavior(newButton) {
  // Remove any existing hover event listeners by cloning and replacing the element
  const newButtonClone = newButton.cloneNode(true);
  newButton.parentNode.replaceChild(newButtonClone, newButton);
  
  // Add CSS to prevent the dropdown from showing
  const style = document.createElement('style');
  style.textContent = `
    #wp-admin-bar-new-content .ab-sub-wrapper {
      display: none !important;
    }
    #wp-admin-bar-new-content:hover .ab-sub-wrapper {
      display: none !important;
    }
  `;
  document.head.appendChild(style);
  
  // Return the cloned button so it can be used for click behavior
  return newButtonClone;
}

/**
 * Overrides the click behavior of the "New" button to redirect to the current URL with model=archive parameter
 *
 * @param {HTMLElement} newButton - The "New" button element (or its clone)
 */
function overrideClickBehavior(newButton) {
  // Get the main link in the "New" button
  const newLink = newButton.querySelector('a.ab-item');
  
  if (!newLink) {
    console.warn('Admin bar "New" button link not found');
    return;
  }
  
  // Override the click behavior
  newLink.addEventListener('click', function(event) {
    // Prevent the default action (creating a new post)
    event.preventDefault();
    event.stopPropagation(); // Also stop event propagation to parent elements
    
    // Log a message to the console
    console.log('New button clicked');
    clearMessages();
    
    // Make API call to archive the conversation
    const postId = cacbotData.getPostId();
    const nonce = AIStyleSettings.nonce;
    console.log("nonce:", nonce);
    
    if (postId && nonce) {
      // Prepare the request data
      const formData = new FormData();
      formData.append('post_id', postId);
      formData.append('nonce', nonce);

      const endpoint = "/wp-json/cacbot/v1/unlink-conversation";
      
      // Use the abstracted function to make the API call
      fetchCacbotLinkAPI(postId, formData, endpoint)
        .then(data => {
          console.log('Archive conversation response:', data);
          // Reload the page to show the changes
          window.location.reload();
        })
        .catch(error => {
          console.error('Error archiving conversation:', error);
        });
    } else {
      console.warn('Cannot archive conversation: Missing post_id or nonce');
    }

  });
}