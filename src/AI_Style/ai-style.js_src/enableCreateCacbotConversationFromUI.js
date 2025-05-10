/**
 * Creates a new Cacbot Conversation post via AJAX and redirects to it
 *
 * This function overrides the default WordPress behavior when a user clicks
 * on "Cacbot Conversation" in the admin bar by:
 * 1. Preventing the default link behavior
 * 2. Making an AJAX call to create a new conversation post
 * 3. Redirecting to the newly created post on the frontend on success
 */
export default function enableCreateCacbotConversationFromUI() {
    console.log("enableCreateCacbotConversationFromUI function loaded!");
    
    // Find the "Cacbot Conversation" link in the admin bar
    const cacbotLink = document.querySelector('#wp-admin-bar-new-cacbot-conversation a');
    
    if (!cacbotLink) {
        console.warn('Cacbot Conversation link not found in admin bar');
        return;
    }
    
    // Add click event listener to the link
    cacbotLink.addEventListener('click', function(event) {
        // Prevent the default link behavior
        event.preventDefault();
        
        console.log('Creating new Cacbot Conversation via AJAX...');
        
        // Get the REST API URL
        const apiUrl = '/wp-json/ai-style/cacbot-conversation';
        
        // Make AJAX call to the REST API endpoint using jQuery (which is included by WordPress)
        jQuery.ajax({
            url: apiUrl,
            method: 'POST',
            beforeSend: function(xhr) {
                // If a nonce is available in cacbot_data, use it
                if (window.cacbot_data && window.cacbot_data.nonce) {
                    xhr.setRequestHeader('X-WP-Nonce', window.cacbot_data.nonce);
                }
            },
            success: function(data) {
                console.log('Cacbot Conversation created successfully:', data);
                
                if (data.success && data.post_id) {
                    // Redirect to the edit screen for the new post
                    window.location.href = `/wp-admin/post.php?post=${data.post_id}&action=edit`;
                } else {
                    console.error('Invalid response from server:', data);
                    alert('Error creating Cacbot Conversation. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error creating Cacbot Conversation:', error);
                alert('Error creating Cacbot Conversation. Please try again.');
            }
        });
    });
}