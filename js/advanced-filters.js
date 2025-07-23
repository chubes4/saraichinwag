document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('load-more');
    const postGrid = document.getElementById('post-grid');
    const filterBar = document.getElementById('filter-bar');
    const filterLoading = document.getElementById('filter-loading');
    
    if (!loadMoreButton || !postGrid || !filterBar) {
        return; // Exit if essential elements are missing
    }

    // Filter state management
    let currentFilters = {
        sort_by: 'random',
        post_type_filter: 'all',
        category: document.getElementById('filter-category') ? document.getElementById('filter-category').value : '',
        tag: document.getElementById('filter-tag') ? document.getElementById('filter-tag').value : '',
        search: document.getElementById('filter-search') ? document.getElementById('filter-search').value : ''
    };
    
    let currentPage = 1;
    let loadedPosts = [];

    // Initialize loaded posts from existing grid
    function initializeLoadedPosts() {
        loadedPosts = Array.from(postGrid.querySelectorAll('article')).map(post => post.id.replace('post-', ''));
    }
    
    // Update filter button states
    function updateFilterStates() {
        // Update sort buttons
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.sort === currentFilters.sort_by) {
                btn.classList.add('active');
            }
        });
        
        // Update type buttons
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.type === currentFilters.post_type_filter) {
                btn.classList.add('active');
            }
        });
    }
    
    // Show/hide loading state
    function setLoadingState(isLoading) {
        if (filterLoading) {
            filterLoading.style.display = isLoading ? 'block' : 'none';
        }
        if (loadMoreButton) {
            loadMoreButton.disabled = isLoading;
            loadMoreButton.textContent = isLoading ? 'Loading...' : 'Load More';
        }
    }
    
    // Main function to load posts with current filters
    function loadPosts(page = 1, append = false) {
        setLoadingState(true);
        
        const xhr = new XMLHttpRequest();
        const data = new FormData();

        // Add all filter parameters
        data.append('action', 'filter_posts');
        data.append('nonce', sarai_chinwag_ajax.nonce);
        data.append('page', page);
        data.append('sort_by', currentFilters.sort_by);
        data.append('post_type_filter', currentFilters.post_type_filter);
        data.append('loadedPosts', JSON.stringify(append ? loadedPosts : []));

        // Add context parameters
        if (currentFilters.category) {
            data.append('category', currentFilters.category);
        }
        if (currentFilters.tag) {
            data.append('tag', currentFilters.tag);
        }
        if (currentFilters.search) {
            data.append('search', currentFilters.search);
        }

        xhr.open('POST', sarai_chinwag_ajax.ajaxurl, true);
        
        xhr.onload = function () {
            setLoadingState(false);
            
            if (xhr.status >= 200 && xhr.status < 400) {
                const response = xhr.responseText.trim();
                
                if (response === '0' || response === '<p>No posts found.</p>' || response.includes('No posts found')) {
                    if (append) {
                        loadMoreButton.textContent = 'No more posts';
                        loadMoreButton.disabled = true;
                    } else {
                        postGrid.innerHTML = '<p>No posts found.</p>';
                        loadMoreButton.style.display = 'none';
                    }
                } else {
                    if (append) {
                        // Append new posts
                        postGrid.insertAdjacentHTML('beforeend', response);
                    } else {
                        // Replace all posts
                        postGrid.innerHTML = response;
                        currentPage = 1;
                        loadMoreButton.style.display = 'block';
                        loadMoreButton.disabled = false;
                        loadMoreButton.textContent = 'Load More';
                    }

                    // Update loaded posts array
                    const newPosts = postGrid.querySelectorAll('article');
                    loadedPosts = Array.from(newPosts).map(post => post.id.replace('post-', ''));

                    // Check if we should disable load more
                    const loadedPostsCount = (response.match(/<article/g) || []).length;
                    if (loadedPostsCount < sarai_chinwag_ajax.posts_per_page) {
                        loadMoreButton.textContent = 'No more posts';
                        loadMoreButton.disabled = true;
                    }
                }
            } else {
                console.error('Filter request failed: ' + xhr.statusText);
                setLoadingState(false);
            }
        };

        xhr.onerror = function () {
            console.error('Filter request failed');
            setLoadingState(false);
        };

        xhr.send(data);
    }
    
    // Apply filters (reload posts with new filters)
    function applyFilters() {
        currentPage = 1;
        updateFilterStates();
        loadPosts(1, false);
    }
    
    // Sort button event listeners
    document.querySelectorAll('.sort-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const sortValue = this.dataset.sort;
            
            if (currentFilters.sort_by !== sortValue) {
                currentFilters.sort_by = sortValue;
                applyFilters();
            }
        });
    });
    
    // Type filter button event listeners
    document.querySelectorAll('.type-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const typeValue = this.dataset.type;
            
            if (currentFilters.post_type_filter !== typeValue) {
                currentFilters.post_type_filter = typeValue;
                applyFilters();
            }
        });
    });
    
    // Clear filters button
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Reset to defaults
            currentFilters.sort_by = 'random';
            currentFilters.post_type_filter = 'all';
            
            applyFilters();
        });
    }
    
    // Load More button event listener
    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', function(e) {
            e.preventDefault();
            currentPage++;
            loadPosts(currentPage, true);
        });
    }
    
    // Initialize
    initializeLoadedPosts();
    updateFilterStates();
    
    // Legacy support for existing checkbox filters (if present)
    const filterBlogPosts = document.getElementById('filter-blog-posts');
    const filterRecipes = document.getElementById('filter-recipes');
    
    if (filterBlogPosts) {
        filterBlogPosts.addEventListener('change', function() {
            // Convert legacy checkbox to new button system
            if (filterRecipes) {
                if (this.checked && filterRecipes.checked) {
                    currentFilters.post_type_filter = 'all';
                } else if (this.checked) {
                    currentFilters.post_type_filter = 'posts';
                } else if (filterRecipes.checked) {
                    currentFilters.post_type_filter = 'recipes';
                } else {
                    currentFilters.post_type_filter = 'all';
                }
                applyFilters();
            }
        });
    }
    
    if (filterRecipes) {
        filterRecipes.addEventListener('change', function() {
            // Convert legacy checkbox to new button system
            if (filterBlogPosts) {
                if (this.checked && filterBlogPosts.checked) {
                    currentFilters.post_type_filter = 'all';
                } else if (this.checked) {
                    currentFilters.post_type_filter = 'recipes';
                } else if (filterBlogPosts.checked) {
                    currentFilters.post_type_filter = 'posts';
                } else {
                    currentFilters.post_type_filter = 'all';
                }
                applyFilters();
            }
        });
    }
});