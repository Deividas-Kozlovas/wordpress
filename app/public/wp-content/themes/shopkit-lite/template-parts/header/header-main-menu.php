<?php

/**
 * Template part for displaying header top bar
 *
 * @link https://wpthemespace.com/product/shop-kit
 *
 * @package Shop Kit 
 */

$shop_kit_menu_logo = get_theme_mod('shop_kit_menu_logo');
$shop_kit_logo_position = get_theme_mod('shop_kit_logo_position', 'left');

?>

<div class="shop-kit-main-nav bg-light text-dark menulogo-<?php echo esc_attr($shop_kit_logo_position); ?>">
	<div class="container">
		<div class="<?php if ($shop_kit_menu_logo) : ?>d-flex has-logo-menu<?php else : ?>logo-hide<?php endif; ?>">
			<?php if ($shop_kit_logo_position == 'left' && $shop_kit_menu_logo) : ?>
				<div class="menu-logo">
					<?php shop_kit_header_logo(1); ?>
				</div>
			<?php endif; ?>
			<div class="shop-kit-main-menu flex-grow-1">
				<nav id="site-navigation" class="main-navigation">
					<?php
					if (has_nav_menu('main-menu')) {
						wp_nav_menu(
							array(
								'theme_location' => 'main-menu',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'shop-kit-main-menu-container',
							)
						);
					} elseif (!has_nav_menu('expanded')) { ?>
						<ul id="primary-menu" class="menu nav-menu">
							<?php
							wp_list_pages(
								array(
									'match_menu_classes' => true,
									'show_sub_menu_icons' => true,
									'title_li' => false,
								)
							);
							?>
						</ul>
					<?php

					}

					?>
				</nav><!-- #site-navigation -->
			</div>
			<?php if ($shop_kit_logo_position == 'right' && $shop_kit_menu_logo) : ?>
				<div class="menu-logo">
					<?php shop_kit_header_logo(); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ($shop_kit_logo_position == 'center' && $shop_kit_menu_logo) : ?>
			<div class="menu-logo mt-3">
				<?php shop_kit_header_logo(); ?>
			</div>
		<?php endif; ?>
	</div>
</div>