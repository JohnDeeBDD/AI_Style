/**
 * Arrow Toggle Button Module
 *
 * This module handles the desktop-specific arrow toggle button functionality
 * for the sidebar. It creates and manages the arrow toggle button in the
 * WordPress admin bar for desktop views.
 *
 * @package AI_Style
 */

import { updateToggleButton, toggleSidebarVisibility } from './toggleSidebarVisible.js';

/**
 * State management for the arrow toggle button
 */
let arrowToggleState = {
  button: null,
  icon: null,
  label: null,
  isInitialized: false
};

/**
 * Initialize the desktop arrow toggle button
 * Creates the button element and sets up event listeners
 */
export function initializeArrowToggleButton() {
  console.log('Initializing desktop arrow toggle button');
  
  // Check if already initialized
  if (arrowToggleState.isInitialized) {
    console.log('Arrow toggle button already initialized');
    return;
  }
  
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
  
  // Store references for later use
  arrowToggleState.button = toggleButton;
  arrowToggleState.icon = icon;
  arrowToggleState.label = label;
  
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
  toggleLink.addEventListener('click', handleArrowToggleClick);
  
  // Mark as initialized
  arrowToggleState.isInitialized = true;
  
  // Set initial icon and text based on current sidebar state (after initialization)
  updateArrowToggleButton();
  
  console.log('Desktop arrow toggle button initialized successfully');
}

/**
 * Handle click events on the arrow toggle button
 * @param {Event} event - The click event
 */
function handleArrowToggleClick(event) {
  event.preventDefault();
  event.stopPropagation();
  
  console.log('Desktop arrow toggle button clicked');
  
  // Toggle the sidebar
  toggleSidebarVisibility();
  
  // Update the button to reflect new state
  updateArrowToggleButton();
}

/**
 * Update the arrow toggle button state using the common updateToggleButton function
 * This function uses the shared updateToggleButton from toggleSidebarVisible.js
 */
export function updateArrowToggleButton() {
  if (!arrowToggleState.isInitialized || !arrowToggleState.icon || !arrowToggleState.label) {
    console.warn('Arrow toggle button not initialized or elements missing');
    return;
  }
  
  // Use the common updateToggleButton function from toggleSidebarVisible.js
  updateToggleButton(arrowToggleState.icon, arrowToggleState.label);
  
  console.log('Desktop arrow toggle button state updated');
}

/**
 * Get the current arrow toggle button element
 * @returns {HTMLElement|null} The toggle button element or null if not initialized
 */
export function getArrowToggleButton() {
  return arrowToggleState.button;
}

/**
 * Check if the arrow toggle button is initialized
 * @returns {boolean} True if initialized, false otherwise
 */
export function isArrowToggleInitialized() {
  return arrowToggleState.isInitialized;
}

/**
 * Cleanup the arrow toggle button
 * Removes the button from the DOM and resets state
 */
export function cleanupArrowToggleButton() {
  if (arrowToggleState.button && arrowToggleState.button.parentNode) {
    arrowToggleState.button.parentNode.removeChild(arrowToggleState.button);
  }
  
  // Reset state
  arrowToggleState = {
    button: null,
    icon: null,
    label: null,
    isInitialized: false
  };
  
  console.log('Desktop arrow toggle button cleaned up');
}

// Export default object for easy importing
export default {
  initializeArrowToggleButton,
  updateArrowToggleButton,
  getArrowToggleButton,
  isArrowToggleInitialized,
  cleanupArrowToggleButton
};