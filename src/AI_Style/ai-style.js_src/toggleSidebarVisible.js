/**
 * Toggle Sidebar Visibility
 *
 * This module provides functionality to toggle the sidebar visibility with smooth animations.
 * It includes state management, zoom level detection, and smooth slide animations.
 *
 * @package AI_Style
 */

/**
 * State management for sidebar visibility
 */
let sidebarState = {
  isVisible: true,
  isAnimating: false,
  originalWidth: '377px',
  zoomLevel: 100
};

/**
 * Initialize the toggle sidebar functionality
 * Sets up initial state based on zoom level detection
 */
export function initToggleSidebar() {
  console.log('Initializing toggle sidebar functionality');
  
  // Detect zoom level and set initial sidebar state
  detectZoomLevel();
  setInitialSidebarState();
  
  // Add CSS for animations if not already present
  addToggleAnimationCSS();
  
  console.log('Toggle sidebar initialized with state:', sidebarState);
}

/**
 * Detect the current zoom level of the browser
 * Uses a combination of devicePixelRatio and window dimensions
 */
function detectZoomLevel() {
  // Method 1: Using devicePixelRatio (most reliable)
  const devicePixelRatio = window.devicePixelRatio || 1;
  
  // Method 2: Using screen dimensions vs window dimensions
  const screenWidth = screen.width;
  const windowWidth = window.outerWidth;
  const ratio = screenWidth / windowWidth;
  
  // Calculate zoom level (approximate)
  let zoomLevel = Math.round(devicePixelRatio * 100);
  
  // Fallback calculation if devicePixelRatio seems off
  if (zoomLevel === 100 && ratio > 1) {
    zoomLevel = Math.round(ratio * 100);
  }
  
  sidebarState.zoomLevel = zoomLevel;
  console.log('Detected zoom level:', zoomLevel + '%');
  
  return zoomLevel;
}

/**
 * Set initial sidebar state based on zoom level
 * Hides sidebar by default at 175%+ zoom
 */
function setInitialSidebarState() {
  const sidebar = document.getElementById('chat-sidebar');
  if (!sidebar) {
    console.warn('Sidebar element not found');
    return;
  }
  
  // Store original width
  const computedStyle = window.getComputedStyle(sidebar);
  sidebarState.originalWidth = computedStyle.width;
  
  // Hide sidebar by default at 175%+ zoom
  if (sidebarState.zoomLevel >= 175) {
    sidebarState.isVisible = false;
    hideSidebarImmediate(sidebar);
    console.log('Sidebar hidden due to high zoom level:', sidebarState.zoomLevel + '%');
  } else {
    sidebarState.isVisible = true;
    showSidebarImmediate(sidebar);
    console.log('Sidebar visible at zoom level:', sidebarState.zoomLevel + '%');
  }
}

/**
 * Toggle sidebar visibility with smooth animation
 * Prevents rapid clicking during animation
 */
export function toggleSidebarVisibility() {
  if (sidebarState.isAnimating) {
    console.log('Sidebar animation in progress, ignoring toggle request');
    return;
  }
  
  const sidebar = document.getElementById('chat-sidebar');
  if (!sidebar) {
    console.warn('Sidebar element not found');
    return;
  }
  
  sidebarState.isAnimating = true;
  
  if (sidebarState.isVisible) {
    hideSidebarAnimated(sidebar);
  } else {
    showSidebarAnimated(sidebar);
  }
  
  // Update state
  sidebarState.isVisible = !sidebarState.isVisible;
  
  console.log('Toggled sidebar visibility. New state:', sidebarState.isVisible ? 'visible' : 'hidden');
}

/**
 * Hide sidebar with smooth slide-out animation
 */
function hideSidebarAnimated(sidebar) {
  // Add transitioning class for animation
  sidebar.classList.add('sidebar-transitioning');
  
  // Start the animation
  sidebar.style.width = '0';
  sidebar.style.minWidth = '0';
  sidebar.style.overflow = 'hidden';
  sidebar.style.paddingLeft = '0';
  sidebar.style.paddingRight = '0';
  
  // Update footer position to extend to left edge when sidebar is hidden
  updateFooterPosition(true);
  
  // Remove animation class and reset state after animation completes
  setTimeout(() => {
    sidebar.classList.remove('sidebar-transitioning');
    sidebar.classList.add('sidebar-hidden');
    sidebarState.isAnimating = false;
  }, 300);
}

/**
 * Show sidebar with smooth slide-in animation
 */
function showSidebarAnimated(sidebar) {
  // Remove hidden class and add transitioning class
  sidebar.classList.remove('sidebar-hidden');
  sidebar.classList.add('sidebar-transitioning');
  
  // Start the animation
  sidebar.style.width = sidebarState.originalWidth;
  sidebar.style.minWidth = sidebarState.originalWidth;
  sidebar.style.overflow = 'hidden'; // Keep hidden during animation
  sidebar.style.paddingLeft = '16px';
  sidebar.style.paddingRight = '16px';
  
  // Update footer position to align with sidebar when sidebar is shown
  updateFooterPosition(false);
  
  // Remove animation class and restore overflow after animation completes
  setTimeout(() => {
    sidebar.classList.remove('sidebar-transitioning');
    sidebar.style.overflow = 'auto'; // Restore scrolling
    sidebarState.isAnimating = false;
  }, 300);
}

/**
 * Hide sidebar immediately without animation
 */
function hideSidebarImmediate(sidebar) {
  sidebar.style.width = '0';
  sidebar.style.minWidth = '0';
  sidebar.style.overflow = 'hidden';
  sidebar.style.paddingLeft = '0';
  sidebar.style.paddingRight = '0';
  sidebar.classList.add('sidebar-hidden');
  
  // Update footer position immediately
  updateFooterPosition(true);
}

/**
 * Show sidebar immediately without animation
 */
function showSidebarImmediate(sidebar) {
  sidebar.classList.remove('sidebar-hidden');
  sidebar.style.width = sidebarState.originalWidth;
  sidebar.style.minWidth = sidebarState.originalWidth;
  sidebar.style.overflow = 'auto';
  sidebar.style.paddingLeft = '16px';
  sidebar.style.paddingRight = '16px';
  
  // Update footer position immediately
  updateFooterPosition(false);
}

/**
 * Update footer position based on sidebar visibility
 * @param {boolean} sidebarHidden - Whether the sidebar is hidden
 */
function updateFooterPosition(sidebarHidden) {
  const footer = document.querySelector('.site-footer');
  if (!footer) {
    console.warn('Footer element not found');
    return;
  }
  
  if (sidebarHidden) {
    // When sidebar is hidden, footer should extend to left edge
    footer.style.left = '0px';
    console.log('Footer position updated: left = 0px (sidebar hidden)');
  } else {
    // When sidebar is visible, footer should start at sidebar width
    footer.style.left = '377px';
    console.log('Footer position updated: left = 377px (sidebar visible)');
  }
}

/**
 * Add CSS for smooth animations
 */
function addToggleAnimationCSS() {
  // Check if styles already exist
  if (document.getElementById('sidebar-toggle-styles')) {
    return;
  }
  
  const style = document.createElement('style');
  style.id = 'sidebar-toggle-styles';
  style.textContent = `
    /* Sidebar toggle animation styles */
    #chat-sidebar.sidebar-transitioning {
      transition: width 300ms ease-in-out, 
                  min-width 300ms ease-in-out,
                  padding-left 300ms ease-in-out,
                  padding-right 300ms ease-in-out;
    }
    
    #chat-sidebar.sidebar-hidden {
      width: 0 !important;
      min-width: 0 !important;
      padding-left: 0 !important;
      padding-right: 0 !important;
      overflow: hidden !important;
      border-right: none !important;
    }
    
    /* Ensure main content expands when sidebar is hidden */
    #chat-sidebar.sidebar-hidden + #chat-main {
      width: 100% !important;
      max-width: 100% !important;
    }
    
    /* Add smooth transition for footer position changes */
    .site-footer {
      transition: left 300ms ease-in-out;
    }
    
    /* Admin bar toggle button styles */
    #wp-admin-bar-sidebar-toggle .ab-item {
      display: flex !important;
      align-items: center;
      gap: 4px;
    }
    
    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {
      font-size: 24px !important;
      width: 24px !important;
      height: 24px !important;
      line-height: 1 !important;
      vertical-align: middle !important;
      margin: 0 !important;
      padding: 0 !important;
      display: inline-block !important;
    }
    
    #wp-admin-bar-sidebar-toggle .ab-item .ab-label {
      font-size: 13px;
    }
    
    /* Hide label text at high zoom levels (175%+) following WordPress patterns */
    @media screen and (min-resolution: 1.75dppx) {
      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {
        display: none;
      }
    }
    
    /* Alternative media query for browsers that don't support dppx */
    @media screen and (-webkit-min-device-pixel-ratio: 1.75) {
      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {
        display: none;
      }
    }
  `;
  
  document.head.appendChild(style);
  console.log('Added sidebar toggle animation CSS');
}

/**
 * Get current sidebar visibility state
 */
export function isSidebarVisible() {
  return sidebarState.isVisible;
}

/**
 * Get current sidebar state object
 */
export function getSidebarState() {
  return { ...sidebarState };
}

/**
 * Force sidebar to visible state
 */
export function showSidebar() {
  if (sidebarState.isVisible) return;
  
  const sidebar = document.getElementById('chat-sidebar');
  if (sidebar) {
    showSidebarAnimated(sidebar);
    sidebarState.isVisible = true;
  }
}

/**
 * Force sidebar to hidden state
 */
export function hideSidebar() {
  if (!sidebarState.isVisible) return;
  
  const sidebar = document.getElementById('chat-sidebar');
  if (sidebar) {
    hideSidebarAnimated(sidebar);
    sidebarState.isVisible = false;
  }
}

// Export default function for easy importing
export default {
  initToggleSidebar,
  toggleSidebarVisibility,
  isSidebarVisible,
  getSidebarState,
  showSidebar,
  hideSidebar
};