<?php
// Define the function to send IndexNow request
function send_indexnow_request($urlList) {
    $api_url = 'https://api.indexnow.org/indexnow';
    $host = 'saraichinwag.com';
    $key = '4ee5f0302df14ea9b2d2f5e9dd919fb0';
    $key_location = 'https://saraichinwag.com/4ee5f0302df14ea9b2d2f5e9dd919fb0.txt';

    $data = array(
        'host' => $host,
        'key' => $key,
        'keyLocation' => $key_location,
        'urlList' => $urlList
    );

    $args = array(
        'body'        => json_encode($data),
        'headers'     => array(
            'Content-Type' => 'application/json; charset=utf-8',
        ),
        'timeout'     => 20,
    );

    $response = wp_remote_post($api_url, $args);

    if (is_wp_error($response)) {
        error_log('IndexNow request failed: ' . $response->get_error_message());
    } else {
        error_log('IndexNow request succeeded: ' . wp_remote_retrieve_body($response));
    }
}

// Hook into WordPress post publish/update
function notify_indexnow_on_post_save($post_id, $post, $update) {
    if ($post->post_status != 'publish') {
        return;
    }

    $post_url = get_permalink($post_id);
    send_indexnow_request(array($post_url));
}
add_action('save_post', 'notify_indexnow_on_post_save', 10, 3);
?>
