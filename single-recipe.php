<?php
/**
 * Template for displaying single Recipe posts
 *
 * @package Sarai_Chinwag
 */

// If recipes are disabled, use the standard single post template
if (sarai_chinwag_recipes_disabled()) {
    include(get_template_directory() . '/single.php');
    return;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    while ( have_posts() ) :
        the_post();

        echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '" itemscope itemtype="http://schema.org/Recipe">';

        get_template_part( 'template-parts/content', 'recipe' );
    ?>

    
    <?php
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        echo '</article>';

    endwhile;
    ?>

    </main><!-- #main -->
    <?php do_action( 'after_post_main' ); ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>
