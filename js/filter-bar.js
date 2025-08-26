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

    // Determine default type based on page context
    function getDefaultType() {
        if (isImageGallery) return 'images';
        const hasAll = !!document.querySelector('.type-btn[data-type="all"]');
        return hasAll ? 'all' : 'posts';
    }

    function determineInitialType() {
        const activeBtn = document.querySelector('.type-btn.active');
        if (activeBtn && activeBtn.dataset && activeBtn.dataset.type) {
            return activeBtn.dataset.type;
        }
        return getDefaultType();
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
    let loadedPosts = [];
    let loadedImages = [];

    // Initialize loaded posts/images from existing grid
    function initializeLoadedPosts() {
        if (isImageGallery) {
            loadedImages = Array.from(postGrid.querySelectorAll('.gallery-item img')).map(img => {
                const match = img.className.match(/wp-image-(\d+)/);
                return match ? match[1] : '';
            }).filter(id => id !== '');
        } else {
            loadedPosts = Array.from(postGrid.querySelectorAll('article')).map(post => post.id.replace('post-', ''));
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
            data.append('loadedImages', JSON.stringify(append ? loadedImages : []));
            
            // Check if this is a site-wide image gallery (even when Load More is absent)
            const loadMoreBtn = document.getElementById('load-more');
            const hasAllSiteAttr = loadMoreBtn && loadMoreBtn.getAttribute('data-all-site') === 'true';
            if (hasAllSiteAttr || isAllSiteImages) {
                data.append('all_site', 'true');
            }
        } else {
            data.append('loadedPosts', JSON.stringify(append ? loadedPosts : []));
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
                        const noContentMsg = isImageGallery ? 
                            '<p>No images found.</p>' : 
                            '<p>No posts found.</p>';
                        postGrid.innerHTML = noContentMsg;
                        if (loadMoreButton) {
                            loadMoreButton.style.display = 'none';
                        }
                    }
                } else {
                    if (isImageGallery) {
                        // Parse incoming figures
                        const tmp = document.createElement('div');
                        tmp.innerHTML = response;
                        const newFigs = Array.from(tmp.querySelectorAll('figure.gallery-item'));

                        // Ensure columns exist on replace
                        if (!append) {
                            // Build 4 columns container
                            postGrid.innerHTML = '';
                            let colCount = 4;
                            if (window.innerWidth < 450) colCount = 1; // mobile
                            else if (window.innerWidth <= 1200) colCount = 3; // tablet
                            for (let i = 0; i < colCount; i++) {
                                const col = document.createElement('div');
                                col.className = 'gallery-col';
                                postGrid.appendChild(col);
                            }
                            currentPage = 1;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'block';
                                loadMoreButton.disabled = false;
                                loadMoreButton.textContent = 'Load More';
                            }
                        }

                        const cols = Array.from(postGrid.querySelectorAll('.gallery-col'));
                        if (!append) {
                            // On full replace, columns start equal; use round-robin
                            newFigs.forEach((fig, i) => {
                                cols[i % cols.length].appendChild(fig);
                            });
                        } else {
                            // On append, prefer shortest; if equal heights, round-robin
                            let rr = 0;
                            const getShortest = () => cols.reduce((s, c) => (s.offsetHeight <= c.offsetHeight ? s : c));
                            newFigs.forEach((fig) => {
                                const heights = cols.map(c => c.offsetHeight || 0);
                                const minH = Math.min.apply(null, heights);
                                const maxH = Math.max.apply(null, heights);
                                let target;
                                if (maxH - minH < 2) {
                                    target = cols[rr % cols.length];
                                    rr++;
                                } else {
                                    target = getShortest();
                                }
                                target.appendChild(fig);
                            });
                        }
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
                        loadedImages = Array.from(newImages).map(img => {
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
                        loadedPosts = Array.from(newPosts).map(post => post.id.replace('post-', ''));
                        
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
    
    // Type filter button event listeners
    document.querySelectorAll('.type-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const typeValue = this.dataset.type;
            
            // Handle Images type - navigate to /images/ URL
            if (typeValue === 'images') {
                const currentUrl = window.location.pathname;
                
                // Don't navigate if already on images page
                if (currentUrl.includes('/images')) {
                    return;
                }
                
                // For homepage, navigate to site-wide images gallery
                if (currentUrl === '/' || currentUrl.match(/^\/$/)) {
                    window.location.href = '/images/';
                    return;
                }
                
                // For category/tag pages, append /images/
                const imageUrl = currentUrl.replace(/\/$/, '') + '/images/';
                window.location.href = imageUrl;
                return;
            }
            
            // Handle Posts button click - navigate back from images or handle filtering
            if (typeValue === 'posts') {
                const currentUrl = window.location.pathname;
                
                // If on any images page, navigate back to posts view
                if (currentUrl.includes('/images')) {
                    // If on site-wide image gallery (/images/), go to homepage
                    if (currentUrl === '/images/' || currentUrl.match(/^\/images\/$/)) {
                        window.location.href = '/';
                        return;
                    }
                    
                    // For category/tag image galleries, remove /images/
                    const postUrl = currentUrl.replace('/images/', '/').replace('/images', '/');
                    window.location.href = postUrl;
                    return;
                }
                
                // If not on images page, handle normal filtering
                if (currentFilters.post_type_filter !== typeValue) {
                    currentFilters.post_type_filter = typeValue;
                    applyFilters();
                }
                return;
            }
            
            // Handle other types (All, Recipes) - navigate back from images or filter
            if (typeValue === 'all' || typeValue === 'recipes') {
                const currentUrl = window.location.pathname;
                
                // If on any images page, navigate back to posts view
                if (currentUrl.includes('/images')) {
                    // If on site-wide image gallery (/images/), go to homepage
                    if (currentUrl === '/images/' || currentUrl.match(/^\/images\/$/)) {
                        window.location.href = '/';
                        return;
                    }
                    
                    // For category/tag image galleries, remove /images/
                    const postUrl = currentUrl.replace('/images/', '/').replace('/images', '/');
                    window.location.href = postUrl;
                    return;
                }
                
                // If not on images page, handle normal filtering
                if (currentFilters.post_type_filter !== typeValue) {
                    currentFilters.post_type_filter = typeValue;
                    applyFilters();
                }
                return;
            }
            
            // Regular filter handling for non-image types on post pages
            if (currentFilters.post_type_filter !== typeValue) {
                currentFilters.post_type_filter = typeValue;
                applyFilters();
            }
        });
    });
    
    // Load-more handling in separate js/load-more.js file
    
    // Initialize
    initializeLoadedPosts();
    updateFilterStates();
    
});