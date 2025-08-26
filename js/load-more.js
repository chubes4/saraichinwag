document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButton = document.getElementById('load-more');
    const postGrid = document.getElementById('post-grid');
    if (!loadMoreButton || !postGrid) return;

    // Read config injected by PHP
    const perPage = parseInt(window.sarai_chinwag_ajax && window.sarai_chinwag_ajax.posts_per_page ? window.sarai_chinwag_ajax.posts_per_page : 10, 10);
    let currentPage = 1;

    // Determine mode (images vs posts)
    // More robust: detect by container class (server-side HTML has class="image-gallery")
    const isImageGallery = postGrid.classList.contains('image-gallery');

    // Columns rendered server-side; adapt count for viewport (3 on mobile, 4 desktop)
    let galleryColumns = isImageGallery ? Array.from(postGrid.querySelectorAll('.gallery-col')) : [];

    function desiredColCount() {
        if (window.innerWidth < 450) return 1; // mobile
        if (window.innerWidth <= 1200) return 3; // tablet
        return 4; // desktop
    }

    function reflowColumnsTo(count) {
        if (!isImageGallery) return;
        // Collect all existing figures
        const figs = Array.from(postGrid.querySelectorAll('figure.gallery-item'));
        // Clear and rebuild columns
        postGrid.innerHTML = '';
        const cols = [];
        for (let i = 0; i < count; i++) {
            const col = document.createElement('div');
            col.className = 'gallery-col';
            postGrid.appendChild(col);
            cols.push(col);
        }
        // Distribute figures round-robin
        figs.forEach((fig, i) => {
            cols[i % count].appendChild(fig);
        });
        galleryColumns = cols;
    }

    // Ensure initial column count matches viewport preference
    if (isImageGallery && galleryColumns.length && galleryColumns.length !== desiredColCount()) {
        reflowColumnsTo(desiredColCount());
    }

    // Debounced resize handler to adjust columns on orientation/size changes
    let resizeTimer;
    window.addEventListener('resize', () => {
        if (!isImageGallery) return;
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            const want = desiredColCount();
            const current = postGrid.querySelectorAll('.gallery-col').length || 0;
            if (current !== want) {
                reflowColumnsTo(want);
            }
            // refresh columns cache
            galleryColumns = Array.from(postGrid.querySelectorAll('.gallery-col'));
        }, 150);
    });

    function getShortestColumn() {
        if (!isImageGallery || galleryColumns.length === 0) return null;
        return galleryColumns.reduce((shortest, col) => (
            (shortest.offsetHeight <= col.offsetHeight) ? shortest : col
        ));
    }

    // Strict round-robin distribution across existing columns
    function getTotalFiguresInColumns() {
        if (!isImageGallery || galleryColumns.length === 0) return 0;
        return galleryColumns.reduce((sum, col) => sum + col.children.length, 0);
    }

    let rrIndex = 0; // round-robin fallback index across batches

    function appendFiguresBalanced(figs) {
        if (!isImageGallery) return false;
        // Refresh columns cache; filter replaces can invalidate the old list
        galleryColumns = Array.from(postGrid.querySelectorAll('.gallery-col'));

        // If no columns exist (unexpected), build them and move any existing figures
        if (galleryColumns.length === 0) {
            const want = desiredColCount();
            const existingFigs = Array.from(postGrid.querySelectorAll('figure.gallery-item'));
            // Clear and rebuild columns
            postGrid.innerHTML = '';
            for (let i = 0; i < want; i++) {
                const col = document.createElement('div');
                col.className = 'gallery-col';
                postGrid.appendChild(col);
            }
            galleryColumns = Array.from(postGrid.querySelectorAll('.gallery-col'));
            // Seed existing figures round-robin so heights start even
            existingFigs.forEach((fig, i) => {
                galleryColumns[i % galleryColumns.length].appendChild(fig);
            });
            rrIndex = existingFigs.length % galleryColumns.length;
        }

        figs.forEach((fig) => {
            // Decide target column: prefer shortest; if all equal, round-robin
            const heights = galleryColumns.map(c => c.offsetHeight || 0);
            const minH = Math.min.apply(null, heights);
            const maxH = Math.max.apply(null, heights);
            let targetCol;
            if (maxH - minH < 2) {
                targetCol = galleryColumns[rrIndex % galleryColumns.length];
                rrIndex++;
            } else {
                targetCol = getShortestColumn() || galleryColumns[0];
            }
            targetCol.appendChild(fig);
        });
        return true;
    }

    // JS no longer wraps/rebuilds columns; server renders columns to avoid FOUC

    function collectLoadedIds() {
        if (isImageGallery) {
            const imgs = postGrid.querySelectorAll('.gallery-item img');
            return Array.from(imgs).map(img => {
                const m = img.className.match(/wp-image-(\d+)/);
                return m ? m[1] : null;
            }).filter(Boolean);
        }
        const articles = postGrid.querySelectorAll('article');
        return Array.from(articles).map(a => a.id.replace('post-', ''));
    }

    function sendLoadRequest() {
        loadMoreButton.disabled = true;
        loadMoreButton.textContent = 'Loading...'; // No change here, keeping for context

        const loaded = collectLoadedIds();
        const xhr = new XMLHttpRequest();
        const data = new FormData();

        data.append('nonce', sarai_chinwag_ajax.nonce);
        data.append('page', currentPage + 1); // request next page
        data.append('posts_per_page', perPage);

        if (isImageGallery) {
            data.append('action', 'filter_images');
            data.append('loadedImages', JSON.stringify(loaded));
            const loadMoreBtn = document.getElementById('load-more');
            if (loadMoreBtn && loadMoreBtn.getAttribute('data-all-site') === 'true') {
                data.append('all_site', 'true');
            }

            // Respect selected sort and type filters
            const activeSort = document.querySelector('.sort-btn.active');
            if (activeSort && activeSort.dataset.sort) {
                data.append('sort_by', activeSort.dataset.sort);
            }
            const activeType = document.querySelector('.type-btn.active');
            if (activeType && activeType.dataset.type) {
                data.append('post_type_filter', activeType.dataset.type);
            }

            // Include context params when present (category/tag/search)
            const categoryEl = document.getElementById('filter-category');
            const tagEl = document.getElementById('filter-tag');
            const searchEl = document.getElementById('filter-search');
            if (categoryEl && categoryEl.value) data.append('category', categoryEl.value);
            if (tagEl && tagEl.value) data.append('tag', tagEl.value);
            if (searchEl && searchEl.value) data.append('search', searchEl.value);
        } else {
            data.append('action', 'filter_posts');
            data.append('loadedPosts', JSON.stringify(loaded));

            // Include any context params present on the page (category/tag/search)
            const categoryEl = document.getElementById('filter-category');
            const tagEl = document.getElementById('filter-tag');
            const searchEl = document.getElementById('filter-search');
            if (categoryEl && categoryEl.value) data.append('category', categoryEl.value);
            if (tagEl && tagEl.value) data.append('tag', tagEl.value);
            if (searchEl && searchEl.value) data.append('search', searchEl.value);
        }

        xhr.open('POST', sarai_chinwag_ajax.ajaxurl, true);
        xhr.onload = function () {
            loadMoreButton.disabled = false;
            if (xhr.status >= 200 && xhr.status < 400) {
                const resp = xhr.responseText.trim();
                if (resp === '0' || resp === '') {
                        loadMoreButton.textContent = 'No more';
                    loadMoreButton.disabled = true;
                    return;
                }

                // Append new content without disturbing existing layout
                if (isImageGallery) {
                    const tmp = document.createElement('div');
                    tmp.innerHTML = resp;
                    let newFigs = Array.from(tmp.querySelectorAll('figure.gallery-item'));
                    if (newFigs.length === 0) {
                        // Fallback: accept any figures if class is missing
                        newFigs = Array.from(tmp.querySelectorAll('figure'));
                    }
                    appendFiguresBalanced(newFigs);
                } else {
                    postGrid.insertAdjacentHTML('beforeend', resp);
                }

                // Advance page only when new content is received
                const addedCount = (
                    resp.match(/<article/gi) ||
                    resp.match(/<figure[^>]*class="[^"]*gallery-item/gi) ||
                    resp.match(/<figure/gi) ||
                    []
                ).length;
                if (addedCount === 0) {
                    // No recognizable items added; hide button
                    loadMoreButton.textContent = 'No more';
                    loadMoreButton.disabled = true;
                    return;
                }

                currentPage++;

                // If fewer than perPage items returned, disable further loads
                if (addedCount < perPage) {
                        loadMoreButton.textContent = 'No more';
                    loadMoreButton.disabled = true;
                } else {
                        loadMoreButton.textContent = 'Load More';
                    loadMoreButton.disabled = false;
                }
            } else {
                console.error('Load more request failed:', xhr.statusText);
            }
        };
        xhr.onerror = function () {
            loadMoreButton.disabled = false;
            console.error('Load more request error');
        };
        xhr.send(data);
    }

    loadMoreButton.addEventListener('click', function (e) {
        e.preventDefault();
        sendLoadRequest();
    });
});
