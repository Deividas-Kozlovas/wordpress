<?php

/**
 * About setup
 *
 * @package Shop Kit
 */

require_once trailingslashit(get_template_directory()) . 'inc/about/class.about.php';

if (!function_exists('shop_kit_about_setup')) :

	/**
	 * About setup.
	 *
	 * @since 1.0.0
	 */
	function shop_kit_about_setup()
	{
		$theme = wp_get_theme();
		$xtheme_name = $theme->get('Name');
		$xtheme_domain = $theme->get('TextDomain');
		if ($xtheme_domain == 'x-magazine') {
			$theme_slug = $xtheme_domain;
		} else {
			$theme_slug = 'shop-kit';
		}



		$config = array(
			// Menu name under Appearance.
			'menu_name'               => sprintf(esc_html__('%s Info', 'shop-kit'), $xtheme_name),
			// Page title.
			'page_name'               => sprintf(esc_html__('%s Info', 'shop-kit'), $xtheme_name),
			/* translators: Main welcome title */
			'welcome_title'         => sprintf(esc_html__('Welcome to %s! - Version ', 'shop-kit'), $theme['Name']),
			// Main welcome content
			// Welcome content.
			'welcome_content' => sprintf(esc_html__('%1$s is now installed and ready to use. We want to make sure you have the best experience using the theme and that is why we gathered here all the necessary information for you. Thanks for using our theme!', 'shop-kit'), $theme['Name']),

			// Tabs.
			'tabs' => array(
				'getting_started' => esc_html__('Getting Started', 'shop-kit'),
				'recommended_actions' => esc_html__('Recommended Actions', 'shop-kit'),
				'useful_plugins'  => esc_html__('Useful Plugins', 'shop-kit'),
				'free_pro'  => esc_html__('Free Vs Pro', 'shop-kit'),
			),

			// Quick links.
			'quick_links' => array(
				'xmagazine_url' => array(
					'text'   => esc_html__('UPGRADE SHOP KIT PRO', 'shop-kit'),
					'url'    => 'https://wpthemespace.com/product/shop-kit-pro/?add-to-cart=11021',
					'button' => 'danger',
				),
				'update_url' => array(
					'text'   => esc_html__('View demo', 'shop-kit'),
					'url'    => 'https://wpthemespace.com/shop-kit-demo/',
					'button' => 'primery',
				),
				/* 'video_url' => array(
					'text'   => esc_html__('Show Video', 'shop-kit'),
					'url'    => 'https://www.youtube.com/watch?v=ATu84uap_bc',
					'button' => 'danger',
				), */

			),

			// Getting started.
			'getting_started' => array(
				'one' => array(
					'title'       => esc_html__('Demo Content', 'shop-kit'),
					'icon'        => 'dashicons dashicons-layout',
					'description' => sprintf(esc_html__('Demo content is pro feature. To import sample demo content, %1$s plugin should be installed and activated. After plugin is activated, visit Import Demo Data menu under Appearance.', 'shop-kit'), esc_html__('One Click Demo Import', 'shop-kit')),
					'button_text' => esc_html__('UPGRADE For  Demo Content', 'shop-kit'),
					'button_url'  => 'https://wpthemespace.com/product/shop-kit-pro/?add-to-cart=11021',
					'button_type' => 'primary',
					'is_new_tab'  => true,
				),

				'two' => array(
					'title'       => esc_html__('Theme Options', 'shop-kit'),
					'icon'        => 'dashicons dashicons-admin-customizer',
					'description' => esc_html__('Theme uses Customizer API for theme options. Using the Customizer you can easily customize different aspects of the theme.', 'shop-kit'),
					'button_text' => esc_html__('Customize', 'shop-kit'),
					'button_url'  => wp_customize_url(),
					'button_type' => 'primary',
				),
				'three' => array(
					'title'       => esc_html__('Show Video', 'shop-kit'),
					'icon'        => 'dashicons dashicons-layout',
					'description' => sprintf(esc_html__('You may show Shop Kit short video for better understanding', 'shop-kit'), esc_html__('One Click Demo Import', 'shop-kit')),
					'button_text' => esc_html__('Show video', 'shop-kit'),
					'button_url'  => 'https://www.youtube.com/watch?v=ATu84uap_bc',
					'button_type' => 'primary',
					'is_new_tab'  => true,
				),
				'five' => array(
					'title'       => esc_html__('Set Widgets', 'shop-kit'),
					'icon'        => 'dashicons dashicons-tagcloud',
					'description' => esc_html__('Set widgets in your sidebar, Offcanvas as well as footer.', 'shop-kit'),
					'button_text' => esc_html__('Add Widgets', 'shop-kit'),
					'button_url'  => admin_url() . '/widgets.php',
					'button_type' => 'link',
					'is_new_tab'  => true,
				),
				'six' => array(
					'title'       => esc_html__('Theme Preview', 'shop-kit'),
					'icon'        => 'dashicons dashicons-welcome-view-site',
					'description' => esc_html__('You can check out the theme demos for reference to find out what you can achieve using the theme and how it can be customized. Theme demo only work in pro theme', 'shop-kit'),
					'button_text' => esc_html__('View Demo', 'shop-kit'),
					'button_url'  => 'https://wpthemespace.com/shop-kit-demo/',
					'button_type' => 'link',
					'is_new_tab'  => true,
				),
				'seven' => array(
					'title'       => esc_html__('Contact Support', 'shop-kit'),
					'icon'        => 'dashicons dashicons-sos',
					'description' => esc_html__('Got theme support question or found bug or got some feedbacks? Best place to ask your query is the dedicated Support forum for the theme.', 'shop-kit'),
					'button_text' => esc_html__('Contact Support', 'shop-kit'),
					'button_url'  => 'https://wpthemespace.com/support/',
					'button_type' => 'link',
					'is_new_tab'  => true,
				),
			),

			'useful_plugins'        => array(
				'description' => esc_html__('Theme supports some helpful WordPress plugins to enhance your site. But, please enable only those plugins which you need in your site. For example, enable WooCommerce only if you are using e-commerce.', 'shop-kit'),
				'already_activated_message' => esc_html__('Already activated', 'shop-kit'),
				'version_label' => esc_html__('Version: ', 'shop-kit'),
				'install_label' => esc_html__('Install and Activate', 'shop-kit'),
				'activate_label' => esc_html__('Activate', 'shop-kit'),
				'deactivate_label' => esc_html__('Deactivate', 'shop-kit'),
				'content'                   => array(
					array(
						'slug' => 'magical-addons-for-elementor',
						'icon' => 'svg',
					),
					array(
						'slug' => 'magical-products-display'
					),
					array(
						'slug' => 'magical-posts-display'
					),
					array(
						'slug' => 'click-to-top'
					),
					array(
						'slug' => 'gallery-box',
						'icon' => 'svg',
					),
					array(
						'slug' => 'magical-blocks'
					),
					array(
						'slug' => 'easy-share-solution',
						'icon' => 'svg',
					),
					array(
						'slug' => 'wp-edit-password-protected',
						'icon' => 'svg',
					),
				),
			),
			// Required actions array.
			'recommended_actions'        => array(
				'install_label' => esc_html__('Install and Activate', 'shop-kit'),
				'activate_label' => esc_html__('Activate', 'shop-kit'),
				'deactivate_label' => esc_html__('Deactivate', 'shop-kit'),
				'content'            => array(
					'magical-blocks' => array(
						'title'       => __('Magical Posts Display', 'shop-kit'),
						'description' => __('Now you can add or update your site elements very easily by Magical Products Display. Supercharge your Elementor block with highly customizable Magical Blocks For WooCommerce.', 'shop-kit'),
						'plugin_slug' => 'magical-products-display',
						'id' => 'magical-posts-display'
					),
					'go-pro' => array(
						'title'       => '<a target="_blank" class="activate-now button button-danger" href="https://wpthemespace.com/product/shop-kit-pro/?add-to-cart=11021">' . __('UPGRADE SHOP KIT PRO', 'shop-kit') . '</a>',
						'description' => __('You will get more frequent updates and quicker support with the Pro version.', 'shop-kit'),
						//'plugin_slug' => 'x-instafeed',
						'id' => 'go-pro'
					),

				),
			),
			// Free vs pro array.
			'free_pro'                => array(
				'free_theme_name'     => $xtheme_name,
				'pro_theme_name'      => $xtheme_name . __(' Pro', 'shop-kit'),
				'pro_theme_link'      => 'https://wpthemespace.com/product/shop-kit-pro/',
				/* translators: View link */
				'get_pro_theme_label' => sprintf(__('Get %s', 'shop-kit'), 'Shop Kit Pro'),
				'features'            => array(
					array(
						'title'       => esc_html__('Daring Design for Devoted Readers', 'shop-kit'),
						'description' => esc_html__('Shop Kit \'s design helps you stand out from the crowd and create an experience that your readers will love and talk about. With a flexible home page you have the chance to easily showcase appealing content with ease.', 'shop-kit'),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Mobile-Ready For All Devices', 'shop-kit'),
						'description' => esc_html__('Shop Kit makes room for your readers to enjoy your articles on the go, no matter the device their using. We shaped everything to look amazing to your audience.', 'shop-kit'),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Home slider', 'shop-kit'),
						'description' => esc_html__('Shop Kit gives you extra slider feature. You can create awesome home slider in this theme.', 'shop-kit'),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Widgetized Sidebars To Keep Attention', 'shop-kit'),
						'description' => esc_html__('Shop Kit comes with a widget-based flexible system which allows you to add your favorite widgets over the Sidebar as well as on offcanvas too.', 'shop-kit'),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Auto Set-up Feature', 'shop-kit'),
						'description' => esc_html__('You can import demo site only one click so you can setup your site like demo very easily.', 'shop-kit'),
						'is_in_lite'  => 'ture',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Multiple Header Layout', 'shop-kit'),
						'description' => esc_html__('Shop Kit gives you extra ways to showcase your header with miltiple layout option you can change it on the basis of your requirement', 'shop-kit'),
						'is_in_lite'  => 'true',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('One Click Demo install', 'shop-kit'),
						'description' => esc_html__('You can import demo site only one click so you can setup your site like demo very easily.', 'shop-kit'),
						'is_in_lite'  => 'ture',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Extra Drag and drop support', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Advanced Portfolio Filter', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Testimonial Carousel', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Diffrent Style Blog', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Flexible Home Page Design', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Pro Service Section', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Animation Home Text', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Advance Customizer Options', 'shop-kit'),
						'description' => esc_html__('Advance control for each element gives you different way of customization and maintained you site as you like and makes you feel different.', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('Advance Pagination', 'shop-kit'),
						'description' => esc_html__('Multiple Option of pagination via customizer can be obtained on your site like Infinite scroll, Ajax Button On Click, Number as well as classical option are available.', 'shop-kit'),
						'is_in_lite'  => 'ture',
						'is_in_pro'   => 'true',
					),

					array(
						'title'       => esc_html__('Premium Support and Assistance', 'shop-kit'),
						'description' => esc_html__('We offer ongoing customer support to help you get things done in due time. This way, you save energy and time, and focus on what brings you happiness. We know our products inside-out and we can lend a hand to help you save resources of all kinds.', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
					array(
						'title'       => esc_html__('No Credit Footer Link', 'shop-kit'),
						'description' => esc_html__('You can easily remove the Theme: Shop Kit by Shop Kit copyright from the footer area and make the theme yours from start to finish.', 'shop-kit'),
						'is_in_lite'  => 'false',
						'is_in_pro'   => 'true',
					),
				),
			),

		);

		shop_kit_About::init($config);
	}

endif;

add_action('after_setup_theme', 'shop_kit_about_setup');

/**
 * Pro notice text
 *
 */
function shop_kit_pnotice_output()
{
?>
	<div class="mgadin-hero">
		<div class="mge-info-content">
			<div class="mge-info-hello">
				<?php
				$current_theme = wp_get_theme();
				$current_theme_name = $current_theme->get('Name');
				$current_user = wp_get_current_user();

				$demo_link = esc_url('https://wpthemespace.com/product/shop-kit-pro/');
				$pro_link = esc_url('https://wpthemespace.com/product/shop-kit-pro/?add-to-cart=11021');


				esc_html_e('Hello, ', 'shop-kit');
				echo esc_html($current_user->display_name);
				?>

				<?php esc_html_e('👋🏻', 'shop-kit'); ?>
			</div>
			<div class="mge-info-desc">
				<div><?php printf(esc_html__('🚨 Attention, don\'t miss out on Shop Kit Pro! 🚀 Upgrade to Shop Kit Pro today and unlock all shop features tools, SEO-friendly architecture, lightning-fast speed optimization, enhanced security features, premade demos, effortless one-click demo imports, and exclusive custom Elementor premium widgets. With the pro version, you can elevate your website to the next level and truly stand out from the competition.', 'shop-kit'), $current_theme_name); ?></div>
				<div class="mge-offer"><?php printf(esc_html__('Upgrade to Shop Kit Pro now and enjoy 30%% off with our early bird discount! Use code SPKI30ERB at checkout. Don\'t miss out on this incredible offer!', 'shop-kit'), $current_theme_name); ?></div>
			</div>
			<div class="mge-info-actions">
				<a href="<?php echo esc_url($pro_link); ?>" target="_blank" class="button button-primary upgrade-btn">
					<?php esc_html_e('Upgrade Pro', 'shop-kit'); ?>
				</a>
				<a href="<?php echo esc_url($demo_link); ?>" target="_blank" class="button button-primary demo-btn">
					<?php esc_html_e('View Details', 'shop-kit'); ?>
				</a>
				<button class="button button-info btnend"><?php esc_html_e('Dismiss this notice', 'shop-kit') ?></button>
			</div>

		</div>

	</div>
<?php
}
//Admin notice 
function shop_kit_new_optins_texts()
{
	$hide_date = get_option('shop_kit_infotext');
	if (!empty($hide_date)) {
		$clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
		if ($clickhide < 25) {
			return;
		}
	}
?>
	<div class="mgadin-notice notice notice-info mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
		<?php shop_kit_pnotice_output(); ?>
	</div>
<?php

}
add_action('admin_notices', 'shop_kit_new_optins_texts');

function shop_kit_new_optins_texts_init()
{
	if (isset($_GET['xbnotice']) && $_GET['xbnotice'] == 1) {
		update_option('shop_kit_infotext', current_time('mysql'));
	}
}
add_action('init', 'shop_kit_new_optins_texts_init');
