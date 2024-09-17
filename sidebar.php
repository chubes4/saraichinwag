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
                $random_posts = get_posts(array(
                    'posts_per_page' => 3, // Number of random posts to display
                    'orderby' => 'rand', // Order by random
                    'post_status' => 'publish' // Only show published posts
                ));
                foreach( $random_posts as $post_item ) : ?>
                        <a href="<?php echo get_permalink($post_item->ID); ?>">
                            <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url($post_item->ID, 'medium'); ?>" />
                            <?php endif; ?>
                            <span><?php echo esc_html($post_item->post_title); ?></span>
                        </a>
                <?php endforeach; wp_reset_postdata(); ?>
                <button onclick="window.location.href='<?php echo esc_url(home_url('/random-post')); ?>'" class="button"><?php _e('Random Post', 'sarai-chinwag'); ?></button>
        </section>

        <!-- Random Recipes Widget -->
        <section id="random_recipes" class="widget">
            <h2 class="widget-title"><?php _e( 'Random Recipes', 'sarai-chinwag' ); ?></h2>
                <?php
                $random_recipes = get_posts(array(
                    'posts_per_page' => 3, // Number of random recipes to display
                    'orderby' => 'rand', // Order by random
                    'post_type' => 'recipe', // Only show posts of type 'recipe'
                    'post_status' => 'publish' // Only show published posts
                ));
                foreach( $random_recipes as $post_item ) : ?>
                        <a href="<?php echo get_permalink($post_item->ID); ?>">
                            <?php if ( has_post_thumbnail($post_item->ID) ) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url($post_item->ID, 'medium'); ?>" />
                            <?php endif; ?>
                            <span><?php echo esc_html($post_item->post_title); ?></span>
                        </a>
                <?php endforeach; wp_reset_postdata(); ?>
                <button onclick="window.location.href='<?php echo esc_url(home_url('/random-recipe')); ?>'" class="button"><?php _e('Random Recipe', 'sarai-chinwag'); ?></button>
        </section>

    <?php endif; ?>
</aside><!-- #secondary -->
