/**
 * Async View Counter
 *
 * Tracks post views via REST API after page load to avoid blocking render.
 * Uses sessionStorage to prevent duplicate counts within same session.
 */
(function() {
    'use strict';

    if (typeof saraiViewCounter === 'undefined') {
        return;
    }

    var postId = saraiViewCounter.postId;
    var storageKey = 'sarai_viewed_' + postId;

    if (sessionStorage.getItem(storageKey)) {
        return;
    }

    fetch(saraiViewCounter.restUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': saraiViewCounter.nonce
        },
        body: JSON.stringify({ post_id: postId })
    })
    .then(function(response) {
        if (response.ok) {
            sessionStorage.setItem(storageKey, '1');
        }
    })
    .catch(function() {
        // Silent fail - view tracking is non-critical
    });
})();
