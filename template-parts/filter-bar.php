<?php
/**
 * Template part for displaying the advanced filter bar
 * 
 * Provides AJAX-powered filtering controls for sort options (Random, Popular, Recent, Oldest)
 * and content type filtering (All, Posts, Recipes, Images). Includes context detection
 * for image gallery mode and maintains filter state across load more operations.
 *
 * @package Sarai_Chinwag
 * @since 2.0.0
 */

// Get current page context for filter persistence
// Consider both term-specific and site-wide image gallery routes using native endpoint
// Must check both query var exists AND URL contains /images to avoid false positives on normal archives
$has_images_var = get_query_var('images') !== false;
$url_has_images = strpos($_SERVER['REQUEST_URI'], '/images/') !== false || strpos($_SERVER['REQUEST_URI'], '/images') !== false;
$is_image_gallery = $has_images_var && $url_has_images;
$category = is_category() ? get_queried_object()->slug : '';
$tag = is_tag() ? get_queried_object()->slug : '';
$search_term = is_search() ? get_search_query() : '';
?>

<div class="filter-bar" id="filter-bar">
    <div class="filter-section sort-filters">
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
    
    <?php 
    // Determine what buttons to show
    $has_both_post_types = !sarai_chinwag_recipes_disabled() && sarai_chinwag_has_both_posts_and_recipes();
    $can_show_mode_toggle = is_category() || is_tag() || is_home() || is_search() || $is_image_gallery;
    
    // Show type filters if we have multiple post types OR can show mode toggle
    $show_type_filters = $has_both_post_types || $can_show_mode_toggle;
    
    if ($show_type_filters) :
    ?>
    <div class="filter-section type-filters">
        <div class="filter-buttons">
            <?php 
            // Show All button only when we have both posts AND recipes
            if ($has_both_post_types) : 
            ?>
            <button class="filter-btn type-btn <?php echo (!$is_image_gallery) ? 'active' : ''; ?>" data-type="all">
                <?php _e('All', 'sarai-chinwag'); ?>
            </button>
            <button class="filter-btn type-btn" data-type="posts">
                <?php _e('Posts', 'sarai-chinwag'); ?>
            </button>
            <button class="filter-btn type-btn" data-type="recipes">
                <?php _e('Recipes', 'sarai-chinwag'); ?>
            </button>
            <?php else : ?>
            <!-- When only posts exist, show Posts button (always active when not in image mode) -->
            <button class="filter-btn type-btn <?php echo $is_image_gallery ? '' : 'active'; ?>" data-type="posts">
                <?php _e('Posts', 'sarai-chinwag'); ?>
            </button>
            <?php endif; ?>
            
            <?php if ($can_show_mode_toggle) : ?>
                <button class="filter-btn type-btn <?php echo $is_image_gallery ? 'active' : ''; ?>" data-type="images">
                    <?php _e('Images', 'sarai-chinwag'); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Hidden data for JavaScript -->
    <div class="filter-data">
        <input type="hidden" id="filter-category" value="<?php echo esc_attr($category); ?>">
        <input type="hidden" id="filter-tag" value="<?php echo esc_attr($tag); ?>">
        <input type="hidden" id="filter-search" value="<?php echo esc_attr($search_term); ?>">
    <input type="hidden" id="filter-image-gallery" value="<?php echo $is_image_gallery ? '1' : '0'; ?>">
    </div>
</div>

