<?php
/**
 * Handle recipe rating via AJAX.
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

    $rating_update = update_post_meta($post_id, 'rating_value', $new_rating_value);
    $review_update = update_post_meta($post_id, 'review_count', $new_review_count);

    if ($rating_update === false || $review_update === false) {
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
 * Enqueue scripts and localize AJAX URL.
 */
function sarai_chinwag_enqueue_rating_script() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }
    
    if (is_singular('recipe')) {
        // Use dynamic versioning for cache busting
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
