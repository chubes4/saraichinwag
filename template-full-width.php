<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * @package Sarai_Chinwag
 */

get_header();
?>

	<div id="primary" class="content-area full-width">
		<main id="main" class="site-main">
			<?php sarai_chinwag_page_breadcrumbs(); ?>
			<?php
			/**
			 * Hook: sarai_chinwag_before_page_content
			 * Fires before page content on single pages.
			 */
			do_action( 'sarai_chinwag_before_page_content' );
			?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/WebPage">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</main>
	</div>

<?php
get_footer();
