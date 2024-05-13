<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('OTFW_Cart')) {
    /**
     *Class OTFW_Cart
     *
     * @since 1.0.0
     */
    class OTFW_Cart {
        protected $cart;
        protected $product;
        public function __construct(OTFW_Product $product) {
            $this->product = $product;
        }
        public function get_id2($product, $variation_id = 0, $variation = array(), $cart_item_data = array()) {


           if (null === (WC()->cart)){
            wc()->frontend_includes();
            WC()->session = new WC_Session_Handler();
            WC()->session->init();
            WC()->customer = new WC_Customer( get_current_user_id(), true );
            WC()->cart = new WC_Cart();
            }

            if ($product->is_type('variation')) {
                $cart_item_data = (array) apply_filters('woocommerce_add_cart_item_data', $cart_item_data, $product->get_parent_id(), $variation_id);
                $cart_id        = WC()->cart->generate_cart_id($product->get_parent_id(), $variation_id, $variation, $cart_item_data);
                return WC()->cart->find_product_in_cart($cart_id);
            } else {
                $cart_item_data = (array) apply_filters('woocommerce_add_cart_item_data', $cart_item_data, $product->get_id(), $variation_id);
                $cart_id        = WC()->cart->generate_cart_id($product->get_id(), $variation_id, $variation, $cart_item_data);
                return WC()->cart->find_product_in_cart($cart_id);
            }

        }
        public function get_item_quantity($cart_item_hash) {
            if (!$cart_item_hash) {
                return 0;
            }
            $contents = $this->get_cart_contents();
            return (float) (isset($contents[$cart_item_hash]) ? $contents[$cart_item_hash]['quantity'] : 0);
        }
        public function get_cart_contents() {
            if (!$this->cart) {
                $this->cart = WC()->cart->get_cart();
            }
            return $this->cart;
        }
    }
}
