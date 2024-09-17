document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('load-more');
    const postGrid = document.getElementById('post-grid');
    const filterBlogPosts = document.getElementById('filter-blog-posts');
    const filterRecipes = document.getElementById('filter-recipes');
    let currentPage = 1;
    const category = loadMoreButton.getAttribute('data-category');
    const tag = loadMoreButton.getAttribute('data-tag');
    const searchTerm = loadMoreButton.getAttribute('data-search');

    // Initialize loadedPosts with the IDs of the posts already present in the postGrid
    let loadedPosts = Array.from(postGrid.querySelectorAll('article')).map(post => post.id.replace('post-', ''));

    function loadPosts(page = 1, append = false) {
        const xhr = new XMLHttpRequest();
        const data = new FormData();

        data.append('action', 'filter_posts');
        data.append('nonce', sarai_chinwag_ajax.nonce);
        data.append('page', page);
        data.append('loadedPosts', JSON.stringify(loadedPosts));

        if (filterBlogPosts) {
            data.append('filter_blog_posts', filterBlogPosts.checked);
        }

        if (filterRecipes) {
            data.append('filter_recipes', filterRecipes.checked);
        }

        if (category) {
            data.append('category', category);
        }

        if (tag) {
            data.append('tag', tag);
        }

        if (searchTerm) {
            data.append('search', searchTerm);
        }

        xhr.open('POST', sarai_chinwag_ajax.ajaxurl, true);
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                const response = xhr.responseText.trim();
                if (response === '0' || response === '<p>No posts found.</p>') {
                    loadMoreButton.textContent = 'No more posts';
                    loadMoreButton.disabled = true;
                } else {
                    if (append) {
                        postGrid.insertAdjacentHTML('beforeend', xhr.responseText);
                    } else {
                        postGrid.innerHTML = xhr.responseText;
                    }

                    // Update loadedPosts array with new post IDs
                    const newPosts = postGrid.querySelectorAll('article');
                    newPosts.forEach(post => {
                        const postId = post.id.replace('post-', '');
                        if (!loadedPosts.includes(postId)) {
                            loadedPosts.push(postId);
                        }
                    });

                    // Check if fewer than posts_per_page posts were loaded
                    const loadedPostsCount = (xhr.responseText.match(/<article/g) || []).length;
                    if (loadedPostsCount < sarai_chinwag_ajax.posts_per_page) {
                        loadMoreButton.textContent = 'No more posts';
                        loadMoreButton.disabled = true;
                    } else {
                        loadMoreButton.textContent = 'Load More';
                        loadMoreButton.disabled = false;
                    }
                }
            } else {
                console.error('Error: ' + xhr.statusText);
            }
        };
        xhr.onerror = function () {
            console.error('Request failed');
        };
        xhr.send(data);
    }

    if (filterBlogPosts) {
        filterBlogPosts.addEventListener('change', function () {
            currentPage = 1;
            loadedPosts = [];
            loadPosts(currentPage);
        });
    }

    if (filterRecipes) {
        filterRecipes.addEventListener('change', function () {
            currentPage = 1;
            loadedPosts = [];
            loadPosts(currentPage);
        });
    }

    loadMoreButton.addEventListener('click', function () {
        currentPage++;
        loadPosts(currentPage, true);
    });
});
