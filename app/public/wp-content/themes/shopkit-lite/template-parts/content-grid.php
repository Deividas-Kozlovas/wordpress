<?php
/*
*
* The file for display blog content for shop kit theme
*
*/
$shop_kit_blogdate = get_theme_mod('shop_kit_blogdate', 1);
$shop_kit_blogauthor = get_theme_mod('shop_kit_blogauthor', 1);
$shop_kit_blog_readmore = get_theme_mod('shop_kit_blog_readmore', esc_html__('Read More', 'shop-kit'));

?>
<div class="col-lg-4">
	<div class="xskit-blog-grid mb-5">
		<?php if (has_post_thumbnail()) : ?>
			<div class="xskit-hasimg">
				<div class="shop-kit-grid-img">
					<?php shop_kit_post_thumbnail(); ?>
				</div>
			<?php else : ?>
				<div class="xskit-hasimg-no-img">
				<?php endif; ?>

				<div class="shop-kit-grid-text">
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
						<?php the_excerpt(); ?>
						<a href="<?php the_permalink(); ?>" class="readmore"><?php echo esc_html($shop_kit_blog_readmore); ?></a>
					</div><!-- .entry-content -->

				</div>

				</div>
			</div>
	</div>