<?php

include_once("/var/www/html/wp-content/themes/ai_style/src/AI_Style/autoloader.php");

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
 * Display the current template file being used
 */
function ai_style_debug_template() {
    global $template;
    if (is_user_logged_in()) {
        echo '<!-- Current template file: ' . basename($template) . ' -->';
    }
}
add_action('wp_footer', 'ai_style_debug_template');

