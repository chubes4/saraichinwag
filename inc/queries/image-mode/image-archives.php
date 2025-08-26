<?php
/**
 * Image Archives System
 * 
 * Handles category/tag image gallery pages accessible via:
 * /category/category-name/images/
 * /tag/tag-name/images/
 *
 * @package Sarai_Chinwag
 */

/**
 * Handle image gallery template loading
 */
function sarai_chinwag_load_image_gallery_template() {
    // Check if this is an image gallery request using WordPress native endpoint
    // Must check both query var exists AND URL contains /images to avoid false positives
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_images_endpoint = $has_images_var && $url_has_images;
    
    if ($is_images_endpoint && ((is_category() || is_tag()) || is_home() || is_search())) {
        // Load our custom template for all image gallery types
        sarai_chinwag_display_image_gallery();
        exit;
    }
}
add_action('template_redirect', 'sarai_chinwag_load_image_gallery_template');

/**
 * Display the image gallery
 */
function sarai_chinwag_display_image_gallery() {
    get_header();
    
    echo '<main id="primary" class="site-main">';
    
    // Determine header title and description for gallery pages
    $header_title = '';
    $header_description = '';
    if (is_category()) {
        $term = get_queried_object();
        $term_type = 'category';
        $header_title = sprintf(__('%s Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $term->name);
        $header_description = sprintf(__('Discover premium %s digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), strtolower($term->name));
    } elseif (is_tag()) {
        $term = get_queried_object();
        $term_type = 'post_tag';
        $header_title = sprintf(__('%s Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $term->name);
        $header_description = sprintf(__('Discover premium %s digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), strtolower($term->name));
    } elseif (is_search()) {
        $search_query = get_search_query();
        $term = null;
        $term_type = 'search';
        $header_title = sprintf(__('"%s" Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $search_query);
        $header_description = sprintf(__('Discover premium digital wallpapers and AI artwork matching "%s". Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), $search_query);
    } elseif (is_home() && (strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false)) {
        // Homepage image gallery - all site images
        $term = null;
        $term_type = 'all';
        $header_title = __('Digital Wallpapers & High-Res Artwork', 'sarai-chinwag');
        $header_description = __('Discover premium digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag');
    } else {
        echo '<p>' . esc_html__('Invalid gallery request.', 'sarai-chinwag') . '</p>';
        echo '</main>';
        get_footer();
        return;
    }
    
    // Header above filter bar
    if (!empty($header_title)) {
        echo '<header class="image-gallery-header">';
        echo '<h1>' . esc_html($header_title) . '</h1>';
        echo '<p>' . esc_html($header_description) . '</p>';
        echo '</header>';
    }

    // Include filter bar before gallery content
    do_action('before_post_grid');
    
    // Load the gallery content
    get_template_part('template-parts/content', 'image-gallery');
    
    echo '</main>';
    
    get_footer();
}


/**
 * Get all images from posts in current term
 */
function sarai_chinwag_get_term_images($term_id, $term_type, $limit = 30) {
    // Handle site-wide image gallery
    if ($term_type === 'all') {
        if (!function_exists('sarai_chinwag_get_all_site_images')) {
            return array();
        }
        return sarai_chinwag_get_all_site_images($limit);
    }
    
    // Handle search image gallery
    if ($term_type === 'search') {
        if (!function_exists('sarai_chinwag_extract_images_from_search')) {
            return array();
        }
        return sarai_chinwag_extract_images_from_search($term_id, $limit);
    }
    
    // Get the extractor function for term-specific galleries
    if (!function_exists('sarai_chinwag_extract_images_from_term')) {
        return array();
    }
    
    return sarai_chinwag_extract_images_from_term($term_id, $term_type, $limit);
}

/**
 * Activate SMI plugin on image gallery pages
 */
function sarai_chinwag_activate_smi_on_gallery($should_load) {
    // Check if this is an actual image gallery request, not just an archive with endpoint available
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_images_endpoint = $has_images_var && $url_has_images;
    
    if ($is_images_endpoint && ((is_category() || is_tag()) || is_home() || is_search())) {
        return true;
    }
    return $should_load;
}
add_filter('smi_load_assets', 'sarai_chinwag_activate_smi_on_gallery');