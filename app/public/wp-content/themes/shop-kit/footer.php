<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Shop Kit 
 */
$shop_kit_topfooter_show = get_theme_mod('shop_kit_topfooter_show', 1);

?>
<?php if (is_active_sidebar('footer-widget') && $shop_kit_topfooter_show) : ?>
	<div class="footer-top mt-5 pb-5 pt-5 bg-light">
		<div class="footer-widget">
			<div class="container">
				<div class="row">
					<?php dynamic_sidebar('footer-widget') ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<footer id="colophon" class="site-footer text-center">
	<div class="site-info finfo">
		<a href="<?php echo esc_url(esc_html__('https://wordpress.org/', 'shop-kit')); ?>">
			<?php esc_html_e('Powered by WordPress', 'shop-kit'); ?>
		</a>

		<?php
		/* translators: 1: Theme name, 2: Theme author. */
		printf(esc_html__('Theme: %1$s by %2$s.', 'shop-kit'), 'shop-kit', '<a href="https://wpthemespace.com/">wp theme space</a>');
		?>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>

</body>

</html>