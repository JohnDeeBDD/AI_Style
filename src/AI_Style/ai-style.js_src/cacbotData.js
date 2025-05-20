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
}

// Create and export a singleton instance
const cacbotData = new CacbotData();

export default cacbotData;