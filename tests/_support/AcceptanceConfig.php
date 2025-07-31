<?php
/**
 * AcceptanceConfig.php
 * 
 * Configuration file for acceptance tests containing common URLs, selectors,
 * and other constants used across test files.
 * 
 * This improves maintainability by centralizing values that might need to change
 * across environments or as the application evolves.
 */

class AcceptanceConfig
{
    // Base URLs
    const BASE_URL = 'http://localhost';
    
    // Common test pages
    const TEST_POST_PAGE = '/testpost';
    
    // Admin pages
    const ADMIN_DASHBOARD = '/wp-admin/';
    const ADMIN_POSTS = '/wp-admin/edit.php';
    const ADMIN_NEW_POST = '/wp-admin/post-new.php';
    const ADMIN_PAGES = '/wp-admin/edit.php?post_type=page';
    const ADMIN_THEMES = '/wp-admin/themes.php';
    
    // Common selectors
    const ADMIN_BAR = '#wpadminbar';
    const ADMIN_BAR_NEW_CONTENT = '#wp-admin-bar-new-content';
    const ADMIN_BAR_NEW_CONTENT_LINK = '#wp-admin-bar-new-content a.ab-item';
    const ADMIN_BAR_DROPDOWN = '#wp-admin-bar-new-post';
    const ADMIN_BAR_EDIT = '#wp-admin-bar-edit';
    const ADMIN_BAR_MY_ACCOUNT = '#wp-admin-bar-my-account';
    const ADMIN_BAR_WP_LOGO = '#wp-admin-bar-wp-logo';
    const ADMIN_BAR_CUSTOMIZE = '#wp-admin-bar-customize';
    const ADMIN_BAR_COMMENTS = '#wp-admin-bar-comments';
    const ADMIN_BAR_SEARCH = '#wp-admin-bar-search';
    const ADMIN_BAR_SIDEBAR_TOGGLE = '#wp-admin-bar-sidebar-toggle';
    
    // Chat UI selectors
    const CHAT_CONTAINER = '#chat-container';
    const CHAT_SIDEBAR = '#chat-sidebar';
    const CHAT_MAIN = '#chat-main';
    const CHAT_MESSAGES = '#chat-messages';
    const CHAT_INPUT = '#chat-input';
    const POST_CONTENT = '.post-content';
    const FIXED_COMMENT_BOX = '#fixed-comment-box';
    const SCROLLABLE_CONTENT = '#scrollable-content';
    const INTERLOCUTOR_MESSAGE = '.interlocutor-message';
    const RESPONDENT_MESSAGE = '.respondent-message';
    const SITE_FOOTER = '.site-footer';
    const SUBMIT_BUTTON = 'input[type=submit], button[type=submit]';
    const SUBMIT_ARROW_BUTTON = '.submit-arrow.submit-button';
    
    // Comment form and toolbar selectors
    const COMMENT_BOX_INNER = '.comment-box-inner';
    const COMMENT_INPUT_ROW = '.comment-input-row';
    const COMMENT_TOOLS_ROW = '.comment-tools-row';
    const TOOLS_LEFT = '.tools-left';
    const TOOLS_RIGHT = '.tools-right';
    const MESSAGE_CONTENT = '.message-content';
    
    // Button selectors
    const PLUS_ICON_BUTTON = '.plus-icon.add-button';
    const TOOLS_BUTTON = '.tools-button';
    const MICROPHONE_BUTTON = '.microphone-button';
    
    // Sidebar selectors
    const SIDEBAR_ANCHOR = '#chat-sidebar li a';
    const SIDEBAR_FIRST_ANCHOR = '#chat-sidebar li a:first-of-type';
    const SIDEBAR_LIST_ITEM = '#chat-sidebar li';
    
    // JavaScript file paths
    const CHAT_MESSAGES_JS = '/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js';
    
    // Zoom Management Constants
    const ZOOM_LEVEL_25 = 0.25;
    const ZOOM_LEVEL_50 = 0.5;
    const ZOOM_LEVEL_75 = 0.75;
    const ZOOM_LEVEL_100 = 1.0;
    const ZOOM_LEVEL_150 = 1.5;
    const ZOOM_LEVEL_200 = 2.0;
    const ZOOM_LEVEL_DEFAULT = self::ZOOM_LEVEL_100;
    
    // Viewport configurations for zoom testing
    const VIEWPORT_DESKTOP_DEFAULT = '1920x1080';
    const VIEWPORT_DESKTOP_75 = '1440x810';
    const VIEWPORT_DESKTOP_50 = '960x540';
    
    // Zoom enforcement settings
    const ZOOM_ENFORCEMENT_ENABLED = true;
    const ZOOM_RESET_DELAY = 500; // milliseconds
    
    // Device mode configurations
    const DEVICE_MODE_DESKTOP = 'desktop';
    const DEVICE_MODE_TABLET_PORTRAIT = 'tablet_portrait';
    const DEVICE_MODE_TABLET_LANDSCAPE = 'tablet_landscape';
    const DEVICE_MODE_MOBILE_PORTRAIT = 'mobile_portrait';
    const DEVICE_MODE_MOBILE_LANDSCAPE = 'mobile_landscape';
    
    // Device window size mappings
    const DEVICE_WINDOW_SIZES = [
        self::DEVICE_MODE_DESKTOP => '1920x1080',
        self::DEVICE_MODE_TABLET_PORTRAIT => '768x1024',
        self::DEVICE_MODE_TABLET_LANDSCAPE => '1024x768',
        self::DEVICE_MODE_MOBILE_PORTRAIT => '375x667',
        self::DEVICE_MODE_MOBILE_LANDSCAPE => '667x375'
    ];
    
    /**
     * Get the current window size from the suite configuration
     * This method reads from the codeception configuration to determine
     * the current window size setting
     *
     * @return string Window size in format "widthxheight" (e.g., "1920x1080")
     */
    public static function getWindowSize()
    {
        // Try to get from Codeception configuration
        if (class_exists('\Codeception\Configuration')) {
            $config = \Codeception\Configuration::config();
            if (isset($config['modules']['config']['WPWebDriver']['window_size'])) {
                return $config['modules']['config']['WPWebDriver']['window_size'];
            }
        }
        
        // Fallback to default desktop size
        return self::VIEWPORT_DESKTOP_DEFAULT;
    }
    
    /**
     * Get the current device mode from the suite configuration
     * This method reads from the codeception configuration to determine
     * the current device mode setting
     *
     * @return string Device mode (desktop, tablet_portrait, tablet_landscape, mobile_portrait, mobile_landscape)
     */
    public static function getDeviceMode()
    {
        // Try to get from Codeception configuration
        if (class_exists('\Codeception\Configuration')) {
            $config = \Codeception\Configuration::config();
            if (isset($config['modules']['config']['WPWebDriver']['device_mode'])) {
                return $config['modules']['config']['WPWebDriver']['device_mode'];
            }
        }
        
        // Fallback: determine device mode from window size
        $windowSize = self::getWindowSize();
        
        // Map window size to device mode
        foreach (self::DEVICE_WINDOW_SIZES as $deviceMode => $size) {
            if ($windowSize === $size) {
                return $deviceMode;
            }
        }
        
        // Default fallback
        return self::DEVICE_MODE_DESKTOP;
    }
    
    /**
     * Check if current configuration is desktop mode
     *
     * @return bool True if desktop mode
     */
    public static function isDesktop()
    {
        return self::getDeviceMode() === self::DEVICE_MODE_DESKTOP;
    }
    
    /**
     * Check if current configuration is tablet mode (portrait or landscape)
     *
     * @return bool True if tablet mode
     */
    public static function isTablet()
    {
        $deviceMode = self::getDeviceMode();
        return $deviceMode === self::DEVICE_MODE_TABLET_PORTRAIT ||
               $deviceMode === self::DEVICE_MODE_TABLET_LANDSCAPE;
    }
    
    /**
     * Check if current configuration is mobile mode (portrait or landscape)
     *
     * @return bool True if mobile mode
     */
    public static function isMobile()
    {
        $deviceMode = self::getDeviceMode();
        return $deviceMode === self::DEVICE_MODE_MOBILE_PORTRAIT ||
               $deviceMode === self::DEVICE_MODE_MOBILE_LANDSCAPE;
    }
}