<?php
/**
 * Register a custom post type called "Recipe".
 */
function extra_chill_register_recipe_post_type() {
    $labels = array(
        'name'                  => _x( 'Recipes', 'Post type general name', 'sarai-chinwag' ),
        'singular_name'         => _x( 'Recipe', 'Post type singular name', 'sarai-chinwag' ),
        'menu_name'             => _x( 'Recipes', 'Admin Menu text', 'sarai-chinwag' ),
        'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'sarai-chinwag' ),
        'add_new'               => __( 'Add New', 'recipe', 'sarai-chinwag' ),
        'add_new_item'          => __( 'Add New Recipe', 'sarai-chinwag' ),
        'new_item'              => __( 'New Recipe', 'sarai-chinwag' ),
        'edit_item'             => __( 'Edit Recipe', 'sarai-chinwag' ),
        'view_item'             => __( 'View Recipe', 'sarai-chinwag' ),
        'all_items'             => __( 'All Recipes', 'sarai-chinwag' ),
        'search_items'          => __( 'Search Recipes', 'sarai-chinwag' ),
        'parent_item_colon'     => __( 'Parent Recipes:', 'sarai-chinwag' ),
        'not_found'             => __( 'No recipes found.', 'sarai-chinwag' ),
        'not_found_in_trash'    => __( 'No recipes found in Trash.', 'sarai-chinwag' ),
        'featured_image'        => _x( 'Recipe Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'sarai-chinwag' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'sarai-chinwag' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'sarai-chinwag' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'sarai-chinwag' ),
        'archives'              => _x( 'Recipe archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'sarai-chinwag' ),
        'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'sarai-chinwag' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'sarai-chinwag' ),
        'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'sarai-chinwag' ),
        'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'sarai-chinwag' ),
        'items_list'            => _x( 'Recipes list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'sarai-chinwag' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'recipe' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'         => array( 'category', 'post_tag' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'recipe', $args );
}

add_action( 'init', 'extra_chill_register_recipe_post_type' );





