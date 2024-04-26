<?php

/**
 * Template part for displaying woocommerce cart and checkout page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Shop Kit 
 */

?>



<div class="container mt-3 mb-5 pt-5 pb-3">
	<div class="row">
		<div class="col-lg-12 shop-kit-cart-checkout">
			<main id="primary" class="site-main">
				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', 'page');

				endwhile; // End of the loop.
				?>
			</main><!-- #main -->
		</div>
	</div> <!-- end row -->
</div> <!-- end container -->