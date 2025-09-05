<?php
/**
 * Template part for displaying image galleries from category/tag archives or site-wide
 *
 * @package Sarai_Chinwag
 */

// Get current term or check for site-wide gallery
$term = get_queried_object();
$url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
$is_site_wide = is_home() && $url_has_images;

if ($is_site_wide) {
    $term_type = 'all';
    $term_id = null;
} elseif (is_category()) {
    $term_type = 'category';
    $term_id = $term->term_id;
} elseif (is_tag()) {
    $term_type = 'post_tag';
    $term_id = $term->term_id;
} elseif (is_search() && $url_has_images) {
    $term_type = 'search';
    $term_id = get_search_query();
} else {
    echo '<p>' . esc_html__('Unable to load gallery.', 'sarai-chinwag') . '</p>';
    return;
}

$posts_per_page = get_option('posts_per_page', 10);
$images = sarai_chinwag_get_term_images($term_id, $term_type, $posts_per_page);

if (empty($images)) {
    echo '<div class="no-images-found">';
    
    if ($term_type === 'search') {
        $search_query = get_search_query();
        echo '<p>' . sprintf(esc_html__('No images found for "%s".', 'sarai-chinwag'), esc_html($search_query)) . '</p>';
        echo '<p><a href="' . esc_url(home_url('/?s=' . urlencode($search_query))) . '">' . 
             sprintf(esc_html__('← Back to "%s" search results', 'sarai-chinwag'), esc_html($search_query)) . 
             '</a></p>';
    } elseif ($is_site_wide) {
        echo '<p>' . esc_html__('No images found on this site.', 'sarai-chinwag') . '</p>';
        echo '<p><a href="' . esc_url(home_url('/')) . '">' . 
             esc_html__('← Back to homepage', 'sarai-chinwag') . 
             '</a></p>';
    } else {
        echo '<p>' . esc_html__('No images found in this category/tag.', 'sarai-chinwag') . '</p>';
        echo '<p><a href="' . esc_url(get_term_link($term)) . '">' . 
             sprintf(esc_html__('← Back to %s posts', 'sarai-chinwag'), esc_html($term->name)) . 
             '</a></p>';
    }
    
    echo '</div>';
    return;
}
?>

<div class="image-gallery masonry" id="post-grid">
    <?php
    // Build 4 columns server-side
    $col_count = 4;
    $cols = array_fill(0, $col_count, '');
    foreach ($images as $index => $image) {
        $attachment_id = $image['attachment_id'];
        $post_id = isset($image['post_id']) ? (int) $image['post_id'] : 0;
        $alt_text = $image['alt'] ?: $image['source_post_title'];
        $image_url = $image['url_medium'] ?: $image['url_full'];

        if ($post_id) {
            $source_post = get_post($post_id);
            if ($source_post) {
                setup_postdata($source_post);
            }
        }

        $fig = '<figure class="wp-block-image gallery-item"'
            . ' data-post-id="' . esc_attr($post_id) . '"'
            . ' data-attachment-id="' . esc_attr($attachment_id) . '">'
            . '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($alt_text) . '"'
            . ' loading="' . ($index < 12 ? 'eager' : 'lazy') . '"'
            . ' class="gallery-image wp-image-' . esc_attr($attachment_id) . '"'
            . ' width="' . esc_attr(intval($image['width'])) . '" height="' . esc_attr(intval($image['height'])) . '"'
            . ' data-attachment-id="' . esc_attr($attachment_id) . '" />'
            . '<div class="gallery-item-overlay">'
            . '<a href="' . esc_url($image['source_post_url']) . '" class="source-post-link"'
            . ' data-post-id="' . esc_attr($post_id) . '"'
            . ' data-attachment-id="' . esc_attr($attachment_id) . '">'
            . esc_html($image['source_post_title'])
            . '</a>'
            . '</div>'
            . '</figure>';
        $cols[$index % $col_count] .= $fig;
    }
    wp_reset_postdata();
    foreach ($cols as $col_html) {
        echo '<div class="gallery-col">' . $col_html . '</div>';
    }
    ?>
</div>

<?php if (count($images) >= $posts_per_page) : 
    $category = (!$is_site_wide && is_category()) ? get_queried_object()->slug : '';
    $tag = (!$is_site_wide && is_tag()) ? get_queried_object()->slug : '';
    $search_query = (is_search() && $url_has_images) ? get_search_query() : '';
    $is_all_site = $is_site_wide ? 'true' : 'false';
?>
    <div class="load-more-container">
        <button id="load-more" 
                data-page="1"
                <?php echo $category ? 'data-category="' . esc_attr($category) . '"' : ''; ?>
                <?php echo $tag ? 'data-tag="' . esc_attr($tag) . '"' : ''; ?>
                <?php echo $search_query ? 'data-search="' . esc_attr($search_query) . '"' : ''; ?>
                data-all-site="<?php echo esc_attr($is_all_site); ?>"
        >
            <?php esc_html_e('Load More', 'sarai-chinwag'); ?>
        </button>
    </div>
<?php endif; ?>