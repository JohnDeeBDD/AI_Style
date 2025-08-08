/**
 * Toggle Sidebar Visibility
 *
 * This module provides functionality to toggle the sidebar visibility with smooth animations.
 * It includes state management and smooth slide animations using CSS media queries.
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
  isMobileView: false,
  isDesktopView: true,
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
 * Sets up initial state based on localStorage and responsive breakpoints
 */
export function initToggleSidebar() {
  console.log('Initializing toggle sidebar functionality');
  
  // Update responsive mode detection
  updateResponsiveMode();
  
  console.log('Responsive modes detected:', {
    isMobileView: sidebarState.isMobileView,
    isDesktopView: sidebarState.isDesktopView
  });
  
  // Load saved state from localStorage
  const savedState = loadSidebarState();
  
  // Set initial sidebar state based on saved state or responsive mode
  setInitialSidebarState(savedState);
  
  // Add CSS for animations if not already present
  addToggleAnimationCSS();
  
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
    // Update responsive modes
    const modeChanged = updateResponsiveMode();
    
    // If responsive mode changed, handle the transition
    if (modeChanged) {
      console.log('Responsive mode changed on resize:', {
        isMobileView: sidebarState.isMobileView,
        isDesktopView: sidebarState.isDesktopView
      });
      
      const sidebar = document.getElementById('chat-sidebar');
      if (sidebar) {
        // Handle mode transition for sidebar
/**
 * Handle transition between mobile and desktop modes
 * @param {HTMLElement} sidebar - The sidebar element
 */
function handleModeTransition(sidebar) {
  if (sidebarState.isMobileView) {
    // Switching to mobile view: use overlay behavior
    console.log('Switching to mobile overlay mode');
    
    if (sidebarState.isVisible) {
      // Show sidebar with mobile overlay behavior
      sidebar.classList.add('sidebar-visible');
      sidebar.style.left = '0';
    } else {
      // Hide sidebar with mobile overlay behavior
      sidebar.classList.remove('sidebar-visible');
      sidebar.style.left = '-100%';
    }
    
    // Reset desktop-specific styles
    sidebar.style.width = '';
    sidebar.style.minWidth = '';
    sidebar.style.position = '';
    
  } else {
    // Switching to desktop view: use push/shrink behavior
    console.log('Switching to desktop push/shrink mode');
    
    // Remove mobile overlay classes
    sidebar.classList.remove('sidebar-visible');
    sidebar.style.left = '';
    
    if (sidebarState.isVisible) {
      // Show sidebar with desktop push behavior
      const sidebarWidth = getSidebarWidth();
      sidebar.style.width = sidebarWidth;
      sidebar.style.minWidth = sidebarWidth;
      sidebar.classList.remove('sidebar-hidden');
    } else {
      // Hide sidebar with desktop push behavior
      sidebar.style.width = '0';
      sidebar.style.minWidth = '0';
      sidebar.classList.add('sidebar-hidden');
    }
  }
}

        handleModeTransition(sidebar);
      }
    }
  }, 250);
}

/**
 * Detect if we're in mobile view based on 782px breakpoint
 * This is separate from zoom detection and matches CSS media queries
 * @returns {boolean}
 */
function isMobileView() {
  return window.innerWidth < 782;
}

/**
 * Detect if we're in desktop view based on 782px breakpoint
 * @returns {boolean}
 */
function isDesktopView() {
  return window.innerWidth >= 782;
}

/**
 * Update responsive mode detection
 * Updates breakpoint-based detection using WordPress-compliant breakpoints
 */
function updateResponsiveMode() {
  const wasMobileView = sidebarState.isMobileView;
  const wasDesktopView = sidebarState.isDesktopView;
  
  sidebarState.isMobileView = isMobileView();
  sidebarState.isDesktopView = isDesktopView();
  
  const modeChanged = wasMobileView !== sidebarState.isMobileView ||
                     wasDesktopView !== sidebarState.isDesktopView;
  
  console.log('Responsive mode updated:', {
    isMobileView: sidebarState.isMobileView,
    isDesktopView: sidebarState.isDesktopView,
    modeChanged: modeChanged
  });
  
  return modeChanged;
}

/**
 * Get the appropriate sidebar width based on current mode
 * @returns {string}
 */
function getSidebarWidth() {
  return sidebarState.originalWidth;
}

/**
 * Set initial sidebar state based on saved state or responsive mode
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
    // Default to visible on desktop, hidden on mobile
    shouldBeVisible = sidebarState.isDesktopView;
    console.log('Using responsive logic for sidebar state:', shouldBeVisible, 'Mode:', sidebarState.isDesktopView ? 'desktop' : 'mobile');
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
  
  // Update responsive mode before toggling
  updateResponsiveMode();
  
  if (sidebarState.isVisible) {
    hideSidebarAnimated(sidebar);
  } else {
    showSidebarAnimated(sidebar);
  }
  
  // Update state
  sidebarState.isVisible = !sidebarState.isVisible;
  
  // Save the new state to localStorage
  saveSidebarState();
  
  // Update toggle button to reflect new state
  updateToggleButtonState();
  
  console.log('Toggled sidebar visibility. New state:', sidebarState.isVisible ? 'visible' : 'hidden', 'Mode:', sidebarState.isMobileView ? 'mobile' : 'desktop');
}

/**
 * Hide sidebar with smooth slide-out animation
 */
function hideSidebarAnimated(sidebar) {
  // Add transitioning class for animation
  sidebar.classList.add('sidebar-transitioning');
  
  if (sidebarState.isMobileView) {
    // Mobile: Use overlay behavior - slide out to the left
    sidebar.classList.remove('sidebar-visible');
    sidebar.style.left = '-100%';
    
    // Don't modify width/padding for mobile overlay
    
  } else {
    // Desktop: Use push/shrink behavior
    sidebar.style.width = '0';
    sidebar.style.minWidth = '0';
    sidebar.style.paddingLeft = '0';
    sidebar.style.paddingRight = '0';
    
    // Update footer position to extend to left edge when sidebar is hidden
    updateFooterPosition(true);
  }
  
  sidebar.style.overflow = 'hidden';
  
  // Remove animation class and reset state after animation completes
  setTimeout(() => {
    sidebar.classList.remove('sidebar-transitioning');
    if (!sidebarState.isMobileView) {
      sidebar.classList.add('sidebar-hidden');
    }
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
  
  if (sidebarState.isMobileView) {
    // Mobile: Use overlay behavior - slide in from the left
    sidebar.classList.add('sidebar-visible');
    sidebar.style.left = '0';
    
    // Don't modify width/padding for mobile overlay - CSS handles it
    
  } else {
    // Desktop: Use push/shrink behavior
    const sidebarWidth = getSidebarWidth();
    sidebar.style.width = sidebarWidth;
    sidebar.style.minWidth = sidebarWidth;
    sidebar.style.paddingLeft = '16px';
    sidebar.style.paddingRight = '16px';
    
    // Update footer position to align with sidebar when sidebar is shown
    updateFooterPosition(false);
  }
  
  sidebar.style.overflow = 'hidden'; // Keep hidden during animation
  
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
  if (sidebarState.isMobileView) {
    // Mobile: Use overlay behavior
    sidebar.classList.remove('sidebar-visible');
    sidebar.style.left = '-100%';
    
  } else {
    // Desktop: Use push/shrink behavior
    sidebar.style.width = '0';
    sidebar.style.minWidth = '0';
    sidebar.style.paddingLeft = '0';
    sidebar.style.paddingRight = '0';
    sidebar.classList.add('sidebar-hidden');
    
    // Update footer position immediately
    updateFooterPosition(true);
  }
  
  sidebar.style.overflow = 'hidden';
}

/**
 * Show sidebar immediately without animation
 */
function showSidebarImmediate(sidebar) {
  sidebar.classList.remove('sidebar-hidden');
  
  if (sidebarState.isMobileView) {
    // Mobile: Use overlay behavior
    sidebar.classList.add('sidebar-visible');
    sidebar.style.left = '0';
    
  } else {
    // Desktop: Use push/shrink behavior
    const sidebarWidth = getSidebarWidth();
    sidebar.style.width = sidebarWidth;
    sidebar.style.minWidth = sidebarWidth;
    sidebar.style.paddingLeft = '16px';
    sidebar.style.paddingRight = '16px';
    
    // Update footer position immediately
    updateFooterPosition(false);
  }
  
  sidebar.style.overflow = 'auto';
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
    #fixed-content {
      transition: display 300ms ease-in-out;
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
    
    /* Ensure icon remains visible at all viewport sizes */
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
 * Update toggle button icon and text based on sidebar visibility state
 * Generic function that can handle both mobile and desktop toggle buttons
 *
 * @param {HTMLElement|null} iconElement - The icon element (optional, will auto-detect if not provided)
 * @param {HTMLElement|null} labelElement - The label element (optional, will auto-detect if not provided)
 * @param {boolean|null} isVisible - Override visibility state (optional, uses current state if not provided)
 */
export function updateToggleButton(iconElement = null, labelElement = null, isVisible = null) {
  // Use provided visibility state or current sidebar state
  const sidebarVisible = isVisible !== null ? isVisible : sidebarState.isVisible;
  
  // Auto-detect elements if not provided (for backward compatibility)
  const toggleIcon = iconElement || document.querySelector('#wp-admin-bar-sidebar-toggle .dashicons');
  const toggleLabel = labelElement || document.querySelector('#wp-admin-bar-sidebar-toggle .ab-label');
  
  if (!toggleIcon || !toggleLabel) {
    console.warn('Toggle button elements not found for state update');
    return;
  }
  
  // Remove existing arrow classes
  toggleIcon.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right');
  
  // Add appropriate class and text based on sidebar state
  if (sidebarVisible) {
    toggleIcon.classList.add('dashicons-arrow-left');
    toggleIcon.setAttribute('title', 'Close Sidebar');
    toggleLabel.textContent = 'Close Sidebar';
  } else {
    toggleIcon.classList.add('dashicons-arrow-right');
    toggleIcon.setAttribute('title', 'Open Sidebar');
    toggleLabel.textContent = 'Open Sidebar';
  }
  
  console.log('Updated toggle button state:', sidebarVisible ? 'arrow-left (close)' : 'arrow-right (open)');
}

/**
 * Update the toggle button state to reflect current sidebar visibility
 * Wrapper function for backward compatibility
 */
function updateToggleButtonState() {
  updateToggleButton();
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
    updateToggleButtonState();
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
    updateToggleButtonState();
  }
}

// Export additional functions for testing and external use
export {
  isMobileView,
  isDesktopView,
  updateResponsiveMode,
  getSidebarWidth
};

// Export default function for easy importing
export default {
  initToggleSidebar,
  toggleSidebarVisibility,
  isSidebarVisible,
  getSidebarState,
  showSidebar,
  hideSidebar,
  isMobileView,
  isDesktopView,
  updateResponsiveMode,
  getSidebarWidth,
  updateToggleButton
};