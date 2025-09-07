<?php
/**
 * The home page template file
 *
 * Displays the main blog index with randomized post order, full-width
 * 4-column grid layout, advanced filtering, and AJAX load more functionality.
 * Includes both posts and recipes when enabled.
 *
 * @package Sarai_Chinwag
 * @since 1.0.0
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <?php      do_action( 'before_post_grid' ); ?>
        <div class="post-grid" id="post-grid">
            <?php
            /* Start the Loop */
            while ( have_posts() ) :
                the_post();
                echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '">';
                get_template_part( 'template-parts/content', get_post_type() );
                echo '</article>';
            endwhile;
            ?>
        </div><!-- .post-grid -->

        <div class="load-more-container">
            <button id="load-more" data-page="1"><?php esc_html_e( 'Load More', 'sarai-chinwag' ); ?></button>
        </div>

        <?php get_template_part( 'template-parts/archive-image-mode-link' ); ?>

    <?php else : ?>

        <p><?php esc_html_e( 'No posts found.', 'sarai-chinwag' ); ?></p>

    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
?>
