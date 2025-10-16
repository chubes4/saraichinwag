<?php
/**
 * Cloudflare Turnstile Integration
 *
 * Handles verification of Turnstile tokens with Cloudflare API
 *
 * @package Sarai_Chinwag
 * @since 1.0.0
 */

/**
 * Verify Turnstile token with Cloudflare API
 *
 * @param string $token Turnstile response token from frontend
 * @param string $user_ip User's IP address
 * @return true|WP_Error True if verification succeeds, WP_Error on failure
 * @since 1.0.0
 */
function sarai_chinwag_verify_turnstile_token($token, $user_ip) {
    $secret_key = get_option('sarai_chinwag_turnstile_secret_key', '');

    if (empty($secret_key)) {
        return new WP_Error('missing_secret', __('Turnstile secret key not configured', 'sarai-chinwag'));
    }

    if (empty($token)) {
        return new WP_Error('missing_token', __('Turnstile token is required', 'sarai-chinwag'));
    }

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'body' => array(
            'secret' => $secret_key,
            'response' => $token,
            'remoteip' => $user_ip
        ),
        'timeout' => 10
    ));

    if (is_wp_error($response)) {
        return new WP_Error('api_error', __('Turnstile API request failed', 'sarai-chinwag'));
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($body['success']) || !$body['success']) {
        $error_codes = isset($body['error-codes']) ? implode(', ', $body['error-codes']) : 'Unknown error';
        return new WP_Error('verification_failed', sprintf(__('Turnstile verification failed: %s', 'sarai-chinwag'), $error_codes));
    }

    return true;
}
?>
