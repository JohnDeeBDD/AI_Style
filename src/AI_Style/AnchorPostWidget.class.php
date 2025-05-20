<?php

namespace AI_Style;

class AnchorPostWidget extends \WP_Widget {
    /**
     * Constructor for the widget
     */
    public function __construct() {
        parent::__construct(
            'ai_style_sidebar', // Base ID
            'AI Style Sidebar', // Name
            array('description' => 'Displays a list of archived conversations for the current user') // Args
        );
    }

    /**
     * Front-end display of widget
     *
     * @param array $args     Widget arguments
     * @param array $instance Saved values from database
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Get current user ID
        $current_user_id = get_current_user_id();
        
        // Get current post ID
        $current_post_id = get_the_ID();
        
        // Query arguments for posts
        $query_args = array(
            'author' => $current_user_id,
            'post_type' => 'post',
            'post_status' => 'private', // Only get privately published posts
            'posts_per_page' => -1, // No pagination
            'orderby' => 'date',
            'order' => 'ASC', // Chronological order
            'meta_query' => array(
                array(
                    'key' => '_cacbot_linked_to',
                    'value' => $current_post_id,
                    'compare' => '='
                )
            ),
        );
        
        // Get posts
        $posts = get_posts($query_args);
                
        // Display posts
        if (!empty($posts)) {
            echo '<ul class="anchor-post-list">';
            foreach ($posts as $post) {
                echo '<li data-post-id="' . esc_attr($post->ID) . '">';
                echo '<a href="' . get_permalink($post->ID) . '">' . esc_html($post->post_title) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No archived conversations found!</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     *
     * @param array $instance Previously saved values from database
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Archived Conversations';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
     *
     * @param array $new_instance Values just sent to be saved
     * @param array $old_instance Previously saved values from database
     * @return array Updated safe values to be saved
     */
    public function update($instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}