/**
 * Justifies one-line comments in interlocutor messages to the right
 * and shrinks the bubble to fit the text size
 */
export default function justifyOneLiner() {
    console.log("Applying right justification to one-liner comments");
    
    // Select all interlocutor messages
    const interlocutorMessages = document.querySelectorAll('.interlocutor-message');
    
    interlocutorMessages.forEach(message => {
        const messageContent = message.querySelector('.message-content');
        
        if (messageContent) {
            // Check if this is a one-liner (no line breaks and no HTML breaks)
            const text = messageContent.textContent.trim();
            const hasNoLineBreaks = !text.includes('\n');
            const hasNoHtmlBreaks = !messageContent.innerHTML.includes('<br');
            const isOneLiner = hasNoLineBreaks && hasNoHtmlBreaks && text.length > 0;
            
            if (isOneLiner) {
                // Apply right justification
                message.style.marginLeft = 'auto';
                message.style.marginRight = '0';
                
                // Shrink bubble to fit content
                message.style.width = 'auto';
                message.style.maxWidth = '70%';
                message.style.display = 'inline-block';
                
                // Add padding to ensure text has some space
                message.style.paddingLeft = '20px';
                message.style.paddingRight = '20px';
                
                // Add a class for potential additional styling
                message.classList.add('one-liner');
            }
        }
    });
    
    // Add a mutation observer to handle dynamically added messages
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach(node => {
                        if (node.classList && node.classList.contains('interlocutor-message')) {
                            const messageContent = node.querySelector('.message-content');
                            if (messageContent) {
                                const text = messageContent.textContent.trim();
                                const hasNoLineBreaks = !text.includes('\n');
                                const hasNoHtmlBreaks = !messageContent.innerHTML.includes('<br');
                                const isOneLiner = hasNoLineBreaks && hasNoHtmlBreaks && text.length > 0;
                                
                                if (isOneLiner) {
                                    node.style.marginLeft = 'auto';
                                    node.style.marginRight = '0';
                                    node.style.width = 'auto';
                                    node.style.maxWidth = '70%';
                                    node.style.display = 'inline-block';
                                    node.style.paddingLeft = '20px';
                                    node.style.paddingRight = '20px';
                                    node.classList.add('one-liner');
                                }
                            }
                        }
                    });
                }
            });
        });
        
        observer.observe(chatMessages, { childList: true, subtree: true });
    }
}