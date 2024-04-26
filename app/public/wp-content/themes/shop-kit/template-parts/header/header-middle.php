<?php

/**
 * Template part for displaying header middle
 *
 * @link https://wpthemespace.com/product/shop-kit
 *
 * @package Shop Kit 
 */
$shop_kit_logo_position = get_theme_mod('shop_kit_logo_position', 'left');
$shop_kit_himg_height = get_theme_mod('shop_kit_himg_height', 'fixed');
$shop_kit_head_cart_show = get_theme_mod('shop_kit_head_cart_show', 1);

?>
<div class="shop-kit-header-middle">

	<div class="site-branding logo-<?php echo esc_attr($shop_kit_logo_position); ?>">
		<div class="container py-3">
			<div class="row">
				<?php if (class_exists('WooCommerce') && $shop_kit_head_cart_show) : ?>
					<div class="col-lg-9">
					<?php else : ?>
						<div class="col-lg-12">
						<?php endif; ?>
						<div class="headerlogo-text text-<?php echo esc_attr($shop_kit_logo_position); ?>">
							<?php the_custom_logo(); ?>
							<?php if (display_header_text() == true || (display_header_text() == true && is_customize_preview())) : ?>
								<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
								<?php
								$shop_kit_description = get_bloginfo('description', 'display');
								if ($shop_kit_description || is_customize_preview()) :
								?>
									<p class="site-description">
										<?php echo $shop_kit_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped  
										?></p>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						</div>
						<?php if (class_exists('WooCommerce') && $shop_kit_head_cart_show) : ?>
							<div class="col-lg-3 ml-auto">
								<div class="hmiddle-right">
									<?php shop_kit_woo_action_icons(); ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
			</div><!-- .site-branding -->
		</div>
	</div>