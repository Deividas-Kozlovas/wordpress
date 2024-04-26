<?php
/*
*
* The file for display blog content for shop kit theme
*
*/
$shop_kit_blogdate = get_theme_mod('shop_kit_blogdate', 1);
$shop_kit_blogauthor = get_theme_mod('shop_kit_blogauthor', 1);
$shop_kit_postcat = get_theme_mod('shop_kit_postcat', 1);
$shop_kit_posttag = get_theme_mod('shop_kit_posttag', 1);
?>
<div class="xskit-single-list">
	<header class="entry-header text-center mb-5">
		<?php
		if (is_singular()) :
			the_title('<h2 class="entry-title">', '</h2>');
		else :
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
		endif;

		if ('post' === get_post_type() && (!empty($shop_kit_blogdate) || !empty($shop_kit_blogauthor))) :
		?>
			<div class="entry-meta">
				<?php
				if ($shop_kit_blogdate) {
					shop_kit_posted_on();
				}
				if ($shop_kit_blogauthor) {
					shop_kit_posted_by();
				}
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php shop_kit_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		if (is_single()) {
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'shop-kit'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'shop-kit'),
					'after'  => '</div>',
				)
			);
		} else {
			the_excerpt();
		}


		?>
	</div><!-- .entry-content -->

	<?php if (!empty($shop_kit_postcat) || !empty($shop_kit_posttag)) : ?>
		<footer class="entry-footer">
			<?php shop_kit_entry_footer($shop_kit_postcat, $shop_kit_posttag); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>


</div>