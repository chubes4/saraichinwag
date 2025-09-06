<?php
// Define the function to send IndexNow request
function send_indexnow_request($urlList) {
    // Get API key from database
    $key = get_option('sarai_chinwag_indexnow_key', '');
    
    // Exit early if no API key is configured
    if (empty($key)) {
        return;
    }
    
    $api_url = 'https://api.indexnow.org/indexnow';
    $host = 'saraichinwag.com';
    $key_location = 'https://saraichinwag.com/' . $key . '.txt';

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
