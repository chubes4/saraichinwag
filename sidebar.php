<?php
/**
 * The sidebar containing the main widget area
 *
 * @package ExtraChill
 */
?>

<aside id="secondary" class="widget-area">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>

        <!-- Pinterest Follow Widget -->
        <section id="pinterest_follow" class="widget">
            <h2 class="widget-title"><?php _e( 'Follow Me', 'sarai-chinwag' ); ?></h2>
            <a href="https://www.pinterest.com/saraichinwag/"
                data-pin-do="embedUser"
                data-pin-scale-height="400"
                data-pin-scale-width="80">
            </a>
        </section>

        <!-- Random Posts Widget -->
        <section id="random_posts" class="widget">
            <h2 class="widget-title"><?php _e( 'Random Posts', 'sarai-chinwag' ); ?></h2>
                <?php
                // Get cached random posts
                $cached_random_posts = get_transient('sidebar_random_posts');
                if ( false === $cached_random_posts ) {
                    $cached_random_posts = get_posts(array(
                        'posts_per_page' => 15, // Get more posts to cache
                        'orderby' => 'rand',
                        'post_status' => 'publish'
                    ));
                    set_transient('sidebar_random_posts', $cached_random_posts, 15 * MINUTE_IN_SECONDS);
                }
                
                // Randomly select 3 from cached results
                $random_posts = array_slice($cached_random_posts, array_rand($cached_random_posts, min(3, count($cached_random_posts))));
                foreach( $random_posts as $post_item ) : ?>
                    <a href="<?php echo get_permalink($post_item->ID); ?>">
                        <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                            <div class="post-thumbnail">
                                <?php echo get_the_post_thumbnail($post_item->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($post_item->post_title); ?></h3>
                    </a>
                <?php endforeach; wp_reset_postdata(); ?>
                <button onclick="window.location.href='<?php echo esc_url(home_url('/random-post')); ?>'" class="button"><?php _e('Random Post', 'sarai-chinwag'); ?></button>
        </section>

        <!-- Random Recipes Widget -->
        <section id="random_recipes" class="widget">
            <h2 class="widget-title"><?php _e( 'Random Recipes', 'sarai-chinwag' ); ?></h2>
                <?php
                // Get cached random recipes
                $cached_random_recipes = get_transient('sidebar_random_recipes');
                if ( false === $cached_random_recipes ) {
                    $cached_random_recipes = get_posts(array(
                        'posts_per_page' => 10, // Get more recipes to cache
                        'orderby' => 'rand',
                        'post_type' => 'recipe',
                        'post_status' => 'publish'
                    ));
                    set_transient('sidebar_random_recipes', $cached_random_recipes, 15 * MINUTE_IN_SECONDS);
                }
                
                // Randomly select 2 from cached results
                $random_recipes = array_slice($cached_random_recipes, array_rand($cached_random_recipes, min(2, count($cached_random_recipes))));
                foreach( $random_recipes as $post_item ) : ?>
                    <a href="<?php echo get_permalink($post_item->ID); ?>">
                        <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                            <div class="post-thumbnail">
                                <?php echo get_the_post_thumbnail($post_item->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($post_item->post_title); ?></h3>
                    </a>
                <?php endforeach; wp_reset_postdata(); ?>
                <button onclick="window.location.href='<?php echo esc_url(home_url('/random-recipe')); ?>'" class="button"><?php _e('Random Recipe', 'sarai-chinwag'); ?></button>
        </section>

    <?php endif; ?>
</aside><!-- #secondary -->

