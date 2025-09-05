<?php
/**
 * Filter bar system for home and archive pages
 * Provides sorting options and post type filtering
 */

/**
 * Display the filter bar interface
 * Shows sort options and post type filters
 */
function sarai_chinwag_display_filter_bar() {
    // Only show on home, archive, and search pages, or image galleries using native endpoint
    // Must check both query var exists AND URL contains /images to avoid false positives on normal archives
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_image_gallery = $has_images_var && $url_has_images;
    
    if (!is_home() && !is_archive() && !is_search() && !$is_image_gallery) {
        return;
    }
    
    // Load the filter bar template part
    get_template_part('template-parts/filter', 'bar');
}

/**
 * Check if current page has both posts and recipes for type filtering
 * Reuses existing function but adds context for filter bar
 */
function sarai_chinwag_show_type_filters() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return false;
    }
    
    // Use existing function to check for both post types
    return sarai_chinwag_has_both_posts_and_recipes();
}

// Hook the filter bar to display before post grid
add_action('before_post_grid', 'sarai_chinwag_display_filter_bar');
