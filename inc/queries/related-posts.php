<?php

/**
 * Helper function to get posts from specific taxonomy terms
 */
function sarai_chinwag_get_posts_from_taxonomy_terms($term_ids, $taxonomy, $post_types, $exclude_post_id, $limit) {
    if (empty($term_ids) || $limit <= 0) {
        return [];
    }

    return get_posts([
        'post_type' => $post_types,
        'posts_per_page' => $limit,
        'post__not_in' => [$exclude_post_id],
        'tax_query' => [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_ids,
                'operator' => 'IN'
            ]
        ],
        'orderby' => 'rand',
        'post_status' => 'publish'
    ]);
}

/**
 * Get hierarchically related posts with fallback system
 * Priority: Tags → Direct Categories → Parent Categories → Site-wide random
 */
function sarai_chinwag_get_hierarchical_related_posts($current_post_id, $limit = 3) {
    $related_posts = [];
    $post_types = ['post'];
    if (!sarai_chinwag_recipes_disabled()) {
        $post_types[] = 'recipe';
    }

    // Priority 1: Same tags
    $tags = get_the_tags($current_post_id);
    if (!empty($tags)) {
        $tag_ids = wp_list_pluck($tags, 'term_id');
        $related_posts = array_merge($related_posts,
            sarai_chinwag_get_posts_from_taxonomy_terms($tag_ids, 'post_tag', $post_types, $current_post_id, $limit - count($related_posts))
        );
    }

    // Priority 2: Direct categories
    if (count($related_posts) < $limit) {
        $categories = get_the_category($current_post_id);
        if (!empty($categories)) {
            $cat_ids = wp_list_pluck($categories, 'term_id');
            $related_posts = array_merge($related_posts,
                sarai_chinwag_get_posts_from_taxonomy_terms($cat_ids, 'category', $post_types, $current_post_id, $limit - count($related_posts))
            );
        }
    }

    // Priority 3: Parent categories (hierarchical traversal)
    if (count($related_posts) < $limit) {
        $categories = get_the_category($current_post_id);
        $parent_categories = [];
        foreach ($categories as $category) {
            $ancestors = get_ancestors($category->term_id, 'category');
            $parent_categories = array_merge($parent_categories, $ancestors);
        }
        $parent_categories = array_unique($parent_categories);

        if (!empty($parent_categories)) {
            $related_posts = array_merge($related_posts,
                sarai_chinwag_get_posts_from_taxonomy_terms($parent_categories, 'category', $post_types, $current_post_id, $limit - count($related_posts))
            );
        }
    }

    // Fallback: Site-wide random if still under limit
    if (count($related_posts) < $limit) {
        $remaining = $limit - count($related_posts);
        $exclude_ids = array_merge([$current_post_id], wp_list_pluck($related_posts, 'ID'));
        $fallback_posts = get_posts([
            'post_type' => $post_types,
            'posts_per_page' => $remaining,
            'post__not_in' => $exclude_ids,
            'orderby' => 'rand',
            'post_status' => 'publish'
        ]);
        $related_posts = array_merge($related_posts, $fallback_posts);
    }

    return array_slice($related_posts, 0, $limit);
}

function sarai_chinwag_random_discovery() {
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }

    global $post;
    $current_post_id = $post->ID;
    
    // Check cache first - random discovery cached for 15 minutes
    $cache_key = "random_discovery_{$current_post_id}";
    $cached_content = wp_cache_get($cache_key, 'sarai_chinwag_related');
    
    if (false !== $cached_content) {
        echo $cached_content;
        return;
    }
    
    // Start output buffering to cache the result
    ob_start();

    // Get 3 hierarchically related posts using intelligent fallback system
    $related_posts = sarai_chinwag_get_hierarchical_related_posts($current_post_id, 3);

    if (empty($related_posts)) {
        ob_end_clean();
        return;
    }

    // Display gallery discovery badges before "Keep Exploring" section
    sarai_chinwag_gallery_discovery_badges();

    echo '<aside class="random-discovery">';
    echo '<h2 class="widget-title">' . __('Keep Exploring', 'sarai-chinwag') . '</h2>';
    echo '<div class="post-grid discovery-grid">';

    foreach ($related_posts as $related_post) {
        // Set up post data for template functions
        setup_postdata($related_post);

        echo '<article id="discovery-post-' . get_the_ID() . '" class="discovery-post">';

        // Thumbnail
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            the_post_thumbnail('grid-thumb', array('itemprop' => 'image'));
            echo '</a>';
            echo '</div>';
        }

        // Title
        echo '<h3 class="entry-title">';
        echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . get_the_title() . '</a>';
        echo '</h3>';

        echo '</article>';
    }

    wp_reset_postdata();
    echo '</div>'; // End of discovery-grid
    echo '</aside>';
    
    // Cache the output for 15 minutes
    $output = ob_get_contents();
    wp_cache_set($cache_key, $output, 'sarai_chinwag_related', 15 * MINUTE_IN_SECONDS);
    
    ob_end_flush();
}

add_action('after_post_main', 'sarai_chinwag_random_discovery');
