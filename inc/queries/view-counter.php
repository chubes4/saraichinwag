<?php
/**
 * View Counter System
 *
 * Tracks post views for popularity sorting
 *
 * @package Sarai_Chinwag
 * @since 2.0
 */

/**
 * Track view for current post on singular pages
 *
 * @since 2.0
 */
function sarai_chinwag_track_post_view() {
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }
    
    if (is_admin()) {
        return;
    }
    
    global $post;
    if (!$post) {
        return;
    }
    
    $post_id = $post->ID;
    
    $views = get_post_meta($post_id, '_post_views', true);
    $views = $views ? intval($views) : 0;
    
    $new_views = $views + 1;
    update_post_meta($post_id, '_post_views', $new_views);
}

/**
 * Get view count for post
 *
 * @param int $post_id Post ID
 * @return int View count
 * @since 2.0
 */
function sarai_chinwag_get_post_views($post_id) {
    $views = get_post_meta($post_id, '_post_views', true);
    return $views ? intval($views) : 0;
}

/**
 * Display formatted view count
 *
 * @param int $post_id Post ID
 * @return string Formatted count
 * @since 2.0
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
 * @param array $args WP_Query arguments
 * @return WP_Query Query object
 * @since 2.0
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

add_action('wp_head', 'sarai_chinwag_track_post_view');

/**
 * Initialize view count for posts without meta
 *
 * @since 2.1
 */
function sarai_chinwag_initialize_post_views() {
    if (wp_cache_get('views_initialized', 'sarai_chinwag_views')) {
        return;
    }
    
    global $wpdb;
    
    $posts_without_views = $wpdb->get_results("
        SELECT p.ID 
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_post_views'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'recipe')
        AND pm.meta_id IS NULL
        LIMIT 100
    ");
    
    foreach ($posts_without_views as $post) {
        update_post_meta($post->ID, '_post_views', 0);
    }
    
    wp_cache_set('views_initialized', true, 'sarai_chinwag_views', DAY_IN_SECONDS);
}
add_action('init', 'sarai_chinwag_initialize_post_views');