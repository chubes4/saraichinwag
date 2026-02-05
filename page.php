<?php
/**
 * The template for displaying pages.
 *
 * @package Sarai_Chinwag
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    /**
     * Hook: sarai_chinwag_before_page_content
     * Fires before page content on single pages.
     */
    do_action( 'sarai_chinwag_before_page_content' );
    ?>

    <?php
    while ( have_posts() ) :
        the_post();

        echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '" itemscope itemtype="http://schema.org/WebPage">';

        // Include the template part for the entry header
        get_template_part( 'template-parts/content', 'single' );
    ?>

    <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        // Close the article tag here
        echo '</article>';

    endwhile; // End of the loop.
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>