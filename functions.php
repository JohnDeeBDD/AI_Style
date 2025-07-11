<?php

include_once(get_stylesheet_directory() . "/src/AI_Style/autoloader.php");

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('ai_style', get_stylesheet_uri());
});

function enqueue_chat_scripts() {
    wp_enqueue_script( 'wp-api' );
    // Force load dashicons on frontend
    wp_enqueue_style('dashicons');
    wp_enqueue_script('ai-style', get_stylesheet_directory_uri() . '/src/AI_Style/ai-style.js', ['jquery', 'wp-api'], null, true);
    wp_localize_script( 'ai-style', 'AIStyleSettings', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ) );
}
add_action('wp_enqueue_scripts', 'enqueue_chat_scripts');



/**
 * Modify the comment form textarea to be 1 row vertically
 */
function ai_style_comment_form_defaults($defaults) {
    $defaults['comment_field'] = str_replace('rows="8"', 'rows="1"', $defaults['comment_field']);
    return $defaults;
}
add_filter('comment_form_defaults', 'ai_style_comment_form_defaults');

/**
 * Register widget area for the sidebar
 */
function ai_style_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'ai_style' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'ai_style' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'ai_style_widgets_init' );

/**
 * Register the AI Style Sidebar Widget
 */
function ai_style_register_widgets() {
    register_widget('AI_Style\AnchorPostWidget');
}
add_action('widgets_init', 'ai_style_register_widgets');


/**
 * Callback function to create a new cacbot-conversation post
 *
 * @param WP_REST_Request $request The request object
 * @return WP_REST_Response The response object
 */
function ai_style_create_cacbot_conversation($request) {
    // Generate a random 20 character alphanumeric string for the title
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $title = '';
    for ($i = 0; $i < 20; $i++) {
        $title .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    // Create the post
    $post_id = wp_insert_post(array(
        'post_title'    => $title,
        'post_content'  => '&nbsp;',
        'post_status'   => 'publish',
        'post_type'     => 'cacbot-conversation',
    ));
    
    if (is_wp_error($post_id)) {
        return new WP_REST_Response(array(
            'success' => false,
            'message' => $post_id->get_error_message()
        ), 500);
    }
    
    // Add custom post meta data
    add_post_meta($post_id, '_cacbot_conversation', '1', true);
    
    return new WP_REST_Response(array(
        'success' => true,
        'post_id' => $post_id,
        'title'   => $title
    ), 201);
}

/**
 * Customize the WordPress admin bar
 *
 * Removes:
 * - WordPress logo and information
 * - Customize button
 * - Comments indicator
 * - Search icon
 *
 * Keeps:
 * - New button (will be modified with JS later)
 * - Edit Post button
 * - Howdy user menu
 */


add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!is_admin()) {
        $wp_admin_bar->remove_node('wp-logo');
    
        // Remove Customize button
        $wp_admin_bar->remove_node('customize');
        
        // Remove Comments indicator
        $wp_admin_bar->remove_node('comments');
        
        // Remove Search icon
      //  $wp_admin_bar->remove_node('search');
    }
}, 999);

require_once(plugin_dir_path(__FILE__) . 'src/plugin-update-checker/plugin-update-checker.php');
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
PucFactory::buildUpdateChecker(    'https://cacbot.com/wp-content/uploads/ai_style_details.json',__FILE__,'ai_style');

/**
 * Check if the Markup Markdown (mmd) plugin is available
 *
 * @return bool True if the mmd plugin is loaded and available
 */
function ai_style_is_mmd_available() {
    // Check if the mmd function exists (primary method for Markup Markdown plugin)
    if (function_exists('mmd')) {
        return true;
    }
    
    // Alternative check for class-based implementation
    if (class_exists('MarkupMarkdown')) {
        return true;
    }
    
    // Check if plugin is active using WordPress plugin detection
    if (function_exists('is_plugin_active')) {
        return is_plugin_active('markup-markdown/markup-markdown.php');
    }
    
    return false;
}

/**
 * Process markdown content safely using the mmd plugin
 *
 * @param string $content The content to process
 * @return string The processed content (markdown converted to HTML) or original content if plugin unavailable
 */
function ai_style_process_markdown($content) {
    if (  class_exists( 'Markup_Markdown' ) ) {
        return mmd()->markdown2html( $content);
     }else{
        return $content;
    }

    
}
