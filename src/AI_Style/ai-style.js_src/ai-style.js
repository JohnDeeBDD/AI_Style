//npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style
import commentBoxStyle from "./commentBoxStyle";
import mainCallToAction from "./mainCallToAction";
import justifyOneLiner from "./justifyOneLiner";

document.addEventListener('DOMContentLoaded', function() {
  console.log('ai-style.js is loaded!');
  commentBoxStyle();
  mainCallToAction();
  justifyOneLiner();
});