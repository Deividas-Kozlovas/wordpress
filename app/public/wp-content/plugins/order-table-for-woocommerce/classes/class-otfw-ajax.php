<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('OTFW_AJAX') ) {
    /**
     * Main Class OTFW_AJAX
     *
     * @since 1.0.0
     */
    class OTFW_AJAX {
        public function __construct() {
            $this->reg_my_ajax_methods();
        }
        public function otfw_fetch_products() {
		global $wp_query;
		$ids = explode( ',', wc_clean( $_GET['product_ids'] ) );

		$args = array(
			'post_type'      => array( 'product', 'product_variation' ),
			'post__in'       => $ids,
			'paged'          => get_query_var( 'paged' ),
			'posts_per_page' => apply_filters( 'loop_shop_per_page', get_option( 'posts_per_page' ) ),
		);

		$wp_query = new WP_Query( $args );


		do_action( 'otfw_loop_process_ajax_request' );
	}
        public static function filter_woocommerce_add_to_cart_validation($true, $product_id, $quantity) {
            $product_cart_id = WC()->cart->generate_cart_id($product_id);
            $in_cart         = WC()->cart->find_product_in_cart($product_cart_id);
            if (!$in_cart) {
                return $true;
            } else {
                return false;
            }
        }
        public function otfw_add_to_cart() {
            check_ajax_referer( 'otfw-script-nonce', 'nonce' );
           
            $product_id           = apply_filters('woocommerce_add_to_cart_product_id', absint(sanitize_text_field($_POST['product_id'])));

            $quantity             = empty($_POST['quantity']) ? 1 : wc_stock_amount(sanitize_text_field($_POST['quantity']));

            $product_status       = get_post_status($product_id);
            $variation_id         = empty($_POST['variation_id']) ? 0 : absint(sanitize_text_field($_POST['variation_id']));
            $variation            = empty($_POST['variation_atts']) ? array() : sanitize_term($_POST['variation_atts'],key($_POST['variation_atts']), 'attribute');
            $status               = true;
			 $product = wc_get_product($product_id);
			 $product_ = $product;

              array_pop($variation);
             $passed_validation    = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation );
              
            $product_quantity_new = $quantity;
            if ($passed_validation && 'publish' === $product_status) {
                $status               = false;
                $product_quantity_new = $quantity;
                if ($variation_id != 0) {

                     $variation_product = wc_get_product($variation_id);
					 $product_ = $variation_product;
                  
                    if (($product->is_type('simple') && $product->managing_stock()) || ($product->managing_stock() && (  $variation_product->is_type('variation') && $variation_product-> managing_stock() != 'true' ))) {

                        $product_qty_in_cart = WC()->cart->get_cart_item_quantities();
                        $max_to              = $product->get_max_purchase_quantity();
                       
                        if (array_key_exists($product->get_stock_managed_by_id(), $product_qty_in_cart)) {
                            $in_cart_total = $product_qty_in_cart[$product->get_stock_managed_by_id()];
                        } else {
                            $in_cart_total = 0;
                        }
                        $avalible = ($max_to - $in_cart_total);
						if($max_to == -1){
							$product_quantity_new = $product_quantity_new ;
							// $status               = true;
						}
                       else if ($quantity > $avalible) {
                            $product_quantity_new = $avalible;
                            $status               = true;
                        }
                        
                    }else if($variation_product->managing_stock()){

                       $avalible             = $variation_product->get_max_purchase_quantity();
                     
					   if($avalible == -1){
                      
							$product_quantity_new = $product_quantity_new ;
							
						}
                       else if ($quantity > $avalible) {
                            $product_quantity_new = $avalible;
                            $status               = true;
                        }else{

                             $product_quantity_new = $quantity;
                            $status               = true;

                        }


                    }
                }
               
                if ($product_quantity_new > 0) {



                       $hash = WC()->cart->add_to_cart($product_id, $product_quantity_new, $variation_id, $variation);
                        
                    if (false != $hash) {
                        
                        do_action('woocommerce_ajax_added_to_cart', $product_id);
                        do_action('woocommerce_update_cart_action_cart_updated');

                        $quant            = wp_kses_data(WC()->cart->get_cart_contents_count());
                        $cart = (WC()->cart->get_cart());



                        $data = array(
                            'success' => true,
                            'quant' => $quant,
                            'quant_item' => WC()->cart->get_cart_subtotal(),
                            'cart_item_hash' => $hash,
                            'status' => $status,
                            'product_quantity' => $product_quantity_new,
							'item_amount' =>  WC()->cart->get_product_subtotal( $product_, (isset($hash) && isset($cart[$hash]) ? $cart[$hash]['quantity'] : 0) ),

                        );
                    }else{
                         $data = array(
                        'error' => true,
                         'quant' => wp_kses_data(WC()->cart->get_cart_contents_count()),
                    'quant_item' => WC()->cart->get_cart_subtotal(),
                        );
                    }
                } else {
                        $quant =             wp_kses_data(WC()->cart->get_cart_contents_count());
						 $cart =            (WC()->cart->get_cart());



                    $data = array(
                        'error' => true,
                        'status' => $status,
                        'product_quantity' => $product_quantity_new,
                        'quant' => $quant,
                        'quant_item' => WC()->cart->get_cart_subtotal(),
						'item_amount' =>  WC()->cart->get_product_subtotal( $product_, (isset($hash) && isset($cart[$hash]) ? $cart[$hash]['quantity'] : 0) ),
                    );
                }
            } else {
				 $cart = (WC()->cart->get_cart());
                $data = array(
                    'error' => true,
                     'quant' => wp_kses_data(WC()->cart->get_cart_contents_count()),
                    'quant_item' => WC()->cart->get_cart_subtotal(),
                    'status' => $status,
                    'product_quantity' => $product_quantity_new,
					'item_amount' =>  WC()->cart->get_product_subtotal( $product_, (isset($hash) && isset($cart[$hash]) ? $cart[$hash]['quantity'] : 0) ),
                );
            }
            wp_send_json($data);
        }
        public function reg_my_ajax_methods() {
            $new_reflex = new ReflectionClass(get_class($this));
            foreach ($new_reflex->getMethods() as $method) {
                if (strpos($method->name, 'otfw') === 0) {
                    $ref = new ReflectionMethod(get_class($this), $method->name);
                    add_action('wc_ajax_' . $method->name, array(
                        $this,
                        $method->name
                    ), 10, count($ref->getParameters()));
                }
            }
        }

        public static function otfw_update_cart() {
            check_ajax_referer( 'otfw-script-nonce', 'nonce' );
            $cart_item_key     = sanitize_text_field($_POST['cart_item_key']);
            $product_values    = WC()->cart->get_cart_item($cart_item_key);
            $product_quantity  = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var(sanitize_text_field($_POST['quantity']), FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);
            $passed_validation = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $product_values, $product_quantity);
            if ($passed_validation ) {
                $status               = false;
                $product_quantity_new = $product_quantity;
                if (WC()->cart->find_product_in_cart($cart_item_key) ) {
                    $product = wc_get_product($product_values['product_id']);
                    $variation_product = wc_get_product(intval($product_values['variation_id']));
					$product_ = false != $variation_product && null != $variation_product ? $variation_product : $product;

                    if (($product->is_type('simple') && $product->managing_stock()) || ($product->managing_stock() && (  $variation_product->is_type('variation') && $variation_product-> managing_stock() != 'true' ))) {
                        $product_qty_in_cart = WC()->cart->get_cart_item_quantities();
                        $max_to              = $product->get_max_purchase_quantity();
                        $in_cart_total       = $product_qty_in_cart[$product->get_stock_managed_by_id()];
                        $in_cart             = $product_values['quantity'];
                        $avalible            = ($in_cart) + ($max_to - $in_cart_total);

						if($max_to == -1){
							$product_quantity_new = $product_quantity_new ;
							
						}
						else if ($product_quantity > $avalible) {
                            $product_quantity_new = $avalible;
                            $status               = true;
                        }
                        WC()->cart->set_quantity($cart_item_key, $product_quantity_new, true);

                    }else if(false != $variation_product){

                    if($variation_product-> managing_stock()){
                       $product_qty_in_cart = WC()->cart->get_cart_item_quantities();
                        $max_to              = $variation_product->get_max_purchase_quantity();
                        $in_cart_total       = $product_qty_in_cart[$variation_product->get_stock_managed_by_id()];
                        $in_cart             = $product_values['quantity'];
                        $avalible            = ($in_cart) + ($max_to - $in_cart_total);
						if($max_to == -1){
							$product_quantity_new = $product_quantity_new ;
							
						}

                      else if ($product_quantity > $avalible) {
                            $product_quantity_new = $avalible;
                            $status               = true;
                        }
                        WC()->cart->set_quantity($cart_item_key, $product_quantity_new, true);

                            }else{
								 WC()->cart->set_quantity($cart_item_key, $product_quantity_new, true);
							}
                    }
                    else {
                        WC()->cart->set_quantity($cart_item_key, $product_quantity_new, true);
                    }

                do_action('woocommerce_update_cart_action_cart_updated');
                $item             = $product_values;
                $quant            = wp_kses_data(WC()->cart->get_cart_contents_count());
                $cart = WC()->cart->get_cart();


                $data = array(
                    'success' => true,
                    'status' => $status,
                    'product_quantity' => $product_quantity_new,
                    'quant' => $quant,
                    'quant_item' => WC()->cart->get_cart_subtotal(),
					'item_amount' =>  WC()->cart->get_product_subtotal( $product_, (isset($cart_item_key) && isset($cart[$cart_item_key]) ? $cart[$cart_item_key]['quantity'] : 0) ),

                );
                if (defined('WP_DEBUG') && WP_DEBUG)
                    $data['item'] = $item;
            } else {
                $data = array(
                    'error' => true,
                     'quant' => wp_kses_data(WC()->cart->get_cart_contents_count()),
                    'quant_item' => WC()->cart->get_cart_subtotal(),
                );
            }
            wp_send_json($data);
        }else{
           
                $data = array(
                    'error' => true,
                    'quant' => wp_kses_data(WC()->cart->get_cart_contents_count()),
                    'quant_item' => WC()->cart->get_cart_subtotal(),
                    
                );
            wp_send_json($data); 
        }
    }
}
}
