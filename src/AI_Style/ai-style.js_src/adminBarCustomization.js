/**
 * Admin Bar Customization
 * 
 * This file contains JavaScript to override the default behavior of the WordPress admin bar's "New" button.
 * It prevents the hover behavior (no expanded menu) and overrides the click behavior to just log a message.
 * 
 * @package AI_Style
 */

/**
 * Customizes the WordPress admin bar "New" button behavior
 * - Prevents hover behavior (no expanded menu)
 * - Overrides click behavior to just log a message
 */
export default function adminBarCustomization() {
  // Only run on the frontend, not in the WordPress admin area
  if (document.body.classList.contains('wp-admin')) {
    return;
  }

  console.log('Customizing admin bar "New" button behavior');
  
  // Get the "New" button in the admin bar
  const newButton = document.getElementById('wp-admin-bar-new-content');
  
  if (!newButton) {
    console.warn('Admin bar "New" button not found');
    return;
  }
  
  // Override hover behavior - prevent the dropdown menu from appearing
  overrideHoverBehavior(newButton);
  
  // Override click behavior - just log a message
  overrideClickBehavior(newButton);
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
}

/**
 * Overrides the click behavior of the "New" button to just log a message
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
    
    // Log a message to the console
    console.log('New button clicked');
  });
}