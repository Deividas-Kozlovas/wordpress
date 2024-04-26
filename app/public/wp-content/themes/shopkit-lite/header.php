<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Shop Kit 
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'shop-kit'); ?></a>
		<?php
		$shop_kit_topbar_show = get_theme_mod('shop_kit_topbar_show');
		$shop_kit_hmiddle_show = get_theme_mod('shop_kit_hmiddle_show', 1);
		$shop_kit_main_menu_show = get_theme_mod('shop_kit_main_menu_show', 1);

		?>
		<header id="masthead" class="shop-kit-header site-header">
			<?php if (has_header_image()) : ?>
				<div class="shop-kit-headerimg-top">
					<?php the_header_image_tag(); ?>
				</div>
			<?php endif; ?>
			<?php
			do_action('shop_kit_mobile_menu');

			if ($shop_kit_topbar_show) {
				get_template_part('template-parts/header/header-top');
			}
			if ($shop_kit_hmiddle_show) {
				get_template_part('template-parts/header/header-middle');
			}
			if ($shop_kit_main_menu_show) {
				get_template_part('template-parts/header/header-main-menu');
			}

			?>


		</header><!-- #masthead -->