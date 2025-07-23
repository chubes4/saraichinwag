<?php
/**
 * The template for displaying single Recipe posts.
 *
 * @package ExtraChill
 */

// If recipes are disabled, use the standard single post template
if (sarai_chinwag_recipes_disabled()) {
    include(get_template_directory() . '/single.php');
    return;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    while ( have_posts() ) :
        the_post();

        echo '<article id="post-' . get_the_ID() . '" class="' . join(' ', get_post_class()) . '" itemscope itemtype="http://schema.org/Recipe">';

        // Include the template part for the entry header and rating
        get_template_part( 'template-parts/content', 'recipe' );

        // Add the schema data if not already included
        if ( ! did_action('extra_chill_recipe_schema') ) {
            $schema_data = extra_chill_recipe_schema();
            $schema_output = $schema_data['output'];
            $rating_display = $schema_data['rating_display'];
            if (!empty($schema_output)) {
                echo $schema_output;
                do_action('extra_chill_recipe_schema'); // Mark schema as included
            }
        }
    ?>

    <footer class="entry-footer">
    <!-- Star Rating Widget -->
    <div id="rating-widget" data-post-id="<?php the_ID(); ?>">
        <h3>Rate this Recipe:</h3>
        <?php wp_nonce_field('rate_recipe_nonce', 'rate_recipe_nonce'); ?>
        <div class="stars">
            <span class="star" data-value="1">&#9734;</span>
            <span class="star" data-value="2">&#9734;</span>
            <span class="star" data-value="3">&#9734;</span>
            <span class="star" data-value="4">&#9734;</span>
            <span class="star" data-value="5">&#9734;</span>
        </div>
        <span id="average-rating"><?php echo esc_html($rating_display); ?></span>
        <span id="user-rating"></span>
    </div>
        <?php
        // Display categories with schema markup
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $categories_list = '';
            $categories_text = [];
            foreach ( $categories as $category ) {
                $category_link = sprintf(
                    '<a href="%1$s" rel="category tag">%2$s</a>',
                    esc_url( get_category_link( $category->term_id ) ),
                    esc_html( $category->name )
                );
                $categories_list .= $category_link . ', ';
                $categories_text[] = $category->name;
            }
            // Remove trailing comma and space
            $categories_list = rtrim( $categories_list, ', ' );
            printf( '<strong>%s</strong> %s<br>', esc_html__( 'Categories: ', 'sarai-chinwag' ), $categories_list );

            // Schema markup for recipeCategory
            echo '<meta itemprop="recipeCategory" content="' . esc_attr( implode( ', ', $categories_text ) ) . '">';
        }

        // Display tags with schema markup
        $tags = get_the_tags();
        if ( ! empty( $tags ) ) {
            $tags_list = '';
            $tags_text = [];
            foreach ( $tags as $tag ) {
                $tag_link = sprintf(
                    '<a href="%1$s" rel="tag">%2$s</a>',
                    esc_url( get_tag_link( $tag->term_id ) ),
                    esc_html( $tag->name )
                );
                $tags_list .= $tag_link . ', ';
                $tags_text[] = $tag->name;
            }
            // Remove trailing comma and space
            $tags_list = rtrim( $tags_list, ', ' );
            printf( '<strong>%s</strong> %s<br>', esc_html__( 'Tags: ', 'sarai-chinwag' ), $tags_list );

            // Schema markup for keywords
            echo '<meta itemprop="keywords" content="' . esc_attr( implode( ', ', $tags_text ) ) . '">';
        }
        ?>
    </footer><!-- .entry-footer -->
    
    <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

        // Close the article tag here
        echo '</article>';

    endwhile; // End of the loop.
    ?>

    </main><!-- #main -->
    <?php do_action( 'after_post_main' ); ?>
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>
