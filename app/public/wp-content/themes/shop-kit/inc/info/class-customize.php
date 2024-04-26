<?php

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class shop_kit_pro_Customize
{

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance()
	{

		static $instance = null;

		if (is_null($instance)) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct()
	{
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions()
	{

		// Register panels, sections, settings, controls, and partials.
		add_action('customize_register', array($this, 'sections'));

		// Register scripts and styles for the controls.
		add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_control_scripts'), 0);
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections($manager)
	{

		// Load custom sections.
		require get_template_directory() . '/inc/info/section-pro.php';

		// Register custom section types.
		$manager->register_section_type('shop_kit_pro_Customize_Section_Pro');

		// Register sections.
		$manager->add_section(
			new shop_kit_pro_Customize_Section_Pro(
				$manager,
				'shop_kit_pro',
				array(
					'title'    => esc_html__('Upgrade Shop Kit Pro', 'shop-kit'),
					'pro_text' => esc_html__('Go Pro', 'shop-kit'),
					'pro_url'  => 'https://wpthemespace.com/product/shop-kit-pro/?add-to-cart=11021',
					'priority' => 10,
				)
			)
		);
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts()
	{

		wp_enqueue_script('shop-kit-pro-customize-controls', trailingslashit(get_template_directory_uri()) . 'inc/info/customize-controls.js', array('customize-controls'));

		wp_enqueue_style('shop-kit-pro-customize-controls', trailingslashit(get_template_directory_uri()) . 'inc/info/customize-controls.css');
	}
}

// Doing this customizer thang!
shop_kit_pro_Customize::get_instance();
