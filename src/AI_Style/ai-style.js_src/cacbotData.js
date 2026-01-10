/**
 * CacbotData - A module to handle Cacbot data with standardized access methods
 * 
 * This class encapsulates the Cacbot data and provides methods to access
 * and validate the data in a standardized way.
 * 
 * @module cacbotData
 */

/**
 * Class representing Cacbot data management
 */
class CacbotData {
  /**
   * Create a CacbotData instance
   */
  constructor() {
    /**
     * The internal data object
     * @private
     * @type {Object}
     */
    this.data = {};
    
    /**
     * Set of comment change subscribers
     * @private
     * @type {Set<Function>}
     */
    this.commentSubscribers = new Set();
    
    /**
     * Last known comment count for change detection
     * @private
     * @type {number}
     */
    this.lastCommentCount = 0;
    
    /**
     * Last known comment IDs for change detection
     * @private
     * @type {Set<string>}
     */
    this.lastCommentIds = new Set();
    
    /**
     * Flag to track if event monitoring is initialized
     * @private
     * @type {boolean}
     */
    this.isInitialized = false;
    
    /**
     * Polling interval ID for comment monitoring
     * @private
     * @type {number|null}
     */
    this.pollingIntervalId = null;
    
    /**
     * Current post ID being monitored
     * @private
     * @type {number|null}
     */
    this.currentPostId = null;
    
    /**
     * Polling interval in milliseconds
     * @private
     * @type {number}
     */
    this.pollingInterval = 3000; // 3 seconds
  }

  /**
   * Initialize the data object with raw data
   * 
   * @param {Object} rawData - The raw data object to initialize with
   * @throws {Error} If rawData is not an object or is null
   */
  initialize(rawData) {
    if (!rawData || typeof rawData !== 'object') {
      throw new Error('Invalid data: rawData must be a non-null object');
    }

    // Validate required fields
    const requiredFields = ['nonce'];
    const missingFields = requiredFields.filter(field => !rawData.hasOwnProperty(field));
    
    if (missingFields.length > 0) {
      throw new Error(`Missing required fields: ${missingFields.join(', ')}`);
    }

    // Store the data
    this.data = { ...rawData };
    
    console.log('CacbotData initialized successfully');
  }

  /**
   * Get a value from the data object by key
   * 
   * @param {string} key - The key to get the value for
   * @returns {*} The value for the key, or undefined if not found
   */
  get(key) {
    return this.data[key];
  }

  /**
   * Check if a key exists in the data object
   * 
   * @param {string} key - The key to check
   * @returns {boolean} True if the key exists, false otherwise
   */
  has(key) {
    return Object.prototype.hasOwnProperty.call(this.data, key);
  }

  /**
   * Get all data as an object
   * 
   * @returns {Object} A copy of the entire data object
   */
  getAll() {
    return { ...this.data };
  }

  /**
   * Get the nonce value
   * 
   * @returns {string} The nonce value
   * @throws {Error} If nonce is not available
   */
  getNonce() {
    if (!this.has('nonce')) {
      throw new Error('Nonce is not available');
    }
    return this.get('nonce');
  }

  /**
   * Get the post ID
   * 
   * @returns {string|null} The post ID, or null if not available
   */
  getPostId() {
    return this.get('post_id');
  }

  /**
   * Get the user ID
   * 
   * @returns {string|null} The user ID, or null if not available
   */
  getUserId() {
    return this.get('user_id');
  }

  /**
   * Check if the user can create a conversation
   * 
   * @returns {boolean} True if the user can create a conversation, false otherwise
   */
  canCreateConversation() {
    const canCreate = this.get('can_create_conversation');
    return canCreate === '1' || canCreate === true;
  }

  /**
   * Check if a specific action is enabled
   * 
   * @param {string} action - The action to check
   * @returns {boolean} True if the action is enabled, false otherwise
   */
  isActionEnabled(action) {
    if (!action || typeof action !== 'string') {
      return false;
    }
    
    const key = `action_enabled_${action}`;
    const value = this.get(key);
    
    return value === '1' || value === true;
  }

  /**
   * Initialize event monitoring
   */
  initializeEventMonitoring() {
    if (this.isInitialized) return;
    
    console.log('CacbotData: Initializing event monitoring...');
    
    // Listen for cacbot events
    document.addEventListener('cacbot-data-updated', this.handleCacbotEvent.bind(this));
    this.isInitialized = true;
    console.log('CacbotData: Event monitoring initialized - listening for cacbot-data-updated events');
    
    // DIAGNOSTIC: Add a test event listener to see if ANY events are being fired
    document.addEventListener('DOMContentLoaded', () => {
      console.log('CacbotData: DOM loaded, checking for existing comment data...');
      // Check if there's any initial comment data we should process
      this.checkForInitialCommentData();
    });
  }

  /**
   * DIAGNOSTIC: Check for initial comment data that might be available
   */
  checkForInitialCommentData() {
    console.log('CacbotData: Checking for initial comment data...');
    console.log('CacbotData: Current data keys:', Object.keys(this.data));
    console.log('CacbotData: Has comments in data:', this.has('comments'));
    
    if (this.has('comments')) {
      const comments = this.get('comments');
      console.log('CacbotData: Found initial comments:', comments);
      this.notifyCommentSubscribers({ comments, comment_count: comments.length });
    } else {
      console.log('CacbotData: No initial comment data found');
    }
  }

  /**
   * Handle incoming cacbot events
   */
  handleCacbotEvent(event) {
    console.log('CacbotData: Received cacbot-data-updated event', event);
    
    if (!event.detail) {
      console.warn('CacbotData: Event has no detail data');
      return;
    }
    
    const newData = event.detail;
    console.log('CacbotData: Processing new data', {
      hasComments: !!newData.comments,
      commentCount: newData.comment_count || 0,
      dataKeys: Object.keys(newData),
      postId: newData.post_id,
      commentsData: newData.comments
    });
    
    this.updateData(newData);
    
    // Check for comment changes
    if (this.hasCommentChanges(newData)) {
      console.log('CacbotData: Comment changes detected, notifying subscribers');
      this.notifyCommentSubscribers(newData);
    } else {
      console.log('CacbotData: No comment changes detected');
    }
  }

  /**
   * Update internal data with new data
   * @param {Object} newData - The new data to update with
   */
  updateData(newData) {
    this.data = { ...this.data, ...newData };
    this.updateCommentTracking(newData);
  }

  /**
   * Detect if comments have changed
   * @param {Object} newData - The new data to check
   * @returns {boolean} True if comments have changed
   */
  hasCommentChanges(newData) {
    if (!newData.comments || !Array.isArray(newData.comments)) {
      console.log('CacbotData: No comments array in data or not an array', {
        hasComments: !!newData.comments,
        isArray: Array.isArray(newData.comments),
        commentsType: typeof newData.comments
      });
      return false;
    }

    // Transform WordPress comment objects to expected format
    const transformedComments = this.transformWordPressComments(newData.comments);
    newData.comments = transformedComments;

    const newCommentCount = newData.comment_count || 0;
    const newCommentIds = new Set(transformedComments.map(comment => comment.comment_ID));
    
    console.log('CacbotData: Comment change detection', {
      newCommentCount,
      lastCommentCount: this.lastCommentCount,
      newCommentIds: Array.from(newCommentIds),
      lastCommentIds: Array.from(this.lastCommentIds),
      transformedComments
    });
    
    // Check for count changes
    if (newCommentCount !== this.lastCommentCount) {
      console.log('CacbotData: Comment count changed', {
        old: this.lastCommentCount,
        new: newCommentCount
      });
      return true;
    }
    
    // Check for new/removed comments
    const hasNewComments = [...newCommentIds].some(id => !this.lastCommentIds.has(id));
    const hasRemovedComments = [...this.lastCommentIds].some(id => !newCommentIds.has(id));
    
    if (hasNewComments || hasRemovedComments) {
      console.log('CacbotData: Comment composition changed', {
        newComments: hasNewComments,
        removedComments: hasRemovedComments,
        newCommentsList: [...newCommentIds].filter(id => !this.lastCommentIds.has(id)),
        removedCommentsList: [...this.lastCommentIds].filter(id => !newCommentIds.has(id))
      });
      return true;
    }
    
    return false;
  }

  /**
   * Transform WordPress comment objects to expected format
   * @param {Array} wpComments - WordPress comment objects
   * @returns {Array} Transformed comment objects
   */
  transformWordPressComments(wpComments) {
    return wpComments.map(wpComment => {
      // Handle both WordPress comment object format and already transformed format
      const transformed = {
        comment_ID: wpComment.comment_ID || wpComment.id || wpComment.ID,
        comment_content: wpComment.comment_content || wpComment.content,
        comment_author: wpComment.comment_author || wpComment.author_name || 'Anonymous',
        comment_author_email: wpComment.comment_author_email || wpComment.author_email || '',
        comment_date: wpComment.comment_date || wpComment.date,
        user_id: wpComment.user_id || wpComment.author || '0',
        post_id: wpComment.comment_post_ID || wpComment.post_id || wpComment.post
      };
      
      console.log('CacbotData: Transforming comment', {
        original: wpComment,
        transformed: transformed
      });
      
      return transformed;
    });
  }

  /**
   * Update tracking data
   * @param {Object} newData - The new data to update tracking with
   */
  updateCommentTracking(newData) {
    if (newData.comments && Array.isArray(newData.comments)) {
      this.lastCommentCount = newData.comment_count || 0;
      this.lastCommentIds = new Set(newData.comments.map(comment => comment.comment_ID));
      
      console.log('CacbotData: Updated comment tracking', {
        commentCount: this.lastCommentCount,
        commentIds: Array.from(this.lastCommentIds)
      });
    }
  }

  /**
   * Subscribe to comment changes
   * @param {Function} callback - The callback function to call when comments change
   */
  subscribeToComments(callback) {
    if (typeof callback !== 'function') {
      throw new Error('Callback must be a function');
    }
    this.commentSubscribers.add(callback);
    console.log('CacbotData: Comment subscriber added');
  }

  /**
   * Unsubscribe from comment changes
   * @param {Function} callback - The callback function to remove
   */
  unsubscribeFromComments(callback) {
    this.commentSubscribers.delete(callback);
    console.log('CacbotData: Comment subscriber removed');
  }

  /**
   * Notify all comment subscribers
   * @param {Object} newData - The new data containing comments
   */
  notifyCommentSubscribers(newData) {
    this.commentSubscribers.forEach(callback => {
      try {
        callback(newData.comments, newData.comment_count);
      } catch (error) {
        console.error('CacbotData: Error notifying comment subscriber:', error);
      }
    });
  }
}

// Create and export a singleton instance
const cacbotData = new CacbotData();

// Auto-initialize event monitoring when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    cacbotData.initializeEventMonitoring();
    // DIAGNOSTIC: Initialize with any existing cacbot_data
    if (window.cacbot_data) {
      console.log('CacbotData: Initializing with existing window.cacbot_data:', window.cacbot_data);
      cacbotData.initialize(window.cacbot_data);
    }
  });
} else {
  cacbotData.initializeEventMonitoring();
  // DIAGNOSTIC: Initialize with any existing cacbot_data
  if (window.cacbot_data) {
    console.log('CacbotData: Initializing with existing window.cacbot_data:', window.cacbot_data);
    cacbotData.initialize(window.cacbot_data);
  }
}

// Make cacbotData globally available
window.cacbotData = cacbotData;

export default cacbotData;