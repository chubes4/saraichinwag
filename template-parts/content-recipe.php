<?php
/**
 * Template part for displaying recipes in the loop and single views
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
        the_title( '<h1 class="entry-title p-name" itemprop="name">', '</h1>' );
    } else {
        the_title( '<h2 class="entry-title p-name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" itemprop="name">', '</a></h2>' );
    }

    // Display rating and review count only on single recipe page
    if ( is_singular() ) {
        $rating_value = get_post_meta(get_the_ID(), 'rating_value', true);
        $review_count = get_post_meta(get_the_ID(), 'review_count', true);
        $average_rating = $review_count > 0 ? round($rating_value, 2) : 0;
        $rating_display = $review_count > 0 ? "($review_count reviews)" : "(0 reviews)";
    ?>
    
        <div class="recipe-rating">
            <div class="stars">
                <?php
                // Display stars based on average rating
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $average_rating) {
                        echo '<span class="star">&#9733;</span>'; // filled star
                    } else {
                        echo '<span class="star">&#9734;</span>'; // empty star
                    }
                }
                ?>
            </div>
            <span class="rating-text"><?php echo esc_html($rating_display); ?></span>
        </div>
    
    <?php } ?>
</header><!-- .entry-header -->

<div class="entry-content">
    <?php
    if ( is_singular() ) {
        the_content();
    } else {
        // the_excerpt();
    }
    ?>
</div><!-- .entry-content -->
