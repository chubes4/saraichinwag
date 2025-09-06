<?php
/**
 * The archive template file
 *
 * This template file displays the blog posts index for archive pages.
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <?php
                // Display breadcrumbs for archive pages
                sarai_chinwag_archive_breadcrumbs();
                
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
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
        // Determine if it's a category, tag, or search archive
        $category = is_category() ? get_queried_object()->slug : '';
        $tag = is_tag() ? get_queried_object()->slug : '';
        $searchTerm = is_search() ? get_search_query() : '';

        // Get total number of posts and posts per page setting
        $total_posts = $wp_query->found_posts;
        $posts_per_page = get_option('posts_per_page', 10);

        // Check if there are more posts available
        if ( $total_posts > $posts_per_page ) :
        ?>

        <div class="load-more-container">
            <button id="load-more" 
                    data-page="1" 
                    <?php echo $category ? 'data-category="' . esc_attr($category) . '"' : ''; ?>
                    <?php echo $tag ? 'data-tag="' . esc_attr($tag) . '"' : ''; ?>
                    <?php echo $searchTerm ? 'data-search="' . esc_attr($searchTerm) . '"' : ''; ?>
            >
                <?php esc_html_e( 'Load More', 'sarai-chinwag' ); ?>
            </button>
        </div>

        <?php endif; ?>

        <?php get_template_part( 'template-parts/archive-image-mode-link' ); ?>

    <?php else : ?>

        <p><?php esc_html_e( 'No posts found.', 'sarai-chinwag' ); ?></p>

    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
?>
