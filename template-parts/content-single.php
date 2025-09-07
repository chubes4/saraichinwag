<?php
/**
 * Template part for displaying single posts and recipes
 * 
 * Provides consistent single post display with badge navigation,
 * post title, content, and pagination links. Used by single.php
 * and single-recipe.php templates.
 *
 * @package Sarai_Chinwag
 * @since 1.0.0
 */
?>

<header class="entry-header">
    <?php
    // Show badge-breadcrumbs for single posts
    sarai_chinwag_post_badges();
    
    the_title( '<h1 class="entry-title">', '</h1>' );
    
    ?>
</header><!-- .entry-header -->

<div class="entry-content">
    <?php
    the_content();

    wp_link_pages( array(
        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'sarai-chinwag' ),
        'after'  => '</div>',
    ) );
    ?>
</div><!-- .entry-content -->
