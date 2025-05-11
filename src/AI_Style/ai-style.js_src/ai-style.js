/* 
To compile use this command: 
npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
*/
/* global cacbot_data */

import commentBoxStyle from "./commentBoxStyle";
import mainCallToAction from "./mainCallToAction";
import justifyOneLiner from "./justifyOneLiner";
import chatMessages from "./chatMessages";
import adminBarCustomization from "./adminBarCustomization";
import cacbotData from "./cacbotData";

// Make the chat message functions available globally
window.addInterlocutorMessage = chatMessages.addInterlocutorMessage;
window.addRespondentMessage = chatMessages.addRespondentMessage;


document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  mainCallToAction();
  justifyOneLiner();
  
  console.log("PHP cacbot_data:");
  console.log(window.cacbot_data);
  // Initialize cacbotData with the global cacbot_data object
  try {
    cacbotData.initialize(window.cacbot_data || {});
    console.log(cacbotData.getAll());
  } catch (error) {
    console.error("Failed to initialize cacbotData:", error);
  }
  
  // Log that the chat message functions are available
  console.log('Chat message functions are available globally:');
  console.log('- addInterlocutorMessage(message)');
  console.log('- addRespondentMessage(message)');
  adminBarCustomization();
});