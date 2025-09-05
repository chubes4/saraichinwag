<?php
/**
 * The sidebar containing custom theme widgets
 *
 * @package Sarai_Chinwag
 */
?>

<aside id="secondary" class="widget-area">
    <?php 
    // Check if Pinterest username is set
    $pinterest_username = get_option('sarai_chinwag_pinterest_username', '');
    if (!empty($pinterest_username)) : ?>
    <!-- Pinterest Follow Widget -->
    <section id="pinterest_follow" class="widget">
        <h2 class="widget-title"><?php _e( 'Follow Me', 'sarai-chinwag' ); ?></h2>
        <a href="https://www.pinterest.com/<?php echo esc_attr($pinterest_username); ?>/"
            data-pin-do="embedUser"
            data-pin-scale-height="400"
            data-pin-scale-width="80">
        </a>
    </section>
    <?php endif; ?>

    <!-- Random Posts Widget -->
    <section id="random_posts" class="widget">
        <h2 class="widget-title"><?php _e( 'Random Posts', 'sarai-chinwag' ); ?></h2>
            <?php
            // Get cached random posts
            $cached_random_posts = wp_cache_get('sidebar_random_posts', 'sarai_chinwag_sidebar');
            if ( false === $cached_random_posts ) {
                $cached_random_posts = get_posts(array(
                    'posts_per_page' => 15, // Get more posts to cache
                    'orderby' => 'rand',
                    'post_status' => 'publish'
                ));
                wp_cache_set('sidebar_random_posts', $cached_random_posts, 'sarai_chinwag_sidebar', 15 * MINUTE_IN_SECONDS);
            }
            
            // Randomly select 3 from cached results
            if (!empty($cached_random_posts)) {
                shuffle($cached_random_posts);
                $random_posts = array_slice($cached_random_posts, 0, min(3, count($cached_random_posts)));
                foreach( $random_posts as $post_item ) : ?>
                    <a href="<?php echo get_permalink($post_item->ID); ?>">
                        <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                            <div class="post-thumbnail">
                                <?php echo get_the_post_thumbnail($post_item->ID, 'grid-thumb'); ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($post_item->post_title); ?></h3>
                    </a>
                <?php endforeach; wp_reset_postdata();
            }
            ?>
            <button onclick="window.location.href='<?php echo esc_url(home_url('/random-post')); ?>'" class="button"><?php _e('Random Post', 'sarai-chinwag'); ?></button>
    </section>

    <?php if (!sarai_chinwag_recipes_disabled()) : ?>
    <!-- Random Recipes Widget -->
    <section id="random_recipes" class="widget">
        <h2 class="widget-title"><?php _e( 'Random Recipes', 'sarai-chinwag' ); ?></h2>
            <?php
            // Get cached random recipes
            $cached_random_recipes = wp_cache_get('sidebar_random_recipes', 'sarai_chinwag_sidebar');
            if ( false === $cached_random_recipes ) {
                $cached_random_recipes = get_posts(array(
                    'posts_per_page' => 10, // Get more recipes to cache
                    'orderby' => 'rand',
                    'post_type' => 'recipe',
                    'post_status' => 'publish'
                ));
                wp_cache_set('sidebar_random_recipes', $cached_random_recipes, 'sarai_chinwag_sidebar', 15 * MINUTE_IN_SECONDS);
            }
            
            // Randomly select 2 from cached results
            if (!empty($cached_random_recipes)) {
                shuffle($cached_random_recipes);
                $random_recipes = array_slice($cached_random_recipes, 0, min(2, count($cached_random_recipes)));
                foreach( $random_recipes as $post_item ) : ?>
                    <a href="<?php echo get_permalink($post_item->ID); ?>">
                        <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                            <div class="post-thumbnail">
                                <?php echo get_the_post_thumbnail($post_item->ID, 'grid-thumb'); ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($post_item->post_title); ?></h3>
                    </a>
                <?php endforeach; wp_reset_postdata();
            }
            ?>
            <button onclick="window.location.href='<?php echo esc_url(home_url('/random-recipe')); ?>'" class="button"><?php _e('Random Recipe', 'sarai-chinwag'); ?></button>
    </section>
    <?php endif; ?>
</aside><!-- #secondary -->

