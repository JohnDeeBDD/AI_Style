/* global jQuery */
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
import { toggleSidebarVisibility, isSidebarVisible } from './toggleSidebarVisible';

/**
 * Prevents the dropdown menu from appearing when hovering over the "New" button
 *
 * @param {HTMLElement} newButton - The "New" button element
 */
export function overrideHoverBehavior(newButton) {
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
export function overrideClickBehavior(newButton) {
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

/**
 * Add sidebar toggle button to the WordPress admin bar
 * Positions it to the right of the "New" button
 */
export function addSidebarToggleButton() {
  const adminBar = document.getElementById('wp-admin-bar-root-default');
  if (!adminBar) {
    console.warn('Admin bar root element not found');
    return;
  }
  
  // Find the "New" button to position our toggle after it
  const newButton = document.getElementById('wp-admin-bar-new-content');
  if (!newButton) {
    console.warn('Cannot position sidebar toggle: New button not found');
    return;
  }
  
  // Create the sidebar toggle button
  const toggleButton = document.createElement('li');
  toggleButton.id = 'wp-admin-bar-sidebar-toggle';
  toggleButton.className = 'menupop';
  
  // Create the link element
  const toggleLink = document.createElement('a');
  toggleLink.className = 'ab-item';
  toggleLink.href = '#';
  toggleLink.setAttribute('aria-label', 'Toggle Sidebar');
  
  // Create icon element
  const icon = document.createElement('span');
  icon.className = 'dashicons';
  
  // Create label element
  const label = document.createElement('span');
  label.className = 'ab-label';
  
  // Set initial icon and text based on current sidebar state
  updateToggleButton(icon, label);
  
  // Assemble the button
  toggleLink.appendChild(icon);
  toggleLink.appendChild(label);
  toggleButton.appendChild(toggleLink);
  
  // Position the button after the "New" button
  const nextSibling = newButton.nextSibling;
  if (nextSibling) {
    adminBar.insertBefore(toggleButton, nextSibling);
  } else {
    adminBar.appendChild(toggleButton);
  }
  
  // Add click event listener
  toggleLink.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('Sidebar toggle button clicked');
    
    // Toggle the sidebar
    toggleSidebarVisibility();
    
    // Update the button to reflect new state
    updateToggleButton(icon, label);
  });
  
  console.log('Added sidebar toggle button to admin bar');
}

/**
 * Update the toggle button icon and text based on sidebar visibility state
 * Uses dashicons "arrow-left" for close and "arrow-right" for open
 * Updates button text to "Close Sidebar" or "Open Sidebar"
 */
export function updateToggleButton(iconElement, labelElement) {
  if (!iconElement || !labelElement) return;
  
  // Remove existing arrow classes
  iconElement.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right');
  
  // Add appropriate class and text based on sidebar state
  if (isSidebarVisible()) {
    iconElement.classList.add('dashicons-arrow-left');
    iconElement.setAttribute('title', 'Close Sidebar');
    labelElement.textContent = 'Close Sidebar';
  } else {
    iconElement.classList.add('dashicons-arrow-right');
    iconElement.setAttribute('title', 'Open Sidebar');
    labelElement.textContent = 'Open Sidebar';
  }
}

/**
 * Initialize zoom detection for admin bar responsiveness
 * Detects browser zoom level and applies appropriate CSS classes to body
 * This allows CSS to hide/show admin bar elements based on zoom level
 */
export function initializeZoomDetection() {
  console.log('Initializing zoom detection for admin bar');
  
  // Function to detect and apply zoom classes
  function detectAndApplyZoom() {
    // Get the current zoom level by comparing window dimensions
    const devicePixelRatio = window.devicePixelRatio || 1;
    const zoomLevel = Math.round(devicePixelRatio * 100) / 100;
    
    // Alternative method: detect zoom by comparing screen width to window width
    const screenWidth = screen.width;
    const windowWidth = window.outerWidth;
    const calculatedZoom = Math.round((screenWidth / windowWidth) * 100) / 100;
    
    // Use the more reliable method (devicePixelRatio for most cases)
    let detectedZoom = devicePixelRatio;
    
    // For browsers where devicePixelRatio doesn't change with zoom, use alternative method
    if (Math.abs(calculatedZoom - 1) > 0.1) {
      detectedZoom = calculatedZoom;
    }
    
    console.log('Detected zoom level:', detectedZoom);
    
    // Remove existing zoom classes
    document.body.classList.remove('zoom-200-plus', 'zoom-250-plus');
    
    // Apply appropriate zoom classes
    if (detectedZoom >= 2.5) {
      document.body.classList.add('zoom-250-plus');
      console.log('Applied zoom-250-plus class');
    }
    
  }
  
  // Initial detection
  detectAndApplyZoom();
  
  // Listen for resize events (which can indicate zoom changes)
  window.addEventListener('resize', detectAndApplyZoom);
  
  // Listen for zoom events (if supported)
  if ('onzoom' in window) {
    window.addEventListener('zoom', detectAndApplyZoom);

  }
  
  // Periodically check for zoom changes (fallback method)
  setInterval(detectAndApplyZoom, 1000);
}

/**
 * Customizes the WordPress admin bar "New" button behavior and adds sidebar toggle
 * - Prevents hover behavior (no expanded menu)
 * - Overrides click behavior to redirect with model=archive and nonce parameters
 * - Adds sidebar toggle button to admin bar
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
  
  // Add sidebar toggle button to admin bar
  addSidebarToggleButton();
  
  // Initialize zoom detection for admin bar responsiveness
  initializeZoomDetection();
}