<?php

/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Shop Kit 
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses shop_kit_header_style()
 */
function shop_kit_custom_header_setup()
{
	add_theme_support(
		'custom-header',
		apply_filters(
			'shop_kit_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1800,
				'height'             => 250,
				'flex-height'        => true,
				'wp-head-callback'   => '',
			)
		)
	);
}
add_action('after_setup_theme', 'shop_kit_custom_header_setup');


// Shop Kit Eye mene style
function shop_kit_mobile_menu_output()
{
?>
	<div id="wsm-menu" class="mobile-menu-bar wsm-menu">
		<div class="container">
			<nav id="mobile-navigation" class="mobile-navigation">
				<button id="mmenu-btn" class="menu-btn" aria-expanded="false">
					<span class="mopen"><?php esc_html_e('Menu', 'shop-kit'); ?></span>
					<span class="mclose"><?php esc_html_e('Close', 'shop-kit'); ?></span>
				</button>
				<?php
				wp_nav_menu(array(
					'theme_location' => 'main-menu',
					'menu_id'        => 'wsm-menu-ul',
					'menu_class'        => 'wsm-menu-has',
				));
				?>
			</nav><!-- #site-navigation -->
		</div>
	</div>

<?php
}
add_action('shop_kit_mobile_menu', 'shop_kit_mobile_menu_output');
