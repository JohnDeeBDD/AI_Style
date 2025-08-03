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
  overrideClickBehavior,
  addSidebarToggleButton,
  updateToggleButton,
  initializeZoomDetection
} from "./adminBarCustomization";
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
  
  // Initialize admin bar customization (includes sidebar toggle button)
  adminBarCustomization();
  
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
  console.log('- addSidebarToggleButton()');
  console.log('- updateToggleButton(iconElement, labelElement)');
  console.log('- initializeZoomDetection()');
  
  // Set focus to the last comment on page load
  focusLastComment();
});

