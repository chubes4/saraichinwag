/**
 * Recipe Rating System with AJAX submission and localStorage persistence
 *
 * Handles user ratings (1-5 stars) with dual-state management:
 * - localStorage for immediate UI feedback
 * - AJAX to server for persistent storage and average calculation
 *
 * @version 2.2
 * @since 1.0.0
 */
document.addEventListener('DOMContentLoaded', function () {
    const { __, sprintf } = wp.i18n;
    const stars = document.querySelectorAll('.star');
    const averageRatingElement = document.getElementById('average-rating');
    const userRatingElement = document.getElementById('user-rating');
    const ratingWidget = document.getElementById('rating-widget');
    const postId = ratingWidget.getAttribute('data-post-id');
    const nonce = rating_ajax_obj.nonce;

    if (!stars.length || !postId || !nonce) {
        return;
    }

    /**
     * Update visual star display
     * @param {number} rating Rating value (1-5)
     */
    function setStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('selected');
                star.innerHTML = '&#9733;'; // Filled star
            } else {
                star.classList.remove('selected');
                star.innerHTML = '&#9734;'; // Empty star
            }
        });
    }

    let averageRating = averageRatingElement.textContent.match(/[\d\.]+/);
    averageRating = averageRating ? parseFloat(averageRating[0]) : 0;

    if (averageRating > 0) {
        setStars(Math.round(averageRating));
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

    /**
     * Submit rating to server with error handling and UI updates
     * @param {number} rating User's rating (1-5)
     */
    function saveRatingToServer(rating) {
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
                const reviewsText = data.data.reviewCount == 1 ? 'review' : 'reviews';
                averageRatingElement.textContent = `(${data.data.averageRating}/5 based on ${data.data.reviewCount} ${reviewsText})`;
                userRatingElement.textContent = sprintf(__('You rated this %d stars.', 'sarai-chinwag'), rating);
                setStars(Math.round(data.data.averageRating));
            } else {
                    userRatingElement.textContent = __('Error saving rating. Please try again.', 'sarai-chinwag');
            }
        }).catch(error => {
            userRatingElement.textContent = __('Error saving rating. Please try again.', 'sarai-chinwag');
        }).finally(() => {
            ratingWidget.classList.remove('loading');
        });
    }
});