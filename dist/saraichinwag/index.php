<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    if ( have_posts() ) :

        /* Start the Loop */
        while ( have_posts() ) :
            the_post();

            if ( is_singular() ) {
                get_template_part( 'template-parts/content', 'single' );
            } else {
                get_template_part( 'template-parts/content', get_post_format() );
            }

        endwhile;

        the_posts_navigation();

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;
    ?>
</main><!-- #main -->

<?php
get_footer();
?>
