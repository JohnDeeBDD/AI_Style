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
    const ADMIN_BAR_DROPDOWN = '#wp-admin-bar-new-content .ab-sub-wrapper:not([style*="display: none"])';
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
    
    // Sidebar selectors
    const SIDEBAR_ANCHOR = '#chat-sidebar li a';
    const SIDEBAR_FIRST_ANCHOR = '#chat-sidebar li a:first-of-type';
    const SIDEBAR_LIST_ITEM = '#chat-sidebar li';
    
    // JavaScript file paths
    const CHAT_MESSAGES_JS = '/wp-content/themes/ai_style/src/AI_Style/ai-style.js_src/chatMessages.js';
}