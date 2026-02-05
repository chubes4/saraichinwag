<?php
/**
 * "Try Image Mode" link for archive pages
 * 
 * Links to image gallery view with accurate counts.
 *
 * @package Sarai_Chinwag
 */

if (!is_home() && !is_category() && !is_tag() && !is_search()) {
    return;
}
$has_images_var = get_query_var('images') !== false;
$url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
$is_image_gallery = $has_images_var && $url_has_images;

if ($is_image_gallery) {
    return;
}

$link_data = array();

if (is_home()) {
    $image_count = sarai_chinwag_get_site_wide_image_count();
    if ($image_count > 0) {
        $link_data = array(
            'url' => home_url('/images/'),
            'text' => __('All Site Images', 'sarai-chinwag'),
            'type' => 'site',
            'count' => $image_count
        );
    }
} elseif (is_category()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
        $image_count = sarai_chinwag_get_accurate_term_image_count($term->term_id, 'category');
        if ($image_count > 0) {
            $category_url = get_category_link($term->term_id);
            $link_data = array(
                'url' => trailingslashit($category_url) . 'images/',
                'text' => sprintf(__('%s Images', 'sarai-chinwag'), $term->name),
                'type' => 'category',
                'count' => $image_count
            );
        }
    }
} elseif (is_tag()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
        $image_count = sarai_chinwag_get_accurate_term_image_count($term->term_id, 'post_tag');
        if ($image_count > 0) {
            $tag_url = get_tag_link($term->term_id);
            $link_data = array(
                'url' => trailingslashit($tag_url) . 'images/',
                'text' => sprintf(__('%s Images', 'sarai-chinwag'), $term->name),
                'type' => 'tag',
                'count' => $image_count
            );
        }
    }
} elseif (is_search()) {
    $search_query = get_search_query();
    if (!empty($search_query)) {
        $image_count = sarai_chinwag_get_search_image_count($search_query);
        if ($image_count > 0) {
            $search_url = add_query_arg(array('s' => $search_query, 'images' => '1'), home_url('/'));
            $link_data = array(
                'url' => $search_url,
                'text' => sprintf(__('"%s" Images', 'sarai-chinwag'), $search_query),
                'type' => 'search',
                'count' => $image_count
            );
        }
    }
}

if (empty($link_data)) {
    return;
}
?>

<aside class="gallery-discovery-badges archive-image-mode">
    <h3 class="gallery-badges-title"><?php _e('Try Image Mode', 'sarai-chinwag'); ?></h3>
    <p class="gallery-badges-description"><?php _e('Browse high-resolution images from this collection:', 'sarai-chinwag'); ?></p>
    <nav class="gallery-badges-nav" aria-label="<?php esc_attr_e('Image gallery navigation', 'sarai-chinwag'); ?>">
        <a href="<?php echo esc_url($link_data['url']); ?>" class="gallery-badge badge-<?php echo esc_attr($link_data['type']); ?>">
            <span class="badge-text"><?php echo esc_html($link_data['text']); ?></span>
            <span class="badge-count">(<?php echo absint($link_data['count']); ?>)</span>
        </a>
    </nav>
</aside>
<?php
/**
 * Hook: sarai_chinwag_after_gallery_badges
 * Fires after gallery discovery badges.
 */
do_action( 'sarai_chinwag_after_gallery_badges' );
?>