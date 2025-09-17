<?php
/**
 * Recipe rating system with default 5-star assignment and AJAX functionality
 *
 * @package Sarai_Chinwag
 * @version 2.2
 * @since 1.0.0
 */

/**
 * Handle recipe rating submissions via AJAX
 *
 * Processes user ratings (1-5) and calculates weighted averages.
 * Includes nonce verification and input validation.
 *
 * @since 1.0.0
 */
function sarai_chinwag_rate_recipe() {
    if (sarai_chinwag_recipes_disabled()) {
        wp_send_json_error(['message' => 'Recipe functionality is disabled.']);
        wp_die();
    }

    if (!check_ajax_referer('rate_recipe_nonce', 'rate_recipe_nonce', false)) {
        wp_send_json_error(['message' => 'Nonce verification failed.']);
        wp_die();
    }

    $post_id = intval($_POST['post_id']);
    $rating = floatval($_POST['rating']);

    if (!$post_id || get_post_status($post_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid post ID.']);
        wp_die();
    }

    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Rating must be between 1 and 5.']);
        wp_die();
    }

    $rating_value = get_post_meta($post_id, 'rating_value', true);
    $review_count = get_post_meta($post_id, 'review_count', true);

    $rating_value = $rating_value ? floatval($rating_value) : 0;
    $review_count = $review_count ? intval($review_count) : 0;

    // Calculate weighted average: ((current_avg * count) + new_rating) / (count + 1)
    $new_rating_value = (($rating_value * $review_count) + $rating) / ($review_count + 1);
    $new_review_count = $review_count + 1;

    update_post_meta($post_id, 'rating_value', $new_rating_value);
    update_post_meta($post_id, 'review_count', $new_review_count);

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
 * Enqueue rating script with AJAX localization on recipe pages
 *
 * @since 1.0.0
 */
function sarai_chinwag_enqueue_rating_script() {
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
 * Assign default 5-star rating to newly published recipes
 *
 * Only applies to recipe post type on first publish, skips existing ratings.
 * Sets rating_value to 5.0 and review_count to 1.
 *
 * @param int $post_id The post ID
 * @param WP_Post $post The post object
 * @param bool $update Whether this is an existing post being updated
 * @since 1.0.0
 */
function sarai_chinwag_set_default_recipe_rating($post_id, $post, $update) {
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }

    if ($post->post_type !== 'recipe') {
        return;
    }

    if ($post->post_status !== 'publish') {
        return;
    }

    $existing_rating = get_post_meta($post_id, 'rating_value', true);
    if ($existing_rating) {
        return;
    }

    update_post_meta($post_id, 'rating_value', 5.0);
    update_post_meta($post_id, 'review_count', 1);
}

add_action('save_post', 'sarai_chinwag_set_default_recipe_rating', 10, 3);
add_action('publish_recipe', 'sarai_chinwag_set_default_recipe_rating', 10, 3);

/**
 * Apply default 5-star ratings to existing recipes without ratings
 *
 * Bulk operation for recipes created before default rating feature.
 * Uses meta_query to target only recipes missing rating_value.
 *
 * @return int Number of recipes updated
 * @since 1.0.0
 */
function sarai_chinwag_apply_default_ratings_to_existing() {
    if (sarai_chinwag_recipes_disabled()) {
        return 0;
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
