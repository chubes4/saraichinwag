<?php
/**
 * Image Mode rewrite endpoints and query vars
 *
 * This file registers WordPress native rewrite endpoints for the image gallery mode.
 * Uses WordPress native add_rewrite_endpoint() instead of custom regex rules.
 */

/**
 * Register WordPress native rewrite endpoint for image archives
 */
function sarai_chinwag_add_image_archive_endpoint() {
    // Add /images/ endpoint to categories, tags, homepage, and search
    // This automatically handles nested categories via WordPress core
    add_rewrite_endpoint('images', EP_CATEGORIES | EP_TAGS | EP_ROOT | EP_SEARCH);
}
add_action('init', 'sarai_chinwag_add_image_archive_endpoint');


/**
 * Register query vars used by the image gallery system
 */
function sarai_chinwag_add_image_archive_query_vars($vars) {
    // WordPress native endpoint automatically adds 'images' query var
    // Legacy custom query vars no longer needed with native endpoint system
    return $vars;
}
add_filter('query_vars', 'sarai_chinwag_add_image_archive_query_vars');
