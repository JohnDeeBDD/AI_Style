/**
 * Functions to control the sidebar
 */

/**
 * Clears all links from the sidebar
 */
export function clearSidebar() {
    const sidebar = document.querySelector('#chat-sidebar');
    if (!sidebar) {
        console.error('Sidebar element not found');
        return;
    }
    
    // Find the unordered list in the sidebar
    const ulElement = sidebar.querySelector('ul');
    if (ulElement) {
        // Remove all list items from the unordered list
        while (ulElement.firstChild) {
            ulElement.removeChild(ulElement.firstChild);
        }
    }
}

/**
 * Adds a link to the sidebar
 * @param {string} title - The text content of the link
 * @param {string} url - The URL the link points to
 * @param {string} data_postID - The post ID to be stored as a data attribute
 */
export function addSidebarLink(title, url, data_postID) {
    const sidebar = document.querySelector('#chat-sidebar');
    if (!sidebar) {
        console.error('Sidebar element not found');
        return;
    }
    
    // Check if the sidebar already has a ul element, create one if it doesn't
    let ulElement = sidebar.querySelector('ul');
    if (!ulElement) {
        ulElement = document.createElement('ul');
        sidebar.appendChild(ulElement);
    }
    
    // Create a new list item
    const liElement = document.createElement('li');
    
    // Create a new link element
    const linkElement = document.createElement('a');
    linkElement.textContent = title;
    linkElement.href = url;
    linkElement.setAttribute('data-postid', data_postID);
    
    // Append the link to the list item
    liElement.appendChild(linkElement);
    
    // Append the list item to the unordered list
    ulElement.appendChild(liElement);
}

// Export functions as a default object for easier importing
export default {
    clearSidebar,
    addSidebarLink
};