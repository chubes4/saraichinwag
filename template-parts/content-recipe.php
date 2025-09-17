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
        $review_count = $review_count ? intval($review_count) : 0;
        $reviews_text = $review_count == 1 ? "review" : "reviews";
        $rating_display = $review_count > 0 ? "(" . round(floatval($rating_value), 2) . "/5 based on " . $review_count . " " . $reviews_text . ")" : "(Not yet rated)";
    ?>
    
        <div id="rating-widget" data-post-id="<?php the_ID(); ?>" class="recipe-rating">
            <h3>Rate this Recipe:</h3>
            <?php wp_nonce_field('rate_recipe_nonce', 'rate_recipe_nonce'); ?>
            <div class="stars">
                <span class="star" data-value="1">&#9734;</span>
                <span class="star" data-value="2">&#9734;</span>
                <span class="star" data-value="3">&#9734;</span>
                <span class="star" data-value="4">&#9734;</span>
                <span class="star" data-value="5">&#9734;</span>
            </div>
            <span id="average-rating"><?php echo esc_html($rating_display); ?></span>
            <span id="user-rating"></span>
        </div>
    
    <?php } ?>
</header><!-- .entry-header -->

<div class="entry-content" lang="<?php echo get_locale() === 'en_US' ? 'en' : substr(get_locale(), 0, 2); ?>">
    <?php
    if ( is_singular() ) {
        sarai_chinwag_display_featured_image_as_block();

        the_content();
    } else {
    }
    ?>
</div><!-- .entry-content -->
