<?php
/**
 * Centralized Asset Management
 *
 * Handles all CSS and JavaScript enqueuing for the Sarai Chinwag theme.
 * This file serves as the single source of truth for asset loading,
 * managing dependencies, versioning, and conditional loading.
 *
 * @package Sarai_Chinwag
 * @since 2.2
 */

/**
 * Enqueue all frontend CSS assets
 *
 * Loads stylesheets in proper dependency order with file-based versioning.
 */
function sarai_chinwag_enqueue_styles() {
    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    // Root CSS variables (must load first)
    $root_version = filemtime($theme_dir . '/inc/assets/css/root.css');
    wp_enqueue_style(
        'sarai-chinwag-root-css',
        $theme_uri . '/inc/assets/css/root.css',
        array(),
        $root_version
    );

    // Main stylesheet (depends on root variables)
    $style_version = filemtime($theme_dir . '/style.css');
    wp_enqueue_style(
        'sarai-chinwag-style',
        get_stylesheet_uri(),
        array('sarai-chinwag-root-css'),
        $style_version
    );

    // Recipe-specific styles (conditionally loaded)
    if (!sarai_chinwag_recipes_disabled() && (is_singular('recipe') || is_post_type_archive('recipe'))) {
        $recipes_version = filemtime($theme_dir . '/inc/assets/css/recipes.css');
        wp_enqueue_style(
            'sarai-chinwag-recipes',
            $theme_uri . '/inc/assets/css/recipes.css',
            array('sarai-chinwag-root-css'),
            $recipes_version
        );
    }

    // Single view-specific styles (conditionally loaded)
    if (is_single() || is_page() || (!sarai_chinwag_recipes_disabled() && is_singular('recipe'))) {
        $single_version = filemtime($theme_dir . '/inc/assets/css/single.css');
        wp_enqueue_style(
            'sarai-chinwag-single',
            $theme_uri . '/inc/assets/css/single.css',
            array('sarai-chinwag-root-css'),
            $single_version
        );
    }

    // Archive-specific styles (conditionally loaded)
    if ((is_home() || is_archive() || is_search()) && !sarai_chinwag_is_image_mode()) {
        $archive_version = filemtime($theme_dir . '/inc/assets/css/archive.css');
        wp_enqueue_style(
            'sarai-chinwag-archive',
            $theme_uri . '/inc/assets/css/archive.css',
            array('sarai-chinwag-root-css'),
            $archive_version
        );
    }

    // Image mode-specific styles (conditionally loaded)
    if (sarai_chinwag_is_image_mode()) {
        $image_mode_version = filemtime($theme_dir . '/inc/assets/css/image-mode.css');
        wp_enqueue_style(
            'sarai-chinwag-image-mode',
            $theme_uri . '/inc/assets/css/image-mode.css',
            array('sarai-chinwag-root-css'),
            $image_mode_version
        );
    }

    // Sidebar-specific styles (conditionally loaded)
    if (is_single() || is_page() || (!sarai_chinwag_recipes_disabled() && is_singular('recipe'))) {
        $sidebar_version = filemtime($theme_dir . '/inc/assets/css/sidebar.css');
        wp_enqueue_style(
            'sarai-chinwag-sidebar',
            $theme_uri . '/inc/assets/css/sidebar.css',
            array('sarai-chinwag-root-css'),
            $sidebar_version
        );
    }

    // Contact form-specific styles (conditionally loaded)
    if (sarai_chinwag_has_contact_form()) {
        $contact_version = filemtime($theme_dir . '/inc/assets/css/contact.css');
        wp_enqueue_style(
            'sarai-chinwag-contact',
            $theme_uri . '/inc/assets/css/contact.css',
            array('sarai-chinwag-root-css'),
            $contact_version
        );
    }
}

/**
 * Enqueue all frontend JavaScript assets
 *
 * Loads scripts with proper dependencies and versions.
 * All scripts load in footer for optimal page performance.
 */
function sarai_chinwag_enqueue_scripts() {
    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    // Navigation
    $nav_version = filemtime($theme_dir . '/inc/assets/js/nav.js');
    wp_enqueue_script(
        'sarai-chinwag-nav',
        $theme_uri . '/inc/assets/js/nav.js',
        array(),
        $nav_version,
        true
    );

    // Gallery utilities
    $gallery_version = filemtime($theme_dir . '/inc/assets/js/gallery-utils.js');
    wp_enqueue_script(
        'sarai-chinwag-gallery-utils',
        $theme_uri . '/inc/assets/js/gallery-utils.js',
        array(),
        $gallery_version,
        true
    );

    // Pinterest integration
    $pinterest_version = filemtime($theme_dir . '/inc/assets/js/pinterest.js');
    wp_enqueue_script(
        'sarai-chinwag-pinterest',
        $theme_uri . '/inc/assets/js/pinterest.js',
        array(),
        $pinterest_version,
        true
    );
}

/**
 * Enqueue admin-only CSS assets
 *
 * Loads customizer styles in WordPress admin context.
 */
function sarai_chinwag_enqueue_admin_styles() {
    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();

    $customizer_version = filemtime($theme_dir . '/inc/assets/css/customizer.css');
    wp_enqueue_style(
        'sarai-chinwag-customizer',
        $theme_uri . '/inc/assets/css/customizer.css',
        array(),
        $customizer_version
    );
}

add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_styles');
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_scripts');
add_action('admin_enqueue_scripts', 'sarai_chinwag_enqueue_admin_styles');
