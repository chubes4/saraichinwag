<?php
/**
 * Template Name: Blank Canvas
 * Template Post Type: page
 *
 * Minimal, full-bleed template for app-like interfaces.
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
	<body <?php body_class(); ?>>
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
		<?php wp_footer(); ?>
	</body>
</html>
