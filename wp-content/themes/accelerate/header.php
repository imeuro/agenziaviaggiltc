<?php
/**
 * Theme Header Section for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main" class="clearfix"> <div class="inner-wrap">
 *
 * @package ThemeGrill
 * @subpackage Accelerate
 * @since Accelerate 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11" />
<?php
/**
 * This hook is important for wordpress plugins and other many things
 */
wp_head();
?>
</head>

<body <?php body_class(); ?>>

<?php
/**
 * WordPress function to load custom scripts after body.
 *
 * Introduced in WordPress 5.2.0
 *
 * @since Accelerate 1.4.2
 */
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<?php	do_action( 'accelerate_before' ); ?>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'accelerate' ); ?></a>

	<?php do_action( 'accelerate_before_header' ); ?>
	<header id="masthead" class="site-header clearfix">

		<div id="header-text-nav-container" class="clearfix">

			<?php if( accelerate_options( 'accelerate_header_image_position', 'position_two' ) == 'position_one' ) { accelerate_render_header_image(); } ?>

			<div class="inner-wrap">

				<div id="header-text-nav-wrap" class="clearfix">
					<div id="header-left-section">
						<?php
						if( ( accelerate_options( 'accelerate_show_header_logo_text', 'text_only' ) == 'both' || accelerate_options( 'accelerate_show_header_logo_text', 'text_only' ) == 'logo_only' ) ) {
						?>
							<div id="header-logo-image">
								<?php if( function_exists( 'the_custom_logo' ) && has_custom_logo( $blog_id = 0 ) ) {
							      accelerate_the_custom_logo();
							   } ?>
							</div><!-- #header-logo-image -->
						<?php
						}
						$screen_reader = '';
						if ( ( accelerate_options( 'accelerate_show_header_logo_text', 'text_only' ) == 'logo_only' || accelerate_options( 'accelerate_show_header_logo_text', 'text_only' ) == 'none' ) ) {
							$screen_reader = 'screen-reader-text';
						}
						?>
						<div id="header-text" class="<?php echo $screen_reader; ?>">
						<?php
							if ( is_front_page() || is_home() ) : ?>
								<h1 id="site-title">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
								</h1>
							<?php else : ?>
								<h3 id="site-title">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
								</h3>
							<?php endif;
							$description = get_bloginfo( 'description', 'display' );
							if ( $description || is_customize_preview() ) : ?>
								<p id="site-description"><?php echo $description; ?></p>
							<?php endif;
						?>
						</div><!-- #header-text -->
					</div><!-- #header-left-section -->
					<div id="header-right-section">
						<?php
						if( is_active_sidebar( 'accelerate_header_sidebar' ) ) {
						?>
						<div id="header-right-sidebar" class="clearfix">
							<?php
								// Calling the header sidebar if it exists.
								if ( !dynamic_sidebar( 'accelerate_header_sidebar' ) ):
								endif;
							?>
							<?php
							}
							?>
							<aside class="widget widget_woocommerce">
								<h3 class="widget-title">Shop</h3>
								<p>
									<?php if ( is_user_logged_in() ) { ?>
										<a class="wcicon wcicon-user-logged" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My account page','woocommerce'); ?>"><?php //_e('My account page','woocommerce'); ?></a>
									<?php } 
									else { ?>
										<a class="wcicon wcicon-user" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','woocommerce'); ?>"><?php // _e('Login / Register','woocommerce'); ?></a>
									<?php } ?>

									<a class="wcicon wcicon-cart" href="<?php echo get_site_url(null, '/cart/', 'https'); ?>" title="<?php _e('Cart','woocommerce'); ?>"><?php // _e('Cart','woocommerce'); ?>
										
										<?php	
										if ( class_exists( 'WooCommerce' ) && ( !is_checkout() ) ) {
											$cart_items = WC()->cart->get_cart_contents_count();
											// echo $cart_items.'sdve';
											if ( $cart_items > 0  ) {
												echo '<span id="ltc_cart_qty" title="Hai ' . $cart_items . ' elementi nel carrello">' . $cart_items . '</span>'; 
											}
										}
										?>

									</a>
								</p>
							</aside>
						</div>
			    	</div><!-- #header-right-section -->

			   </div><!-- #header-text-nav-wrap -->

			</div><!-- .inner-wrap -->

			<?php if( accelerate_options( 'accelerate_header_image_position', 'position_two' ) == 'position_two' ) { accelerate_render_header_image(); } ?>

			<nav id="site-navigation" class="main-navigation inner-wrap clearfix" role="navigation">
				<h3 class="menu-toggle"><?php _e( 'Menu', 'accelerate' ); ?></h3>
				<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array( 'theme_location' => 'primary' ) );
					}
					else {
						wp_page_menu();
					}
				?>
			</nav>
		</div><!-- #header-text-nav-container -->

		<?php if( accelerate_options( 'accelerate_header_image_position', 'position_two' ) == 'position_three' ) { accelerate_render_header_image(); } ?>

		<?php
   	if( accelerate_options( 'accelerate_activate_slider', '0' ) == '1' ) {
			if ( is_front_page() ) {
   			accelerate_featured_image_slider();
			}
   	}
   	?>

	</header>
	<?php do_action( 'accelerate_after_header' ); ?>
	<?php do_action( 'accelerate_before_main' ); ?>
	<div id="main" class="clearfix">
		<div class="inner-wrap clearfix">
