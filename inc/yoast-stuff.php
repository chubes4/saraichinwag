<?php
/**
 * Image Gallery SEO Meta
 *
 * Provides custom meta titles and descriptions for image gallery routes.
 * Integrates with Lean SEO plugin via filters.
 *
 * @package Sarai_Chinwag
 * @since 1.0.0
 */

// Hook into Lean SEO for custom descriptions on image gallery routes
add_filter('lean_seo_custom_description', 'sarai_chinwag_lean_seo_image_description');
function sarai_chinwag_lean_seo_image_description($desc) {
    $gallery_desc = sarai_chinwag_image_gallery_meta_description();
    return $gallery_desc ? $gallery_desc : $desc;
}

// Add image gallery URLs to Lean SEO sitemap
add_action('lean_seo_sitemap_index', 'sarai_chinwag_lean_seo_images_sitemap');
function sarai_chinwag_lean_seo_images_sitemap() {
    echo '  <sitemap>' . "\n";
    echo '    <loc>' . home_url('/sitemap-images.xml') . '</loc>' . "\n";
    echo '  </sitemap>' . "\n";
}

// Register images sitemap route
add_action('init', 'sarai_chinwag_register_images_sitemap');
function sarai_chinwag_register_images_sitemap() {
    add_rewrite_rule('^sitemap-images\.xml$', 'index.php?sarai_images_sitemap=1', 'top');
}

add_filter('query_vars', 'sarai_chinwag_images_sitemap_query_vars');
function sarai_chinwag_images_sitemap_query_vars($vars) {
    $vars[] = 'sarai_images_sitemap';
    return $vars;
}

add_action('template_redirect', 'sarai_chinwag_handle_images_sitemap');
function sarai_chinwag_handle_images_sitemap() {
    if (!get_query_var('sarai_images_sitemap')) {
        return;
    }
    
    header('Content-Type: application/xml; charset=UTF-8');
    header('X-Robots-Tag: noindex, follow');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Main images page
    echo '  <url>' . "\n";
    echo '    <loc>' . home_url('/images/') . '</loc>' . "\n";
    echo '  </url>' . "\n";
    
    // Category image galleries
    $categories = get_categories(array('hide_empty' => true));
    foreach ($categories as $category) {
        echo '  <url>' . "\n";
        echo '    <loc>' . trailingslashit(get_category_link($category->term_id)) . 'images/</loc>' . "\n";
        echo '  </url>' . "\n";
    }
    
    echo '</urlset>';
    exit;
}

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

    // Search gallery
    if (is_search()) {
        $search_query = get_search_query();
        return sprintf(__('"%s" Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $search_query);
    }

    // Category gallery
    if (is_category()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('%s Digital Wallpapers & High-Res Artwork', 'sarai-chinwag'), $term->name);
        }
    }

    // Tag gallery
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
 * @return string|false Description to use, or false when not on an image gallery route
 */
function sarai_chinwag_image_gallery_meta_description() {
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

    // Search gallery
    if (is_search()) {
        $search_query = get_search_query();
        return sprintf(__('Discover premium digital wallpapers and AI artwork matching "%s". Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), $search_query);
    }

    // Category gallery
    if (is_category()) {
        $term = get_queried_object();
        if ($term && !is_wp_error($term)) {
            return sprintf(__('Discover premium %s digital wallpapers, AI artwork & high resolution backgrounds. Download stunning images for phone wallpapers, social media & wall art', 'sarai-chinwag'), strtolower($term->name));
        }
    }

    // Tag gallery
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
 */
function sarai_chinwag_filter_document_title_for_images($parts) {
    $title = sarai_chinwag_image_gallery_meta_title();
    if ($title) {
        $parts['title'] = $title;
    }
    return $parts;
}
add_filter('document_title_parts', 'sarai_chinwag_filter_document_title_for_images', 99);
