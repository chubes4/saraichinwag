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

<?php
// Show template header unless page has a block with built-in header (chat, dashboard).
$content = get_the_content();
$has_block_header = has_block( 'spawn/chat' ) || has_block( 'spawn/dashboard' );
if ( ! $has_block_header ) {
	get_template_part( 'template-parts/spawn-header' );
}
?>

<?php
// Fullpage blocks (chat, dashboard) don't want the container wrapper.
$is_fullpage = has_block( 'spawn/chat' ) || has_block( 'spawn/dashboard' );
?>
<main class="spawn-main">
	<?php
	/**
	 * Hook: sarai_chinwag_before_page_content
	 * Used by sarai-ad-blocker mu-plugin to disable ads on Spawn pages.
	 */
	do_action( 'sarai_chinwag_before_page_content' );
	?>
	<?php if ( ! $is_fullpage ) : ?>
	<div class="spawn-container">
	<?php endif; ?>
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	<?php if ( ! $is_fullpage ) : ?>
	</div>
	<?php endif; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
