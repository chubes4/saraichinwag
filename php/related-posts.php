<?php
function sarai_chinwag_related_content() {
    if (!is_singular(array('post', 'recipe'))) {
        return;
    }

    global $post;
    $post_id = $post->ID;

    // Get categories and tags
    $categories = wp_get_post_categories($post_id);
    $tags = wp_get_post_tags($post_id);
    $tag_ids = wp_list_pluck($tags, 'term_id');

    // Initialize an empty array to hold related content IDs
    $related_content_ids = array();

    // Function to query related content
    function query_related_content($post_types, $taxonomy, $term_ids, $exclude_ids, $count) {
        $args = array(
            'post_type' => $post_types,
            'posts_per_page' => $count,
            'orderby' => 'rand',
            'post__not_in' => $exclude_ids,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ),
            ),
        );
        $query = new WP_Query($args);
        return $query->posts;
    }

    echo '<aside class="related-content">';
    echo '<h2 class="widget-title">Explore More</h2>';

    $post_types = array('post', 'recipe');

    // Display related content by tags
    if (!empty($tag_ids)) {
        foreach ($tags as $tag) {
            $tag_related_posts = query_related_content($post_types, 'post_tag', array($tag->term_id), array_merge(array($post_id), $related_content_ids), 3);

            if (!empty($tag_related_posts)) {
                echo '<h3 class="related-title">More from <a href="' . get_term_link($tag) . '">' . $tag->name . '</a></h3>';
                echo '<div class="related-items">';
                foreach ($tag_related_posts as $related_post) {
                    setup_postdata($related_post);
                    $related_content_ids[] = $related_post->ID; // Track displayed posts to avoid duplicates

                    echo '<div class="related-item">';
                    if (has_post_thumbnail($related_post->ID)) {
                        echo '<a href="' . get_permalink($related_post->ID) . '">';
                        echo '<div class="post-thumbnail">';
                        echo get_the_post_thumbnail($related_post->ID, 'medium');
                        echo '</div>';
                        echo '</a>';
                    }
                    echo '<h4><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h4>';
                    echo '</div>';
                }
                echo '</div>'; // End of related-items
            }
        }
    }

    // Display related content by categories
    if (!empty($categories)) {
        foreach ($categories as $category_id) {
            $category_related_posts = query_related_content($post_types, 'category', array($category_id), array_merge(array($post_id), $related_content_ids), 3);

            if (!empty($category_related_posts)) {
                $category_name = get_cat_name($category_id);
                echo '<h3>More from <a href="' . get_category_link($category_id) . '">' . $category_name . '</a></h3>';
                echo '<div class="related-items">';
                foreach ($category_related_posts as $related_post) {
                    setup_postdata($related_post);
                    $related_content_ids[] = $related_post->ID; // Track displayed posts to avoid duplicates

                    echo '<div class="related-item">';
                    if (has_post_thumbnail($related_post->ID)) {
                        echo '<a href="' . get_permalink($related_post->ID) . '">';
                        echo '<div class="post-thumbnail">';
                        echo get_the_post_thumbnail($related_post->ID, 'medium');
                        echo '</div>';
                        echo '</a>';
                    }
                    echo '<h4><a href="' . get_permalink($related_post->ID) . '">' . get_the_title($related_post->ID) . '</a></h4>';
                    echo '</div>';
                }
                echo '</div>'; // End of related-items
            }
        }
    }

    echo '</aside>';
    wp_reset_postdata();
}

add_action('after_post_main', 'sarai_chinwag_related_content');
