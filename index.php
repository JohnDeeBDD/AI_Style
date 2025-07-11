<?php get_header(); ?>
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
                        <?php the_content(); ?>
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
                $comments = get_comments(array('post_id' => $post_id, 'status' => 'approve'));
                $comments = array_reverse($comments); // Reverse the order of comments
                foreach ($comments as $comment) {
                    $class = "interlocutor-message";
                    if($respondent_user_id){
                        if($respondent_user_id === $comment->user_id){
                            $class = "respondent-message";
                        }else{
                            $class = "interlocutor-message";
                        }
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
                // For blog roll pages, we can still show comments if there are any
                // This maintains the special feature of having comments on blog rolls
                $comments = get_comments(array('status' => 'approve'));
                if (!empty($comments)) {
                    $comments = array_reverse($comments); // Reverse the order of comments
                    foreach ($comments as $comment) {
                        $class = "interlocutor-message";
                        echo '<div class="message ' . $class . '" id="message-' . $comment->comment_ID . '">';
                        // Process markdown if mmd plugin is available
                        $processed_content = ai_style_process_markdown($comment->comment_content);
                        echo '<div class="message-content" id="message-content-' . $comment->comment_ID . '">' . $processed_content . '</div>';
                        echo '</div>';
                    }
                }
                ?>
                </div> <!-- end: #chat-messages -->
            </div> <!-- end: #scrollable-content -->
            <?php endif; ?>
            <div id="fixed-comment-box">
                <div class="comment-input-row">
                    <div id="chat-input">
                        <?php comment_form(); ?>
                    </div> <!-- end: #chat-input -->
                </div> <!-- end: .comment-input-row -->
                <div class="comment-tools-row">
                    <div class="tools-left">
                        <button class="plus-icon add-button" type="button" title="Add attachment">
                            <span>+</span>
                        </button>
                        <button class="tools-button tools-text" type="button" title="Tools">
                            <span>Tools</span>
                        </button>
                    </div>
                    <div class="tools-right">
                        <button class="microphone-button" type="button" title="Voice input">
                            <span>🎤</span>
                        </button>
                        <button class="submit-arrow submit-button" type="submit" title="Send message">
                            <span>↑</span>
                        </button>
                    </div>
                </div> <!-- end: .comment-tools-row -->
            </div> <!-- end: #fixed-comment-box -->
        </div> <!-- end: #chat-main -->
    </div> <!-- end: #chat-container -->
<?php get_footer(); ?>

