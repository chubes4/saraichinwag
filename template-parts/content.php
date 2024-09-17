<?php
/**
 * Template part for displaying posts in a loop
 *
 * @package Sarai_Chinwag
 */
?>

<header class="entry-header">
    <?php
    if ( !is_singular() && has_post_thumbnail() ) {
        echo '<div class="post-thumbnail">';
        echo '<a href="' . esc_url( get_permalink() ) . '">';
        the_post_thumbnail('medium', array('itemprop' => 'image'));
        echo '</a>';
        echo '</div>';
    }

    if ( is_singular() ) {
        the_title( '<h1 class="entry-title">', '</h1>' );
    } else {
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
    }
    ?>
</header><!-- .entry-header -->

<div class="entry-content">
    <?php
    if ( is_singular() ) {
        the_content();
    } else {
      //  the_excerpt();
    }
    ?>
</div><!-- .entry-content -->

<footer class="entry-footer">
    <?php
    // Optionally add post metadata here
    ?>
</footer><!-- .entry-footer -->
