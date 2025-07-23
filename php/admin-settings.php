<?php
/**
 * Admin settings page for Sarai Chinwag theme
 *
 * @package Sarai_Chinwag
 */

// Add admin menu
function sarai_chinwag_add_admin_menu() {
    add_options_page(
        __('Sarai Chinwag Settings', 'sarai-chinwag'),
        __('Sarai Chinwag', 'sarai-chinwag'),
        'manage_options',
        'sarai-chinwag-settings',
        'sarai_chinwag_settings_page'
    );
}
add_action('admin_menu', 'sarai_chinwag_add_admin_menu');

// Initialize settings
function sarai_chinwag_settings_init() {
    register_setting('sarai_chinwag_settings', 'sarai_chinwag_indexnow_key');
    
    add_settings_section(
        'sarai_chinwag_settings_section',
        __('API Configuration', 'sarai-chinwag'),
        'sarai_chinwag_settings_section_callback',
        'sarai_chinwag_settings'
    );
    
    add_settings_field(
        'sarai_chinwag_indexnow_key',
        __('IndexNow API Key', 'sarai-chinwag'),
        'sarai_chinwag_indexnow_key_render',
        'sarai_chinwag_settings',
        'sarai_chinwag_settings_section'
    );
}
add_action('admin_init', 'sarai_chinwag_settings_init');

// Settings section callback
function sarai_chinwag_settings_section_callback() {
    echo '<p>' . __('Configure API keys and external service settings for your theme.', 'sarai-chinwag') . '</p>';
}

// IndexNow API key field render
function sarai_chinwag_indexnow_key_render() {
    $value = get_option('sarai_chinwag_indexnow_key', '');
    echo '<input type="text" name="sarai_chinwag_indexnow_key" value="' . esc_attr($value) . '" size="50" />';
    echo '<p class="description">' . __('Enter your IndexNow API key for automatic search engine indexing. Leave empty to disable IndexNow functionality.', 'sarai-chinwag') . '</p>';
    echo '<p class="description">' . __('Format: 32-character hexadecimal string (e.g., 4ee5f0302df14ea9b2d2f5e9dd919fb0)', 'sarai-chinwag') . '</p>';
}

// Settings page content
function sarai_chinwag_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Sarai Chinwag Settings', 'sarai-chinwag'); ?></h1>
        
        <?php
        // Display admin notices
        if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            add_settings_error('sarai_chinwag_messages', 'sarai_chinwag_message', __('Settings saved successfully.', 'sarai-chinwag'), 'updated');
        }
        settings_errors('sarai_chinwag_messages');
        ?>
        
        <form action="options.php" method="post">
            <?php
            settings_fields('sarai_chinwag_settings');
            do_settings_sections('sarai_chinwag_settings');
            submit_button(__('Save Settings', 'sarai-chinwag'));
            ?>
        </form>
    </div>
    <?php
}

// Sanitize IndexNow API key
function sarai_chinwag_sanitize_indexnow_key($input) {
    $input = sanitize_text_field($input);
    
    // Validate format if not empty
    if (!empty($input) && !preg_match('/^[a-f0-9]{32}$/', $input)) {
        add_settings_error(
            'sarai_chinwag_indexnow_key',
            'invalid-key',
            __('IndexNow API key must be a 32-character hexadecimal string.', 'sarai-chinwag')
        );
        // Return the old value if validation fails
        return get_option('sarai_chinwag_indexnow_key', '');
    }
    
    return $input;
}
add_filter('pre_update_option_sarai_chinwag_indexnow_key', 'sarai_chinwag_sanitize_indexnow_key');
?>