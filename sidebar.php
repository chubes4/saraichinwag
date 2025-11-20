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

</aside><!-- #secondary -->

