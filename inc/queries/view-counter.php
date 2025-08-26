<?php
/**
 * Simple view counter system for tracking post popularity
 * Stores view counts in post meta for efficient querying
 */

/**
 * Track a view for the current post
 * Only increments for singular posts and recipes, once per page load
 */
function sarai_chinwag_track_post_view() {
    // Only track views on singular posts and recipes
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }
    
    // Don't track admin views
    if (is_admin()) {
        return;
    }
    
    global $post;
    if (!$post) {
        return;
    }
    
    $post_id = $post->ID;
    
    // Get current view count
    $views = get_post_meta($post_id, '_post_views', true);
    $views = $views ? intval($views) : 0;
    
    // Increment and update
    $new_views = $views + 1;
    update_post_meta($post_id, '_post_views', $new_views);
}

/**
 * Get view count for a specific post
 * 
 * @param int $post_id Post ID to get views for
 * @return int Number of views
 */
function sarai_chinwag_get_post_views($post_id) {
    $views = get_post_meta($post_id, '_post_views', true);
    return $views ? intval($views) : 0;
}

/**
 * Display formatted view count
 * 
 * @param int $post_id Post ID to display views for
 * @return string Formatted view count
 */
function sarai_chinwag_display_post_views($post_id) {
    $views = sarai_chinwag_get_post_views($post_id);
    
    if ($views == 0) {
        return __('No views yet', 'sarai-chinwag');
    } elseif ($views == 1) {
        return __('1 view', 'sarai-chinwag');
    } else {
        return sprintf(__('%s views', 'sarai-chinwag'), number_format($views));
    }
}

/**
 * Get most popular posts by view count
 * 
 * @param array $args WP_Query arguments to merge with view count query
 * @return WP_Query Query object with posts sorted by popularity
 */
function sarai_chinwag_get_popular_posts($args = array()) {
    $default_args = array(
        'meta_key' => '_post_views',
        'orderby' => 'meta_value_num date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_post_views',
                'value' => 0,
                'compare' => '>',
                'type' => 'NUMERIC'
            )
        )
    );
    
    $query_args = array_merge($default_args, $args);
    return new WP_Query($query_args);
}

// Hook the view tracking to wp_head on singular pages
add_action('wp_head', 'sarai_chinwag_track_post_view');

/**
 * Initialize view count for posts that don't have it yet
 * This helps with sorting queries (posts without meta will have 0 views)
 */
function sarai_chinwag_initialize_post_views() {
    // Only run occasionally to avoid performance impact
    if (wp_cache_get('views_initialized', 'sarai_chinwag_views')) {
        return;
    }
    
    global $wpdb;
    
    // Find posts without view count meta
    $posts_without_views = $wpdb->get_results("
        SELECT p.ID 
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_post_views'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'recipe')
        AND pm.meta_id IS NULL
        LIMIT 100
    ");
    
    // Initialize with 0 views
    foreach ($posts_without_views as $post) {
        update_post_meta($post->ID, '_post_views', 0);
    }
    
    // Set cache to prevent this from running too often
    wp_cache_set('views_initialized', true, 'sarai_chinwag_views', DAY_IN_SECONDS);
}
add_action('init', 'sarai_chinwag_initialize_post_views');