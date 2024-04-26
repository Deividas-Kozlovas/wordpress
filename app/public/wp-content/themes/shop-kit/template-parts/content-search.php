<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Shop Kit 
 */
$shop_kit_blog_style = get_theme_mod('shop_kit_blog_style', 'grid');
if ($shop_kit_blog_style == 'grid' && !is_single()) :
	get_template_part('template-parts/content', 'grid');
else :
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php get_template_part('template-parts/content', 'blog'); ?>

	</article><!-- #post-<?php the_ID(); ?> -->
<?php endif; ?>