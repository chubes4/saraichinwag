<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Sarai_Chinwag
 */

?>
</div> <!-- .content-wrap -->

<?php do_action( 'before_footer' ); ?>

<footer id="colophon" class="site-footer">
    <button onclick="window.location.href='<?php echo esc_url(home_url('/random-all')); ?>'" class="surprise-me">
        <?php _e('Surprise Me', 'sarai-chinwag'); ?>
        <svg id="random-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path d="M18 9v-3c-1 0-3.308-.188-4.506 2.216l-4.218 8.461c-1.015 2.036-3.094 3.323-5.37 3.323h-3.906v-2h3.906c1.517 0 2.903-.858 3.58-2.216l4.218-8.461c1.356-2.721 3.674-3.323 6.296-3.323v-3l6 4-6 4zm-9.463 1.324l1.117-2.242c-1.235-2.479-2.899-4.082-5.748-4.082h-3.906v2h3.906c2.872 0 3.644 2.343 4.631 4.324zm15.463 8.676l-6-4v3c-3.78 0-4.019-1.238-5.556-4.322l-1.118 2.241c1.021 2.049 2.1 4.081 6.674 4.081v3l6-4z"/>
        </svg>
    </button>
    <div class="footer-clouds">
    <!-- Category Cloud -->
    <div class="footer-category-cloud">
        <h2><?php _e('Categories', 'sarai-chinwag'); ?></h2>
        <?php
        // Get categories with caching
        $categories = get_transient('footer_categories');
        if ( false === $categories ) {
            $categories = get_categories(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true, // Only get categories with posts
                'number' => 0,
            ));
            set_transient('footer_categories', $categories, HOUR_IN_SECONDS);
        }

        $counts = [];
        foreach ($categories as $category) {
            $term_ids = get_term_children($category->term_id, 'category');
            $term_ids[] = $category->term_id;

            $terms = get_terms(array(
                'taxonomy'   => 'category',
                'include'    => $term_ids,
                'hide_empty' => false,
            ));

            $post_count = array_sum(wp_list_pluck($terms, 'count'));
            $counts[$category->term_id] = $post_count;
        }

        // Get min and max post counts for scaling
        $min_count = min($counts);
        $max_count = max($counts);
        $min_font_size = 16; // Minimum font size
        $max_font_size = 34; // Maximum font size

        foreach ($categories as $category) {
            $post_count = $counts[$category->term_id];

            // Scale the font size based on the normalized count
            if ($post_count > 3) {
                if ($max_count == $min_count) {
                    // Avoid division by zero if all counts are the same
                    $font_size = ($max_font_size + $min_font_size) / 2;
                } else {
                    $normalized = ($post_count - $min_count) / ($max_count - $min_count);
                    $font_size = $min_font_size + $normalized * ($max_font_size - $min_font_size);
                }

                echo '<a href="' . get_category_link($category->term_id) . '" style="font-size: ' . round($font_size) . 'px;">' . $category->name . ' (' . $post_count . ')</a> ';
            }
        }
        ?>
    </div>

    <!-- Tag Cloud -->
    <div class="footer-tag-cloud">
        <h2><?php _e('Tags', 'sarai-chinwag'); ?></h2>
        <?php
        // Get tags with caching
        $tags = get_transient('footer_tags');
        if ( false === $tags ) {
            $tags = get_tags(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true, // Only get tags with posts
                'number' => 0,
            ));
            set_transient('footer_tags', $tags, HOUR_IN_SECONDS);
        }

        $tag_counts = wp_list_pluck($tags, 'count');
        $min_tag_count = min($tag_counts);
        $max_tag_count = max($tag_counts);

        foreach ($tags as $tag) {
            $post_count = $tag->count;

            // Scale the font size for tags similarly
            if ($post_count > 3) {
                if ($max_tag_count == $min_tag_count) {
                    $font_size = ($max_font_size + $min_font_size) / 2;
                } else {
                    $normalized = ($post_count - $min_tag_count) / ($max_tag_count - $min_tag_count);
                    $font_size = $min_font_size + $normalized * ($max_font_size - $min_font_size);
                }

                echo '<a href="' . get_tag_link($tag->term_id) . '" style="font-size: ' . round($font_size) . 'px;">' . $tag->name . ' (' . $post_count . ')</a> ';
            }
        }
        ?>
    </div>
</div><!-- .footer-clouds -->

    <?php
    // Display the footer menu if it's set
    if ( has_nav_menu( 'footer' ) ) {
        wp_nav_menu( array(
            'theme_location' => 'footer',
            'menu_id'        => 'footer-menu',
            'menu_class'     => 'footer-menu',
        ) );
    }
    ?>
    <div class="site-info">
        <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved. Built by <a href="https://chubes.net" style="color: #fff; text-decoration: underline; text-decoration-style: dotted; text-decoration-color:#1fc5e2;"  target="_blank">Chubes</a>.</p>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
