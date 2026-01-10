<?php

namespace AI_Style;

class BlogRoll{

    /**
     * Echo HTML output for the blog roll displaying posts from the main WordPress query
     */
    public static function echoBlogRoll() {
        // Check if we have posts in the main query
        if (!have_posts()) {
            echo '<div class="blog-roll-container">';
            echo '<div class="blog-roll-empty">';
            echo '<p>No posts found.</p>';
            echo '</div>';
            echo '</div>';
            return;
        }

        echo '<div class="blog-roll-container">';
        echo '<div class="blog-roll-grid">';

        // Use the main WordPress query loop
        while (have_posts()) {
            the_post();
            
            $post_id = get_the_ID();
            $permalink = get_permalink();
            $title = get_the_title();
            $excerpt = get_the_excerpt();
            $featured_image = get_the_post_thumbnail($post_id, 'medium', array('class' => 'blog-roll-thumbnail'));
            $post_date = get_the_date('F j, Y');
            
            // If no excerpt, create one from content
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 30, '...');
            }

            echo '<article class="blog-roll-item">';
            echo '<a href="' . esc_url($permalink) . '" class="blog-roll-link">';
            
            // Featured image section
            echo '<div class="blog-roll-image-container">';
            if ($featured_image) {
                echo $featured_image;
            } else {
                echo '<div class="blog-roll-placeholder">📝</div>';
            }
            echo '</div>';
            
            // Content section
            echo '<div class="blog-roll-content">';
            echo '<h3 class="blog-roll-title">' . esc_html($title) . '</h3>';
            echo '<div class="blog-roll-meta">';
            echo '<span class="blog-roll-date">' . esc_html($post_date) . '</span>';
            echo '</div>';
            echo '<div class="blog-roll-excerpt">' . wp_kses_post($excerpt) . '</div>';
            echo '</div>';
            
            echo '</a>';
            echo '</article>';
        }

        echo '</div>'; // .blog-roll-grid
        
        // Add pagination if there are multiple pages
        self::echoPagination();
        
        echo '</div>'; // .blog-roll-container

        // Reset post data after the loop
        wp_reset_postdata();
    }

    /**
     * Echo HTML output for pagination navigation
     */
    private static function echoPagination() {
        global $wp_query;
        
        // Only show pagination if there are multiple pages
        if ($wp_query->max_num_pages <= 1) {
            return;
        }
        
        $current_page = max(1, get_query_var('paged'));
        
        $pagination_args = array(
            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'    => '?paged=%#%',
            'current'   => $current_page,
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '&laquo; Previous',
            'next_text' => 'Next &raquo;',
            'type'      => 'array',
            'mid_size'  => 2,
            'end_size'  => 1,
        );
        
        $pagination_links = paginate_links($pagination_args);
        
        if ($pagination_links) {
            echo '<nav class="blog-roll-pagination" role="navigation" aria-label="Posts pagination">';
            echo '<ul class="pagination-list">';
            
            foreach ($pagination_links as $link) {
                $class = '';
                if (strpos($link, 'current') !== false) {
                    $class = ' class="current-page"';
                } elseif (strpos($link, 'prev') !== false) {
                    $class = ' class="prev-page"';
                } elseif (strpos($link, 'next') !== false) {
                    $class = ' class="next-page"';
                } else {
                    $class = ' class="page-number"';
                }
                
                echo '<li' . $class . '>' . $link . '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        }
    }
}