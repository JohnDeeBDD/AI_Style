<?php get_header(); ?>
    <div id="chat-container">
        <div id="chat-sidebar">
            <?php dynamic_sidebar('chat-sidebar');
            ?>HERE IS THE SIDEBAR
        </div>
        <div id="chat-main">
            <div id="chat-messages">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="message ai-message">
                        <div class="message-content"><?php the_content(); ?></div>
                    </div>
                    <?php
                    $comments = get_comments(array('post_id' => get_the_ID(), 'status' => 'approve'));
                    foreach ($comments as $comment) {
                        $class = ($comment->user_id == get_the_author_meta('ID')) ? 'ai-message' : 'user-message';
                        echo '<div class="message ' . $class . '">';
                        echo '<div class="message-content">' . $comment->comment_content . '</div>';
                        echo '</div>';
                    }
                    ?>
                <?php endwhile; endif; ?>
            </div>
            <div id="what-are-you-working-on">
                What are you working on?
            </div>
            <div id="chat-input">
                <?php comment_form(); ?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>