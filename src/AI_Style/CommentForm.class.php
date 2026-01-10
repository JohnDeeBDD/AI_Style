<?php

namespace AI_Style;

class CommentForm {
    /**
     * Outputs the comment form HTML.
     * Always displays the full comment form regardless of login status.
     *
     * @return void
     */
    public static function doEchoCommentForm() {
        // Single wrapper for targeting and seamless styling with #fixed-content
        echo '<div id="chat-input" class="comment-form-container">';
        
        // Use the same minimal form for all users
        $comment_form_args = array(
            'logged_in_as' => '',
            'must_log_in' => '',
            'title_reply' => '',
            'title_reply_to' => '',
            'cancel_reply_link' => '',
            'label_submit' => 'Post Comment',
            'fields' => array(
                'author' => '',
                'email' => '',
                'url' => '',
            ),
        );
        
        comment_form($comment_form_args);
        
        echo '</div> <!-- end: #chat-input -->';
        
        // Add JavaScript for non-logged-in users
        self::addLoginRedirectScript();
    }

    /**
     * Modify the comment form textarea to be 1 row vertically
     *
     * @param array $defaults The default comment form arguments
     * @return array Modified defaults
     */
    public static function modifyCommentFormDefaults($defaults) {
        $defaults['comment_field'] = str_replace('rows="8"', 'rows="1"', $defaults['comment_field']);
        return $defaults;
    }

    /**
     * Add JavaScript for non-logged-in users to redirect to login page
     * when they interact with the comment form
     *
     * @return void
     */
    public static function addLoginRedirectScript() {
        if (!is_user_logged_in()) {
            $login_url = wp_login_url(get_permalink());
            ?>
            <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var commentFormContainer = document.querySelector('.comment-form-container');
                var commentForm = document.getElementById('commentform');
                
                if (commentFormContainer && commentForm) {
                    // Add a visual indicator that login is required
                    commentFormContainer.style.position = 'relative';
                    commentFormContainer.style.cursor = 'pointer';
                    
                    // Create overlay div for better click detection
                    var overlay = document.createElement('div');
                    overlay.style.position = 'absolute';
                    overlay.style.top = '0';
                    overlay.style.left = '0';
                    overlay.style.width = '100%';
                    overlay.style.height = '100%';
                    overlay.style.zIndex = '10';
                    overlay.style.backgroundColor = 'transparent';
                    overlay.style.cursor = 'pointer';
                    overlay.title = 'Click to log in and comment';
                    
                    commentFormContainer.appendChild(overlay);
                    
                    // Add click event listener to redirect to login
                    overlay.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = '<?php echo esc_js($login_url); ?>';
                    });
                    
                    // Prevent form submission and input focus for non-logged-in users
                    var formInputs = commentForm.querySelectorAll('input, textarea, button');
                    formInputs.forEach(function(input) {
                        input.addEventListener('focus', function(e) {
                            e.preventDefault();
                            e.blur();
                            window.location.href = '<?php echo esc_js($login_url); ?>';
                        });
                        
                        input.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.location.href = '<?php echo esc_js($login_url); ?>';
                        });
                    });
                    
                    // Prevent form submission
                    commentForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        window.location.href = '<?php echo esc_js($login_url); ?>';
                    });
                }
            });
            // console.log("$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$");
            jQuery(".comment-form-cookies-consent").hide();
            </script>
            <?php
        }
    }

}