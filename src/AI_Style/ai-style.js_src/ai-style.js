/* 
To compile use this command: 
npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
*/
/* global cacbot_data */

import commentBoxStyle from "./CommentFormStyle";
import justifyOneLiner from "./justifyOneLiner";
import chatMessages, { addInterlocutorMessage, addRespondentMessage, clearMessages } from "./chatMessages";
import adminBarCustomization, {
  overrideHoverBehavior,
  overrideClickBehavior
} from "./adminBarCustomization";
import { addMobileHamburgerIcon } from "./HamburgerButton";
import {
  initializeArrowToggleButton,
  updateArrowToggleButton
} from "./ArrowToggleButton";
import cacbotData from "./cacbotData";
import fetchPost, { updatePostUI } from "./fetchPost";
import sidebarAnchorPostLinkClick, { initSidebarClickListeners } from "./sidebarAnchorPostLinkClick";
import { clearSidebar, addSidebarLink } from "./sidebarLinks";
import toggleSidebarVisible, {
  initToggleSidebar,
  toggleSidebarVisibility,
  isSidebarVisible,
  showSidebar,
  hideSidebar
} from "./toggleSidebarVisible";
import focusLastComment from "./focusLastComment";
import setupPostNavigation from "./setupPostNavigation";



document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  justifyOneLiner();
  

  try {
    cacbotData.initialize(window.cacbot_data || {});
    window.cacbotData = cacbotData;
    console.log(cacbotData.getAll());
  } catch (error) {
    console.error("Failed to initialize cacbotData:", error);
  }
  
  // Make chat message functions available globally
  window.addInterlocutorMessage = addInterlocutorMessage;
  window.addRespondentMessage = addRespondentMessage;
  window.clearMessages = clearMessages;
  
  // Log that the chat message functions are available
  console.log('Chat message functions are available globally:');
  console.log('- addInterlocutorMessage(message)');
  console.log('- addRespondentMessage(message)');
  console.log('- clearMessages()');
  
  // Initialize toggle sidebar functionality
  initToggleSidebar();
  
  // Make toggle sidebar functions available globally
  window.toggleSidebarVisibility = toggleSidebarVisibility;
  window.isSidebarVisible = isSidebarVisible;
  window.showSidebar = showSidebar;
  window.hideSidebar = hideSidebar;
  
  // Initialize admin bar customization (New button only)
  adminBarCustomization();
  
  // Initialize desktop arrow toggle button
  initializeArrowToggleButton();
  
  // Initialize mobile hamburger button
  addMobileHamburgerIcon();
  
  // Set up event listeners for post navigation
  setupPostNavigation();
  initSidebarClickListeners();
  
  // Log that the toggle sidebar functions are available
  console.log('Toggle sidebar functions are available globally:');
  console.log('- toggleSidebarVisibility()');
  console.log('- isSidebarVisible()');
  console.log('- showSidebar()');
  console.log('- hideSidebar()');
  
  // Log that the admin bar customization functions are available
  console.log('Admin bar customization functions are available globally:');
  console.log('- overrideHoverBehavior(newButton)');
  console.log('- overrideClickBehavior(newButton)');
  
  // Log that the toggle button functions are available
  console.log('Toggle button functions initialized:');
  console.log('- Desktop arrow toggle button initialized');
  console.log('- Mobile hamburger button initialized');
  
  // Set focus to the last comment on page load
  focusLastComment();
});

