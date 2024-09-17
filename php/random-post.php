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
        $args = array(
            'post_type' => array('post', 'recipe'), // Include both 'post' and 'recipe' post types
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
