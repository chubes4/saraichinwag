<?php
/**
 * Sarai Chinwag WordPress Theme
 * 
 * A versatile WordPress theme designed for saraichinwag.com with dynamic Google Fonts 
 * integration and universal customization capabilities. Features include recipe functionality,
 * percentage-based font scaling, responsive design, and white-label ready architecture.
 *
 * Features:
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


function extra_chill_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Sidebar', 'extra-chill' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here to appear in your sidebar.', 'extra-chill' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'extra_chill_widgets_init' );
?>
