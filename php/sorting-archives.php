<?php

function sarai_chinwag_enqueue_filter_scripts() {
    if (is_home() || is_archive() || is_search()) {
        wp_enqueue_script('sarai-chinwag-filters', get_template_directory_uri() . '/js/filters.js', array(), filemtime(get_template_directory() . '/js/filters.js'), true);
        wp_localize_script('sarai-chinwag-filters', 'sarai_chinwag_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('filter_posts_nonce'),
            'posts_per_page' => get_option('posts_per_page', 10)
        ));
    }
}
add_action('wp_enqueue_scripts', 'sarai_chinwag_enqueue_filter_scripts');


function sarai_chinwag_display_post_type_filters() {
    if (sarai_chinwag_has_both_posts_and_recipes()) {
        ?>
        <div class="post-type-filters">
            <label>
                <input type="checkbox" id="filter-blog-posts" checked>
                Blog Posts
            </label>
            <label>
                <input type="checkbox" id="filter-recipes" checked>
                Recipes
            </label>
        </div>
        <?php
    }
}

function sarai_chinwag_has_both_posts_and_recipes() {
    $has_posts = false;
    $has_recipes = false;

    if (is_home() || is_archive() || is_search()) {
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
    }

    return $has_posts && $has_recipes;
}

add_action('before_post_grid', 'sarai_chinwag_display_post_type_filters');
function sarai_chinwag_filter_posts() {
    check_ajax_referer('filter_posts_nonce', 'nonce');

    $post_types = array();

    $filter_blog_posts = isset($_POST['filter_blog_posts']) ? sanitize_text_field($_POST['filter_blog_posts']) : '';
    $filter_recipes = isset($_POST['filter_recipes']) ? sanitize_text_field($_POST['filter_recipes']) : '';
    
    if ($filter_blog_posts === 'true') {
        $post_types[] = 'post';
    }
    if ($filter_recipes === 'true') {
        $post_types[] = 'recipe';
    }

    $loaded_posts = isset($_POST['loadedPosts']) ? json_decode(stripslashes($_POST['loadedPosts']), true) : array();
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    // Retrieve the posts per page setting from the admin
    $posts_per_page = get_option('posts_per_page', 10); // Default to 10 if the option is not set

    // Initial Query to get remaining posts
    $initial_args = array(
        'post_type' => !empty($post_types) ? $post_types : array('post', 'recipe'), // Default to both if no filters set
        'posts_per_page' => -1, // Retrieve all matching posts
        'post_status' => 'publish',
        'post__not_in' => $loaded_posts,
        'fields' => 'ids',
    );

    if ($category) {
        $initial_args['category_name'] = $category;
    }

    if ($tag) {
        $initial_args['tag'] = $tag;
    }

    if ($search) {
        $initial_args['s'] = $search;
    }

    // Execute the initial query to get remaining post IDs
    $initial_query = new WP_Query($initial_args);
    $remaining_posts = $initial_query->posts;
    $remaining_posts_count = count($remaining_posts);

    // If no remaining posts, return no posts found
    if ($remaining_posts_count == 0) {
        echo '<p>' . esc_html__('No posts found.', 'sarai-chinwag') . '</p>';
        wp_die();
    }

    // Shuffle the remaining posts to randomize the selection
    shuffle($remaining_posts);

    // Calculate the number of posts to load
    $posts_to_load = min($posts_per_page, $remaining_posts_count);

    // Get the IDs of posts to load in this request
    $post_ids_to_load = array_slice($remaining_posts, 0, $posts_to_load);

    // Construct the main query arguments
    $args = array(
        'post_type' => !empty($post_types) ? $post_types : array('post', 'recipe'), // Default to both if no filters set
        'posts_per_page' => $posts_to_load,
        'post_status' => 'publish',
        'post__in' => $post_ids_to_load,
        'orderby' => 'post__in',
    );

    // Execute the main query
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
