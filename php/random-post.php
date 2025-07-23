<?php
function extra_chill_redirect_to_random_post() {
    if (is_page('random-post')) { // Replace 'random-post' with the slug of your page
        $args = array(
            'post_type' => 'post',
            'orderby' => 'rand',
            'posts_per_page' => 1,
        );
        $random_post_query = new WP_Query($args);
        if ($random_post_query->have_posts()) {
            $random_post_query->the_post();
            wp_redirect(get_the_permalink());
            exit;
        }
    }
}
add_action('template_redirect', 'extra_chill_redirect_to_random_post');

function extra_chill_redirect_to_random_recipe() {
    // Skip if recipes are disabled
    if (sarai_chinwag_recipes_disabled()) {
        // Redirect to random post instead
        wp_redirect(home_url('/random-post'));
        exit;
    }
    
    if (is_page('random-recipe')) { // Replace 'random-recipe' with the slug of your page
        $args = array(
            'post_type' => 'recipe', // Only include the 'recipe' post type
            'orderby' => 'rand',
            'posts_per_page' => 1,
        );
        $random_recipe_query = new WP_Query($args);
        if ($random_recipe_query->have_posts()) {
            $random_recipe_query->the_post();
            wp_redirect(get_the_permalink());
            exit;
        }
    }
}
add_action('template_redirect', 'extra_chill_redirect_to_random_recipe');

function extra_chill_redirect_to_random_all() {
    if (is_page('random-all')) { // Replace 'random-all' with the slug of your page
        $post_types = array('post');
        if (!sarai_chinwag_recipes_disabled()) {
            $post_types[] = 'recipe';
        }
        
        $args = array(
            'post_type' => $post_types, // Include post types based on recipe status
            'orderby' => 'rand',
            'posts_per_page' => 1,
        );
        $random_all_query = new WP_Query($args);
        if ($random_all_query->have_posts()) {
            $random_all_query->the_post();
            wp_redirect(get_the_permalink());
            exit;
        }
    }
}
add_action('template_redirect', 'extra_chill_redirect_to_random_all');
