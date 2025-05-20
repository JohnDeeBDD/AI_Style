/**
 * Functions to fetch post content and comments without page refresh
 */

/**
 * Fetches both post content and comments for a given post ID
 * @param {number} postID - The WordPress post ID to fetch
 * @returns {Promise<Object>} - Promise resolving to an object containing post content and comments
 */
export default function fetchPost(postID) {
    return Promise.all([
        fetchPostContent(postID),
        fetchComments(postID)
    ])
    .then(([postContent, comments]) => {
        return {
            postContent,
            comments
        };
    })
    .catch(error => {
        console.error('Error fetching post data:', error);
        throw error;
    });
}

/**
 * Fetches post content for a given post ID using WordPress REST API
 * @param {number} postID - The WordPress post ID to fetch
 * @returns {Promise<Object>} - Promise resolving to the post data
 */
function fetchPostContent(postID) {
    return fetch(`/wp-json/wp/v2/posts/${postID}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch post content: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(postData => {
            // Process the post data if needed
            return {
                id: postData.id,
                title: postData.title.rendered,
                content: postData.content.rendered,
                date: postData.date,
                author: postData.author,
                status: postData.status
            };
        });
}

/**
 * Fetches comments for a given post ID using WordPress REST API
 * @param {number} postID - The WordPress post ID to fetch comments for
 * @returns {Promise<Array>} - Promise resolving to an array of comments
 */
function fetchComments(postID) {
    return fetch(`/wp-json/wp/v2/comments?post=${postID}&orderby=date&order=asc`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch comments: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(comments => {
            // Process the comments if needed
            return comments.map(comment => {
                return {
                    id: comment.id,
                    author: comment.author_name,
                    content: comment.content.rendered,
                    date: comment.date,
                    parent: comment.parent
                };
            });
        });
}

/**
 * Updates the UI with fetched post data
 * @param {Object} postData - Object containing post content and comments
 */
export function updatePostUI(postData) {
    // Update post content
    const contentContainer = document.querySelector('.entry-content');
    if (contentContainer && postData.postContent) {
        contentContainer.innerHTML = postData.postContent.content;
        
        // Update post title
        const titleElement = document.querySelector('.entry-title');
        if (titleElement) {
            titleElement.innerHTML = postData.postContent.title;
        }
        
        // Update URL without refreshing the page
        const newUrl = `/index.php/${postData.postContent.id}/`;
        window.history.pushState({ postId: postData.postContent.id }, postData.postContent.title, newUrl);
    }
    
    // Update comments
    const commentsContainer = document.getElementById('comments');
    if (commentsContainer && postData.comments) {
        // Clear existing comments
        const commentsList = commentsContainer.querySelector('.comment-list') || document.createElement('ol');
        commentsList.className = 'comment-list';
        commentsList.innerHTML = '';
        
        // Add new comments
        postData.comments.forEach(comment => {
            const commentElement = document.createElement('li');
            commentElement.id = `comment-${comment.id}`;
            commentElement.className = 'comment';
            
            commentElement.innerHTML = `
                <article class="comment-body">
                    <footer class="comment-meta">
                        <div class="comment-author">
                            <b class="fn">${comment.author}</b>
                        </div>
                        <div class="comment-metadata">
                            <time datetime="${comment.date}">${new Date(comment.date).toLocaleString()}</time>
                        </div>
                    </footer>
                    <div class="comment-content">${comment.content}</div>
                </article>
            `;
            
            commentsList.appendChild(commentElement);
        });
        
        // Add the comments list to the container if it's not already there
        if (!commentsContainer.querySelector('.comment-list')) {
            commentsContainer.appendChild(commentsList);
        }
    }
}