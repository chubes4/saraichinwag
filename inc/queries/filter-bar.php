<?php
/**
 * Filter bar system for home and archive pages
 */

function sarai_chinwag_display_filter_bar() {
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_image_gallery = $has_images_var && $url_has_images;
    
    if (!is_home() && !is_archive() && !is_search() && !$is_image_gallery) {
        return;
    }
    
    get_template_part('template-parts/filter', 'bar');
}

/**
 * Check if current page has both posts and recipes for type filtering
 */
function sarai_chinwag_show_type_filters() {
    if (sarai_chinwag_recipes_disabled()) {
        return false;
    }
    
    return sarai_chinwag_has_both_posts_and_recipes();
}

add_action('before_post_grid', 'sarai_chinwag_display_filter_bar');
