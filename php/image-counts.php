<?php 

// Saves image counts as post meta on save
function update_image_count_on_save($post_id) {
    // Check if this is a valid save operation
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Get the post content
    $post = get_post($post_id);
    if ($post->post_type !== 'post') {
        return; // Only for standard posts
    }

    // Count the number of wp-block-image occurrences in post content
    $content = $post->post_content;
    $image_count = substr_count($content, 'wp-block-image');

    // Update post meta with the image count
    update_post_meta($post_id, '_image_count', $image_count);
}
add_action('save_post', 'update_image_count_on_save');

// Append image count to the post title on the frontend
function append_image_count_to_title($title, $post_id) {
    if (is_admin() || get_post_type($post_id) !== 'post') {
        return $title; // Skip in admin area or for non-post types
    }

    // Get the image count from post meta
    $image_count = get_post_meta($post_id, '_image_count', true);

    // Append the image count to the title only if it's 2 or more
    if (!empty($image_count) && $image_count >= 2) {
        $title .= ' (' . $image_count . ' Images)';
    }

    return $title;
}
// add_filter('the_title', 'append_image_count_to_title', 10, 2);

// Register a custom replacement variable for Yoast SEO
function register_imagecount_replacement() {
    wpseo_register_var_replacement(
        '%%imagecount%%', // The variable name
        'get_image_count_replacement', // The callback function
        'advanced', // Type: 'basic' or 'advanced'
        'The number of images in the post' // Description
    );
}
// add_action( 'wpseo_register_extra_replacements', 'register_imagecount_replacement' );

// Function to return the image count for the custom variable
function get_image_count_replacement() {
    if ( is_singular('post') ) {
        global $post;
        $image_count = get_post_meta( $post->ID, '_image_count', true );

        if ( !empty( $image_count ) && $image_count >= 2 ) {
            return ' (' . $image_count . ' Images)';
        }
    }
    return '';
}
