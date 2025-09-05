<?php
function sarai_chinwag_random_discovery() {
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }

    global $post;
    $current_post_id = $post->ID;
    
    // Check cache first - random discovery cached for 15 minutes
    $cache_key = "random_discovery_{$current_post_id}";
    $cached_content = wp_cache_get($cache_key, 'sarai_chinwag_related');
    
    if (false !== $cached_content) {
        echo $cached_content;
        return;
    }
    
    // Start output buffering to cache the result
    ob_start();

    $post_types = array('post');
    if (!sarai_chinwag_recipes_disabled()) {
        $post_types[] = 'recipe';
    }
    
    // Get 6 random posts using simple, fast query
    $args = array(
        'post_type' => $post_types,
        'posts_per_page' => 6,
        'post__not_in' => array($current_post_id),
        'orderby' => 'rand',
        'post_status' => 'publish'
    );
    
    $random_query = new WP_Query($args);
    
    if (!$random_query->have_posts()) {
        wp_reset_postdata();
        ob_end_clean();
        return;
    }

    // Display gallery discovery badges before "Keep Exploring" section
    sarai_chinwag_gallery_discovery_badges();

    echo '<aside class="random-discovery">';
    echo '<h2 class="widget-title">' . __('Keep Exploring', 'sarai-chinwag') . '</h2>';
    echo '<div class="post-grid discovery-grid">';
    
    while ($random_query->have_posts()) {
        $random_query->the_post();
        
        echo '<article id="discovery-post-' . get_the_ID() . '" class="discovery-post">';
        
        // Thumbnail
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            the_post_thumbnail('grid-thumb', array('itemprop' => 'image'));
            echo '</a>';
            echo '</div>';
        }
        
        // Title
        echo '<h3 class="entry-title">';
        echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . get_the_title() . '</a>';
        echo '</h3>';
        
        echo '</article>';
    }
    
    wp_reset_postdata();
    echo '</div>'; // End of discovery-grid
    echo '</aside>';
    
    // Cache the output for 15 minutes
    $output = ob_get_contents();
    wp_cache_set($cache_key, $output, 'sarai_chinwag_related', 15 * MINUTE_IN_SECONDS);
    
    ob_end_flush();
}

add_action('after_post_main', 'sarai_chinwag_random_discovery');
