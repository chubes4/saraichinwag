<?php
/**
 * Contact Form Frontend
 *
 * Handles shortcode registration and script enqueuing
 *
 * @package Sarai_Chinwag
 * @since 2.2
 */

/**
 * Contact form shortcode
 *
 * @return string Form HTML
 * @since 2.2
 */
function sarai_chinwag_contact_form_shortcode() {
    ob_start();
    get_template_part('template-parts/contact-form');
    return ob_get_clean();
}
add_shortcode('sarai_contact_form', 'sarai_chinwag_contact_form_shortcode');

/**
 * Enqueue contact form scripts when shortcode is present
 *
 * @since 2.2
 */
function sarai_chinwag_enqueue_contact_form_scripts() {
    global $post;

    if (!is_a($post, 'WP_Post') || !has_shortcode($post->post_content, 'sarai_contact_form')) {
        return;
    }

    $site_key = get_option('sarai_chinwag_turnstile_site_key', '');

    if (empty($site_key)) {
        return;
    }

    wp_enqueue_script('turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, true);

    $script_version = filemtime(get_template_directory() . '/js/contact-form.js');
    wp_enqueue_script('contact-form-js', get_template_directory_uri() . '/js/contact-form.js',
        array('wp-i18n'), $script_version, true);

    wp_localize_script('contact-form-js', 'contact_form_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('contact_form_nonce'),
        'turnstile_site_key' => $site_key
    ));
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_contact_form_scripts');
?>
