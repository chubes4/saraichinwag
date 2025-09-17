<?php
/**
 * Image Migration Tool
 *
 * Migrates manually added first content images to proper featured image display
 * Eliminates duplicate image processing and improves performance
 *
 * @package Sarai_Chinwag
 * @since 2.3.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin notice for image migration
 */
function sarai_chinwag_image_migration_notice() {
    // Don't show if migration is complete
    if (get_option('sarai_chinwag_migration_complete')) {
        return;
    }

    // Only show to administrators
    if (!current_user_can('manage_options')) {
        return;
    }

    // Don't show on migration-related pages to avoid conflicts
    $screen = get_current_screen();
    if ($screen && strpos($screen->id, 'migration') !== false) {
        return;
    }

    ?>
    <div class="notice notice-warning" id="sarai-migration-notice">
        <p>
            <strong><?php _e('Image Migration Available', 'sarai-chinwag'); ?>:</strong>
            <?php _e('Convert manually added first images to proper featured images for better performance and SEO.', 'sarai-chinwag'); ?>
        </p>
        <p>
            <button id="migrate-images-btn" class="button button-primary">
                <?php _e('Migrate All Images', 'sarai-chinwag'); ?>
            </button>
            <span id="migration-progress" style="margin-left: 15px; font-weight: bold;"></span>
        </p>
        <div id="migration-details" style="margin-top: 10px; display: none;">
            <div style="background: #f0f0f1; padding: 10px; border-radius: 4px;">
                <div id="migration-status"></div>
                <div id="migration-log" style="max-height: 200px; overflow-y: auto; margin-top: 5px; font-family: monospace; font-size: 12px;"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        let migrationInProgress = false;

        $('#migrate-images-btn').on('click', function() {
            if (migrationInProgress) return;

            if (!confirm('<?php _e('This will migrate all posts with manually added first images. Continue?', 'sarai-chinwag'); ?>')) {
                return;
            }

            migrationInProgress = true;
            $(this).prop('disabled', true).text('<?php _e('Starting Migration...', 'sarai-chinwag'); ?>');
            $('#migration-details').show();
            $('#migration-progress').text('<?php _e('Initializing...', 'sarai-chinwag'); ?>');

            startMigration();
        });

        function startMigration() {
            // Get total posts count first
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sarai_count_migration_posts',
                    nonce: '<?php echo wp_create_nonce('sarai_migration_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        processBatch(0, response.data.total);
                    } else {
                        showError('Failed to count posts: ' + response.data);
                    }
                },
                error: function() {
                    showError('Failed to connect to server');
                }
            });
        }

        function processBatch(offset, total) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sarai_migrate_images_batch',
                    nonce: '<?php echo wp_create_nonce('sarai_migration_nonce'); ?>',
                    offset: offset,
                    limit: 20
                },
                success: function(response) {
                    if (response.success) {
                        const processed = offset + response.data.processed;
                        const percentage = Math.round((processed / total) * 100);

                        $('#migration-progress').text('Progress: ' + processed + '/' + total + ' (' + percentage + '%)');
                        $('#migration-status').text('Processed batch: ' + response.data.processed + ' posts');

                        // Add log entries
                        if (response.data.log && response.data.log.length > 0) {
                            response.data.log.forEach(function(entry) {
                                $('#migration-log').append('<div>' + entry + '</div>');
                            });
                            $('#migration-log').scrollTop($('#migration-log')[0].scrollHeight);
                        }

                        if (processed < total) {
                            // Continue with next batch
                            setTimeout(function() {
                                processBatch(processed, total);
                            }, 500);
                        } else {
                            // Migration complete
                            completeMigration(response.data.stats);
                        }
                    } else {
                        showError('Migration failed: ' + response.data);
                    }
                },
                error: function() {
                    showError('Connection failed during migration');
                }
            });
        }

        function completeMigration(stats) {
            $('#migrate-images-btn').text('<?php _e('Migration Complete!', 'sarai-chinwag'); ?>').removeClass('button-primary').addClass('button-disabled');
            $('#migration-progress').html('✓ <?php _e('Migration Complete!', 'sarai-chinwag'); ?>').css('color', 'green');
            $('#migration-status').html(
                '<?php _e('Successfully migrated', 'sarai-chinwag'); ?> ' + stats.migrated + ' <?php _e('posts', 'sarai-chinwag'); ?>. ' +
                stats.skipped + ' <?php _e('posts skipped (no matching images)', 'sarai-chinwag'); ?>.'
            );

            // Hide notice after 3 seconds
            setTimeout(function() {
                $('#sarai-migration-notice').fadeOut();
            }, 3000);
        }

        function showError(message) {
            migrationInProgress = false;
            $('#migrate-images-btn').prop('disabled', false).text('<?php _e('Migrate All Images', 'sarai-chinwag'); ?>');
            $('#migration-progress').html('❌ ' + message).css('color', 'red');
        }
    });
    </script>
    <?php
}
add_action('admin_notices', 'sarai_chinwag_image_migration_notice');

/**
 * AJAX handler to count posts that need migration
 */
function sarai_chinwag_count_migration_posts() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'sarai_migration_nonce')) {
        wp_die('Security check failed');
    }

    // Only allow administrators
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }

    $posts = get_posts(array(
        'post_type' => array('post', 'recipe'),
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    ));

    wp_send_json_success(array('total' => count($posts)));
}
add_action('wp_ajax_sarai_count_migration_posts', 'sarai_chinwag_count_migration_posts');

/**
 * AJAX handler for batch migration processing
 */
function sarai_chinwag_migrate_images_batch() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'sarai_migration_nonce')) {
        wp_die('Security check failed');
    }

    // Only allow administrators
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }

    $offset = intval($_POST['offset']);
    $limit = intval($_POST['limit']);
    $log = array();
    $migrated = 0;
    $skipped = 0;

    // Get batch of posts with featured images
    $posts = get_posts(array(
        'post_type' => array('post', 'recipe'),
        'post_status' => 'publish',
        'numberposts' => $limit,
        'offset' => $offset,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    ));

    foreach ($posts as $post) {
        $result = sarai_chinwag_migrate_post_image($post->ID);

        if ($result['migrated']) {
            $migrated++;
            $log[] = "✓ Migrated post #{$post->ID}: {$post->post_title}";
        } else {
            $skipped++;
            $log[] = "- Skipped post #{$post->ID}: {$result['reason']}";
        }
    }

    // Mark migration as complete if this was the last batch
    if (count($posts) < $limit) {
        update_option('sarai_chinwag_migration_complete', true);
    }

    wp_send_json_success(array(
        'processed' => count($posts),
        'log' => $log,
        'stats' => array(
            'migrated' => $migrated,
            'skipped' => $skipped
        )
    ));
}
add_action('wp_ajax_sarai_migrate_images_batch', 'sarai_chinwag_migrate_images_batch');

/**
 * Migrate a single post's first image block to featured image display
 *
 * @param int $post_id Post ID to migrate
 * @return array Migration result with status and details
 */
function sarai_chinwag_migrate_post_image($post_id) {
    $post = get_post($post_id);
    if (!$post) {
        return array('migrated' => false, 'reason' => 'Post not found');
    }

    $featured_image_id = get_post_thumbnail_id($post_id);
    if (!$featured_image_id) {
        return array('migrated' => false, 'reason' => 'No featured image');
    }

    $content = $post->post_content;

    // Only process Gutenberg content
    if (!has_blocks($content)) {
        return array('migrated' => false, 'reason' => 'No Gutenberg blocks');
    }

    $blocks = parse_blocks($content);
    $first_image_block = sarai_chinwag_find_first_image_block($blocks);

    if (!$first_image_block) {
        return array('migrated' => false, 'reason' => 'No image blocks found');
    }

    // Check if first image matches featured image
    $first_image_id = $first_image_block['attrs']['id'] ?? 0;
    if ($first_image_id != $featured_image_id) {
        return array('migrated' => false, 'reason' => 'First image does not match featured image');
    }

    // Remove the first image block
    $updated_blocks = sarai_chinwag_remove_first_image_block($blocks);
    $new_content = serialize_blocks($updated_blocks);

    // Update post content
    $result = wp_update_post(array(
        'ID' => $post_id,
        'post_content' => $new_content
    ));

    if (is_wp_error($result)) {
        return array('migrated' => false, 'reason' => 'Failed to update post: ' . $result->get_error_message());
    }

    return array('migrated' => true, 'reason' => 'Successfully migrated');
}

/**
 * Find the first image block in parsed blocks array
 *
 * @param array $blocks Parsed blocks array
 * @return array|null First image block or null if not found
 */
function sarai_chinwag_find_first_image_block($blocks) {
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/image' && isset($block['attrs']['id'])) {
            return $block;
        }

        // Check inner blocks recursively
        if (!empty($block['innerBlocks'])) {
            $inner_result = sarai_chinwag_find_first_image_block($block['innerBlocks']);
            if ($inner_result) {
                return $inner_result;
            }
        }
    }

    return null;
}

/**
 * Remove the first image block from parsed blocks array
 *
 * @param array $blocks Parsed blocks array
 * @return array Updated blocks array with first image removed
 */
function sarai_chinwag_remove_first_image_block($blocks) {
    foreach ($blocks as $index => $block) {
        if ($block['blockName'] === 'core/image' && isset($block['attrs']['id'])) {
            // Remove this block
            unset($blocks[$index]);
            // Re-index array
            return array_values($blocks);
        }

        // Check inner blocks recursively
        if (!empty($block['innerBlocks'])) {
            $updated_inner = sarai_chinwag_remove_first_image_block($block['innerBlocks']);
            if ($updated_inner !== $block['innerBlocks']) {
                $blocks[$index]['innerBlocks'] = $updated_inner;
                return $blocks;
            }
        }
    }

    return $blocks;
}

/**
 * Display featured image styled as a Gutenberg image block
 *
 * @param string $size Image size to use (default: 'grid-thumb')
 * @param array $attr Additional image attributes
 */
function sarai_chinwag_display_featured_image_as_block($size = 'large', $attr = array()) {
    if (!has_post_thumbnail()) {
        return;
    }

    $attachment_id = get_post_thumbnail_id();
    $default_attr = array(
        'class' => 'wp-image-' . $attachment_id,
        'itemprop' => 'image'
    );

    $attr = array_merge($default_attr, $attr);

    echo '<figure class="wp-block-image">';
    the_post_thumbnail($size, $attr);
    echo '</figure>';
}