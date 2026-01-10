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
add_filter('comment_form_defaults', ['AI_Style\CommentForm', 'modifyCommentFormDefaults']);

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
   // if (!is_admin()) {
        $wp_admin_bar->remove_node('wp-logo');
    
        // Remove Customize button
        $wp_admin_bar->remove_node('customize');
        
        // Remove Comments indicator
        $wp_admin_bar->remove_node('comments');
        
        // Remove Search icon
      //  $wp_admin_bar->remove_node('search');
   // }
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

//add_filter('comment_form_defaults', ['AI_Style\CommentForm', 'addLoginPrompt']);

add_action('template_redirect', function () {
    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        $forced_login = get_post_meta($post_id, 'ai_style_force_user_login', true);

        if ( $forced_login && $forced_login == '1' && !is_user_logged_in() ) {
            wp_redirect( wp_login_url( get_permalink($post_id) ) );
            exit;
        }
    }
});

function custom_comment_form_text($fields) {
    // The key 'comment_notes_before' holds the text you want to change.
    // By default, it includes "<p class=\"comment-notes\">" and the text.
    // You can replace it with any text or HTML you want.

    $fields['comment_notes_before'] = '';

    // To remove the text entirely, uncomment the line below:
    // $fields['comment_notes_before'] = '';

    return $fields;
}
add_filter('comment_form_defaults', 'custom_comment_form_text');



add_shortcode('include_file', function ($atts) {
    $a = shortcode_atts([
        'path'  => '',      // absolute path required
        'html'  => 'no',    // yes|no
        'pre'   => 'yes',   // yes|no
        'lang'  => '',      // language hint for <code> class
        'start' => '',      // start line number
        'end'   => '',      // end line number
    ], $atts, 'include_file');

    if (empty($a['path'])) {
        return '<em>[include_file error: missing path]</em>';
    }

    $full = $a['path'];

    // Must be an existing file
    if (!is_file($full)) {
        return '<em>[include_file error: file not found]</em>';
    }

    // Optional: block PHP-like extensions
    $ext = strtolower(pathinfo($full, PATHINFO_EXTENSION));
    $blocked = ['php','phtml','php3','php4','php5','phar'];
    if (in_array($ext, $blocked, true)) {
        return '<em>[include_file error: blocked extension]</em>';
    }

    $contents = @file_get_contents($full);
    if ($contents === false) {
        return '<em>[include_file error: unable to read file]</em>';
    }

    // Line slicing
    $start = max(1, (int)$a['start']);
    $end   = (int)$a['end'] > 0 ? (int)$a['end'] : 0;
    if ($start > 1 || $end > 0) {
        $lines = preg_split("/\r\n|\n|\r/", $contents);
        $total = count($lines);
        $iStart = $start - 1;
        $iEnd   = $end > 0 ? min($end, $total) - 1 : $total - 1;
        $contents = $iStart <= $iEnd
            ? implode("\n", array_slice($lines, $iStart, $iEnd - $iStart + 1))
            : '';
    }

    // Escape or allow HTML
    $out = (strtolower($a['html']) === 'yes')
        ? wp_kses_post($contents)
        : esc_html($contents);

    // Optional pre/code block
    if (strtolower($a['pre']) === 'yes') {
        $langClass = $a['lang'] ? ' class="language-' . esc_attr($a['lang']) . '"' : '';
        $out = '<pre><code' . $langClass . '>' . $out . '</code></pre>';
    }

    return $out;
});