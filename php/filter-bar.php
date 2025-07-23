<?php
/**
 * Filter bar system for home and archive pages
 * Provides sorting options and post type filtering
 */

/**
 * Display the filter bar interface
 * Shows sort options and post type filters
 */
function sarai_chinwag_display_filter_bar() {
    // Only show on home, archive, and search pages
    if (!is_home() && !is_archive() && !is_search()) {
        return;
    }
    
    // Get current page context for filter persistence
    $category = is_category() ? get_queried_object()->slug : '';
    $tag = is_tag() ? get_queried_object()->slug : '';
    $search_term = is_search() ? get_search_query() : '';
    
    ?>
    <div class="filter-bar" id="filter-bar">
        <div class="filter-section sort-filters">
            <label class="filter-label"><?php _e('Sort by:', 'sarai-chinwag'); ?></label>
            <div class="filter-buttons">
                <button class="filter-btn sort-btn active" data-sort="random">
                    <?php _e('Random', 'sarai-chinwag'); ?>
                </button>
                <button class="filter-btn sort-btn" data-sort="popular">
                    <?php _e('Most Popular', 'sarai-chinwag'); ?>
                </button>
                <button class="filter-btn sort-btn" data-sort="recent">
                    <?php _e('Most Recent', 'sarai-chinwag'); ?>
                </button>
                <button class="filter-btn sort-btn" data-sort="oldest">
                    <?php _e('Oldest', 'sarai-chinwag'); ?>
                </button>
            </div>
        </div>
        
        <?php if (!sarai_chinwag_recipes_disabled() && sarai_chinwag_has_both_posts_and_recipes()) : ?>
        <div class="filter-section type-filters">
            <label class="filter-label"><?php _e('Content:', 'sarai-chinwag'); ?></label>
            <div class="filter-buttons">
                <button class="filter-btn type-btn active" data-type="all">
                    <?php _e('All', 'sarai-chinwag'); ?>
                </button>
                <button class="filter-btn type-btn" data-type="posts">
                    <?php _e('Posts', 'sarai-chinwag'); ?>
                </button>
                <button class="filter-btn type-btn" data-type="recipes">
                    <?php _e('Recipes', 'sarai-chinwag'); ?>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="filter-section filter-actions">
            <button class="filter-btn clear-btn" id="clear-filters">
                <?php _e('Clear All', 'sarai-chinwag'); ?>
            </button>
        </div>
        
        <!-- Hidden data for JavaScript -->
        <div class="filter-data" style="display: none;">
            <input type="hidden" id="filter-category" value="<?php echo esc_attr($category); ?>">
            <input type="hidden" id="filter-tag" value="<?php echo esc_attr($tag); ?>">
            <input type="hidden" id="filter-search" value="<?php echo esc_attr($search_term); ?>">
        </div>
    </div>
    
    <div class="filter-loading" id="filter-loading" style="display: none;">
        <div class="loading-spinner"></div>
        <span><?php _e('Loading...', 'sarai-chinwag'); ?></span>
    </div>
    <?php
}

/**
 * Check if current page has both posts and recipes for type filtering
 * Reuses existing function but adds context for filter bar
 */
function sarai_chinwag_show_type_filters() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        return false;
    }
    
    // Use existing function to check for both post types
    return sarai_chinwag_has_both_posts_and_recipes();
}

// Hook the filter bar to display before post grid
add_action('before_post_grid', 'sarai_chinwag_display_filter_bar');