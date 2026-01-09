/* global jQuery */
/**
 * Mobile Hamburger Button
 *
 * This file contains JavaScript for the mobile hamburger button that toggles the sidebar.
 * The hamburger button is visible on mobile devices only and provides an alternative
 * to the desktop arrow/text button for sidebar control.
 *
 * @package AI_Style
 */

import { toggleSidebarVisibility } from './toggleSidebarVisible';

/**
 * Add mobile hamburger icon to the WordPress admin bar
 * Positions it as the first item in the admin bar for mobile devices
 */
export function addMobileHamburgerIcon() {
  const adminBar = document.getElementById('wp-admin-bar-root-default');
  if (!adminBar) {
    console.warn('Admin bar root element not found');
    return;
  }
  
  // Create the mobile hamburger button
  const hamburgerButton = document.createElement('li');
  hamburgerButton.id = 'wp-admin-bar-mobile-hamburger';
  hamburgerButton.className = 'menupop';
  
  // Create the link element
  const hamburgerLink = document.createElement('a');
  hamburgerLink.className = 'ab-item';
  hamburgerLink.href = '#';
  hamburgerLink.setAttribute('aria-label', 'Toggle Sidebar');
  
  // Create icon element - using WordPress hamburger icon (dashicons-menu)
  const icon = document.createElement('span');
  icon.className = 'dashicons dashicons-menu';
  icon.setAttribute('title', 'Toggle Sidebar');
  
  // Assemble the button
  hamburgerLink.appendChild(icon);
  hamburgerButton.appendChild(hamburgerLink);
  
  // Position the button as the first item in the admin bar
  const firstChild = adminBar.firstChild;
  if (firstChild) {
    adminBar.insertBefore(hamburgerButton, firstChild);
  } else {
    adminBar.appendChild(hamburgerButton);
  }
  
  // Add click event listener
  hamburgerLink.addEventListener('click', function(event) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('Mobile hamburger icon clicked');
    
    // Toggle the sidebar
    toggleSidebarVisibility();
  });
  
  console.log('Added mobile hamburger icon to admin bar');
}