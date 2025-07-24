<?php

function sarai_chinwag_enqueue_filter_scripts() {
    if (is_home() || is_archive() || is_search()) {
        wp_enqueue_script('sarai-chinwag-advanced-filters', get_template_directory_uri() . '/js/advanced-filters.js', array(), filemtime(get_template_directory() . '/js/advanced-filters.js'), true);
        wp_localize_script('sarai-chinwag-advanced-filters', 'sarai_chinwag_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('filter_posts_nonce'),
            'posts_per_page' => get_option('posts_per_page', 10)
        ));
    }
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_filter_scripts');


function sarai_chinwag_display_post_type_filters() {
    // Legacy function - post type filtering now handled by advanced filter bar
    // Kept for backwards compatibility but no longer outputs anything
    return;
}

function sarai_chinwag_has_both_posts_and_recipes() {
    // If recipes are disabled, no need to show filters
    if (sarai_chinwag_recipes_disabled()) {
        return false;
    }
    
    // For homepage, check if both post types exist on the site
    if (is_home()) {
        $has_posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields' => 'ids'
        ));
        
        $has_recipes = get_posts(array(
            'post_type' => 'recipe',
            'post_status' => 'publish', 
            'numberposts' => 1,
            'fields' => 'ids'
        ));
        
        return !empty($has_posts) && !empty($has_recipes);
    }
    
    // For archives and search, check within current query context
    if (is_archive() || is_search()) {
        global $wp_query;

        // Clone the original query
        $post_query_args = $wp_query->query_vars;
        $post_query_args['post_type'] = 'post';
        $post_query_args['posts_per_page'] = 1;
        $post_query = new WP_Query($post_query_args);
        $has_posts = $post_query->have_posts();

        // Clone the original query
        $recipe_query_args = $wp_query->query_vars;
        $recipe_query_args['post_type'] = 'recipe';
        $recipe_query_args['posts_per_page'] = 1;
        $recipe_query = new WP_Query($recipe_query_args);
        $has_recipes = $recipe_query->have_posts();

        wp_reset_postdata();
        
        return $has_posts && $has_recipes;
    }

    return false;
}

add_action('before_post_grid', 'sarai_chinwag_display_post_type_filters');
function sarai_chinwag_filter_posts() {
    check_ajax_referer('filter_posts_nonce', 'nonce');

    // Get sort parameter
    $sort_by = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']) : 'random';
    
    // Get post type filters
    $post_type_filter = isset($_POST['post_type_filter']) ? sanitize_text_field($_POST['post_type_filter']) : 'all';
    
    // Legacy support for existing checkbox system
    $filter_blog_posts = isset($_POST['filter_blog_posts']) ? sanitize_text_field($_POST['filter_blog_posts']) : '';
    $filter_recipes = isset($_POST['filter_recipes']) ? sanitize_text_field($_POST['filter_recipes']) : '';
    
    // Determine post types to query
    $post_types = array();
    if ($post_type_filter === 'posts') {
        $post_types = array('post');
    } elseif ($post_type_filter === 'recipes') {
        $post_types = array('recipe');
    } elseif ($post_type_filter === 'all') {
        $post_types = array('post');
        if (!sarai_chinwag_recipes_disabled()) {
            $post_types[] = 'recipe';
        }
    } else {
        // Legacy checkbox system
        if ($filter_blog_posts === 'true') {
            $post_types[] = 'post';
        }
        if ($filter_recipes === 'true') {
            $post_types[] = 'recipe';
        }
        
        // Default to all if none specified
        if (empty($post_types)) {
            $post_types = array('post');
            if (!sarai_chinwag_recipes_disabled()) {
                $post_types[] = 'recipe';
            }
        }
    }

    $loaded_posts = isset($_POST['loadedPosts']) ? json_decode(stripslashes($_POST['loadedPosts']), true) : array();
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Retrieve the posts per page setting from the admin
    $posts_per_page = get_option('posts_per_page', 10);

    // Build query arguments based on sort type
    $args = array(
        'post_type' => $post_types,
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
        'post__not_in' => $loaded_posts,
    );

    // Add category/tag/search constraints
    if ($category) {
        $args['category_name'] = $category;
    }
    if ($tag) {
        $args['tag'] = $tag;
    }
    if ($search) {
        $args['s'] = $search;
    }

    // Apply sorting based on sort_by parameter
    switch ($sort_by) {
        case 'popular':
            $args['meta_key'] = '_post_views';
            $args['orderby'] = 'meta_value_num date';
            $args['order'] = 'DESC';
            $args['meta_query'] = array(
                array(
                    'key' => '_post_views',
                    'compare' => 'EXISTS'
                )
            );
            break;
            
        case 'recent':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
            
        case 'oldest':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
            
        case 'random':
        default:
            $args['orderby'] = 'rand';
            break;
    }

    // Execute the query
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '">';
            get_template_part('template-parts/content', get_post_type());
            echo '</article>';
        endwhile;
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No posts found.', 'sarai-chinwag') . '</p>';
    }

    wp_die();
}
add_action('wp_ajax_filter_posts', 'sarai_chinwag_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'sarai_chinwag_filter_posts');
