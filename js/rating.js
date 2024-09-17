document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star');
    const averageRatingElement = document.getElementById('average-rating');
    const userRatingElement = document.getElementById('user-rating');
    const ratingWidget = document.getElementById('rating-widget');
    const postId = ratingWidget.getAttribute('data-post-id');
    const nonce = rating_ajax_obj.nonce;

    console.log('Stars:', stars);
    console.log('Post ID:', postId);
    console.log('Nonce:', nonce);

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

    console.log('Average Rating:', averageRating);

    if (averageRating > 0) {
        setStars(averageRating);
    }

    const userRating = localStorage.getItem(`recipe-rating-${postId}`);
    console.log('User Rating:', userRating);

    if (userRating) {
        setStars(userRating);
        userRatingElement.textContent = `You rated this ${userRating} stars.`;
    } else {
        stars.forEach(star => {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-value');
                console.log('User selected rating:', rating);

                userRatingElement.textContent = `You rated this ${rating} stars.`;

                saveRatingToServer(rating);

                localStorage.setItem(`recipe-rating-${postId}`, rating);

                setStars(rating);
            });
        });
    }

    function saveRatingToServer(rating) {
        console.log('Saving rating to server...');
        const formData = new FormData();
        formData.append('action', 'rate_recipe');
        formData.append('rate_recipe_nonce', nonce);
        formData.append('rating', rating);
        formData.append('post_id', postId);

        fetch(rating_ajax_obj.ajax_url, {
            method: 'POST',
            body: formData
        }).then(response => {
            console.log('AJAX response:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        }).then(data => {
            console.log('Server response:', data);
            if (data.success) {
                averageRatingElement.textContent = `(${data.data.averageRating}/5 based on ${data.data.reviewCount} reviews)`;
                userRatingElement.textContent = `You rated this ${rating} stars.`;
                setStars(data.data.averageRating);
            } else {
                const errorMessage = data.data && data.data.message ? data.data.message : 'Unknown error';
                console.error('Error saving rating:', errorMessage);
            }
        }).catch(error => {
            console.error('Error saving rating:', error);
        });
    }
});