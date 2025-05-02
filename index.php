<?php get_header(); ?>
    <div id="chat-container">
        <div id="chat-sidebar">
        <?php dynamic_sidebar('sidebar-1');?>
        </div> <!-- end: #chat-sidebar -->
        <div id="chat-main">
            <?php if (is_singular()) : ?>
            <div class="scrollable-content">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="post-content" id="post-content-1">
                        <?php the_content(); ?>
                    </div> <!-- end: #post-content-1 -->
                <?php endwhile; endif; ?>
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
                    //echo("UserID: " . $comment->user_id);
                    $class = "interlocutor-message";
                    if($respondent_user_id){
                        if($respondent_user_id === $comment->user_id){
                            $class = "respondent-message";
                        }else{
                            $class = "interlocutor-message";
                        }
                    }
                    echo '<div class="message ' . $class . '" id="message-' . $comment->comment_ID . '">';
                    echo '<div class="message-content" id="message-content-' . $comment->comment_ID . '">' . $comment->comment_content . '</div>';
                    echo '</div>';
                }
                ?>
                </div> <!-- end: #chat-messages -->
            </div> <!-- end: .scrollable-content -->

            <?php else : ?>
                NOT SINGULAR
            <?php endif; ?>
            <div id="floating-items-group">
                <div class="main-call-to-action" id="main-call-to-action-1">
                    What are you working on?
                </div> <!-- end: #main-call-to-action-1 -->
                <div id="chat-input">
                    <?php comment_form(); ?>
                </div> <!-- end: #chat-input -->
            </div> <!-- end: #floating-items-group -->
        </div> <!-- end: #chat-main -->
    </div> <!-- end: #chat-container -->
<?php get_footer(); ?>
