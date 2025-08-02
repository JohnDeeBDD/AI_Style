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
  zoomLevel: 100,
  isHighZoomOrMobilePortrait: false,
  resizeTimeout: null
};

/**
 * LocalStorage key for persisting sidebar state
 */
const SIDEBAR_STORAGE_KEY = 'ai_style_sidebar_visible';

/**
 * Save sidebar state to localStorage
 */
function saveSidebarState() {
  try {
    localStorage.setItem(SIDEBAR_STORAGE_KEY, sidebarState.isVisible.toString());
    console.log('Sidebar state saved to localStorage:', sidebarState.isVisible);
  } catch (error) {
    console.warn('Failed to save sidebar state to localStorage:', error);
  }
}

/**
 * Load sidebar state from localStorage
 * @returns {boolean|null} The saved state, or null if not found
 */
function loadSidebarState() {
  try {
    const savedState = localStorage.getItem(SIDEBAR_STORAGE_KEY);
    if (savedState !== null) {
      const isVisible = savedState === 'true';
      console.log('Sidebar state loaded from localStorage:', isVisible);
      return isVisible;
    }
  } catch (error) {
    console.warn('Failed to load sidebar state from localStorage:', error);
  }
  return null;
}

/**
 * Initialize the toggle sidebar functionality
 * Sets up initial state based on zoom level detection and localStorage
 */
export function initToggleSidebar() {
  console.log('Initializing toggle sidebar functionality');
  
  // Detect zoom level first
  detectZoomLevel();
  
  // Detect if we're in high zoom or mobile portrait mode
  sidebarState.isHighZoomOrMobilePortrait = isHighZoomOrMobilePortrait();
  console.log('High zoom or mobile portrait mode:', sidebarState.isHighZoomOrMobilePortrait);
  
  // Load saved state from localStorage
  const savedState = loadSidebarState();
  
  // Set initial sidebar state based on saved state or zoom level
  setInitialSidebarState(savedState);
  
  // Add CSS for animations if not already present
  addToggleAnimationCSS();
  
  // Update element visibility based on current mode
  updateElementVisibility();
  
  // Add resize listener to handle dynamic changes
  window.addEventListener('resize', handleWindowResize);
  
  console.log('Toggle sidebar initialized with state:', sidebarState);
}

/**
 * Handle window resize events to update responsive behavior
 */
function handleWindowResize() {
  // Debounce resize events
  clearTimeout(sidebarState.resizeTimeout);
  sidebarState.resizeTimeout = setTimeout(() => {
    // Re-detect zoom level and mobile portrait mode
    detectZoomLevel();
    const wasHighZoomOrMobilePortrait = sidebarState.isHighZoomOrMobilePortrait;
    sidebarState.isHighZoomOrMobilePortrait = isHighZoomOrMobilePortrait();
    
    // If mode changed, update sidebar and element visibility
    if (wasHighZoomOrMobilePortrait !== sidebarState.isHighZoomOrMobilePortrait) {
      console.log('Mode changed on resize. High zoom or mobile portrait:', sidebarState.isHighZoomOrMobilePortrait);
      
      const sidebar = document.getElementById('chat-sidebar');
      if (sidebar && sidebarState.isVisible) {
        // Update sidebar width if it's currently visible
        const sidebarWidth = getSidebarWidth();
        sidebar.style.width = sidebarWidth;
        sidebar.style.minWidth = sidebarWidth;
      }
      
      // Update element visibility
      updateElementVisibility();
    }
  }, 250);
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
 * Detect if we're in mobile portrait mode
 * @returns {boolean}
 */
function isMobilePortrait() {
  // Check if viewport width is typical mobile portrait (less than 480px) and height > width
  const viewportWidth = window.innerWidth;
  const viewportHeight = window.innerHeight;
  
  return viewportWidth <= 480 && viewportHeight > viewportWidth;
}

/**
 * Detect if we're in high zoom mode (250%+) or mobile portrait
 * @returns {boolean}
 */
function isHighZoomOrMobilePortrait() {
  const zoomLevel = sidebarState.zoomLevel;
  const mobilePortrait = isMobilePortrait();
  
  console.log('Zoom level:', zoomLevel + '%', 'Mobile portrait:', mobilePortrait);
  
  return zoomLevel >= 250 || mobilePortrait;
}

/**
 * Get the appropriate sidebar width based on current mode
 * @returns {string}
 */
function getSidebarWidth() {
  if (sidebarState.isHighZoomOrMobilePortrait) {
    return '85%';
  }
  return sidebarState.originalWidth;
}

/**
 * Set initial sidebar state based on saved state or zoom level
 * @param {boolean|null} savedState - The saved state from localStorage, or null if not found
 */
function setInitialSidebarState(savedState) {
  const sidebar = document.getElementById('chat-sidebar');
  if (!sidebar) {
    console.warn('Sidebar element not found');
    return;
  }
  
  // Store original width
  const computedStyle = window.getComputedStyle(sidebar);
  sidebarState.originalWidth = computedStyle.width;
  
  // Determine initial visibility state
  let shouldBeVisible;
  
  if (savedState !== null) {
    // Use saved state if available
    shouldBeVisible = savedState;
    console.log('Using saved sidebar state:', shouldBeVisible);
  } else {
    // Fall back to zoom level logic if no saved state
    shouldBeVisible = sidebarState.zoomLevel < 175;
    console.log('Using zoom level logic for sidebar state:', shouldBeVisible, 'at zoom level:', sidebarState.zoomLevel + '%');
  }
  
  // Apply the determined state
  sidebarState.isVisible = shouldBeVisible;
  
  if (shouldBeVisible) {
    showSidebarImmediate(sidebar);
  } else {
    hideSidebarImmediate(sidebar);
  }
  
  // Save the initial state to localStorage
  saveSidebarState();
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
  
  // Save the new state to localStorage
  saveSidebarState();
  
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
  
  // Update element visibility based on current mode
  updateElementVisibility();
  
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
  
  // Get appropriate width based on current mode
  const sidebarWidth = getSidebarWidth();
  
  // Start the animation
  sidebar.style.width = sidebarWidth;
  sidebar.style.minWidth = sidebarWidth;
  sidebar.style.overflow = 'hidden'; // Keep hidden during animation
  sidebar.style.paddingLeft = '16px';
  sidebar.style.paddingRight = '16px';
  
  // Update footer position to align with sidebar when sidebar is shown
  updateFooterPosition(false);
  
  // Update element visibility based on current mode
  updateElementVisibility();
  
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
  
  // Update element visibility based on current mode
  updateElementVisibility();
}

/**
 * Update element visibility based on sidebar state and current mode
 */
function updateElementVisibility() {
  const commentForm = document.getElementById('fixed-comment-box');
  const footer = document.querySelector('.site-footer');
  
  if (sidebarState.isHighZoomOrMobilePortrait) {
    // In high zoom or mobile portrait mode
    if (sidebarState.isVisible) {
      // Sidebar is open: hide comment form and footer
      if (commentForm) {
        commentForm.style.display = 'none';
        console.log('Comment form hidden (sidebar open in high zoom/mobile portrait)');
      }
      if (footer) {
        footer.style.display = 'none';
        console.log('Footer hidden (sidebar open in high zoom/mobile portrait)');
      }
    } else {
      // Sidebar is closed: show comment form, keep footer hidden
      if (commentForm) {
        commentForm.style.display = 'block';
        console.log('Comment form shown (sidebar closed in high zoom/mobile portrait)');
      }
      if (footer) {
        footer.style.display = 'none';
        console.log('Footer remains hidden (high zoom/mobile portrait mode)');
      }
    }
  } else {
    // Normal mode: show both elements
    if (commentForm) {
      commentForm.style.display = 'block';
      console.log('Comment form shown (normal mode)');
    }
    if (footer) {
      footer.style.display = 'block';
      console.log('Footer shown (normal mode)');
    }
  }
}

/**
 * Show sidebar immediately without animation
 */
function showSidebarImmediate(sidebar) {
  sidebar.classList.remove('sidebar-hidden');
  
  // Get appropriate width based on current mode
  const sidebarWidth = getSidebarWidth();
  
  sidebar.style.width = sidebarWidth;
  sidebar.style.minWidth = sidebarWidth;
  sidebar.style.overflow = 'auto';
  sidebar.style.paddingLeft = '16px';
  sidebar.style.paddingRight = '16px';
  
  // Update footer position immediately
  updateFooterPosition(false);
  
  // Update element visibility based on current mode
  updateElementVisibility();
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
      transition: left 300ms ease-in-out, display 300ms ease-in-out;
    }
    
    /* Add smooth transition for comment form visibility */
    #fixed-comment-box {
      transition: display 300ms ease-in-out;
    }
    
    /* Mobile portrait and high zoom responsive styles */
    @media screen and (max-width: 480px) and (orientation: portrait) {
      /* Mobile portrait mode */
      #chat-sidebar:not(.sidebar-hidden) {
        width: 85% !important;
        min-width: 85% !important;
      }
      
      /* Hide footer in mobile portrait mode */
      .site-footer {
        display: none !important;
      }
    }
    
    /* High zoom level styles (250%+) */
    @media screen and (min-resolution: 2.5dppx) {
      #chat-sidebar:not(.sidebar-hidden) {
        width: 85% !important;
        min-width: 85% !important;
      }
      
      /* Hide footer in high zoom mode */
      .site-footer {
        display: none !important;
      }
    }
    
    /* Alternative media query for browsers that don't support dppx */
    @media screen and (-webkit-min-device-pixel-ratio: 2.5) {
      #chat-sidebar:not(.sidebar-hidden) {
        width: 85% !important;
        min-width: 85% !important;
      }
      
      /* Hide footer in high zoom mode */
      .site-footer {
        display: none !important;
      }
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
    
    /* Hide label text at high zoom levels (250%+) following WordPress patterns */
    @media screen and (min-resolution: 2.5dppx) {
      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {
        display: none;
      }
    }
    
    /* Alternative media query for browsers that don't support dppx */
    @media screen and (-webkit-min-device-pixel-ratio: 2.5) {
      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {
        display: none;
      }
    }
    
    /* Ensure icon remains visible at all zoom levels */
    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {
      display: inline-block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
  `;
  
  document.head.appendChild(style);
  console.log('Added sidebar toggle animation CSS with responsive styles');
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
    saveSidebarState();
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
    saveSidebarState();
  }
}

// Export additional functions for testing and external use
export {
  isMobilePortrait,
  isHighZoomOrMobilePortrait,
  getSidebarWidth,
  updateElementVisibility
};

// Export default function for easy importing
export default {
  initToggleSidebar,
  toggleSidebarVisibility,
  isSidebarVisible,
  getSidebarState,
  showSidebar,
  hideSidebar,
  isMobilePortrait,
  isHighZoomOrMobilePortrait,
  getSidebarWidth,
  updateElementVisibility
};