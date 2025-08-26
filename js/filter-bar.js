document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('load-more');
    const postGrid = document.getElementById('post-grid');
    const filterBar = document.getElementById('filter-bar');
    
    if (!postGrid || !filterBar) {
        return;
    }

    // Detect image gallery mode from element or URL path
    let isImageGallery = document.getElementById('filter-image-gallery') ? 
        document.getElementById('filter-image-gallery').value === '1' : false;
    const path = window.location.pathname;
    if (!isImageGallery) {
        if (path === '/images/' || /\/images\/?$/.test(path)) {
            isImageGallery = true;
        }
    }
    const isAllSiteImages = isImageGallery && (path === '/images/' || path === '/images');

    // Determine initial type based on page context
    function determineInitialType() {
        const activeBtn = document.querySelector('.type-btn.active');
        if (activeBtn?.dataset?.type) {
            return activeBtn.dataset.type;
        }
        
        if (isImageGallery) return 'images';
        return document.querySelector('.type-btn[data-type="all"]') ? 'all' : 'posts';
    }

    // Filter state
    let currentFilters = {
        sort_by: 'random',
        post_type_filter: determineInitialType(),
        category: document.getElementById('filter-category') ? document.getElementById('filter-category').value : '',
        tag: document.getElementById('filter-tag') ? document.getElementById('filter-tag').value : '',
        search: document.getElementById('filter-search') ? document.getElementById('filter-search').value : ''
    };
    
    let currentPage = 1;
    let loadedPostIds = [];
    let loadedImageIds = [];

    // Initialize loaded posts/images from existing grid
    function initializeLoadedContent() {
        if (isImageGallery) {
            loadedImageIds = Array.from(postGrid.querySelectorAll('.gallery-item img')).map(img => {
                const match = img.className.match(/wp-image-(\d+)/);
                return match ? match[1] : '';
            }).filter(id => id !== '');
        } else {
            loadedPostIds = Array.from(postGrid.querySelectorAll('article')).map(post => post.id.replace('post-', ''));
        }
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
            // Always highlight Images on images pages regardless of filter state
            if (isImageGallery && btn.dataset.type === 'images') {
                btn.classList.add('active');
            }
        });
    }
    
    
    // Main function to load posts/images with current filters
    function loadPosts(page = 1, append = false) {
        
        const xhr = new XMLHttpRequest();
        const data = new FormData();

        // Add all filter parameters
        const actionName = isImageGallery ? 'filter_images' : 'filter_posts';
        data.append('action', actionName);
        data.append('nonce', sarai_chinwag_ajax.nonce);
        data.append('page', page);
        data.append('sort_by', currentFilters.sort_by);
        data.append('post_type_filter', currentFilters.post_type_filter);
        
        // Send appropriate loaded items based on mode
        if (isImageGallery) {
            data.append('loadedImages', JSON.stringify(append ? loadedImageIds : []));
            
            // Check if this is a site-wide image gallery (even when Load More is absent)
            const loadMoreBtn = document.getElementById('load-more');
            const hasAllSiteAttr = loadMoreBtn && loadMoreBtn.getAttribute('data-all-site') === 'true';
            if (hasAllSiteAttr || isAllSiteImages) {
                data.append('all_site', 'true');
            }
        } else {
            data.append('loadedPosts', JSON.stringify(append ? loadedPostIds : []));
        }

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
            
            if (xhr.status >= 200 && xhr.status < 400) {
                const response = xhr.responseText.trim();
                const noContentMessages = isImageGallery ? 
                    ['No images found', 'No more images'] : 
                    ['No posts found', 'No more posts'];
                
                const hasNoContent = response === '0' || 
                    noContentMessages.some(msg => response.includes(msg));
                
                if (hasNoContent) {
                    if (append) {
                        if (loadMoreButton) {
                            loadMoreButton.textContent = isImageGallery ? 'No more images' : 'No more posts';
                            loadMoreButton.disabled = true;
                        }
                    } else {
                        // Load no-content message template
                        const contentType = isImageGallery ? 'images' : 'posts';
                        SaraiGalleryUtils.getNoContentMessage(contentType).then(template => {
                            postGrid.innerHTML = template;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'none';
                            }
                        }).catch(() => {
                            // Fallback to simple message if template fails
                            const fallback = isImageGallery ? 'No images found.' : 'No posts found.';
                            postGrid.innerHTML = `<p class="no-content-message">${fallback}</p>`;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'none';
                            }
                        });
                    }
                } else {
                    if (isImageGallery) {
                        // Parse incoming figures
                        const tmp = document.createElement('div');
                        tmp.innerHTML = response;
                        const newFigs = Array.from(tmp.querySelectorAll('figure.gallery-item'));

                        // Ensure columns exist on replace
                        if (!append) {
                            postGrid.innerHTML = '';
                            const colCount = SaraiGalleryUtils.getColumnCount();
                            const columns = SaraiGalleryUtils.createColumns(colCount);
                            columns.forEach(col => postGrid.appendChild(col));
                            
                            currentPage = 1;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'block';
                                loadMoreButton.disabled = false;
                                loadMoreButton.textContent = 'Load More';
                            }
                        }

                        const cols = Array.from(postGrid.querySelectorAll('.gallery-col'));
                        SaraiGalleryUtils.distributeFigures(newFigs, cols);
                    } else {
                        if (append) {
                            // Append new post articles
                            postGrid.insertAdjacentHTML('beforeend', response);
                        } else {
                            // Replace all post articles
                            postGrid.innerHTML = response;
                            currentPage = 1;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'block';
                                loadMoreButton.disabled = false;
                                loadMoreButton.textContent = 'Load More';
                            }
                        }
                    }

                    // Update loaded items array
                    if (isImageGallery) {
                        const newImages = postGrid.querySelectorAll('.gallery-item img');
                        loadedImageIds = Array.from(newImages).map(img => {
                            const match = img.className.match(/wp-image-(\d+)/);
                            return match ? match[1] : '';
                        }).filter(id => id !== '');
                        
                        // Check if we should disable load more
                        const loadedImagesCount = (response.match(/<figure[^>]*class=\"[^\"]*gallery-item/g) || []).length;
                        if (loadMoreButton && loadedImagesCount < sarai_chinwag_ajax.posts_per_page) {
                            loadMoreButton.textContent = 'No more images';
                            loadMoreButton.disabled = true;
                        }
                    } else {
                        const newPosts = postGrid.querySelectorAll('article');
                        loadedPostIds = Array.from(newPosts).map(post => post.id.replace('post-', ''));
                        
                        // Check if we should disable load more
                        const loadedPostsCount = (response.match(/<article/g) || []).length;
                        if (loadMoreButton && loadedPostsCount < sarai_chinwag_ajax.posts_per_page) {
                            loadMoreButton.textContent = 'No more posts';
                            loadMoreButton.disabled = true;
                        }
                    }
                }
            } else {
                console.error('Filter request failed: ' + xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Filter request failed');
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
    
    // Helper functions for navigation
    function navigateToImages() {
        const currentUrl = window.location.pathname;
        if (currentUrl.includes('/images')) return; // Already on images page
        
        const imageUrl = (currentUrl === '/' || currentUrl.match(/^\/$/)) 
            ? '/images/' 
            : currentUrl.replace(/\/$/, '') + '/images/';
        window.location.href = imageUrl;
    }
    
    function navigateFromImages() {
        const currentUrl = window.location.pathname;
        if (!currentUrl.includes('/images')) return false; // Not on images page
        
        const postUrl = (currentUrl === '/images/' || currentUrl.match(/^\/images\/$/))
            ? '/'
            : currentUrl.replace('/images/', '/').replace('/images', '/');
        window.location.href = postUrl;
        return true; // Navigation handled
    }
    
    function handleFilterOrNavigate(typeValue) {
        if (navigateFromImages()) return; // Navigation handled
        
        if (currentFilters.post_type_filter !== typeValue) {
            currentFilters.post_type_filter = typeValue;
            applyFilters();
        }
    }

    // Type filter button event listeners
    document.querySelectorAll('.type-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const typeValue = this.dataset.type;
            
            if (typeValue === 'images') {
                navigateToImages();
            } else {
                handleFilterOrNavigate(typeValue);
            }
        });
    });
    
    // Load-more handling in separate js/load-more.js file
    
    // Initialize
    initializeLoadedContent();
    updateFilterStates();
    
});