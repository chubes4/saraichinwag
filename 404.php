<?php
/**
 * 404 Error Page Template
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<article class="error-404 not-found">
			<header class="entry-header">
				<h1 class="entry-title">Page Not Found</h1>
			</header>

			<div class="entry-content">
				<p>Oops! The page you're looking for seems to have wandered off. Maybe it's exploring the spiritual meaning of getting lost? 🦋</p>

				<div class="error-search">
					<h2>Try searching:</h2>
					<?php get_search_form(); ?>
				</div>

				<div class="error-suggestions">
					<h2>Or explore something random:</h2>
					<p>
						<a href="<?php echo esc_url( home_url( '/random-all' ) ); ?>" class="button">
							✨ Surprise Me
						</a>
					</p>
				</div>

				<div class="error-popular">
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
			</div>
		</article>
	</main>
</div>

<?php
get_sidebar();
get_footer();
