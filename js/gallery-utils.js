/**
 * Shared gallery utilities for filter-bar.js and load-more.js
 */
window.SaraiGalleryUtils = (function() {
    
    /**
     * Determine responsive column count based on viewport
     */
    function getColumnCount() {
        if (window.innerWidth < 450) return 1;
        if (window.innerWidth <= 1200) return 3;
        return 4;
    }
    
    /**
     * Create gallery columns HTML structure
     */
    function createColumns(count) {
        const columns = [];
        for (let i = 0; i < count; i++) {
            const col = document.createElement('div');
            col.className = 'gallery-col';
            columns.push(col);
        }
        return columns;
    }
    
    /**
     * Get shortest column by height
     */
    function getShortestColumn(columns) {
        if (columns.length === 0) return null;
        return columns.reduce((shortest, col) => (
            shortest.offsetHeight <= col.offsetHeight ? shortest : col
        ));
    }
    
    /**
     * Load template from server
     */
    function loadTemplate(templateName, args = {}) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            const data = new FormData();
            
            data.append('action', 'load_template');
            data.append('nonce', sarai_chinwag_ajax.nonce);
            data.append('template', templateName);
            data.append('args', JSON.stringify(args));
            
            xhr.open('POST', sarai_chinwag_ajax.ajaxurl, true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    resolve(xhr.responseText.trim());
                } else {
                    reject(new Error('Template request failed: ' + xhr.statusText));
                }
            };
            xhr.onerror = function() {
                reject(new Error('Template request error'));
            };
            xhr.send(data);
        });
    }
    
    /**
     * Get no-content message template
     */
    function getNoContentMessage(contentType) {
        return loadTemplate('no-content', { content_type: contentType });
    }
    
    /**
     * Distribute figures across columns with balanced approach
     */
    function distributeFigures(figures, columns) {
        if (columns.length === 0) return;
        
        let rrIndex = 0;
        figures.forEach((fig) => {
            const heights = columns.map(c => c.offsetHeight || 0);
            const minH = Math.min(...heights);
            const maxH = Math.max(...heights);
            
            let targetCol;
            if (maxH - minH < 2) {
                targetCol = columns[rrIndex % columns.length];
                rrIndex++;
            } else {
                targetCol = getShortestColumn(columns);
            }
            targetCol.appendChild(fig);
        });
    }
    
    return {
        getColumnCount,
        createColumns,
        getShortestColumn,
        loadTemplate,
        getNoContentMessage,
        distributeFigures
    };
})();