<?php
/**
 * 404 Error Page Template
 *
 * Uses full-width layout (no sidebar), matching template-full-width.php structure.
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

	<div id="primary" class="content-area full-width">
		<main id="main" class="site-main">
			<article class="error-404 not-found">
				<header class="entry-header">
					<h1 class="entry-title">Page Not Found</h1>
				</header>

				<div class="entry-content">
					<p>Oops! The page you're looking for seems to have wandered off. Maybe it's exploring the spiritual meaning of getting lost?</p>

					<h2>Try searching:</h2>
					<?php get_search_form(); ?>

					<h2>Or explore something random:</h2>
					<div class="wp-block-button">
						<a class="wp-block-button__link surprise-me-link" href="<?php echo esc_url( home_url( '/random-all' ) ); ?>">
							<?php _e('Surprise Me', 'sarai-chinwag'); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
								<path d="M18 9v-3c-1 0-3.308-.188-4.506 2.216l-4.218 8.461c-1.015 2.036-3.094 3.323-5.37 3.323h-3.906v-2h3.906c1.517 0 2.903-.858 3.58-2.216l4.218-8.461c1.356-2.721 3.674-3.323 6.296-3.323v-3l6 4-6 4zm-9.463 1.324l1.117-2.242c-1.235-2.479-2.899-4.082-5.748-4.082h-3.906v2h3.906c2.872 0 3.644 2.343 4.631 4.324zm15.463 8.676l-6-4v3c-3.78 0-4.019-1.238-5.556-4.322l-1.118 2.241c1.021 2.049 2.1 4.081 6.674 4.081v3l6-4z"/>
							</svg>
						</a>
					</div>

					<h2>Popular reads:</h2>
					<ul>
						<?php
						$popular_posts = new WP_Query(
							array(
								'posts_per_page' => 5,
								'orderby'        => 'comment_count',
								'order'          => 'DESC',
								'post_status'    => 'publish',
							)
						);

						if ( $popular_posts->have_posts() ) :
							while ( $popular_posts->have_posts() ) :
								$popular_posts->the_post();
								echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</ul>
				</div>
			</article>
		</main>
	</div>

<?php
get_footer();
