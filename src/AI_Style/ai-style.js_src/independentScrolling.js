/**
 * Enables independent scrolling for chat-sidebar and chat-main divs
 * Prevents mouse wheel events from propagating to parent elements
 */
export default function enableIndependentScrolling() {
    console.log("Enabling independent scrolling for chat elements");
    
    // Suppress the browser's default scrollbar
    document.body.style.overflow = 'hidden';
    
    // Enable independent scrolling for chat-sidebar
    const sidebar = document.getElementById('chat-sidebar');
    if (sidebar) {
        sidebar.style.overflowY = 'auto';
        sidebar.style.height = '100%';
        
        // Prevent wheel events from propagating to parent elements
        sidebar.addEventListener('wheel', function(event) {
            // If the sidebar is at the top and trying to scroll up, or
            // at the bottom and trying to scroll down, don't prevent default
            const atTop = this.scrollTop === 0;
            const atBottom = this.scrollHeight - this.scrollTop === this.clientHeight;
            
            if ((atTop && event.deltaY < 0) || (atBottom && event.deltaY > 0)) {
                return; // Allow parent scrolling at boundaries
            }
            
            event.stopPropagation();
        }, { passive: false });
        
        // Set a flag for testing purposes
        sidebar.__wheelListenerAttached = true;
    }
    
    // Enable independent scrolling for chat-main
    const main = document.getElementById('chat-main');
    if (main) {
        main.style.overflowY = 'auto';
        main.style.height = '100%';
        
        // Prevent wheel events from propagating to parent elements
        main.addEventListener('wheel', function(event) {
            // If the main area is at the top and trying to scroll up, or
            // at the bottom and trying to scroll down, don't prevent default
            const atTop = this.scrollTop === 0;
            const atBottom = this.scrollHeight - this.scrollTop === this.clientHeight;
            
            if ((atTop && event.deltaY < 0) || (atBottom && event.deltaY > 0)) {
                return; // Allow parent scrolling at boundaries
            }
            
            event.stopPropagation();
        }, { passive: false });
        
        // Set a flag for testing purposes
        main.__wheelListenerAttached = true;
    }
    
    // Ensure the chat-messages area is scrollable
    const messages = document.getElementById('chat-messages');
    if (messages) {
        messages.style.overflowY = 'auto';
        messages.style.flex = '1 1 auto';
    }
    
    // Make sure the comment form stays fixed at the bottom
    const chatInput = document.getElementById('chat-input');
    if (chatInput) {
        chatInput.style.position = 'sticky';
        chatInput.style.bottom = '0';
        chatInput.style.zIndex = '10';
        chatInput.style.backgroundColor = '#343541'; // Match the background color
    }
}