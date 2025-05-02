/**
 * Creates a new Cacbot Conversation post via AJAX and redirects to it
 *
 * This function overrides the default WordPress behavior when a user clicks
 * on "Cacbot Conversation" in the admin bar by:
 * 1. Preventing the default link behavior
 * 2. Making an AJAX call to create a new conversation post
 * 3. Redirecting to the newly created post on success
 */
export default function enableCreateCacbotConversationFromUI() {
    console.log("Template function loaded!");
    
    // Function to create a new Cacbot conversation
    window.createNewCacbotConversation = function(event) {
        // Prevent the default link behavior
        if (event) {
            event.preventDefault();
        }
        
        // Show loading indicator or feedback to user
        console.log("Creating new Cacbot conversation...");
        
        // Make AJAX call to the REST API endpoint
        jQuery.ajax({
            url: '/wp-json/ai-style/cacbot-conversation',
            method: 'POST',
            beforeSend: function(xhr) {
                // Include the nonce for security
                if (typeof cacbot_data !== 'undefined' && cacbot_data.nonce) {
                    xhr.setRequestHeader('X-WP-Nonce', cacbot_data.nonce);
                }
            },
            success: function(response) {
                if (response.success && response.post_id) {
                    console.log("Conversation created successfully:", response);
                    
                    // Redirect to the newly created post
                    window.location.href = '/wp-admin/post.php?post=' + response.post_id + '&action=edit';
                } else {
                    console.error("Error creating conversation:", response);
                    alert("Error creating new conversation. Please try again.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert("Error creating new conversation. Please try again.");
            }
        });
    };
    
    // Add event listener to the admin bar link after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Find the admin bar link for Cacbot Conversation
        const cacbotLinks = document.querySelectorAll('#wp-admin-bar-new-cacbot-conversation a');
        
        if (cacbotLinks.length > 0) {
            cacbotLinks.forEach(function(link) {
                link.addEventListener('click', createNewCacbotConversation);
            });
            console.log("Cacbot Conversation admin bar link handler attached");
        }
    });
}