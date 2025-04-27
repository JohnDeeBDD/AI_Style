//npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
import commentBoxStyle from "./commentBoxStyle";
import template from "./template";
import enableIndependentScrolling from "./independentScrolling";
import setupColumns from "./setupColumns";

document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js');
  commentBoxStyle();
  template();
  enableIndependentScrolling();
  setupColumns();

  const commentCount = document.querySelectorAll('#chat-messages .message').length;
  console.log('Number of comments:', commentCount);
  
});