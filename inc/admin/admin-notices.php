<?php
/**
 * Admin notices for missing API keys and theme configuration
 *
 * @package Sarai_Chinwag
 */

/**
 * Display admin notices for missing API keys
 */
function sarai_chinwag_admin_notices() {
    // Only show notices to users who can manage options
    if (!current_user_can('manage_options')) {
        return;
    }

    // Only show on admin pages
    if (!is_admin()) {
        return;
    }

    $indexnow_key = get_option('sarai_chinwag_indexnow_key', '');
    $google_fonts_key = get_option('sarai_chinwag_google_fonts_api_key', '');
    $pinterest_username = get_option('sarai_chinwag_pinterest_username', '');
    
    // Check if notices have been dismissed
    $dismissed_indexnow = get_user_meta(get_current_user_id(), 'sarai_chinwag_dismissed_indexnow_notice', true);
    $dismissed_google_fonts = get_user_meta(get_current_user_id(), 'sarai_chinwag_dismissed_google_fonts_notice', true);
    $dismissed_pinterest = get_user_meta(get_current_user_id(), 'sarai_chinwag_dismissed_pinterest_notice', true);

    // Show IndexNow API key notice
    if (empty($indexnow_key) && !$dismissed_indexnow) {
        ?>
        <div class="notice notice-warning is-dismissible" data-notice="indexnow">
            <p>
                <strong><?php esc_html_e('Sarai Chinwag Theme:', 'sarai-chinwag'); ?></strong>
                <?php esc_html_e('IndexNow API key is missing. Automatic search engine indexing is disabled.', 'sarai-chinwag'); ?>
                <a href="<?php echo esc_url(admin_url('options-general.php?page=sarai-chinwag-settings')); ?>">
                    <?php esc_html_e('Configure in Settings', 'sarai-chinwag'); ?>
                </a>
            </p>
        </div>
        <?php
    }

    // Show Google Fonts API key notice
    if (empty($google_fonts_key) && !$dismissed_google_fonts) {
        ?>
        <div class="notice notice-warning is-dismissible" data-notice="google-fonts">
            <p>
                <strong><?php esc_html_e('Sarai Chinwag Theme:', 'sarai-chinwag'); ?></strong>
                <?php esc_html_e('Google Fonts API key is missing. Only fallback fonts are available in the customizer.', 'sarai-chinwag'); ?>
                <a href="<?php echo esc_url(admin_url('options-general.php?page=sarai-chinwag-settings')); ?>">
                    <?php esc_html_e('Configure in Settings', 'sarai-chinwag'); ?>
                </a>
            </p>
        </div>
        <?php
    }

    // Show Pinterest username notice
    if (empty($pinterest_username) && !$dismissed_pinterest) {
        ?>
        <div class="notice notice-info is-dismissible" data-notice="pinterest">
            <p>
                <strong><?php esc_html_e('Sarai Chinwag Theme:', 'sarai-chinwag'); ?></strong>
                <?php esc_html_e('Pinterest username not set. Pinterest follow widget will not appear in sidebar.', 'sarai-chinwag'); ?>
                <a href="<?php echo esc_url(admin_url('options-general.php?page=sarai-chinwag-settings')); ?>">
                    <?php esc_html_e('Configure in Settings', 'sarai-chinwag'); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'sarai_chinwag_admin_notices');

/**
 * Handle AJAX request to dismiss admin notices
 */
function sarai_chinwag_dismiss_admin_notice() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'sarai_chinwag_dismiss_notice')) {
        wp_die('Security check failed');
    }

    // Verify user permissions
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }

    $notice_type = sanitize_text_field($_POST['notice_type']);
    $user_id = get_current_user_id();

    switch ($notice_type) {
        case 'indexnow':
            update_user_meta($user_id, 'sarai_chinwag_dismissed_indexnow_notice', true);
            break;
        case 'google-fonts':
            update_user_meta($user_id, 'sarai_chinwag_dismissed_google_fonts_notice', true);
            break;
        case 'pinterest':
            update_user_meta($user_id, 'sarai_chinwag_dismissed_pinterest_notice', true);
            break;
    }

    wp_die(); // This is required to terminate immediately and return a proper response
}
add_action('wp_ajax_sarai_chinwag_dismiss_notice', 'sarai_chinwag_dismiss_admin_notice');

/**
 * Enqueue admin scripts for dismissible notices
 */
function sarai_chinwag_admin_scripts() {
    $script = "
    jQuery(document).ready(function($) {
        $('.notice[data-notice]').on('click', '.notice-dismiss', function() {
            var notice = $(this).closest('.notice');
            var noticeType = notice.data('notice');
            
            $.post(ajaxurl, {
                action: 'sarai_chinwag_dismiss_notice',
                notice_type: noticeType,
                nonce: '" . wp_create_nonce('sarai_chinwag_dismiss_notice') . "'
            });
        });
    });
    ";
    
    wp_add_inline_script('jquery', $script);
}
add_action('admin_enqueue_scripts', 'sarai_chinwag_admin_scripts');

/**
 * Reset dismissed notices when API keys are added
 */
function sarai_chinwag_reset_notices_on_key_save($option_name, $old_value, $value) {
    // Reset notice dismissals when keys are added
    if ($option_name === 'sarai_chinwag_indexnow_key' && !empty($value) && empty($old_value)) {
        delete_metadata('user', 0, 'sarai_chinwag_dismissed_indexnow_notice', '', true);
    }
    
    if ($option_name === 'sarai_chinwag_google_fonts_api_key' && !empty($value) && empty($old_value)) {
        delete_metadata('user', 0, 'sarai_chinwag_dismissed_google_fonts_notice', '', true);
    }
    
    if ($option_name === 'sarai_chinwag_pinterest_username' && !empty($value) && empty($old_value)) {
        delete_metadata('user', 0, 'sarai_chinwag_dismissed_pinterest_notice', '', true);
    }
}
add_action('updated_option', 'sarai_chinwag_reset_notices_on_key_save', 10, 3);
?>