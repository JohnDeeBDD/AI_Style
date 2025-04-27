<?php get_header(); ?>
    <div id="chat-container">
        <div id="chat-sidebar">
        <?php dynamic_sidebar('sidebar-1');?>
        HERE IS THE SIDEBAR!
        </div>
        <div id="chat-main">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
            <div id="chat-messages">
                <?php
                $respondent_user_id = 0;
                $post_id = get_the_ID();
                $respondent_user_id = get_post_meta($post_id, "_cacbot_respondent_user_id", true);
                $comments = get_comments(array('post_id' => get_the_ID(), 'status' => 'approve'));
                $comments = array_reverse($comments); // Reverse the order of comments
                foreach ($comments as $comment) {
                    $class = ($comment->user_id == get_the_author_meta('ID')) ? 'interlocutor-message' : 'respondent-message';
                    echo '<div class="message ' . $class . '">';
                    echo '<div class="message-content">' . $comment->comment_content . '</div>';
                    echo '</div>';
                }
                ?>
            </div>
            <div id="floating-items-group">
                <div class="main-call-to-action">
                    What are you working on?
                </div>
                <div id="chat-input">
                    <?php comment_form(); ?>
                </div>
            </div> <!-- end: #floating-items-group -->
        </div>
    </div>
<?php get_footer(); ?>
