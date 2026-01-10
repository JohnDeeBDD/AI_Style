import cacbotData from "./cacbotData"
import fetchCacbotLinkAPI from "./fetchCacbotLinkAPI"

/**
 * Handle clicks on sidebar items in the anchor post list
 *
 * This function is called when a user clicks on a sidebar item in the anchor post list.
 * It checks if the clicked post ID is different from the current linked post ID,
 * and if so, calls the fetchCacbotArchiveConversation function to archive the current post
 * and unarchive the clicked post.
 *
 * @param {string|number} linkedPostID - The ID of the linked post that was clicked
 * @returns {void}
 */
export default function sidebarAnchorPostLinkClick(linkedPostID) {
    // Convert linkedPostID to a number for consistent comparison
    linkedPostID = parseInt(linkedPostID, 10);
    
    // Get the current linked post ID from cacbotData
    const currentLinkedPostID = parseInt(cacbotData.get('linked_post_id'), 10);
    
    // If the clicked post ID equals the current linked post ID, do nothing and return
    if (linkedPostID === currentLinkedPostID) {
        console.log('Clicked post is already the current linked post');
        return;
    }
    
    // Get the current post ID
    const postId = cacbotData.getPostId();
    const nonce = AIStyleSettings.nonce;
    
    // Create a FormData object for the API request
    const formData = new FormData();
    formData.append('nonce', nonce);
    formData.append('post_id', postId);
    formData.append('linked_post_id', linkedPostID);
    var endpoint = "/wp-json/cacbot/v1/link-conversation";

    // Call fetchCacbotArchiveConversation to archive the current post and unarchive the clicked post
    fetchCacbotLinkAPI(postId, formData, endpoint)
        .then(response => {
            console.log('conversation successfully linked:', response);
            window.location.reload();
        })
        .catch(error => {
            console.error('Error linking conversation!:', error);
        });
}

/**
 * Initialize event listeners for sidebar anchor post link clicks
 *
 * This function adds event listeners to all anchor elements in the sidebar list.
 * It extracts the post ID from the parent li element's data-post-id attribute
 * and calls the sidebarAnchorPostLinkClick function with that ID.
 */
export function initSidebarClickListeners() {
    console.log("initSidebarClickListerners");
    // Select all anchor elements in the sidebar list
    const sidebarLinks = document.querySelectorAll('.anchor-post-list li a');
    
    // Add click event listener to each link
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Prevent the default anchor behavior
            event.preventDefault();
            
            // Get the parent li element
            const listItem = this.closest('li');
            
            // Extract the post ID from the data-post-id attribute
            const postId = listItem.getAttribute('data-post-id');
            
            // Call the sidebarAnchorPostLinkClick function with the post ID
            sidebarAnchorPostLinkClick(postId);
        });
    });
}