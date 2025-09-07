<?php
/**
 * Template part for displaying recipes in the loop and single views
 *
 * @package Sarai_Chinwag
 */
?>

<header class="entry-header">
    <?php
    if ( is_singular() ) {
        sarai_chinwag_post_badges();
    }
    
    if ( !is_singular() && has_post_thumbnail() ) {
        echo '<div class="post-thumbnail">';
        echo '<a href="' . esc_url( get_permalink() ) . '">';
        the_post_thumbnail('grid-thumb', array('itemprop' => 'image'));
        echo '</a>';
        echo '</div>';
    }

    if ( is_singular() ) {
        the_title( '<h1 class="entry-title p-name" itemprop="name">', '</h1>' );
    } else {
        the_title( '<h2 class="entry-title p-name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" itemprop="name">', '</a></h2>' );
    }

    if ( is_singular() ) {
        $rating_value = get_post_meta(get_the_ID(), 'rating_value', true);
        $review_count = get_post_meta(get_the_ID(), 'review_count', true);
        $average_rating = $review_count > 0 ? round($rating_value, 2) : 0;
        $rating_display = $review_count > 0 ? "($review_count reviews)" : "(0 reviews)";
    ?>
    
        <div class="recipe-rating">
            <div class="stars">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $average_rating) {
                        echo '<span class="star">&#9733;</span>';
                    } else {
                        echo '<span class="star">&#9734;</span>';
                    }
                }
                ?>
            </div>
            <span class="rating-text"><?php echo esc_html($rating_display); ?></span>
        </div>
    
    <?php } ?>
</header><!-- .entry-header -->

<div class="entry-content" lang="<?php echo get_locale() === 'en_US' ? 'en' : substr(get_locale(), 0, 2); ?>">
    <?php
    if ( is_singular() ) {
        the_content();
    } else {
    }
    ?>
</div><!-- .entry-content -->
