<?php
/**
 * Filter Bar System
 *
 * Displays filter bar on home, archive, search, and image gallery pages
 *
 * @package Sarai_Chinwag
 * @since 2.0
 */

function sarai_chinwag_display_filter_bar() {
    $is_image_gallery = function_exists('sarai_chinwag_is_image_mode') && sarai_chinwag_is_image_mode();
    
    if (!is_home() && !is_archive() && !is_search() && !$is_image_gallery) {
        return;
    }
    
    get_template_part('template-parts/filter', 'bar');
}

function sarai_chinwag_show_type_filters() {
    if (sarai_chinwag_recipes_disabled()) {
        return false;
    }
    
    return sarai_chinwag_has_both_posts_and_recipes();
}

add_action('before_post_grid', 'sarai_chinwag_display_filter_bar');
