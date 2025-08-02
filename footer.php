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
            <svg class="pinterest-logo" width="20" height="20" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0"/>
            </svg>
            <?php _e('Follow on Pinterest', 'sarai-chinwag'); ?>
        </a>
    </div>
    <?php endif; ?>
    

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
