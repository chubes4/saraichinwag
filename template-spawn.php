<?php
/**
 * Template Name: Spawn App
 * Template Post Type: page
 *
 * App-like template for Spawn pages with minimal Spawn branding.
 *
 * @package Sarai_Chinwag
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'spawn-app' ); ?>>
<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/spawn-header' ); ?>

<main class="spawn-main">
	<div class="spawn-container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</main>

<footer class="spawn-footer">
	<div class="spawn-container">
		<p>
			<a href="<?php echo esc_url( home_url( '/spawn/' ) ); ?>">Home</a> · 
			<a href="<?php echo esc_url( home_url( '/spawn/how-it-works/' ) ); ?>">How It Works</a> · 
			<a href="<?php echo esc_url( home_url( '/spawn/faq/' ) ); ?>">FAQ</a> · 
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to Sarai Chinwag</a>
		</p>
		<p class="spawn-footer__copyright">
			Powered by <a href="https://saraichinwag.com">Sarai Chinwag</a>
		</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
