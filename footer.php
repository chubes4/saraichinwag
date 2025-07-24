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
    
    <?php 
    // Pinterest Follow Button with Logo
    $pinterest_username = get_option('sarai_chinwag_pinterest_username', '');
    if (!empty($pinterest_username)) : ?>
    <div class="footer-pinterest">
        <a href="https://www.pinterest.com/<?php echo esc_attr($pinterest_username); ?>/" 
           target="_blank" 
           rel="noopener noreferrer"
           class="pinterest-follow-btn">
            <svg class="pinterest-logo" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,2A10,10 0 0,0 2,12C2,16.42 4.87,20.17 8.84,21.5C8.76,20.53 8.64,19.33 8.85,18.85L9.85,15.32C9.85,15.32 9.6,14.82 9.6,14.82C9.6,13.81 10.13,13.06 10.8,13.06C11.34,13.06 11.6,13.45 11.6,13.92C11.6,14.44 11.28,15.22 11.11,15.93C10.97,16.54 11.42,17.04 12,17.04C13.08,17.04 13.93,15.92 13.93,14.27C13.93,12.81 12.95,11.83 12,11.83C10.78,11.83 10.05,12.74 10.05,13.76C10.05,14.15 10.2,14.57 10.4,14.81C10.46,14.91 10.47,15 10.45,15.1L10.17,16.26C10.13,16.43 10.04,16.47 9.87,16.39C9.09,16 8.57,14.92 8.57,13.73C8.57,11.78 10,10.08 12.21,10.08C14.02,10.08 15.42,11.37 15.42,14.24C15.42,17.2 13.84,19.6 11.66,19.6C10.97,19.6 10.32,19.25 10.11,18.84L9.66,20.68C9.5,21.29 9.12,22.06 8.84,22.5C9.83,22.82 10.9,23 12,23A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
            </svg>
            <?php _e('Follow on Pinterest', 'sarai-chinwag'); ?>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="footer-clouds">
    <!-- Category Cloud -->
    <div class="footer-category-cloud">
        <h2><?php _e('Categories', 'sarai-chinwag'); ?></h2>
        <?php
        // Get categories and counts with indefinite caching (clears when content changes)
        $cache_version = wp_cache_get_last_changed('posts') . wp_cache_get_last_changed('terms');
        $cache_key = 'footer_category_data_' . md5($cache_version);
        $category_data = get_transient($cache_key);
        if ( false === $category_data ) {
            $categories = get_categories(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true, // Only get categories with posts
                'number' => 0,
            ));
            
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
            
            $category_data = array(
                'categories' => $categories,
                'counts' => $counts
            );
            set_transient($cache_key, $category_data, 0); // 0 = never expire (cleared when content changes)
        } else {
            $categories = $category_data['categories'];
            $counts = $category_data['counts'];
        }

        // Get min and max post counts for scaling
        if (empty($counts)) {
            $min_count = 0;
            $max_count = 0;
        } else {
            $min_count = min($counts);
            $max_count = max($counts);
        }
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

                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-cloud-link" style="--cloud-size: ' . round($font_size) . 'px;">' . esc_html($category->name) . ' (' . intval($post_count) . ')</a> ';
            }
        }
        ?>
    </div>

    <!-- Tag Cloud -->
    <div class="footer-tag-cloud">
        <h2><?php _e('Tags', 'sarai-chinwag'); ?></h2>
        <?php
        // Get tags with indefinite caching (clears when content changes)
        $tag_cache_key = 'footer_tags_' . md5($cache_version);
        $tags = get_transient($tag_cache_key);
        if ( false === $tags ) {
            $tags = get_tags(array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true, // Only get tags with posts
                'number' => 0,
            ));
            set_transient($tag_cache_key, $tags, 0); // 0 = never expire (cleared when content changes)
        }

        $tag_counts = wp_list_pluck($tags, 'count');
        
        // Check if we have any tags before using min/max
        if (empty($tag_counts)) {
            $min_tag_count = 0;
            $max_tag_count = 0;
        } else {
            $min_tag_count = min($tag_counts);
            $max_tag_count = max($tag_counts);
        }

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

                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-cloud-link" style="--cloud-size: ' . round($font_size) . 'px;">' . esc_html($tag->name) . ' (' . intval($post_count) . ')</a> ';
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
        <p>&copy; <?php echo date( 'Y' ); ?> <span translate="no"><?php bloginfo( 'name' ); ?></span>. All rights reserved. Built by <a href="https://chubes.net" class="footer-credit-link" target="_blank" translate="no">Chubes</a>.</p>
        <p>As an Amazon Associate I earn from qualifying purchases.</p>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
