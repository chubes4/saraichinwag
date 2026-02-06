<?php
/**
 * Spawn app header - consistent branding across all Spawn pages.
 *
 * @package Sarai_Chinwag
 */

$is_logged_in = is_user_logged_in();
?>

<header class="spawn-topnav">
	<a href="<?php echo esc_url( home_url( '/spawn/' ) ); ?>" class="spawn-topnav__logo" title="Spawn by Sarai Chinwag">
		<img src="https://saraichinwag.com/wp-content/uploads/2023/08/sarai-chinwag.jpeg" alt="Sarai Chinwag" width="32" height="32" />
		<span>Spawn <em>by Sarai Chinwag</em></span>
	</a>

	<nav class="spawn-topnav__links">
		<?php if ( $is_logged_in ) : ?>
			<a href="<?php echo esc_url( home_url( '/spawn/chat/' ) ); ?>">Chat</a>
			<a href="<?php echo esc_url( home_url( '/spawn/dashboard/' ) ); ?>">Dashboard</a>
			<a href="<?php echo esc_url( wp_logout_url( home_url( '/spawn/' ) ) ); ?>">Log out</a>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/spawn/login/' ) ); ?>">Log in</a>
			<a href="<?php echo esc_url( home_url( '/spawn/' ) ); ?>">Get Started</a>
		<?php endif; ?>
	</nav>
</header>
