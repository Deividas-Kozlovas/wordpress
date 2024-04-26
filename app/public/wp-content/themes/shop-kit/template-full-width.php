<?php

/**
 * The custom template for displaying full with page 
 *
 * Template Name: Full Width Template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Shop Kit 
 */

get_header();

?>
<div class="mt-3 mb-5 pt-5 pb-3">
	<main id="primary" class="site-main">

		<?php
		while (have_posts()) :
			the_post();

			get_template_part('template-parts/content', 'page');

			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div> <!-- end container -->

<?php
get_footer();
