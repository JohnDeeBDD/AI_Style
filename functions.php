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

