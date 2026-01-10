/**
 * Fetches the Cacbot Archive Conversation API endpoint
 *
 * This function handles the API call to archive a conversation.
 * It takes a post ID and form data, makes the API request, and returns a promise.
 *
 * @param {number|string} postId - The ID of the post to archive
 * @param {FormData} formData - The form data containing the necessary parameters
 * @returns {Promise} A promise that resolves to the API response
 */
export default function fetchCacbotLinkAPI(postId, formData, endpoint) {
  // Get the nonce from the form data
  const nonce = formData.get('nonce');
  
  if (!postId || !nonce) {
    return Promise.reject(new Error('Cannot archive conversation: Missing post_id or nonce'));
  }
  
  // Make the API call
  return fetch(endpoint, {
    method: 'POST',
    credentials: 'include', // Include cookies for authentication across domains if needed
    headers: {
      'X-WP-Nonce': nonce // Add the nonce as a header as well (WordPress standard)
    },
    body: formData
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
  });
}