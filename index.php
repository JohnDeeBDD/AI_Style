<?php 
    include_once(get_stylesheet_directory() . "/src/AI_Style/autoloader.php");
    get_header(); ?>
    <div id="chat-container">
        <div id="chat-sidebar">
        <?php dynamic_sidebar('sidebar-1');?>
        </div> <!-- end: #chat-sidebar -->
        <div id="chat-main">
            <?php if (is_singular()) : ?>
            <div id="scrollable-content">
                <?php 
                the_post(); 
                ?>
                    <div class="post-content" id="post-content-1">
                        <?php   the_content();
                        ?>
                    </div> <!-- end: #post-content-1 -->
                <div id="chat-messages">
                <?php
                $respondent_user_id = 0;
                $post_id = get_the_ID();
                if(get_post_meta($post_id, "_cacbot_anchor_post", true) === "1"){
                    // Check for Cacbot Anchor Post status
                    if(
                        function_exists('is_user_logged_in') && 
                        is_user_logged_in() &&
                        method_exists('\Cacbot\AnchorPost', 'filter_for_linked_post_id')
                    ) {
                        $current_user_id = get_current_user_id();
                        $post_id = \Cacbot\AnchorPost::filter_for_linked_post_id($post_id, $current_user_id);
                    }
                }
                $respondent_user_id = get_post_meta($post_id, "_cacbot_respondent_user_id", true);
                $interlocutor_user_id = get_post_meta($post_id, "_cacbot_interlocutor_user_id", true);
    
                $comments = get_comments(array('post_id' => $post_id, 'status' => 'approve'));
                $comments = array_reverse($comments); // Reverse the order of comments
                foreach ($comments as $comment) {
                    $class = "respondent-message"; // Default to respondent-message
                    if ($interlocutor_user_id && $comment->user_id == $interlocutor_user_id) {
                        $class = "interlocutor-message";
                    }
                    echo '<div class="message ' . $class . '" id="message-' . $comment->comment_ID . '">';
                    // Process markdown if mmd plugin is available
                    $processed_content = ai_style_process_markdown($comment->comment_content);
                    echo '<div class="message-content" id="message-content-' . $comment->comment_ID . '">' . $processed_content . '</div>';
                    echo '</div>';
                }
                ?>
                </div> <!-- end: #chat-messages -->
            </div> <!-- end: #scrollable-content -->

            <?php else : ?>
            <div id="scrollable-content">
                <?php
                    \AI_Style\BlogRoll::echoBlogRoll();
                ?>
                <div id="chat-messages">
                <?php
                // Comments are only displayed on singular pages (posts/pages)
                // Non-singular pages (like category pages, blog roll) should not show comments
                ?>
                </div> <!-- end: #chat-messages -->
            </div> <!-- end: #scrollable-content -->
            <?php endif; ?>
            <div id="fixed-content">
                <?php
                    \AI_Style\CommentForm::doEchoCommentForm();
                ?>
            </div> <!-- end: #fixed-content -->
        </div> <!-- end: #chat-main -->
    </div> <!-- end: #chat-container -->
<?php get_footer(); ?>

