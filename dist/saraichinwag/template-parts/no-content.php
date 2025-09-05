<?php
/**
 * Template for displaying "no content found" message
 */

$content_type = isset($args['content_type']) ? $args['content_type'] : 'posts';
$message = $content_type === 'images' ? __('No images found.', 'sarai-chinwag') : __('No posts found.', 'sarai-chinwag');
?>

<p class="no-content-message"><?php echo esc_html($message); ?></p>