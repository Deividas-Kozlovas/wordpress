<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('OTFW_Loop')) {
    /**
     * Main Class OTFW_Loop
     *
     * @since 1.0.0
     */
    class OTFW_Loop {
        protected $product;
        protected $products = array();
        protected $next_page;
        public function __construct($product) {
            $this->product = $product;
            add_action( 'get_header', array( $this, 'go_ajax' ), 9 );
            add_action('otfw_loop_process_ajax_request', array(
                $this,
                'process_ajax_request'
            ));
        }
        public function go_ajax() {
		if ( otfw_is_ajax()) {

			$this->process_ajax_request();
		}
	}
        public function process_ajax_request() {
            $return_first = isset($_GET['single']) && $_GET['single'] ? true : false;
             $args = array(
            'post_type' => 'product',
            'orderby' => 'menu_order',

            );

             if (have_posts()) {
                while (have_posts()):
                    the_post();
                    $this->the_product();
                endwhile;
                 //  wp_reset_postdata();
            }
            if ($return_first) {
                wp_send_json($this->get_first_item());
            } else {
                wp_send_json($this->get_loop_items());
            }
        }
        public function the_product() {
            extract($this->product->the_product());
            $this->products  = array_merge($this->products, $products);
            $this->next_page = $next_page;
        }
        public function get_first_item() {
            if (isset($this->products[0])) {
                return $this->products[0];
            } else {
                return array();
            }
        }
        public function get_loop_items() {
            return array(
                'products' => ($this->products),
                'next_page' => ($this->next_page)
            );
        }
    }
}
