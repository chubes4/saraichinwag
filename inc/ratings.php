<?php
/**
 * Recipe rating system with AJAX functionality
 * 
 * @package Sarai_Chinwag
 * @since 1.0.0
 */
/**
 * Handle recipe rating submissions via AJAX
 * 
 * @since 1.0.0
 */
function sarai_chinwag_rate_recipe() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        wp_send_json_error(['message' => 'Recipe functionality is disabled.']);
        wp_die();
    }
    // Check for nonce security
    if (!check_ajax_referer('rate_recipe_nonce', 'rate_recipe_nonce', false)) {
        wp_send_json_error(['message' => 'Nonce verification failed.']);
        wp_die();
    }

    $post_id = intval($_POST['post_id']);
    $rating = floatval($_POST['rating']);

    // Validate post ID
    if (!$post_id || get_post_status($post_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid post ID.']);
        wp_die();
    }
    
    // Validate rating range (1-5)
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Rating must be between 1 and 5.']);
        wp_die();
    }

    $rating_value = get_post_meta($post_id, 'rating_value', true);
    $review_count = get_post_meta($post_id, 'review_count', true);

    $rating_value = $rating_value ? floatval($rating_value) : 0;
    $review_count = $review_count ? intval($review_count) : 0;

    $new_rating_value = (($rating_value * $review_count) + $rating) / ($review_count + 1);
    $new_review_count = $review_count + 1;

    update_post_meta($post_id, 'rating_value', $new_rating_value);
    update_post_meta($post_id, 'review_count', $new_review_count);

    // Verify the meta was actually updated by reading it back
    $verify_rating = get_post_meta($post_id, 'rating_value', true);
    $verify_count = get_post_meta($post_id, 'review_count', true);
    
    if (floatval($verify_rating) != $new_rating_value || intval($verify_count) != $new_review_count) {
        wp_send_json_error(['message' => 'Failed to update rating meta.']);
        wp_die();
    }

    wp_send_json_success([
        'averageRating' => round($new_rating_value, 2),
        'reviewCount' => $new_review_count
    ]);
}

add_action('wp_ajax_rate_recipe', 'sarai_chinwag_rate_recipe');
add_action('wp_ajax_nopriv_rate_recipe', 'sarai_chinwag_rate_recipe');

/**
 * Enqueue rating script with AJAX localization
 * 
 * @since 1.0.0
 */
function sarai_chinwag_enqueue_rating_script() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }
    
    if (is_singular('recipe')) {
        $script_version = filemtime(get_template_directory() . '/js/rating.js');
        wp_enqueue_script('rating-js', get_template_directory_uri() . '/js/rating.js', array('wp-i18n'), $script_version, true);
        wp_set_script_translations('rating-js', 'sarai-chinwag');
        wp_localize_script('rating-js', 'rating_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rate_recipe_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_rating_script');

/**
 * Set default 5-star rating for new recipe posts
 * 
 * @param int $post_id The post ID
 * @param WP_Post $post The post object
 * @param bool $update Whether this is an existing post being updated or not
 * @since 1.0.0
 */
function sarai_chinwag_set_default_recipe_rating($post_id, $post, $update) {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }
    
    // Only apply to recipe post type
    if ($post->post_type !== 'recipe') {
        return;
    }
    
    // Only apply to published posts
    if ($post->post_status !== 'publish') {
        return;
    }
    
    // Check if rating already exists (skip if already has rating)
    $existing_rating = get_post_meta($post_id, 'rating_value', true);
    if ($existing_rating) {
        return;
    }
    
    // Set default 5-star rating with 1 review count
    update_post_meta($post_id, 'rating_value', 5.0);
    update_post_meta($post_id, 'review_count', 1);
}

add_action('save_post', 'sarai_chinwag_set_default_recipe_rating', 10, 3);
add_action('publish_recipe', 'sarai_chinwag_set_default_recipe_rating', 10, 3);

/**
 * Manually set default ratings for existing recipes without ratings
 * Useful for applying defaults to recipes created before this feature
 * 
 * @since 1.0.0
 */
function sarai_chinwag_apply_default_ratings_to_existing() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }
    
    $recipes = get_posts([
        'post_type' => 'recipe',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => [
            [
                'key' => 'rating_value',
                'compare' => 'NOT EXISTS'
            ]
        ]
    ]);
    
    foreach ($recipes as $recipe) {
        update_post_meta($recipe->ID, 'rating_value', 5.0);
        update_post_meta($recipe->ID, 'review_count', 1);
    }
    
    return count($recipes);
}
