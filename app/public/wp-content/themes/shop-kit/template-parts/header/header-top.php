<?php

/**
 * Template part for displaying header top bar
 *
 * @link https://wpthemespace.com/product/shop-kit
 *
 * @package Shop Kit 
 */
$shop_kit_topbar_mtext = get_theme_mod('shop_kit_topbar_mtext', esc_html__('Welcome to Our Website !', 'shop-kit'));
$shop_kit_topbar_menushow = get_theme_mod('shop_kit_topbar_menushow', 1);

$shop_kit_topbar_search_item = get_theme_mod('shop_kit_topbar_search_item', 'simple');


?>

<div class="shop-kit-tophead bg-light text-dark <?php if ($shop_kit_topbar_search_item == 'simple') : ?>has-search pt-1 pb-1<?php else : ?>pt-2 pb-2<?php endif; ?>">
	<div class="container">
		<div class="row">
			<?php if ($shop_kit_topbar_mtext) : ?>
				<div class="col-lg-auto">
					<span class="bhtop-text pt-2"><?php echo esc_html($shop_kit_topbar_mtext); ?></span>
				</div>
			<?php endif; ?>
			<?php if ($shop_kit_topbar_search_item != 'hide' || ($shop_kit_topbar_menushow && has_nav_menu('btop-menu'))) : ?>
				<div class="col-lg-auto ms-auto ml-auto">
					<div class="topmenu-serch bsearch-<?php echo esc_attr($shop_kit_topbar_search_item); ?>">
						<?php if ($shop_kit_topbar_menushow && has_nav_menu('btop-menu')) : ?>
							<div class="top-menu list-hide text-dark">
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'btop-menu',
										'menu_id'        => 'btop-menu',
										'menu_class'     => 'btop-menu',
										'depth'          => 1,
										'fallback_cb'    => false,
									)
								);
								?>
							</div>
						<?php endif; ?>
						<?php if ($shop_kit_topbar_search_item == 'simple') : ?>
							<div class="header-top-search">
								<?php get_search_form(); ?>
							</div>
						<?php endif; ?>
						<?php if ($shop_kit_topbar_search_item == 'popup') : ?>
							<div class="besearch-icon">
								<a href="#" id="besearch"><i class="fas fa-search"></i></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php if ($shop_kit_topbar_search_item == 'popup') : ?>
	<div id="bspopup" class="off">
		<div id="bessearch" class="open">
			<button data-widget="remove" id="removeClass" class="close" type="button">×</button>
			<?php get_search_form(); ?>
			<small class="shop-kit-cradit"><?php esc_html_e('Shop Kit Theme By', 'shop-kit') ?> <a target="_blank" title="<?php esc_attr_e('Shop Kit Theme', 'shop-kit') ?>" href="<?php echo esc_url('https://wpthemespace.com/product/shop-kit/'); ?>"><?php esc_html_e('Wp Theme Space', 'shop-kit') ?></a></small>
		</div>
	</div>
<?php endif; ?>