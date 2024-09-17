<?php 
// Modify the main query for the home page, archive pages, and search results to show random posts
function sarai_chinwag_random_home_archive_search_query( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ! $query->is_feed() && ( $query->is_home() || $query->is_archive() ) ) {
        $query->set( 'post_type', array( 'post', 'recipe' ) );
        $query->set( 'orderby', 'rand' );

        $posts_per_page = get_option('posts_per_page');
        if ( $posts_per_page ) {
            $query->set( 'posts_per_page', $posts_per_page );
        } else {
            $query->set( 'posts_per_page', 10 ); // Default to 10 if the option is not set
        }
    }
}
add_action( 'pre_get_posts', 'sarai_chinwag_random_home_archive_search_query' );

