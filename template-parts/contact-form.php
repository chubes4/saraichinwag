<?php
/**
 * Contact Form Template
 *
 * @package Sarai_Chinwag
 * @since 2.2
 */

$site_key = get_option('sarai_chinwag_turnstile_site_key', '');

if (empty($site_key)) {
    echo '<div class="sarai-contact-form-error">';
    echo '<p>' . __('Contact form is not configured. Please configure Cloudflare Turnstile in Theme Settings.', 'sarai-chinwag') . '</p>';
    echo '</div>';
    return;
}
?>

<div class="sarai-contact-form">
    <form id="sarai-contact-form-element" method="post">

        <div class="form-field">
            <label for="contact-name"><?php _e('Name', 'sarai-chinwag'); ?> *</label>
            <input type="text" id="contact-name" name="name" required
                   aria-required="true" maxlength="100">
            <span class="error-message" role="alert"></span>
        </div>

        <div class="form-field">
            <label for="contact-email"><?php _e('Email', 'sarai-chinwag'); ?> *</label>
            <input type="email" id="contact-email" name="email" required
                   aria-required="true" maxlength="100">
            <span class="error-message" role="alert"></span>
        </div>

        <div class="form-field">
            <label for="contact-subject"><?php _e('Subject', 'sarai-chinwag'); ?> *</label>
            <input type="text" id="contact-subject" name="subject" required
                   aria-required="true" maxlength="200">
            <span class="error-message" role="alert"></span>
        </div>

        <div class="form-field">
            <label for="contact-message"><?php _e('Message', 'sarai-chinwag'); ?> *</label>
            <textarea id="contact-message" name="message" rows="8" required
                      aria-required="true" maxlength="5000"></textarea>
            <span class="error-message" role="alert"></span>
        </div>

        <div class="cf-turnstile" data-sitekey="<?php echo esc_attr($site_key); ?>" data-theme="invisible" data-size="invisible"></div>
        <span class="error-message" id="turnstile-error" role="alert"></span>

        <button type="submit" class="submit-button">
            <span class="button-text"><?php _e('Send Message', 'sarai-chinwag'); ?></span>
            <span class="spinner" style="display:none;"><?php _e('Sending...', 'sarai-chinwag'); ?></span>
        </button>

        <div class="success-message" style="display:none;" role="status"></div>
    </form>
</div>
