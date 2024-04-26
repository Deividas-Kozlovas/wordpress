<?php
/*
*
* The file for display blog content for shop kit theme
*
*/
$shop_kit_blog_style = get_theme_mod('shop_kit_blog_style', 'grid');
$shop_kit_blogdate = get_theme_mod('shop_kit_blogdate', 1);
$shop_kit_blogauthor = get_theme_mod('shop_kit_blogauthor', 1);
$shop_kit_postcat = get_theme_mod('shop_kit_postcat', 1);
$shop_kit_posttag = get_theme_mod('shop_kit_posttag', 1);
$shop_kit_posttag = get_theme_mod('shop_kit_posttag', 1);
$shop_kit_post_comment = get_theme_mod('shop_kit_post_comment', 1);

if ($shop_kit_blog_style == 'style1') {
	$shop_kit_stclass = 'xskit-list-flex';
} else {
	$shop_kit_stclass = 'xskit-simple-list';
}

if ($shop_kit_blog_style != 'style3') :
?>
	<div class="xskit-blog-list">
		<?php if (has_post_thumbnail()) : ?>
			<div class="<?php echo esc_attr($shop_kit_stclass); ?> hasimg">
				<div class="shop-kit-blog-img">
					<?php shop_kit_post_thumbnail(); ?>
				</div>
			<?php else : ?>
				<div class="<?php echo esc_attr($shop_kit_stclass); ?> no-img">
				<?php endif; ?>

				<div class="shop-kit-blog-text">
					<div class="shop-kit-btext">
						<header class="entry-header">
							<?php
							the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');

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
						<div class="entry-content">
							<?php
							the_excerpt();
							?>
						</div><!-- .entry-content -->

					</div>

				</div>
				</div>
			</div>
		<?php else : ?>
			<div class="xskit-single-list">
				<header class="entry-header text-center mb-5">
					<?php
					the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');

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
					the_excerpt();
					?>
				</div><!-- .entry-content -->
				<?php if (!empty($shop_kit_postcat) || !empty($shop_kit_posttag) || !empty($shop_kit_post_comment)) : ?>
					<footer class="entry-footer">
						<?php shop_kit_entry_footer($shop_kit_postcat, $shop_kit_posttag, $shop_kit_post_comment); ?>
					</footer><!-- .entry-footer -->
				<?php endif; ?>

			</div>
		<?php endif; ?>