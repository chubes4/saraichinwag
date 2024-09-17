<?php
/**
 * Handle recipe rating via AJAX.
 */
function extra_chill_rate_recipe() {
    // Check for nonce security
    if (!check_ajax_referer('rate_recipe_nonce', 'rate_recipe_nonce', false)) {
        error_log('Nonce verification failed.');
        wp_send_json_error(['message' => 'Nonce verification failed.']);
        wp_die();
    }

    error_log('Nonce verified.');

    // Log entire request payload for debugging
    error_log('Request Payload: ' . print_r($_POST, true));

    $post_id = intval($_POST['post_id']);
    $rating = floatval($_POST['rating']);

    if (!$post_id || !$rating) {
        error_log('Invalid post ID or rating value.');
        wp_send_json_error(['message' => 'Invalid post ID or rating value.']);
        wp_die();
    }

    error_log('Post ID: ' . $post_id . ', Rating: ' . $rating);

    $rating_value = get_post_meta($post_id, 'rating_value', true);
    $review_count = get_post_meta($post_id, 'review_count', true);

    error_log('Current rating value: ' . $rating_value . ', Current review count: ' . $review_count);

    $rating_value = $rating_value ? floatval($rating_value) : 0;
    $review_count = $review_count ? intval($review_count) : 0;

    $new_rating_value = (($rating_value * $review_count) + $rating) / ($review_count + 1);
    $new_review_count = $review_count + 1;

    error_log('New rating value: ' . $new_rating_value . ', New review count: ' . $new_review_count);

    $rating_update = update_post_meta($post_id, 'rating_value', $new_rating_value);
    $review_update = update_post_meta($post_id, 'review_count', $new_review_count);

    if ($rating_update === false || $review_update === false) {
        error_log('Failed to update rating meta for post ID: ' . $post_id);
        wp_send_json_error(['message' => 'Failed to update rating meta.']);
    }

    error_log('Rating meta updated successfully.');

    wp_send_json_success([
        'averageRating' => round($new_rating_value, 2),
        'reviewCount' => $new_review_count
    ]);
}

add_action('wp_ajax_rate_recipe', 'extra_chill_rate_recipe');
add_action('wp_ajax_nopriv_rate_recipe', 'extra_chill_rate_recipe');

/**
 * Enqueue scripts and localize AJAX URL.
 */
function extra_chill_enqueue_rating_script() {
    if (is_singular('recipe')) {
        wp_enqueue_script('rating-js', get_template_directory_uri() . '/js/rating.js', array(), '1.0.0', true);
        wp_localize_script('rating-js', 'rating_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('rate_recipe_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'extra_chill_enqueue_rating_script');
