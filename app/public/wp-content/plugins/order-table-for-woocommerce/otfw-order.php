<?php
/**
 * Plugin Name: Wholesale Order Table for WooCommerce
 * Plugin URI: https://wholesaleorderplugin.com/
 * Description: A flexible order table for WooCommerce. Perfect for B2B and Wholesalers.
 * Version: 2.2.0
 * Author: Arosoft.se
 * Author URI: https://arosoft.se
 * Developer: Arosoft.se
 * Developer URI: https://arosoft.se
 * Text Domain: otfw
 * Domain Path: /languages
 * WC requires at least: 7.0
 * WC tested up to: 8.5
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Copyright: Arosoft.se 2024
 * License: GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
define('OTFW_VERSION', '2.2.0');
if (!defined('ABSPATH')) {
    exit;
}
register_activation_hook(__FILE__, array(
    'OTFW',
    'activate'
));
register_uninstall_hook(__FILE__, array(
    'OTFW',
    'uninstall'
));
if (!class_exists('OTFW')) {
    /**
     * Main Class OTFW
     *
     * @since 1.0.0
     */
    class OTFW {
        const TEXT_DOMAIN = 'otfw';
        protected static $_instance = null;
        public $override_templates = false;
        public $product;
		protected $shortcode;
        protected $notices;
        public $loop;
        public $ajax;
        public $cart;
        public static $otfw_table_config;
		public static $categories_raw;
		// sets $categories to product categories
        public static function set_categories()
        {
			if(!isset(self::$categories_raw)){
            $taxonomy         = 'product_cat';
            $orderby          = 'menu_order';
            $show_count       = 0; // 1 for yes, 0 for no
            $pad_counts       = 0; // 1 for yes, 0 for no
            $hierarchical     = 1; // 1 for yes, 0 for no
            $title            = '';
            $empty            = 0;
			$fields			  = 'all';
            $args             = array(
                 'taxonomy' => $taxonomy,
                'orderby' => $orderby,
                'show_count' => $show_count,
                'pad_counts' => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li' => $title,
                'hide_empty' => $empty,
				'fields'	=> $fields,
            );

            self::$categories_raw = get_categories( $args );


			}

			return;
        }
        // Return product categories
        public static function get_categories_raw()
        {
            return self::$categories_raw;
        }
        public static function setVar() {
			if(is_string(get_option('otfw_table_config')) ){
            self::$otfw_table_config = json_decode(get_option('otfw_table_config'), TRUE);

			}elseif(is_array(get_option('otfw_table_config' )) || is_object(get_option('otfw_table_config' )) ){


				 self::$otfw_table_config = get_option('otfw_table_config');

			}else{

				self::$otfw_table_config = null;

			}

        }
        public static function getVar() {
			if(( is_array(self::$otfw_table_config) || is_object(self::$otfw_table_config) ) && !empty(self::$otfw_table_config)){

			if(array_key_exists ( 'true_name' , self::$otfw_table_config[0])){
			return self::$otfw_table_config;
			}else{
			 include(plugin_dir_path(__FILE__) . 'includes/start-args.php');
			 $new_start_args = $start_args;
			 $new_array = array();
			 $i=0;
			 foreach(self::$otfw_table_config as $array){
				$arr = $array;
				if($array['label'] == 'quantity_input'){
					$arr['label'] = 'quantity input';
				}

				$arr['true_name'] =  $new_start_args[$i]['true_name'];
				 array_push( $new_array, $arr);
				$i++;

			 }
                 update_option('otfw_table_config', json_encode($new_array));
				 self::setVar();
				 return self::$otfw_table_config;
			}}else{



				$temp_category_order  = array();
				return  $temp_category_order;
			}
        }
        public function __construct() {
            add_action('admin_init', array(
                $this,
                'check_environment'
            ));
            add_action('admin_notices', array(
                $this,
                'admin_notices'
            ), 15);
            add_action('plugins_loaded', array(
                $this,
                'init'
            ));
             // Declare compatibility of WooCommerce HPOS order structure
             add_action('before_woocommerce_init', function ()
             {
                 if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class))
                 {
                     \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
                 }
             });
            
             
        }
        public function enqueue_admin_pages_scripts_and_styles() {
            if (WP_DEBUG === true) {
                wp_enqueue_style('admin-pages-admin-styles', plugin_dir_url(__FILE__) . '/assets/css/admin-style.css');
                wp_enqueue_script('admin-pages-admin-script', $this->get_plugin_url('assets/js/otfw-admin-scripts.js'), array(
                    'jquery',

                ), OTFW_VERSION, true);
                wp_enqueue_style( 'fdoe-order-font-3', $this->get_plugin_url( 'assets/fontawesome/css/fontawesome.min.css' ) );
				wp_enqueue_style( 'fdoe-order-font-4', $this->get_plugin_url( 'assets/fontawesome/css/solid.min.css' ) );
            } else {
                wp_enqueue_style('admin-pages-admin-styles', plugin_dir_url(__FILE__) . '/assets/css/admin-style.min.css');
                wp_enqueue_script('admin-pages-admin-script', $this->get_plugin_url('assets/js/otfw-admin-scripts.min.js'), array(
                    'jquery',

                ), OTFW_VERSION, true);
                wp_enqueue_style( 'fdoe-order-font-3', $this->get_plugin_url( 'assets/fontawesome/css/fontawesome.min.css' ) );
				wp_enqueue_style( 'fdoe-order-font-4', $this->get_plugin_url( 'assets/fontawesome/css/solid.min.css' ) );
            }
        }

        function admin_inline_js() {
			self::setVar();

            echo "<script type='text/javascript'>\n";
            echo 'var option = ' . wp_json_encode(self::getVar()) . ';';
            echo "\n</script>";
        }
        public function includes() {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
            require_once('classes/class-otfw-product.php');
            require_once('classes/class-otfw-ajax.php');
            require_once('classes/class-otfw-cart.php');
            require_once('classes/class-otfw-loop.php');
			require_once( 'classes/class-otfw-shortcode.php' );

            // Check if to run the premium version
		update_option('otfw_is_premium', 'no');

		if( file_exists(plugin_dir_path(__FILE__) . 'premium') ){


					require_once( 'premium/class-otfw-settings-premium.php' );
					update_option('otfw_is_premium', 'yes');
			}
			else{
				 require_once( 'classes/class-otfw-settings.php' );

			}

        }
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
				 function otfw_main_query($query ){
		// Check if shop page
		if(!is_ajax()){
			$page= (wc_get_page_id( 'shop' )) !== -1 ? wc_get_page_id( 'shop' ) :'otfw_test_page';
		if( ($query->is_post_type_archive( 'product' ) || $query->is_page( $page)) && get_option('otfw_override_shop') == "yes"    ){

			$query->set( 'post_type' , 'product');
            $query->set('orderby' , 'menu_order');

			if(get_option('otfw_override_shop_show_cat') == 'yes'){

				$query->set( 'posts_per_page', -1 );
			}


			}
		}
			}
        public static function activate() {
           
            
            update_option( 'Order_Table_Activated_Plugin', 'Order_Table' );
            update_option('otfw_is_premium', 'no');
            add_option('Activated_Plugin', 'Plugin-Slug');
            add_option('otfw_sale_badge', 'yes');
            add_option('otfw_use_plugin_increment_css', 'yes');
            add_option('otfw_override_all', 'yes');
			update_option('otfw_confirm_input','yes');
			delete_option('otfw_table_config');

self::setVar();
		  $isset = self::getVar();
		  if( file_exists(plugin_dir_path(__FILE__) . 'premium') ){
            if ($isset == false || $isset == null) {
               include(plugin_dir_path(__FILE__) . 'premium/start-args-premium.php');
                update_option('otfw_table_config', json_encode($start_args));
            }else{

				if((is_array($isset) && array_key_exists('true_name', $isset[0]) || (is_object($isset) && property_exists('true_name', $isset[0])))){

				}else{
					 include(plugin_dir_path(__FILE__) . 'premium/start-args-premium.php');
                update_option('otfw_table_config', json_encode($start_args));

				}
			}
		  }else{
			 if ($isset == false || $isset == null) {
               include(plugin_dir_path(__FILE__) . 'includes/start-args.php');
                update_option('otfw_table_config', json_encode($start_args));
            }else{

				if((is_array($isset) && array_key_exists('true_name', $isset[0]) || (is_object($isset) && property_exists('true_name', $isset[0])))){
				 include(plugin_dir_path(__FILE__) . 'includes/start-args.php');
                update_option('otfw_table_config', json_encode($start_args));
				}
			}

		  }
        }
        public static function uninstall() {
           // do nothing for now


        }
        public function init() {
            if (self::get_environment_warning()) {
                return;
            }
            if (is_admin() && get_option('Activated_Plugin') == 'Plugin-Slug') {
                delete_option('Activated_Plugin');
            }
            $this->includes();
            
            add_action('wp_footer', array(
                $this,
                'output_modals'
            ), 10);
            add_action('wp_enqueue_scripts', array(
                $this,
                'enqueue_scripts'
            ), 30);
            add_action('wp_enqueue_scripts', array(
                $this,
                'enqueue_scripts_special'
            ), 10);
            add_action('woocommerce_init', array(
                $this,
                'woocommerce_start'
            ));
            add_action('wp_footer', array(
                $this,
                'load_templates'
            ));
            add_action('wp_print_styles', array(
                $this,
                'wp_print_styles'
            ));
            add_action('template_redirect', array(
                $this,
                'check_for_404'
            ));
            add_action('wp', array(
                $this,
                'wp'
            ));
            add_action('wc_dynamic_pricing_load_modules', array(
                $this,
                'wc_dynamic_pricing_load_modules'
            ), 99, 1);
            add_action('admin_enqueue_scripts', array(
                $this,
                'enqueue_admin_pages_scripts_and_styles'
            ));

            add_action('admin_print_footer_scripts', array(
                $this,
                'admin_inline_js'
            ));
			 add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(
                $this,
                'add_action_links'
            ) );
			 add_action( 'pre_get_posts', array($this, 'otfw_main_query' ),99);
             
             
             load_plugin_textdomain(OTFW::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');


            
           
             
             
             
        }
        public function check_environment() {
             if ( is_admin() && get_option( 'Order_Table_Activated_Plugin' ) == 'Order_Table' ) {
                delete_option( 'Order_Table_Activated_Plugin' );
            }
            $environment_warning = self::get_environment_warning();
            if ($environment_warning && is_plugin_active(plugin_basename(__FILE__))) {
                $this->add_admin_notice('bad_environment', 'error', $environment_warning);
                deactivate_plugins(plugin_basename(__FILE__));
            }




        }
        public function add_admin_notice($slug, $class, $message) {
            $this->notices[$slug] = array(
                'class' => $class,
                'message' => $message
            );
        }
        public function admin_notices() {
            foreach ((array) $this->notices as $notice_key => $notice) {
                echo "<div class='" . esc_attr($notice['class']) . "'><p>";
                echo wp_kses($notice['message'], array(
                    'a' => array(
                        'href' => array()
                    )
                ));
                echo '</p></div>';
            }
        }
       // Checks if WooCommerce is active and if not returns error message
         static function get_environment_warning()
        {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            if ( !defined( 'WC_VERSION' ) ) {


                return esc_html__( 'Wholesale Order Table requires WooCommerce to be activated to work.', OTFW::TEXT_DOMAIN );

                die();
            }
			//if this is Premium

			/*else if ( is_plugin_active( 'order-table-for-woocommerce/otfw-order.php') ) {


                return esc_html__( 'Wholesale Order Table Premium can not be activated when the free version is active.', OTFW::TEXT_DOMAIN );
                die();
            }*/
			// If this is free version
			else if ( is_plugin_active( 'order-table-premium/otfw-order.php') ) {
                return esc_html__( 'Wholesale Order Table can not be activated when the premuim version is active.' , OTFW::TEXT_DOMAIN );
                die();
            }

            return false;
        }
        public function wp() {
		if( ( !is_admin() && ( (is_shop() && get_option('otfw_override_shop') == "yes") || wc_post_content_has_shortcode( 'ordertable' )) ) ){

                return;
		}
            $this->override_off();
        }

        public function load_templates() {
            include('templates/product.php');
			include( 'templates/categories.php' );
        }
        public function wp_print_styles() {
            if (get_option('otfw_use_plugin_increment_css') == 'yes') {
                wp_dequeue_style('wcqib-css');
            }
        }
        public function enqueue_scripts_special() {
            wp_enqueue_style('fdoe-order-font-1', $this->get_plugin_url('assets/fontawesome/css/fontawesome.min.css'));
            wp_enqueue_style('fdoe-order-font-2', $this->get_plugin_url('assets/fontawesome/css/solid.min.css'));
            wp_enqueue_style('fdoe-order-boot-css', $this->get_plugin_url('assets/bootstrap/css/bootstrap.min.css'));
            wp_enqueue_script('fdoe-order-boot-js', $this->get_plugin_url('assets/bootstrap/js/bootstrap.min.js'));
        }
        public function enqueue_scripts() {
            if (get_option('otfw_use_plugin_increment_css') == 'yes') {
                wp_enqueue_style('otfw-quantity-increment', $this->get_plugin_url('assets/css/increment-buttons.css'));
            }
            if (WP_DEBUG === true) {
                wp_enqueue_style('otfw-order', $this->get_plugin_url('assets/css/style.css'));
                if (get_option('otfw_table_style', 'default') == 'default') {
                    wp_enqueue_style('otfw-order-skin1', $this->get_plugin_url('assets/css/style-skin1.css'));
                }
                wp_enqueue_script('otfw-order', $this->get_plugin_url('assets/js/otfw-order.js'), array(
                    'jquery',
                    'backbone'
                ), OTFW_VERSION, true);
            } else {
                wp_enqueue_style('otfw-order', $this->get_plugin_url('assets/css/style.min.css'));
                if (get_option('otfw_table_style', 'default') == 'default') {
                    wp_enqueue_style('otfw-order-skin1', $this->get_plugin_url('assets/css/style-skin1.min.css'));
                }
                wp_enqueue_script('otfw-order', $this->get_plugin_url('assets/js/otfw-order.min.js'), array(
                    'jquery',
                    'backbone'
                ), OTFW_VERSION, true);
            }
            if (get_option('otfw_table_header_sticky', 'no') == 'yes') {
                wp_enqueue_script('otfw-order-hooters', $this->get_plugin_url('assets/js/sticky-hooters.js'), array(
                    'jquery',
                    'backbone'
                ), OTFW_VERSION, true);
            }
            global $wp_query;
			self::set_categories();
            $args = array(

                'base_path' => get_option('siteurl'),
                'cart_remove_url' => wc_get_cart_remove_url('otfw_item_key'),
                'stick_table' => get_option('otfw_table_header_sticky'),
                'wc_ajax_url' => WC_AJAX::get_endpoint('%%endpoint%%'),
                'stock_warning' => get_option('otfw_show_stocklevel', 'no'),
                'stock_message_part_1' => esc_html__('Maximum order quantity of ', OTFW::TEXT_DOMAIN),
                'stock_message_part_2' => esc_html__(' is ', OTFW::TEXT_DOMAIN),
                'stock_message_part_3' => esc_html__('Available stock quantity of ', OTFW::TEXT_DOMAIN),
                'min_message_1' => esc_html__('Minimum order quantity of this item is ', OTFW::TEXT_DOMAIN),
                'max_message_1' => esc_html__('Maximum order quantity of this item is ', OTFW::TEXT_DOMAIN),
                'warning_message' => esc_html__('The quantity can not be added to cart', OTFW::TEXT_DOMAIN),
				'sale_string' => esc_html__( 'Sale', OTFW::TEXT_DOMAIN ),
                'is_shop' => 	 ( (is_shop() && get_option('otfw_override_shop') == "yes") || wc_post_content_has_shortcode( 'ordertable' )),

                'image_size' => get_option('otfw_show_images', 'medium'),
				'cats' => self::get_categories_raw(),

				'confirm_input' => get_option('otfw_confirm_input','no'),
				'link_title' => get_option( 'otfw_link_title','no' ),
				'sale_badge' => get_option( 'otfw_sale_badge','no' ),
				'product_list' => OTFW_Product::get_config('true_name'),
				'show_cats_shop' => get_option('otfw_override_shop_show_cat', 'no') == 'yes' ? 1 :0,
				'show_cat_desc' => get_option('otfw_show_cat_desc', 'no') == 'yes' ? 1 :0,
				'show_cats_expand' => get_option('otfw_cats_expand', 'yes') == 'yes' ? 1 :0,
                'nonce' => wp_create_nonce('otfw-script-nonce') ,








            );
            wp_localize_script('otfw-order', 'otfw', $args);
        }
        public function woocommerce_start() {

			if ( !did_action( 'woocommerce_init' ) || is_admin() ){

				return ;
			}


			$this->override_on();

            $this->ajax    = new OTFW_AJAX();
            $this->product = new OTFW_Product();
            $this->cart    = new OTFW_Cart($this->product);
            $this->product->set_cart($this->cart);
            $this->loop = new OTFW_Loop($this->product);
			$this->shortcode = OTFW_Shortcode::instance();

			 $isset = get_option('otfw_table_config');
            if ($isset == false || isset ($isset[0]['true_name']) ) {
                include(plugin_dir_path(__FILE__) . 'includes/start-args.php');
                 update_option('otfw_table_config', json_encode($start_args));
            }
        }
       
        public function check_for_404() {
            if (is_404())
                $this->override_off();
        }
       
        public function override_on() {
            add_filter('woocommerce_locate_template', array(
                $this,
                'override_templates'
            ),9999, 3);
            add_filter('wc_get_template_part', array(
                $this,
                'override_template_part'
            ), 9999, 3);
        }
        public function override_off() {
            remove_filter('woocommerce_locate_template', array(
                $this,
                'override_templates'
            ),9999);
            remove_filter('wc_get_template_part', array(
                $this,
                'override_template_part'
            ),9999);
        }
        public function output_modals() {
            echo '<div class="otfw-aromodals-wrap">
    <div id="max_modal" class="aromodal fade" role="dialog">
  <div class="aromodal-dialog aromodal-sm" role="document">

    <!-- Modal content-->
    <div class="aromodal-content">
      <div class="aromodal-header">
        <button type="button" class="close" data-dismiss="aromodal">&times;</button>
        <h3 class="aromodal-title">' . esc_html__('Order quantity will be adjusted', OTFW::TEXT_DOMAIN) . '</h3>
      </div>
      <div class="aromodal-body">
        <p></p>
      </div>
      <div class="aromodal-footer">
        <button type="button" class="btn btn-default" data-dismiss="aromodal">'. esc_html__('Close', OTFW::TEXT_DOMAIN) .'</button>
      </div>
    </div>

  </div>
</div>

<div id="stock_modal" class="aromodal fade" role="dialog">
  <div class="aromodal-dialog aromodal-sm" role="document">

    <!-- Modal content-->
    <div class="aromodal-content">
      <div class="aromodal-header">
        <button type="button" class="close" data-dismiss="aromodal">&times;</button>
        <h3 class="aromodal-title">' . esc_html__('Order quantity will be adjusted', OTFW::TEXT_DOMAIN) . '</h3>
      </div>
      <div class="aromodal-body">
        <p class="otfw_____"></p>
      </div>
      <div class="aromodal-footer">
        <button type="button" class="btn btn-default" data-dismiss="aromodal">'. esc_html__('Close', OTFW::TEXT_DOMAIN) .'</button>
      </div>
    </div>

  </div>
</div>
</div>';
        }
		  // Add setting link to Plugins page
        public function add_action_links( $links )
        {

			 if(get_option('otfw_is_premium') == 'no'){
            $links_add = array(
                 '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=otfwlayout' ) . '">Settings</a>',

				 '<a target="_blank" rel="noopener noreferrer" href="https://wholesaleorderplugin.com/">Go Premium</a>',
            );
			}
			else{

				 $links_add = array(
                 '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=otfwlayout' ) . '">Settings</a>');

			}
            return array_merge( $links, $links_add);
        }
        public function get_template_path($relativePath = '') {
            return $this->base_path('/templates/' . WC()->template_path() . $relativePath);
        }
        protected function base_path($relativePath = '') {
            $rc = new \ReflectionClass(get_class($this));
            return dirname($rc->getFileName()) . $relativePath;
        }
        public function override_templates($template, $template_name, $template_path) {
            global $woocommerce;
            $_template = $template;
            if (!$template_path) {
                $template_path = $woocommerce->template_url;
            }
            $plugin_path = $this->get_template_path();
            if (file_exists($plugin_path . $template_name)) {
                $template = $plugin_path . $template_name;
            }
            if (!$template) {
                $template = locate_template(array(
                    $template_path . $template_name,
                    $template_name
                ));
            }
            if (!$template) {
                $template = $_template;
            }
            return $template;
        }
        protected function get_plugin_url($relativePath = '') {
            return untrailingslashit(plugins_url($relativePath, $this->base_path_file()));
        }
        protected function base_path_file() {
            $rc = new \ReflectionClass(get_class($this));
            return $rc->getFileName();
        }
        public function override_template_part($template, $slug, $name) {
            if ($name) {
                $path = $this->get_template_path("{$slug}-{$name}.php");
            } else {
                $path = $this->get_template_path("{$slug}.php");
            }
            return file_exists($path) ? $path : $template;
        }
        static function otfw_cart_items_total() {
            
			$packages_string = '' !== get_option('otfw_package_string','') ? get_option('otfw_package_string') : 'Packages' ;
            return '<span class="otfw_cart_items_total">
    <span class="otfw_label_2"><span class="label otfw_label_color">' . esc_html__($packages_string, OTFW::TEXT_DOMAIN) . ': ' .' <span class="otfw_test">' . wp_kses_data(WC()->cart->get_cart_contents_count()) . ' </span></span><span class="otfw_updating"><i class="fas fa-spinner fa-pulse"></i></span>' . '</span>
    <span class="label otfw_label_color">' . esc_html__('Subtotal', 'woocommerce') . ': '. '<span class="otfw_test_2">' . WC()->cart->get_cart_subtotal() . '</span></span></span>';
        }
    }
}
$GLOBALS['wc_list_items'] = OTFW::instance();
function otfw_is_ajax() {
	if ( !empty($_REQUEST['otfw-ajax']) ){
		return true;
	}
	return false;

}

