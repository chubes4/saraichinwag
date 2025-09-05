document.addEventListener('DOMContentLoaded', function () {
    const { __, sprintf } = wp.i18n;
    const stars = document.querySelectorAll('.star');
    const averageRatingElement = document.getElementById('average-rating');
    const userRatingElement = document.getElementById('user-rating');
    const ratingWidget = document.getElementById('rating-widget');
    const postId = ratingWidget.getAttribute('data-post-id');
    const nonce = rating_ajax_obj.nonce;

    // Early exit if required elements are missing
    if (!stars.length || !postId || !nonce) {
        return;
    }

    function setStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }

    let averageRating = averageRatingElement.textContent.match(/[\d\.]+/);
    averageRating = averageRating ? parseFloat(averageRating[0]) : 0;

    if (averageRating > 0) {
        setStars(averageRating);
    }

    const userRating = localStorage.getItem(`recipe-rating-${postId}`);

    if (userRating) {
        setStars(userRating);
        userRatingElement.textContent = sprintf(__('You rated this %d stars.', 'sarai-chinwag'), userRating);
    } else {
        stars.forEach(star => {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-value');

                userRatingElement.textContent = sprintf(__('You rated this %d stars.', 'sarai-chinwag'), rating);

                saveRatingToServer(rating);

                localStorage.setItem(`recipe-rating-${postId}`, rating);

                setStars(rating);
            });
        });
    }

    function saveRatingToServer(rating) {
        // Add loading state
        ratingWidget.classList.add('loading');
        
        const formData = new FormData();
        formData.append('action', 'rate_recipe');
        formData.append('rate_recipe_nonce', nonce);
        formData.append('rating', rating);
        formData.append('post_id', postId);

        fetch(rating_ajax_obj.ajax_url, {
            method: 'POST',
            body: formData
        }).then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        }).then(data => {
            if (data.success) {
                averageRatingElement.textContent = `(${data.data.averageRating}/5 based on ${data.data.reviewCount} reviews)`;
                userRatingElement.textContent = sprintf(__('You rated this %d stars.', 'sarai-chinwag'), rating);
                setStars(data.data.averageRating);
            } else {
                // Silently handle error - user can try again
                userRatingElement.textContent = __('Error saving rating. Please try again.', 'sarai-chinwag');
            }
        }).catch(error => {
            // Silently handle error - user can try again
            userRatingElement.textContent = __('Error saving rating. Please try again.', 'sarai-chinwag');
        }).finally(() => {
            // Remove loading state
            ratingWidget.classList.remove('loading');
        });
    }
});