<?php
/**
 * Contact Form Email Functions
 *
 * Handles admin notifications and submitter confirmations
 *
 * @package Sarai_Chinwag
 * @since 2.2
 */

/**
 * Send admin notification email
 *
 * @param array $form_data Form submission data
 * @return bool True if email sent successfully
 * @since 2.2
 */
function sarai_chinwag_send_admin_notification($form_data) {
    $recipient = get_option('admin_email');
    $site_name = get_bloginfo('name');

    $subject = sprintf(__('[%s] New Contact Form Submission from %s', 'sarai-chinwag'), $site_name, $form_data['name']);

    $message = sprintf(
        __("New contact form submission received:\n\nName: %s\nEmail: %s\nSubject: %s\n\nMessage:\n%s\n\n---\nSubmitted: %s\nIP Address: %s", 'sarai-chinwag'),
        $form_data['name'],
        $form_data['email'],
        $form_data['subject'],
        $form_data['message'],
        current_time('mysql'),
        $form_data['ip']
    );

    $headers = array(
        'From: ' . $form_data['name'] . ' <' . $form_data['email'] . '>',
        'Reply-To: ' . $form_data['email'],
        'Content-Type: text/plain; charset=UTF-8'
    );

    return wp_mail($recipient, $subject, $message, $headers);
}

/**
 * Send confirmation copy to submitter
 *
 * @param array $form_data Form submission data
 * @return bool True if email sent successfully
 * @since 2.2
 */
function sarai_chinwag_send_submitter_copy($form_data) {
    $site_name = get_bloginfo('name');
    $admin_email = get_option('admin_email');

    $subject = sprintf(__('Thank you for contacting %s', 'sarai-chinwag'), $site_name);

    $message = sprintf(
        __("Thank you for reaching out! We have received your message and will respond as soon as possible.\n\nHere is a copy of your submission:\n\nSubject: %s\n\nMessage:\n%s\n\n---\nBest regards,\n%s", 'sarai-chinwag'),
        $form_data['subject'],
        $form_data['message'],
        $site_name
    );

    $headers = array(
        'From: ' . $site_name . ' <' . $admin_email . '>',
        'Reply-To: ' . $admin_email,
        'Content-Type: text/plain; charset=UTF-8'
    );

    return wp_mail($form_data['email'], $subject, $message, $headers);
}
?>
