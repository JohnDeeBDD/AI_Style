/* global jQuery */
/**
 * Admin Bar Customization
 *
 * This file contains JavaScript to customize the WordPress admin bar "New" button behavior.
 * It prevents the hover behavior (no expanded menu) and overrides the click behavior to
 * archive conversations and reload the page.
 *
 * @package AI_Style
 */

import cacbotData from './cacbotData';
import { clearMessages } from './chatMessages';
import fetchCacbotLinkAPI from './fetchCacbotLinkAPI';

/**
 * Prevents the dropdown menu from appearing when hovering over the "New" button
 *
 * @param {HTMLElement} newButton - The "New" button element
 */
export function overrideHoverBehavior(newButton) {
    const newButtonClone = cloneButton(newButton);
    applyNoHoverStyle();
    return newButtonClone;
}

function cloneButton(button) {
    const buttonClone = button.cloneNode(true);
    button.parentNode.replaceChild(buttonClone, button);
    return buttonClone;
}

function applyNoHoverStyle() {
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
}

/**
 * Overrides the click behavior of the "New" button to redirect to the current URL with model=archive parameter
 *
 * @param {HTMLElement} newButton - The "New" button element (or its clone)
 */
export function overrideClickBehavior(newButton) {
    const newLink = getNewButtonLink(newButton);
    if (!newLink) return;

    newLink.addEventListener('click', handleNewButtonClick);
}

function getNewButtonLink(newButton) {
    const newLink = newButton.querySelector('a.ab-item');
    if (!newLink) {
        console.warn('Admin bar "New" button link not found');
    }
    return newLink;
}

function handleNewButtonClick(event) {
    event.preventDefault();
    event.stopPropagation();
    console.log('New button clicked');
    clearMessages();

    const postId = cacbotData.getPostId();
    const nonce = AIStyleSettings.nonce;
    console.log("nonce:", nonce);

    if (postId && nonce) {
        archiveConversation(postId, nonce);
    } else {
        console.warn('Cannot archive conversation: Missing post_id or nonce');
    }
}

function archiveConversation(postId, nonce) {
    const formData = new FormData();
    formData.append('post_id', postId);
    formData.append('nonce', nonce);

    const endpoint = "/wp-json/cacbot/v1/unlink-conversation";
    fetchCacbotLinkAPI(postId, formData, endpoint)
        .then(data => {
            console.log('Archive conversation response:', data);
            window.location.reload();
        })
        .catch(error => {
            console.error('Error archiving conversation:', error);
        });
}

/**
 * Customizes the WordPress admin bar "New" button behavior
 * - Prevents hover behavior (no expanded menu)
 * - Overrides click behavior to archive conversations and reload the page
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
  
  // Override click behavior - archive conversations and reload page
  // Use the cloned button that's actually in the DOM
  overrideClickBehavior(newButtonClone);
}