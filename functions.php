<?php

include_once("/var/www/html/wp-content/themes/ai_style/src/AI_Style/autoloader.php");



add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('ai_style', get_stylesheet_uri());
});

function enqueue_chat_scripts() {
    wp_enqueue_script( 'wp-api' );
    // Enqueue Dashicons for front-end use
    wp_enqueue_style('dashicons');
    wp_enqueue_script('ai-style', get_stylesheet_directory_uri() . '/src/AI_Style/ai-style.js', array('jquery'), null, true);
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
 * Register REST API endpoint for creating cacbot-conversation posts
 */
add_action('rest_api_init', function() {
    // Check if the custom post type exists
    if (post_type_exists('cacbot-conversation')) {
        register_rest_route('ai-style', '/cacbot-conversation', array(
            'methods' => 'POST',
            'callback' => 'ai_style_create_cacbot_conversation',
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            }
        ));
    }
});

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
function ai_style_customize_admin_bar($wp_admin_bar) {
    // Remove WordPress logo
    $wp_admin_bar->remove_node('wp-logo');
    
    // Remove Customize button
    $wp_admin_bar->remove_node('customize');
    
    // Remove Comments indicator
    $wp_admin_bar->remove_node('comments');
    
    // Remove Search icon
    $wp_admin_bar->remove_node('search');
}

// Hook into the admin bar with a high priority to ensure it runs after WordPress has added all default nodes
add_action('admin_bar_menu', 'ai_style_customize_admin_bar', 999);

