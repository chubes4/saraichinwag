<?php
/**
 * Sarai Chinwag WordPress Theme
 * 
 * A versatile WordPress theme designed for saraichinwag.com with dynamic Google Fonts 
 * integration, randomized content discovery, and universal customization capabilities. 
 * Features anti-chronological design, recipe functionality, percentage-based font scaling, 
 * responsive design, and white-label ready architecture.
 *
 * Features:
 * - Randomized content discovery with anti-chronological design  
 * - Random post/recipe pages for serendipitous browsing
 * - Dynamic Google Fonts API integration with category filtering
 * - Universal theme design (recipe mode + standard blog mode)
 * - Percentage-based font scaling system (1-100%)
 * - Recipe post type with ratings and schema markup
 * - Admin settings panel for API keys and toggles
 * - Performance optimized with transient caching
 * - Security focused with proper sanitization
 * 
 * @package Sarai_Chinwag
 * @author Chris Huber
 * @version 2.0
 * @link https://saraichinwag.com
 * @since 1.0.0
 */

// Theme setup function
if ( ! function_exists( 'sarai_chinwag_setup' ) ) :
    function sarai_chinwag_setup() {
        // Load theme text domain for translations
        load_theme_textdomain( 'sarai-chinwag', get_template_directory() . '/languages' );
        
        // Add support for various features.
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo' );
        add_theme_support( 'menus' );
        add_theme_support( 'editor-styles' );
        add_editor_style( 'style.css' ); 
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'sarai-chinwag' ),
            'footer'  => __( 'Footer Menu', 'sarai-chinwag' ),
        ) );
    }
endif;
add_action( 'after_setup_theme', 'sarai_chinwag_setup' );

// Enqueue scripts and styles
function sarai_chinwag_scripts() {
    // Get the modification time of the stylesheet.
    $style_version = filemtime( get_template_directory() . '/style.css' );

    // Enqueue the main stylesheet with dynamic versioning.
    wp_enqueue_style( 'sarai-chinwag-style', get_stylesheet_uri(), array(), $style_version );

    // Get the modification time of the navigation script.
    $nav_version = filemtime( get_template_directory() . '/js/nav.js' );

    // Enqueue the navigation script with dynamic versioning.
    wp_enqueue_script( 'sarai-chinwag-nav', get_template_directory_uri() . '/js/nav.js', array(), $nav_version, true );
}
add_action( 'wp_enqueue_scripts', 'sarai_chinwag_scripts' );

// Add Pinterest script with attributes
function add_pinterest_script_with_attributes() {
    echo '
        <script
            type="text/javascript"
            async defer
            src="https://assets.pinterest.com/js/pinit.js"
            data-pin-hover="true"
            data-pin-tall="true"
            >
        </script>
    ';
}
add_action( 'wp_footer', 'add_pinterest_script_with_attributes' );

function add_data_pin_url_to_featured_images($html, $post_id) {
    if (is_home() || is_archive() || is_search()) {
        $post_url = get_permalink($post_id);
        // Check if the image has a 'data-pin-url' attribute
        if (strpos($html, 'data-pin-url=') === false) {
            $html = str_replace('<img ', '<img data-pin-url="' . esc_url($post_url) . '" ', $html);
        }
    }
    return $html;
}
add_filter('post_thumbnail_html', 'add_data_pin_url_to_featured_images', 10, 2);

// Autoload PHP files from the 'php' directory
function extra_chill_autoload_php_files($directory) {
    // Ensure the directory is a string and has a trailing slash
    if (is_string($directory)) {
        $directory = rtrim($directory, '/') . '/';
        
        // Get all PHP files in the directory
        foreach (glob($directory . '*.php') as $filename) {
            include_once $filename;
        }
    }
}


// Call the autoload function with the 'php' directory path
extra_chill_autoload_php_files(get_template_directory() . '/php');

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
 * Clear footer cache when content changes
 * Cache is indefinite until content is modified
 */
function sarai_chinwag_clear_footer_cache() {
    delete_transient('footer_category_data');
    delete_transient('footer_tags');
}

// Clear footer cache when posts or terms are modified
add_action('save_post', 'sarai_chinwag_clear_footer_cache');
add_action('delete_post', 'sarai_chinwag_clear_footer_cache');
add_action('wp_update_term', 'sarai_chinwag_clear_footer_cache');
add_action('created_term', 'sarai_chinwag_clear_footer_cache');
add_action('delete_term', 'sarai_chinwag_clear_footer_cache');

/**
 * Get cached random post ID for high-performance random post selection
 * Replaces expensive orderby => 'rand' queries with cached ID arrays
 *
 * @param string $post_type The post type to get random ID for
 * @return int|false Random post ID or false if none found
 */
function sarai_chinwag_get_cached_random_post_id($post_type = 'post') {
    $cache_key = "random_{$post_type}_ids";
    $random_ids = get_transient($cache_key);
    
    if (false === $random_ids || empty($random_ids)) {
        // Get all post IDs for this post type (fast query - no content)
        $all_posts = get_posts(array(
            'post_type' => $post_type,
            'fields' => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        
        if (empty($all_posts)) {
            return false;
        }
        
        // Randomize in PHP (much faster than MySQL rand())
        shuffle($all_posts);
        
        // Cache for 1 hour - refreshes automatically
        set_transient($cache_key, $all_posts, HOUR_IN_SECONDS);
        $random_ids = $all_posts;
    }
    
    // Return first ID and rotate array for next request
    $post_id = array_shift($random_ids);
    set_transient($cache_key, $random_ids, HOUR_IN_SECONDS);
    
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
    $random_ids = get_transient($cache_key);
    
    if (false === $random_ids || empty($random_ids)) {
        // Get all post IDs for specified post types (fast query - no content)
        $all_posts = get_posts(array(
            'post_type' => $post_types,
            'fields' => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        
        if (empty($all_posts)) {
            return array();
        }
        
        // Randomize in PHP and take only what we need
        shuffle($all_posts);
        $random_ids = array_slice($all_posts, 0, $count * 3); // Get 3x what we need for variety
        
        // Cache for 30 minutes - shorter than individual post cache for more variety
        set_transient($cache_key, $random_ids, 30 * MINUTE_IN_SECONDS);
    }
    
    // Return the requested number of IDs
    return array_slice($random_ids, 0, $count);
}

/**
 * Clear all performance caches when content changes
 */
function sarai_chinwag_clear_performance_caches($post_id = null) {
    // Clear random post caches
    delete_transient('random_post_ids');
    delete_transient('random_recipe_ids');
    delete_transient('random_query_home');
    delete_transient('random_query_archive');
    
    // Clear related post caches - need to clear all as they're post-specific
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_related_content_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_related_content_%'");
}

// Clear performance caches when posts are modified
add_action('save_post', 'sarai_chinwag_clear_performance_caches');
add_action('delete_post', 'sarai_chinwag_clear_performance_caches');
add_action('wp_trash_post', 'sarai_chinwag_clear_performance_caches');
add_action('untrash_post', 'sarai_chinwag_clear_performance_caches');

function extra_chill_include_recipes_in_main_query($query) {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return;
    }
    
    if (!is_admin() && $query->is_main_query() && !$query->is_feed()) {
        if (is_home() || is_category() || is_tag() || is_search()) {
            $query->set('post_type', array('post', 'recipe'));
        }
    }
}
add_action('pre_get_posts', 'extra_chill_include_recipes_in_main_query');


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
?>
