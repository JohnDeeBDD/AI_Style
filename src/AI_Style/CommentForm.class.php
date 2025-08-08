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
        // Add wrapper div with specific ID for JavaScript targeting
        echo '<div class="comment-box-inner">
                <div class="comment-input-row">
                    <div id="chat-input" class="comment-form-container">';
        
        // Show the same comment form for both logged-in and non-logged-in users
        // Remove name, email, and website fields to match logged-in user experience
        $comment_form_args = array(
            'logged_in_as' => '', // Remove logged-in message
            'comment_notes_before' => '', // Remove notes before form
            'comment_notes_after' => '', // Remove notes after form
            'must_log_in' => '', // Remove must log in message
            'title_reply' => '', // Remove reply title
            'title_reply_to' => '', // Remove reply to title
            'cancel_reply_link' => '', // Remove cancel reply link
            'label_submit' => 'Post Comment',
            'fields' => array(
                'author' => '', // Remove name field
                'email' => '',  // Remove email field
                'url' => '',    // Remove website field
            ), // Remove only name, email, and website fields for non-logged-in users
        );
        
        comment_form($comment_form_args);
        
        echo '    </div> <!-- end: #chat-input -->
                </div> <!-- end: .comment-input-row -->
            </div> <!-- end: .comment-box-inner -->';
        
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
            </script>
            <?php
        }
    }

}