<?php
/**
 * Spawn app header - minimal branding for the Spawn app experience.
 *
 * @package Sarai_Chinwag
 */

$is_logged_in    = is_user_logged_in();
$current_page_id = get_the_ID();
$spawn_home_id   = 13022; // Main Spawn page
$dashboard_id    = 13024;
$login_id        = 13023;
$account_id      = 13025;
?>

<header class="spawn-header">
	<div class="spawn-header__inner">
		<a href="<?php echo esc_url( get_permalink( $spawn_home_id ) ); ?>" class="spawn-header__logo">
			<span class="spawn-header__logo-icon">✨</span>
			<span class="spawn-header__logo-text">Spawn</span>
		</a>

		<nav class="spawn-header__nav">
			<?php if ( $is_logged_in ) : ?>
				<a href="<?php echo esc_url( get_permalink( $dashboard_id ) ); ?>" 
				   class="spawn-header__link <?php echo $current_page_id === $dashboard_id ? 'is-active' : ''; ?>">
					Dashboard
				</a>
				<a href="<?php echo esc_url( get_permalink( $account_id ) ); ?>" 
				   class="spawn-header__link <?php echo $current_page_id === $account_id ? 'is-active' : ''; ?>">
					Account
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( get_permalink( $login_id ) ); ?>" 
				   class="spawn-header__link <?php echo $current_page_id === $login_id ? 'is-active' : ''; ?>">
					Log in
				</a>
				<a href="<?php echo esc_url( get_permalink( $spawn_home_id ) ); ?>" 
				   class="spawn-header__button">
					Get Started
				</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
