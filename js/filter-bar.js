/**
 * Advanced Filter Bar System with AJAX
 */
document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('load-more');
    const postGrid = document.getElementById('post-grid');
    const filterBar = document.getElementById('filter-bar');
    
    if (!postGrid || !filterBar) {
        return;
    }

    let isImageGallery = document.getElementById('filter-image-gallery') ? 
        document.getElementById('filter-image-gallery').value === '1' : false;
    const path = window.location.pathname;
    if (!isImageGallery) {
        if (path === '/images/' || /\/images\/?$/.test(path)) {
            isImageGallery = true;
        }
    }
    const isAllSiteImages = isImageGallery && (path === '/images/' || path === '/images');

    /**
     * Determine initial filter type based on page context
     * @returns {string} Initial filter type
     */
    function determineInitialType() {
        const activeBtn = document.querySelector('.type-btn.active');
        if (activeBtn?.dataset?.type) {
            return activeBtn.dataset.type;
        }
        
        if (isImageGallery) return 'images';
        return document.querySelector('.type-btn[data-type="all"]') ? 'all' : 'posts';
    }

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

    /**
     * Initialize arrays of loaded content IDs from existing grid
     */
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
    
    function updateFilterStates() {
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.sort === currentFilters.sort_by) {
                btn.classList.add('active');
            }
        });
        
        document.querySelectorAll('.type-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.type === currentFilters.post_type_filter) {
                btn.classList.add('active');
            }
            if (isImageGallery && btn.dataset.type === 'images') {
                btn.classList.add('active');
            }
        });
    }
    
    
    /**
     * Load posts or images via AJAX with current filter settings
     * @param {number} page Page number
     * @param {boolean} append Whether to append or replace
     */
    function loadPosts(page = 1, append = false) {
        
        const xhr = new XMLHttpRequest();
        const data = new FormData();

        const actionName = isImageGallery ? 'filter_images' : 'filter_posts';
        data.append('action', actionName);
        data.append('nonce', sarai_chinwag_ajax.nonce);
        data.append('page', page);
        data.append('sort_by', currentFilters.sort_by);
        data.append('post_type_filter', currentFilters.post_type_filter);
        
        if (isImageGallery) {
            data.append('loadedImages', JSON.stringify(append ? loadedImageIds : []));
            
            const loadMoreBtn = document.getElementById('load-more');
            const hasAllSiteAttr = loadMoreBtn && loadMoreBtn.getAttribute('data-all-site') === 'true';
            if (hasAllSiteAttr || isAllSiteImages) {
                data.append('all_site', 'true');
            }
        } else {
            data.append('loadedPosts', JSON.stringify(append ? loadedPostIds : []));
        }

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
                                const contentType = isImageGallery ? 'images' : 'posts';
                        SaraiGalleryUtils.getNoContentMessage(contentType).then(template => {
                            postGrid.innerHTML = template;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'none';
                            }
                        }).catch(() => {
                            const fallback = isImageGallery ? 'No images found.' : 'No posts found.';
                            postGrid.innerHTML = `<p class="no-content-message">${fallback}</p>`;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'none';
                            }
                        });
                    }
                } else {
                    if (isImageGallery) {
                        const tmp = document.createElement('div');
                        tmp.innerHTML = response;
                        const newFigs = Array.from(tmp.querySelectorAll('figure.gallery-item'));

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
                                postGrid.insertAdjacentHTML('beforeend', response);
                        } else {
                                postGrid.innerHTML = response;
                            currentPage = 1;
                            if (loadMoreButton) {
                                loadMoreButton.style.display = 'block';
                                loadMoreButton.disabled = false;
                                loadMoreButton.textContent = 'Load More';
                            }
                        }
                    }

                    if (isImageGallery) {
                        const newImages = postGrid.querySelectorAll('.gallery-item img');
                        loadedImageIds = Array.from(newImages).map(img => {
                            const match = img.className.match(/wp-image-(\d+)/);
                            return match ? match[1] : '';
                        }).filter(id => id !== '');
                        
                        const loadedImagesCount = (response.match(/<figure[^>]*class=\"[^\"]*gallery-item/g) || []).length;
                        if (loadMoreButton && loadedImagesCount < sarai_chinwag_ajax.posts_per_page) {
                            loadMoreButton.textContent = 'No more images';
                            loadMoreButton.disabled = true;
                        }
                    } else {
                        const newPosts = postGrid.querySelectorAll('article');
                        loadedPostIds = Array.from(newPosts).map(post => post.id.replace('post-', ''));
                        
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
    
    function applyFilters() {
        currentPage = 1;
        updateFilterStates();
        loadPosts(1, false);
    }
    
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
    
    function navigateToImages() {
        const currentUrl = window.location.pathname;
        const currentSearch = window.location.search;
        
        if (currentUrl.includes('/images')) return;
        
        if (currentSearch && currentSearch.includes('s=')) {
            window.location.href = currentUrl + 'images/' + currentSearch;
            return;
        }
        
        const imageUrl = (currentUrl === '/' || currentUrl.match(/^\/$/)) 
            ? '/images/' 
            : currentUrl.replace(/\/$/, '') + '/images/';
        window.location.href = imageUrl;
    }
    
    function navigateFromImages() {
        const currentUrl = window.location.pathname;
        const currentSearch = window.location.search;
        
        if (!currentUrl.includes('/images')) return false;
        
        if (currentSearch && currentSearch.includes('s=')) {
            const postUrl = currentUrl.replace('/images/', '/').replace('/images', '/');
            window.location.href = postUrl + currentSearch;
            return true;
        }
        
        const postUrl = (currentUrl === '/images/' || currentUrl.match(/^\/images\/$/))
            ? '/'
            : currentUrl.replace('/images/', '/').replace('/images', '/');
        window.location.href = postUrl;
        return true;
    }
    
    function handleFilterOrNavigate(typeValue) {
        if (navigateFromImages()) return;
        
        if (currentFilters.post_type_filter !== typeValue) {
            currentFilters.post_type_filter = typeValue;
            applyFilters();
        }
    }

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
    
    
    initializeLoadedContent();
    updateFilterStates();
    
});