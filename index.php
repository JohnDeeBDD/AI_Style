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
                <div id = "scrollable-content-header">

                <?php 
                the_post();
                $post_id = get_the_ID(); 
                ?>
                    <div id="">
                    <?php
                    
                    if (class_exists('\AIPluginDev\FrontendViewBuilder') && method_exists('\AIPluginDev\FrontendViewBuilder', 'get_header_content') && \is_singular('ai-plugin')) {
                        echo \AIPluginDev\FrontendViewBuilder::get_header_content($post_id);
                    }
                    ?>
                    </div>
                    <div class="post-content" id="entry-content" >

                        <?php   
                        the_content();
                        ?>

                    </div> 
                <div id="chat-messages">
                <?php
                $respondent_user_id = 0;

                if(get_post_meta($post_id, "_cacbot_anchor_post", true) === "1"){
                    // Check for Cacbot Anchor Post status
                    if(
                        function_exists('is_user_logged_in') &&
                        is_user_logged_in() &&
                        class_exists('\Cacbot\AnchorPost') &&
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

                ?>
                </div> <!-- end: #chat-messages -->
            </div> <!-- end: #scrollable-content -->
            <?php endif; ?>
            <div id="fixed-content">
                <?php
                                // Comments are only displayed on singular pages (posts/pages)
                // Non-singular pages (like category pages, blog roll) should not show comments
                if (is_singular()){
                    $linked_Post_id = $post_id; // Default to current post ID
                    if (class_exists('\Cacbot\AnchorPost') && method_exists('\Cacbot\AnchorPost', 'filter_for_linked_post_id')) {
                        $linked_Post_id = \Cacbot\AnchorPost::filter_for_linked_post_id($post_id, \get_current_user_id());
                    }
                    if ( comments_open( $linked_Post_id ) ) {
                        \AI_Style\CommentForm::doEchoCommentForm();
                    } 
                }

                ?>
            </div> <!-- end: #fixed-content -->
        </div> <!-- end: #chat-main -->
    </div> <!-- end: #chat-container -->
<?php get_footer(); ?>

