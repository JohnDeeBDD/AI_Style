/* 
To compile use this command: 
npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
*/
/* global cacbot_data */

import commentBoxStyle from "./commentBoxStyle";
import mainCallToAction from "./mainCallToAction";
import justifyOneLiner from "./justifyOneLiner";
import chatMessages from "./chatMessages";
import enableCreateCacbotConversationFromUI from "./enableCreateCacbotConversationFromUI";
import newConversationControl from "./newConversationControl";
import adminBarCustomization from "./adminBarCustomization";

// Make the chat message functions available globally
window.addInterlocutorMessage = chatMessages.addInterlocutorMessage;
window.addRespondentMessage = chatMessages.addRespondentMessage;


document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  mainCallToAction();
  justifyOneLiner();
  console.log("Cacbot data:")
  console.log(cacbot_data);
  /*
  Cacbot data contains:
  action_enabled_archive
  action_enabled_fetch_image
  anchor_post_id
  can_create_conversation
  comment_count
  nonce
    :
  "7cffeec2dc"
  post_id
    : 
  "NONE"
  user_id
    :
  "1"
*/
  enableCreateCacbotConversationFromUI();
  
  // Log that the chat message functions are available
  console.log('Chat message functions are available globally:');
  console.log('- addInterlocutorMessage(message)');
  console.log('- addRespondentMessage(message)');
  newConversationControl();
  adminBarCustomization();
});