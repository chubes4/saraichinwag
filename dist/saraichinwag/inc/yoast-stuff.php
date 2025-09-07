<?php
/**
 * Yoast SEO integration and sitemap enhancements
 * 
 * Provides duplicate image filtering for Yoast sitemaps, custom meta titles
 * and descriptions for image gallery routes, and integration with the
 * image mode rewrite system for SEO optimization.
 * 
 * @package Sarai_Chinwag
 * @since 1.0.0
 */

/**
 * Remove duplicate images from Yoast sitemap
 *
 * @param array  $images       Array of images to include in sitemap.
 * @param string $post_id      Post ID.
 *
 * @return array Modified array of images.
 */
function filter_yoast_sitemap_images($images, $post_id) {
    // Get the URL of the featured image.
    $featured_image_url = get_the_post_thumbnail_url($post_id);

    // If there's no featured image, return the original images array.
    if (!$featured_image_url) {
        return $images;
    }

    // Get the post content.
    $post_content = get_post_field('post_content', $post_id);

    // Check if the featured image is already in the post content.
    if (strpos($post_content, $featured_image_url) !== false) {
        // If so, remove the featured image from the images array.
        foreach ($images as $key => $image) {
            if ($image['src'] === $featured_image_url) {
                unset($images[$key]);
                break; // Since there's only one featured image, we can break the loop early.
            }
        }
    }

    return $images;
}
add_filter('wpseo_sitemap_urlimages', 'filter_yoast_sitemap_images', 10, 2);

/**
 * Compute the meta title for image gallery routes to match the on-page H1
 *
 * Routes handled by image-mode rewrite rules:
 * - /images/ (site-wide gallery)
 * - /category/{slug}/images/
 * - /tag/{slug}/images/
 * - /?s={query}/images/ (search gallery)
 *
 * @return string|false Title to use, or false when not on an image gallery route
 */
function sarai_chinwag_image_gallery_meta_title() {
    // Only affect our image gallery requests using native endpoint
    // Must check both query var exists AND URL contains /images to avoid false positives on normal archives
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_images_endpoint = $has_images_var && $url_has_images;
    
    if (!$is_images_endpoint || !(is_category() || is_tag() || is_home() || is_search())) {
        return false;
    }

    // Site-wide images page (/images)
    if (is_home() && $url_has_images) {
        return __('Digital Wallpapers & High-Res Artwork', 'sarai-chinwag');
    }

    // Search gallery (/search/{query}/images or /?s={query}/images)
    if (is_search()) {
        $search_query = get_search_query();
        return sprintf(__('"%s" Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $search_query);
    }

    // Category gallery (/category/{slug}/images)
    if (is_category()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('%s Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $term->name);
        }
    }

    // Tag gallery (/tag/{slug}/images)
    if (is_tag()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('%s Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $term->name);
        }
    }

    return false;
}

/**
 * Compute the meta description for image gallery routes
 *
 * Routes handled by image-mode rewrite rules:
 * - /images/ (site-wide gallery)
 * - /category/{slug}/images/
 * - /tag/{slug}/images/
 * - /?s={query}/images/ (search gallery)
 *
 * @return string|false Description to use, or false when not on an image gallery route
 */
function sarai_chinwag_image_gallery_meta_description() {
    // Only affect our image gallery requests using native endpoint
    // Must check both query var exists AND URL contains /images to avoid false positives on normal archives
    $has_images_var = get_query_var('images') !== false;
    $url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
    $is_images_endpoint = $has_images_var && $url_has_images;
    
    if (!$is_images_endpoint || !(is_category() || is_tag() || is_home() || is_search())) {
        return false;
    }

    // Site-wide images page (/images)
    if (is_home() && $url_has_images) {
        return __('Discover premium digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag');
    }

    // Search gallery (/search/{query}/images or /?s={query}/images)
    if (is_search()) {
        $search_query = get_search_query();
        return sprintf(__('Discover premium digital wallpapers and AI artwork matching "%s". Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), $search_query);
    }

    // Category gallery (/category/{slug}/images)
    if (is_category()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('Discover premium %s digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), strtolower($term->name));
        }
    }

    // Tag gallery (/tag/{slug}/images)
    if (is_tag()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('Discover premium %s digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), strtolower($term->name));
        }
    }

    return false;
}

/**
 * Override the core document <title> parts on image gallery routes
 * This ensures the browser title matches the H1, even without Yoast.
 */
function sarai_chinwag_filter_document_title_for_images($parts) {
    $title = sarai_chinwag_image_gallery_meta_title();
    if ($title) {
        $parts['title'] = $title; // Keep site name/sep handling intact
    }
    return $parts;
}
add_filter('document_title_parts', 'sarai_chinwag_filter_document_title_for_images', 99);

/**
 * Yoast SEO: override SEO title and social titles for image gallery routes
 */
function sarai_chinwag_filter_wpseo_titles_for_images($current) {
    $title = sarai_chinwag_image_gallery_meta_title();
    return $title ? $title : $current;
}
add_filter('wpseo_title', 'sarai_chinwag_filter_wpseo_titles_for_images', 99);
add_filter('wpseo_opengraph_title', 'sarai_chinwag_filter_wpseo_titles_for_images', 99);
add_filter('wpseo_twitter_title', 'sarai_chinwag_filter_wpseo_titles_for_images', 99);

/**
 * Yoast SEO: override meta descriptions and social descriptions for image gallery routes
 */
function sarai_chinwag_filter_wpseo_metadesc_for_images($current) {
    $description = sarai_chinwag_image_gallery_meta_description();
    return $description ? $description : $current;
}
add_filter('wpseo_metadesc', 'sarai_chinwag_filter_wpseo_metadesc_for_images', 99);
add_filter('wpseo_opengraph_desc', 'sarai_chinwag_filter_wpseo_metadesc_for_images', 99);
add_filter('wpseo_twitter_description', 'sarai_chinwag_filter_wpseo_metadesc_for_images', 99);

/**
 * Add custom image gallery URLs to Yoast sitemap index
 */
function sarai_chinwag_add_image_galleries_to_sitemap($sitemap_index) {
    // Add site-wide image gallery
    $sitemap_index .= '<sitemap><loc>' . home_url('/images/') . '</loc></sitemap>' . "\n";
    
    // Get all public categories with posts
    $categories = get_categories(array('hide_empty' => true));
    foreach ($categories as $category) {
        $category_images_url = trailingslashit(get_category_link($category->term_id)) . 'images/';
        $sitemap_index .= '<sitemap><loc>' . esc_url($category_images_url) . '</loc></sitemap>' . "\n";
    }
    
    // Get all public tags with posts
    $tags = get_tags(array('hide_empty' => true));
    foreach ($tags as $tag) {
        $tag_images_url = trailingslashit(get_tag_link($tag->term_id)) . 'images/';
        $sitemap_index .= '<sitemap><loc>' . esc_url($tag_images_url) . '</loc></sitemap>' . "\n";
    }
    
    return $sitemap_index;
}
add_filter('wpseo_sitemap_index', 'sarai_chinwag_add_image_galleries_to_sitemap');
