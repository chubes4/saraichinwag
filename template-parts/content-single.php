<?php
/**
 * Template part for displaying single posts
 *
 * @package Sarai_Chinwag
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
