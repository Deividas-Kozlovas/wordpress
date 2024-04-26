<?php
/*
*
* shop kit woocommerce related functions
*
*
*/
require get_template_directory() . '/inc/woo-items/customizer-woo.php';
require get_template_directory() . '/inc/woo-items/woo-inline-style.php';


if (!function_exists('shop_kit_woocommerce_setup')) {
	function shop_kit_woocommerce_setup()
	{
		add_theme_support('woocommerce');
		add_theme_support('wc-product-gallery-zoom');
		add_theme_support('wc-product-gallery-lightbox');
		add_theme_support('wc-product-gallery-slider');
	}
	add_action('after_setup_theme', 'shop_kit_woocommerce_setup');
}

if (!function_exists('shop_kit_woocommerce_scripts')) {
	function shop_kit_woocommerce_scripts()
	{
		wp_enqueue_style('shop-kit-woocommerce-style', get_template_directory_uri() . '/assets/css/shop-kit-woocommerce.css', array(), SHOP_KIT_VERSION, 'all');
		wp_enqueue_script('shop-kit-number', get_template_directory_uri() . '/assets/js/number.js', array('jquery'), SHOP_KIT_VERSION, false);
		wp_enqueue_script('shop-kit-cart-scripts', get_template_directory_uri() . '/assets/js/cart-scripts.js', array('jquery'), SHOP_KIT_VERSION, false);
	}
	add_action('wp_enqueue_scripts', 'shop_kit_woocommerce_scripts');
}



if (!function_exists('shop_kit_cart_count_icon_fragment')) {
	function shop_kit_cart_count_icon_fragment($fragments)
	{
		ob_start();
		shop_kit_cart_count_icon();
		$fragments['.cart-count-icon'] = ob_get_clean();

		return $fragments;
	}
}
add_filter('woocommerce_add_to_cart_fragments', 'shop_kit_cart_count_icon_fragment');


if (!function_exists('shop_kit_cart_count_icon')) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function shop_kit_cart_count_icon()
	{
		$item_count_text = sprintf(
			/* translators: number of items in the mini cart. */
			_n('%d', '%d', WC()->cart->get_cart_contents_count(), 'shop-kit'),
			WC()->cart->get_cart_contents_count()
		);
?>
		<a href="#" class="action-link cart-icon">
			<div class="action-icon cart-count-icon">
				<i class="fas fa-cart-plus"></i>
				<span class="action-count cart-count count"><?php echo esc_html($item_count_text); ?></span>
			</div>
		</a>

	<?php
	}
}


if (!function_exists('shop_kit_woowidgets_init')) {
	function shop_kit_woowidgets_init()
	{
		register_sidebar(array(
			'name'          => esc_html__('Shop Sidebar', 'shop-kit'),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__('Add shop widgets here.', 'shop-kit'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
		register_sidebar(array(
			'name'          => esc_html__('Shop Page Top Widget.', 'shop-kit'),
			'id'            => 'top-filter',
			'description'   => esc_html__('Shop Page products fileter top widget.', 'shop-kit'),
			'before_widget' => '<div id="%1$s" class="shop-kit-top-filter %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="top-widget-title d-none">',
			'after_title'   => '</h3>',
		));
	}
	add_action('widgets_init', 'shop_kit_woowidgets_init');
}

if (!function_exists('shop_kit_body_wooclasses')) {
	function shop_kit_body_wooclasses($classes)
	{

		if (!is_active_sidebar('shop-sidebar') && is_shop()) {
			$classes[] = 'no-shop-widget';
		}
		if (is_front_page() && is_shop()) {
			$classes[] = 'befront-shop';
		}

		return $classes;
	}
	add_filter('body_class', 'shop_kit_body_wooclasses');
}


/**
 * Change number or products per row 
 */
add_filter('loop_shop_columns', 'shop_kit_loop_columns', 999);
if (!function_exists('shop_kit_loop_columns')) {
	function shop_kit_loop_columns()
	{
		$shop_kit_shop_column = get_theme_mod('shop_kit_shop_column', '4');

		return $shop_kit_shop_column;
	}
}

//add new div for product

function shop_kit_before_shop_loop_div()
{
	$shop_kit_shop_style = get_theme_mod('shop_kit_shop_style', '1');

	echo '<div class="shop-kit-poroduct style' . esc_attr($shop_kit_shop_style) . '">';
}
add_action('woocommerce_before_shop_loop_item', 'shop_kit_before_shop_loop_div', 5);

function shop_kit_after_shop_loop_div()
{
	echo '</div">';
}
add_action('woocommerce_after_shop_loop_item', 'shop_kit_after_shop_loop_div', 15);
// End div for product

function shop_kit_woobody_classes($classes)
{

	if (is_shop()) {
		$classes[] = 'shop-kit';
	}

	return $classes;
}
add_filter('body_class', 'shop_kit_woobody_classes');

function shop_kit_woocommerce_page_title($page_title)
{
	$shop_kit_shop_title = get_theme_mod('shop_kit_shop_title', esc_html__('Shop', 'shop-kit'));
	if (is_shop()) {
		return $shop_kit_shop_title;
	} else {
		return $page_title;
	}
}
add_filter('woocommerce_page_title', 'shop_kit_woocommerce_page_title');


// add filter widget in shop page top 

function shop_kit_woocommerce_before_shop_loop()
{
	if (is_active_sidebar('top-filter')) {
		$shop_kit_ftwidget_position = get_theme_mod('shop_kit_ftwidget_position', 'center');
	?>
		<div class="shop-kit-products-filter bestopwid-<?php echo esc_attr($shop_kit_ftwidget_position); ?>">
			<?php dynamic_sidebar('top-filter'); ?>
		</div>

	<?php
	}
}
add_action('woocommerce_before_shop_loop', 'shop_kit_woocommerce_before_shop_loop', 15);



/*Checkout page edit*/

/*
 Remove all possible fields
 */
function shop_kit__remove_checkout_fields($fields)
{

	$shop_kit_checkout_lastname = get_theme_mod('shop_kit_checkout_lastname', 1);
	$shop_kit_checkout_email = get_theme_mod('shop_kit_checkout_email', 'required');
	$shop_kit_checkout_postcode = get_theme_mod('shop_kit_checkout_postcode', '1');

	if (empty($shop_kit_checkout_lastname)) {
		unset($fields['billing']['billing_last_name']);
		$fields['billing']['billing_first_name']['label'] = esc_html__('Name', 'shop-kit');
	}


	if ($shop_kit_checkout_email == 'hide') {
		unset($fields['billing']['billing_email']);
	}
	if (empty($shop_kit_checkout_postcode)) {
		unset($fields['billing']['billing_postcode']);
	}


	return $fields;
}
add_filter('woocommerce_checkout_fields', 'shop_kit__remove_checkout_fields');

function shop_kit__required_checkout_fields($fields)
{
	$shop_kit_checkout_email = get_theme_mod('shop_kit_checkout_email', 'required');

	if ($shop_kit_checkout_email == 'optional') {
		$fields['billing_email']['required'] = false;
	}



	return $fields;
}
add_filter('woocommerce_billing_fields', 'shop_kit__required_checkout_fields');


function shop_kit_woo_action_icons()
{
	?>
	<ul class="woo-action-icons">
		<li>
			<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" title="<?php esc_attr_e('My Account', 'shop-kit'); ?>" class=" action-link">
				<div class="action-icon">
					<i class="fas fa-user"></i>
				</div>
				<div class="action-text">
					<?php
					if (is_user_logged_in()) :  ?>
						<span><?php echo esc_html('View', 'shop-kit'); ?></span>
					<?php else : ?>
						<span><?php echo esc_html('Login / Register', 'shop-kit'); ?></span>
					<?php endif; ?>
					<p><?php echo esc_html('Account', 'shop-kit'); ?></p>
				</div>
			</a>
		</li>
		<li>
			<?php shop_kit_cart_count_icon(); ?>
			<div class="cart-panel">
				<div class="cart-panel-inside">
					<button class="panel-close-btn"><i class="fas fa-times"></i></button>
					<?php
					// Check if the cart is empty
					if (WC()->cart->is_empty()) :
						wc_get_page_permalink('shop');
					?>
						<div class="cart-panel-body">
							<p><?php esc_html_e('Your cart is empty!', 'shop-kit'); ?></p>
							<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="cart-empty-link"><?php esc_html_e('Start Shopping', 'shop-kit'); ?></a>
						</div>
					<?php else : ?>
						<h2 class="pchead"><?php echo esc_html('Your Cart', 'shop-kit'); ?></h2>
						<div class="panel-cart-items">
						<?php
						$instance = array(
							'title' => '',
						);
						the_widget('WC_Widget_Cart', $instance);
					endif;
						?>
						</div>
				</div>
		</li>
	</ul>

<?php
}
