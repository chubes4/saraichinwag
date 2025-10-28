<?php
/**
 * Contact Form AJAX Handler
 *
 * Processes contact form submissions with Cloudflare Turnstile verification
 *
 * @package Sarai_Chinwag
 * @since 2.2
 */

function sarai_chinwag_submit_contact_form() {
    if (!check_ajax_referer('contact_form_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => __('Security verification failed.', 'sarai-chinwag')));
        wp_die();
    }

    // Sanitize and validate inputs
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    $turnstile_token = isset($_POST['turnstile_token']) ? sanitize_text_field($_POST['turnstile_token']) : '';

    if (empty($name)) {
        wp_send_json_error(array('message' => __('Name is required.', 'sarai-chinwag')));
        wp_die();
    }

    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => __('Valid email is required.', 'sarai-chinwag')));
        wp_die();
    }

    if (empty($subject)) {
        wp_send_json_error(array('message' => __('Subject is required.', 'sarai-chinwag')));
        wp_die();
    }

    if (empty($message)) {
        wp_send_json_error(array('message' => __('Message is required.', 'sarai-chinwag')));
        wp_die();
    }

    $user_ip = $_SERVER['REMOTE_ADDR'];

    $turnstile_result = sarai_chinwag_verify_turnstile_token($turnstile_token, $user_ip);

    if (is_wp_error($turnstile_result)) {
        wp_send_json_error(array('message' => __('Bot verification failed. Please try again.', 'sarai-chinwag')));
        wp_die();
    }

    $form_data = array(
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'ip' => $user_ip
    );

    $admin_sent = sarai_chinwag_send_admin_notification($form_data);

    if (!$admin_sent) {
        wp_send_json_error(array('message' => __('Failed to send message. Please try again later.', 'sarai-chinwag')));
        wp_die();
    }

    sarai_chinwag_send_submitter_copy($form_data);

    wp_send_json_success(array(
        'message' => __('Thank you! Your message has been sent successfully.', 'sarai-chinwag')
    ));
}

add_action('wp_ajax_submit_contact_form', 'sarai_chinwag_submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'sarai_chinwag_submit_contact_form');
?>
