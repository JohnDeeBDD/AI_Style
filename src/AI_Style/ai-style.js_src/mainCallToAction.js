export default function mainCallToAction() {
  // Check if there are any comments
  const commentCount = document.querySelectorAll('#chat-messages .message').length;
  
  // If comments exist, hide the main call to action
  if (commentCount > 0) {
    const mainCallToAction = document.getElementById('main-call-to-action-1');
    if (mainCallToAction) {
      mainCallToAction.style.display = 'none';
    }
  }
}