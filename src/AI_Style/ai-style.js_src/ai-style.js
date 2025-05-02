//npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
/* global cacbot_data */

import commentBoxStyle from "./commentBoxStyle";
import mainCallToAction from "./mainCallToAction";
import justifyOneLiner from "./justifyOneLiner";
import chatMessages from "./chatMessages";
import enableCreateCacbotConversationFromUI from "./enableCreateCacbotConversationFromUI";

// Make the chat message functions available globally
window.addInterlocutorMessage = chatMessages.addInterlocutorMessage;
window.addRespondentMessage = chatMessages.addRespondentMessage;


document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  mainCallToAction();
  justifyOneLiner();
  console.log(cacbot_data);
  enableCreateCacbotConversationFromUI();
  
  // Log that the chat message functions are available
  console.log('Chat message functions are available globally:');
  console.log('- addInterlocutorMessage(message)');
  console.log('- addRespondentMessage(message)');
});