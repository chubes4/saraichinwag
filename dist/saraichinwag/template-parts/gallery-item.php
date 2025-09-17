<?php
/**
 * Template part for displaying a gallery item
 *
 * @package Sarai_Chinwag
 */

if (!isset($image) || empty($image)) {
    return;
}

$attachment_id = $image['attachment_id'];
$post_id = isset($image['post_id']) ? (int) $image['post_id'] : 0;
$alt_text = $image['alt'] ?: $image['source_post_title'];
$image_url = $image['url_medium'] ?: $image['url_full'];
$deep_link = !empty($post_id) ? trailingslashit($image['source_post_url']) . '#sc-image-' . $attachment_id : $image['source_post_url'];
?>
<figure class="wp-block-image gallery-item" data-post-id="<?php echo esc_attr($post_id); ?>" data-attachment-id="<?php echo esc_attr($attachment_id); ?>">
    <a class="gallery-link" href="<?php echo esc_url($deep_link); ?>" aria-label="View image in post: <?php echo esc_attr($image['source_post_title']); ?>">
        <img src="<?php echo esc_url($image_url); ?>" 
             alt="<?php echo esc_attr($alt_text); ?>"
             loading="lazy"
             class="gallery-image wp-image-<?php echo esc_attr($attachment_id); ?>"
             width="<?php echo esc_attr(intval($image['width'])); ?>"
             height="<?php echo esc_attr(intval($image['height'])); ?>"
             data-attachment-id="<?php echo esc_attr($attachment_id); ?>" />
        <div class="gallery-item-overlay">
            <span class="source-post-link" data-post-id="<?php echo esc_attr($post_id); ?>" data-attachment-id="<?php echo esc_attr($attachment_id); ?>"><?php echo esc_html($image['source_post_title']); ?></span>
        </div>
    </a>
</figure>