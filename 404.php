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
					<p>Oops! The page you're looking for seems to have wandered off. Maybe it's exploring the spiritual meaning of getting lost? 🦋</p>

					<h2>Try searching:</h2>
					<?php get_search_form(); ?>

					<h2>Or explore something random:</h2>
					<div class="wp-block-button">
						<a class="wp-block-button__link" href="<?php echo esc_url( home_url( '/random-all' ) ); ?>">✨ Surprise Me</a>
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
