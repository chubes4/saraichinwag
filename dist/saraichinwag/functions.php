<?php
/**
 * Sarai Chinwag WordPress Theme
 * 
 * A versatile WordPress theme with dynamic Google Fonts integration, randomized content
 * discovery, and universal customization capabilities for both recipe sites and standard blogs.
 * 
 * @package Sarai_Chinwag
 * @author Chris Huber
 * @version 2.2
 * @link https://saraichinwag.com
 * @since 1.0.0
 */

/**
 * Theme setup
 */
function sarai_chinwag_setup() {
        load_theme_textdomain( 'sarai-chinwag', get_template_directory() . '/languages' );
        
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'menus' );
        add_theme_support( 'editor-styles' );
        add_editor_style( 'style.css' );
        add_editor_style( 'css/editor.css' );
        
        // Remove unused WordPress image sizes to optimize performance
        remove_image_size('thumbnail');
        remove_image_size('medium');
        remove_image_size('medium_large');
        
        // Custom image size optimized for post grid display
        add_image_size('grid-thumb', 450, 450, true);
        
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'sarai-chinwag' ),
            'footer'  => __( 'Footer Menu', 'sarai-chinwag' ),
        ) );
}
add_action( 'after_setup_theme', 'sarai_chinwag_setup' );

/**
 * Enqueue scripts and styles with dynamic versioning for cache busting
 */
function sarai_chinwag_scripts() {
    $style_version = filemtime( get_template_directory() . '/style.css' );
    wp_enqueue_style( 'sarai-chinwag-style', get_stylesheet_uri(), array('sarai-chinwag-root-css'), $style_version );

    $nav_version = filemtime( get_template_directory() . '/js/nav.js' );
    wp_enqueue_script( 'sarai-chinwag-nav', get_template_directory_uri() . '/js/nav.js', array(), $nav_version, true );

    $gallery_utils_version = filemtime( get_template_directory() . '/js/gallery-utils.js' );
    wp_enqueue_script( 'sarai-chinwag-gallery-utils', get_template_directory_uri() . '/js/gallery-utils.js', array(), $gallery_utils_version, true );

    $pinterest_version = filemtime( get_template_directory() . '/js/pinterest.js' );
    wp_enqueue_script( 'sarai-chinwag-pinterest', get_template_directory_uri() . '/js/pinterest.js', array(), $pinterest_version, true );
}
add_action( 'wp_enqueue_scripts', 'sarai_chinwag_scripts' );


/**
 * Add Pinterest save button data attribute to featured images
 */
function add_data_pin_url_to_featured_images($html, $post_id) {
    if (is_home() || is_archive() || is_search()) {
        $post_url = get_permalink($post_id);
        if (strpos($html, 'data-pin-url=') === false) {
            $html = str_replace('<img ', '<img data-pin-url="' . esc_url($post_url) . '" ', $html);
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'add_data_pin_url_to_featured_images', 10, 2);

// Include module files
require_once get_template_directory() . '/inc/admin/admin-notices.php';
require_once get_template_directory() . '/inc/admin/admin-settings.php';
require_once get_template_directory() . '/inc/admin/customizer.php';

require_once get_template_directory() . '/inc/recipes.php';
require_once get_template_directory() . '/inc/ratings.php';

$queries_dir = get_template_directory() . '/inc/queries';
require_once $queries_dir . '/view-counter.php';
require_once $queries_dir . '/filter-bar.php';
require_once $queries_dir . '/random-post.php';
require_once $queries_dir . '/random-queries.php';
require_once $queries_dir . '/related-posts.php';
require_once $queries_dir . '/sorting-archives.php';

require_once get_template_directory() . '/inc/bing-index-now.php';
require_once get_template_directory() . '/inc/yoast-stuff.php';

// Image gallery system
require_once $queries_dir . '/image-mode/image-extractor.php';
require_once $queries_dir . '/image-mode/image-archives.php';
require_once $queries_dir . '/image-mode/rewrite-rules.php';
require_once $queries_dir . '/image-mode/search-images.php';

/**
 * Check if recipe functionality is disabled
 *
 * @return bool True if recipes are disabled, false if enabled
 */
function sarai_chinwag_recipes_disabled() {
    $disabled = get_option('sarai_chinwag_disable_recipes', false);
    return apply_filters('sarai_chinwag_recipes_disabled', $disabled);
}


/**
 * Get cached random post ID for high-performance random post selection
 * Replaces expensive orderby => 'rand' queries with cached ID arrays
 *
 * @param string $post_type The post type to get random ID for
 * @return int|false Random post ID or false if none found
 */
function sarai_chinwag_get_cached_random_post_id($post_type = 'post') {
    $cache_key = "random_{$post_type}_ids";
    $random_ids = wp_cache_get($cache_key, 'sarai_chinwag_random');
    
    if (false === $random_ids || empty($random_ids)) {
        // Get limited post IDs for this post type (prevent memory issues)
        $all_posts = get_posts(array(
            'post_type' => $post_type,
            'fields' => 'ids',
            'numberposts' => 500, // Limit to prevent memory issues
            'post_status' => 'publish',
            'orderby' => 'rand' // Let MySQL handle randomization for large datasets
        ));
        
        if (empty($all_posts)) {
            return false;
        }
        
        // Already randomized by MySQL - no need to shuffle again
        
        // Cache for 1 hour - refreshes automatically
        wp_cache_set($cache_key, $all_posts, 'sarai_chinwag_random', HOUR_IN_SECONDS);
        $random_ids = $all_posts;
    }
    
    // Return first ID and rotate array for next request
    $post_id = array_shift($random_ids);
    wp_cache_set($cache_key, $random_ids, 'sarai_chinwag_random', HOUR_IN_SECONDS);
    
    return $post_id;
}

/**
 * Get cached random post IDs for main query randomization
 * Used by home/archive pages to replace expensive orderby => 'rand'
 *
 * @param array $post_types Array of post types to include
 * @param int $count Number of random posts needed
 * @return array Array of random post IDs
 */
function sarai_chinwag_get_cached_random_query_ids($post_types, $count = 10) {
    $cache_key = 'random_query_' . md5(serialize($post_types) . $count);
    $random_ids = wp_cache_get($cache_key, 'sarai_chinwag_random');
    
    if (false === $random_ids || empty($random_ids)) {
        // Get limited random post IDs for specified post types
        $all_posts = get_posts(array(
            'post_type' => $post_types,
            'fields' => 'ids',
            'numberposts' => min($count * 10, 500), // Get 10x what we need, max 500
            'post_status' => 'publish',
            'orderby' => 'rand' // Let MySQL handle randomization
        ));
        
        if (empty($all_posts)) {
            return array();
        }
        
        // Already randomized by MySQL
        $random_ids = $all_posts;
        
        // Cache for 30 minutes - shorter than individual post cache for more variety
        wp_cache_set($cache_key, $random_ids, 'sarai_chinwag_random', 30 * MINUTE_IN_SECONDS);
    }
    
    // Return the requested number of IDs
    return array_slice($random_ids, 0, $count);
}

/**
 * Clear performance caches when content changes
 */
function sarai_chinwag_clear_performance_caches($post_id = null) {
    wp_cache_flush_group('sarai_chinwag_random');
    wp_cache_flush_group('sarai_chinwag_related');
}
add_action('save_post', 'sarai_chinwag_clear_performance_caches');
add_action('delete_post', 'sarai_chinwag_clear_performance_caches');
add_action('wp_trash_post', 'sarai_chinwag_clear_performance_caches');
add_action('untrash_post', 'sarai_chinwag_clear_performance_caches');



/**
 * Register widget area
 */
function sarai_chinwag_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'sarai-chinwag' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'sarai-chinwag' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'sarai_chinwag_widgets_init' );

/**
 * Set Content-Language HTTP header for browser translation detection
 * This helps browsers determine when to offer translation services
 */
function sarai_chinwag_set_content_language_header() {
    if (!headers_sent()) {
        header('Content-Language: en');
    }
}
add_action('wp_head', 'sarai_chinwag_set_content_language_header', 1);

/**
 * Display badge-breadcrumbs for single posts and recipes
 * Shows clickable category and tag badges that serve as navigation
 */
function sarai_chinwag_post_badges() {
    // Only show on single posts and recipes
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }
    
    global $post;
    $post_id = $post->ID;
    
    // Get primary category
    $categories = get_the_category($post_id);
    $primary_category = !empty($categories) ? $categories[0] : null;
    
    // Get up to 3 relevant tags
    $tags = get_the_tags($post_id);
    $relevant_tags = !empty($tags) ? array_slice($tags, 0, 3) : array();
    
    // Only display if we have a category or tags
    if (!$primary_category && empty($relevant_tags)) {
        return;
    }
    
    echo '<nav class="post-badge-breadcrumbs" aria-label="' . esc_attr__('Content navigation', 'sarai-chinwag') . '">';
    
    // Primary category badge
    if ($primary_category) {
        echo '<a href="' . esc_url(get_category_link($primary_category->term_id)) . '" class="badge-breadcrumb badge-category" rel="category tag">';
        echo esc_html($primary_category->name);
        echo '</a>';
    }
    
    // Tag badges
    if (!empty($relevant_tags)) {
        foreach ($relevant_tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge-breadcrumb badge-tag" rel="tag">';
            echo esc_html($tag->name);
            echo '</a>';
        }
    }
    
    echo '</nav>';
}

/**
 * Display traditional breadcrumbs for archive pages
 * Shows hierarchical navigation for categories, tags, and search results
 */
function sarai_chinwag_archive_breadcrumbs() {
    // Only show on archive pages (not on singular posts)
    if (!is_archive() && !is_search()) {
        return;
    }
    
    $breadcrumbs = array();
    $separator = ' > ';
    $home_title = __('Home', 'sarai-chinwag');
    $home_url = home_url('/');
    
    // Start with home link
    $breadcrumbs[] = '<a href="' . esc_url($home_url) . '">' . esc_html($home_title) . '</a>';
    
    if (is_category()) {
        $category = get_queried_object();
        $breadcrumbs[] = esc_html($category->name);
    } elseif (is_tag()) {
        $breadcrumbs[] = __('Tags', 'sarai-chinwag');
        $tag = get_queried_object();
        $breadcrumbs[] = esc_html($tag->name);
    } elseif (is_search()) {
        $breadcrumbs[] = __('Search Results', 'sarai-chinwag');
        $search_query = get_search_query();
        if ($search_query) {
            $breadcrumbs[] = '"' . esc_html($search_query) . '"';
        }
    } elseif (is_author()) {
        $author = get_queried_object();
        $breadcrumbs[] = __('Author', 'sarai-chinwag');
        $breadcrumbs[] = esc_html($author->display_name);
    } elseif (is_date()) {
        $breadcrumbs[] = __('Archives', 'sarai-chinwag');
        if (is_year()) {
            $breadcrumbs[] = get_the_date('Y');
        } elseif (is_month()) {
            $breadcrumbs[] = get_the_date('F Y');
        } elseif (is_day()) {
            $breadcrumbs[] = get_the_date('F j, Y');
        }
    }
    
    // Only display if we have more than just home
    if (count($breadcrumbs) > 1) {
        echo '<nav class="archive-breadcrumbs" aria-label="' . esc_attr__('Page navigation', 'sarai-chinwag') . '">';
        echo implode($separator, $breadcrumbs);
        echo '</nav>';
    }
}

/**
 * Display gallery discovery badges above "Keep Exploring" section
 * Shows badge-style links to image galleries for taxonomies assigned to current post
 */
function sarai_chinwag_gallery_discovery_badges() {
    // Only show on single posts and recipes
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }
    
    global $post;
    $post_id = $post->ID;
    
    // Check cache first - gallery badges cached for 15 minutes
    $cache_key = "gallery_badges_{$post_id}";
    $cached_badges = wp_cache_get($cache_key, 'sarai_chinwag_related');
    
    if (false !== $cached_badges) {
        echo $cached_badges;
        return;
    }
    
    // Start output buffering to cache the result
    ob_start();
    
    $gallery_badges = array();
    
    // Get categories for this post
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        foreach ($categories as $category) {
            // Check if category has images and get accurate count
            $image_count = sarai_chinwag_get_accurate_term_image_count($category->term_id, 'category');
            if ($image_count > 0) {
                $category_url = get_category_link($category->term_id);
                $gallery_url = trailingslashit($category_url) . 'images/';
                $gallery_badges[] = array(
                    'url' => $gallery_url,
                    'name' => $category->name,
                    'type' => 'category',
                    'count' => $image_count
                );
            }
        }
    }
    
    // Get tags for this post
    $tags = get_the_tags($post_id);
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            // Check if tag has images and get accurate count
            $image_count = sarai_chinwag_get_accurate_term_image_count($tag->term_id, 'post_tag');
            if ($image_count > 0) {
                $tag_url = get_tag_link($tag->term_id);
                $gallery_url = trailingslashit($tag_url) . 'images/';
                $gallery_badges[] = array(
                    'url' => $gallery_url,
                    'name' => $tag->name,
                    'type' => 'tag',
                    'count' => $image_count
                );
            }
        }
    }
    
    // Only display if we have gallery badges to show
    if (!empty($gallery_badges)) {
        echo '<aside class="gallery-discovery-badges">';
        echo '<h3 class="gallery-badges-title">' . __('Browse Image Galleries', 'sarai-chinwag') . '</h3>';
        echo '<p class="gallery-badges-description">' . __('View and download high-resolution images from these collections:', 'sarai-chinwag') . '</p>';
        echo '<nav class="gallery-badges-nav" aria-label="' . esc_attr__('Image gallery navigation', 'sarai-chinwag') . '">';
        
        foreach ($gallery_badges as $badge) {
            echo '<a href="' . esc_url($badge['url']) . '" class="gallery-badge badge-' . esc_attr($badge['type']) . '">';
            echo '<span class="badge-text">' . esc_html($badge['name']) . ' Images</span>';
            echo '<span class="badge-count">(' . absint($badge['count']) . ')</span>';
            echo '</a>';
        }
        
        echo '</nav>';
        echo '</aside>';
    }
    
    // Cache the output for 15 minutes
    $output = ob_get_contents();
    wp_cache_set($cache_key, $output, 'sarai_chinwag_related', 15 * MINUTE_IN_SECONDS);
    
    ob_end_flush();
}

/**
 * Helper function to get accurate image count for a specific term
 * Uses the existing image extractor function - no fallbacks
 */
function sarai_chinwag_get_accurate_term_image_count($term_id, $taxonomy) {
    $cache_key = "term_image_count_{$term_id}_{$taxonomy}";
    $cached_count = wp_cache_get($cache_key, 'sarai_chinwag_images');
    
    if (false !== $cached_count) {
        return $cached_count;
    }
    
    // Use the existing image extractor function for accurate counts only
    if (function_exists('sarai_chinwag_extract_images_from_term')) {
        $images = sarai_chinwag_extract_images_from_term($term_id, $taxonomy, 999);
        $count = count($images);
        
        // Cache for 2 hours
        wp_cache_set($cache_key, $count, 'sarai_chinwag_images', 2 * HOUR_IN_SECONDS);
        
        return $count;
    }
    
    // If image extractor function doesn't exist, return 0
    return 0;
}

/**
 * Helper function to get accurate site-wide image count
 * Uses the existing site-wide image extractor function
 */
function sarai_chinwag_get_site_wide_image_count() {
    $cache_key = "site_wide_image_count";
    $cached_count = wp_cache_get($cache_key, 'sarai_chinwag_images');
    
    if (false !== $cached_count) {
        return $cached_count;
    }
    
    // Use the existing site-wide image extractor function for accurate counts
    if (function_exists('sarai_chinwag_get_all_site_images')) {
        $images = sarai_chinwag_get_all_site_images(999);
        $count = count($images);
        
        // Cache for 2 hours
        wp_cache_set($cache_key, $count, 'sarai_chinwag_images', 2 * HOUR_IN_SECONDS);
        
        return $count;
    }
    
    // If site images function doesn't exist, return 0
    return 0;
}

/**
 * Helper function to get accurate search image count
 * Uses the existing search image extractor function
 */
function sarai_chinwag_get_search_image_count($search_query) {
    $cache_key = "search_image_count_" . md5($search_query);
    $cached_count = wp_cache_get($cache_key, 'sarai_chinwag_images');
    
    if (false !== $cached_count) {
        return $cached_count;
    }
    
    // Use the existing search image extractor function for accurate counts
    if (function_exists('sarai_chinwag_extract_images_from_search')) {
        $images = sarai_chinwag_extract_images_from_search($search_query, 999);
        $count = count($images);
        
        // Cache for 2 hours
        wp_cache_set($cache_key, $count, 'sarai_chinwag_images', 2 * HOUR_IN_SECONDS);
        
        return $count;
    }
    
    // If search images function doesn't exist, return 0
    return 0;
}

/**
 * AJAX endpoint for loading template parts
 */
function sarai_chinwag_load_template() {
    if (!wp_verify_nonce($_POST['nonce'], 'filter_posts_nonce')) {
        wp_die('Security check failed');
    }
    
    $template = sanitize_text_field($_POST['template']);
    $args = isset($_POST['args']) ? (array) $_POST['args'] : array();
    
    // Sanitize args
    if (isset($args['content_type'])) {
        $args['content_type'] = sanitize_text_field($args['content_type']);
    }
    
    ob_start();
    get_template_part('template-parts/' . $template, null, $args);
    $content = ob_get_clean();
    
    echo $content;
    wp_die();
}
add_action('wp_ajax_load_template', 'sarai_chinwag_load_template');
add_action('wp_ajax_nopriv_load_template', 'sarai_chinwag_load_template');

?>
