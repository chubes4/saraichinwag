<?php
/**
 * The search results template file
 *
 * This template file displays the search results.
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <?php
                // Display breadcrumbs for search results
                sarai_chinwag_archive_breadcrumbs();
            ?>
            <h1 class="page-title">
                <?php printf( esc_html__( 'Search Results for: %s', 'sarai-chinwag' ), '<span translate="no">' . get_search_query() . '</span>' ); ?>
            </h1>
        </header><!-- .page-header -->

        <?php do_action( 'before_post_grid' ); ?>

        <div class="post-grid" id="post-grid">
            <?php
            /* Start the Loop */
            while ( have_posts() ) :
                the_post();
                echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '">';
                get_template_part( 'template-parts/content', get_post_type() );
                echo '</article>';
            endwhile;
            ?>
        </div><!-- .post-grid -->

        <?php
        // Get total number of posts and posts per page setting
        global $wp_query;
        $total_posts = $wp_query->found_posts;
        $posts_per_page = get_option('posts_per_page', 10);

        // Check if there are more posts available
        if ( $total_posts > $posts_per_page ) :
        ?>

        <div class="load-more-container">
            <button id="load-more" 
                    data-page="1" 
                    data-search="<?php echo esc_attr(get_search_query()); ?>">
                <?php esc_html_e( 'Load More', 'sarai-chinwag' ); ?>
            </button>
        </div>

        <?php endif; ?>

        <?php get_template_part( 'template-parts/archive-image-mode-link' ); ?>

        <?php else : ?>

            <p>
        <?php printf( 
            esc_html__( 'No results for "%s". %s, or try %s.', 'sarai-chinwag' ),
            '<span translate="no">' . get_search_query() . '</span>',
            '<a href="#" class="search-toggle">' . esc_html__( 'Try again', 'sarai-chinwag' ) . '</a>',
            '<a href="/random-all">' . esc_html__( 'randomize', 'sarai-chinwag' ) . '</a>'
        ); ?>
    </p>



<?php endif; ?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();
?>
