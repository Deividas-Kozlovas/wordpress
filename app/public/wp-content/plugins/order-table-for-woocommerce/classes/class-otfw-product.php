<?php

if (!defined('ABSPATH')) {

    exit;

}

if (!class_exists('OTFW_Product')) {

    /**

     * Main Class OTFW_Product

     *

     * @since 1.0.0

     */

    class OTFW_Product {

        protected $cart;

        public static $table_config;
        public static $get_config_filtered_flipped;
        public static $in_array;
         public  $all_tags;
         public static $term_array;

        public function __construct() {

         

           

        }



        public function get_formatted_attributes($product){


             $formatted_attributes = array();



             foreach ($product->get_attributes() as $taxonomy => $attribute_obj ) {
    // Get the attribute label
    // $formatted_attributes[wc_attribute_label($taxonomy)] = $product->get_attribute($taxonomy);
    $formatted_attributes[$taxonomy] = $product->get_attribute($taxonomy);
             }

            return $formatted_attributes;
        }
        public function get_batch_to_use($price, $formatted_attributes){

            $batch_to_use = get_option('otfw_batch_attribute_to_use', '');

            if (isset($formatted_attributes[$batch_to_use]) && floatval($formatted_attributes[$batch_to_use]) > 0 && !is_null($formatted_attributes[$batch_to_use])) {

                $unit_price = (((float) $price) / (floatval($formatted_attributes[$batch_to_use])));

                $batch_size = $formatted_attributes[$batch_to_use];

            } else {

                $unit_price = '';

                $batch_size  = '';

            }
            return array('unit_price_pre' => $unit_price, 'batch_size_pre' => $batch_size);

        }
        public static function get_config($context = 'label'){

            if (!isset(self::$table_config))
            {
                OTFW::setVar();
              $otfw_table_config_pre_filter = OTFW::getVar();


            $x = array_filter($otfw_table_config_pre_filter, function($v){

                return ($v['status'] == true);
                    });

             self::$table_config['true_name']  = array_column($x, 'true_name');
               self::$table_config['label']  = array_column($x, 'label');

               // array_intersect_key($array1, $array2)

            }
            return  $context == 'true_name'? self::$table_config['true_name'] : self::$table_config['label'];

        }
        public  function get_all_tags($product){

             // Tags all




            $terms      = get_the_terms($product->get_id(), 'product_tag');

            $term_check = array();

            if (!empty($terms) && !is_wp_error($terms)) {

                foreach ($terms as $term) {

                    $term_check[] = $term->name;

                }

            }
           $this->all_tags = $term_check;

            return $this->all_tags;
        }
         public function get_the_tags($product){



            $term_check = $this->get_all_tags($product);


           // Single term

            $values     = array();
        if (!isset(self::$term_array)){
            $terms      =  get_terms('product_tag');


            $term_array = array();

            if (!empty($terms) && !is_wp_error($terms)) {

                foreach ($terms as $term) {

                    $term_array[] = $term->name;

                }

            }
            self::$term_array = $term_array;
         }

            $arr_v = array_values($term_check);

            foreach (self::$term_array as $bb) {

                if (in_array($bb, $arr_v)) {

                    array_push($values, '<i class="fas fa-check-circle fa-lg"></i>');

                } else {

                    array_push($values, '');

                }

            }
            $keys1          = array_combine(self::$term_array, $values);

            return $keys1;

        }

        public function set_cart($cart) {

            $this->cart = $cart;

        }
        public static function get_config_filtered_flipped($context='label'){

            if(!isset(self::$get_config_filtered_flipped)){
                OTFW::setVar();
              $otfw_table_config_pre_filter = OTFW::getVar();


            $x = array_filter($otfw_table_config_pre_filter, function($v){

                return ($v['status'] == true);
                    });

             $x2  = array_column($x, 'label');
            self::$get_config_filtered_flipped['label']  = array_flip($x2);
             $y2  = array_column($x, 'true_name');
            self::$get_config_filtered_flipped['true_name']  = array_flip($y2);
            }
            return $context == 'true_name' ? self::$get_config_filtered_flipped['true_name'] : self::$get_config_filtered_flipped['label'];

        }
            public static function static_includes(){

                if(!is_array(self::$in_array)){
                     $to_include = self::get_config('true_name');
                self::$in_array = array(

                 'weight_pre' => in_array('weight_pre',$to_include) ? true : false,

                'shipping_class_pre' => in_array('shipping_class_pre',$to_include) ? true : false,



                'tax_class_pre' => in_array('tax_class_pre',$to_include) ? true : false,
                 'package_price_pre' => in_array('package_price_pre',$to_include) ? true : false,
                 'dimensions_pre' => in_array('dimensions_pre',$to_include) ? true : false,


                'currency_pre' =>  in_array('currency_pre',$to_include) ? true : false,
                'unit_pre' => in_array('unit_pre',$to_include)  ? true : false,

                'short_description_pre' => in_array('short_description_pre',$to_include) ? true : false,

                'tax_pre' => in_array('tax_pre',$to_include) ? true : false,

                'sku_pre' => in_array('sku_pre',$to_include) ? true : false,

                'product_tags_pre' => in_array('product_tags_pre',$to_include)  ? true : false,




                'image_pre' => in_array('image_pre',$to_include) ? true : false,


                'title_pre' => in_array('title_pre',$to_include) ? true: false,

                'price_currency_pre' => in_array('price_currency_pre',$to_include) ? true : false,
                 'unit_price_currency_pre' => in_array('unit_price_currency_pre',$to_include)   ? true : false,

                'unit_price_pre' => in_array('unit_price_pre',$to_include) ?  true: false,

                'package_size_pre' => in_array('package_size_pre',$to_include)  ? true: false,

                 'categories_pre' => in_array('categories_pre',$to_include)  ? true: false,

                );
                }




            }
            public function get_cat_names($cat_ids){
                $cats;
                foreach($cat_ids as $cat){



                    $cats_arr = get_term_by( 'id', $cat, 'product_cat', 'ARRAY_A' );
                    $cats[]= $cats_arr['name'];
                }

                return $cats;
            }
        public function the_product() {

            $product = wc_get_product();

            $hash    = $this->get_hash($product);
             global $woocommerce;
          



            $price                = $this->get_price2($product);




            $formatted_attributes = $this -> get_formatted_attributes($product);



            $unit = get_option('otfw_unit_attribute_to_use', '');

            if (isset($formatted_attributes[$unit]) && !is_null($formatted_attributes[$unit])) {

                $unit_ = $formatted_attributes[$unit];

            } else {

                $unit_ = '';

            }

           $batch_to_use = self::get_batch_to_use($price, $formatted_attributes);


              $cat_id_simple = $product->get_category_ids();



              self::static_includes();



            $item = array(



                'quantity_input_pre' => $this->get_quantity_html($product),


                'id' => $product->get_id(),


                'type_pre' => $product->get_type(),


                'variations_pre' => array(),

                'cart_item_hash_pre' => $hash,


                'in_cart_quantity_pre' => $this->cart->get_item_quantity($hash),

                'permalink_pre' => get_the_permalink(),

                'on_sale_pre' =>  $product->is_on_sale() ,

                'in_stock_pre' => self::otfw_in_stock($product),

                'stock_pre' => $product->get_stock_quantity(),

                'manage_stock_pre' => $product->managing_stock(),

                'parent_manage_stock_pre' => false,


                'cat_id_pre' => (array) $cat_id_simple,

                'max_to_purchase_pre' => $product->get_max_purchase_quantity(),


            );


            if(self::$in_array['unit_price_pre'] && $batch_to_use['unit_price_pre'] > 0   ) $item['unit_price_pre'][] =  round($batch_to_use['unit_price_pre'], 2) ;
            if(self::$in_array['package_size_pre']  ) $item['package_size_pre'][] =  $batch_to_use['batch_size_pre'] ;


if(self::$in_array['unit_price_currency_pre'] && $batch_to_use['unit_price_pre'] > 0 ) $item['unit_price_currency_pre'][] =  wc_price($batch_to_use['unit_price_pre']);
if(self::$in_array['price_currency_pre']  ) $item['price_currency_pre'][] =  $product->get_price_html();

if(self::$in_array['weight_pre']  ) $item['weight_pre'][] =  $product->get_weight() ;
if(self::$in_array['shipping_class_pre']  ) $item[ 'shipping_class_pre'][] = $product->get_shipping_class();
if(self::$in_array['tax_class_pre']  ) $item['tax_class_pre'][] =  $product->get_tax_class();
if(self::$in_array['package_price_pre']  ) $item['package_price_pre'][] =  (float) $price;
if(self::$in_array['dimensions_pre']  ) $item['dimensions_pre'][] =  self::otfw_get_product_dimensions($product);
if(self::$in_array['currency_pre']  ) $item['currency_pre'][] =  get_woocommerce_currency_symbol();
if(self::$in_array['unit_pre'] && isset($unit_)  ) $item['unit_pre'][] =  $unit_;
if(self::$in_array['short_description_pre']  ) $item['short_description_pre'][] =  $product->get_short_description();
if(self::$in_array['title_pre']  ) $item['title_pre'][] =  $product->get_title();
if(self::$in_array['tax_pre']  ) $item['tax_pre'][] =  $this->get_product_tax_rate($product) ;

if(self::$in_array['sku_pre']  ) $item['sku_pre'][] =  $product->get_sku() ;
if(self::$in_array['product_tags_pre']  ) $item['product_tags_pre'][] = implode(" ", $this->get_all_tags($product) );

if(self::$in_array['image_pre']  ) $item['image_pre'][] =  array(

                    'src' => $product->get_image()

                )  ;
if(self::$in_array['categories_pre']  ) $item['categories_pre'][] =  implode(' ' , $this -> get_cat_names( $cat_id_simple) );




  if(get_option('otfw_is_premium','no') == 'yes'){

             $keys1  =   $this -> get_the_tags($product);

             }else{
                 $keys1  = array();

             }



            $item_attr      = $formatted_attributes;

            $item           = array_merge( $item, $keys1,$item_attr);

            $filtered_flipped = self::get_config_filtered_flipped('true_name');


            $product_list44 = array_intersect_key($item, $filtered_flipped );



            $product_list4  = array_replace($filtered_flipped, $product_list44);



            foreach ($product_list4 as $key => $value) {

                if (!array_key_exists($key, $item)) {

                    $product_list4[$key] = "";

                }

            }

            $product_list5 = array(

                "nr1",

                "nr2",

                "nr3",

                "nr4",

                "nr5",

                "nr6",

                "nr7",

                "nr8",

                "nr9",

                "nr10",

                "nr11",

                "nr12",

                "nr13",

                "nr14",

                "nr15",

                "nr16"

            );

            $product_list6 = self::otfw_combine_arr($product_list5, $product_list4);

            $product_list7 = array_merge($product_list6, $item);


            $products[]    = apply_filters('otfw_loop_single_item', $product_list7, $product);



            // Now collect variation products
            foreach ($product->get_children() as $variation_id) {

                $variation = new WC_Product_Variation($variation_id);

                $atts      = $variation->get_variation_attributes();

                $hash      = $this->get_hash($product, $variation_id);

                $item      = WC()->cart->get_cart_item($hash);



                $price     = $item ? $item['data']->get_price() : $this->get_price2($variation);
                  $formatted_attributes = $this -> get_formatted_attributes($variation);

                 $batch_to_use = self::get_batch_to_use($price, $formatted_attributes);

                if ('attr' == get_option('otfw_show_attri_in_title', 'attr')) {

                    $title = apply_filters('otfw_variation_title', $variation->get_name(), $variation);
                   
                    

                } elseif ('desc' == get_option('otfw_show_attri_in_title')) {

                    $title = apply_filters('otfw_variation_title', $variation->get_title() . ' - ' . $variation->get_description(), $variation);

                } elseif ('no' == get_option('otfw_show_attri_in_title')) {

                    $title = apply_filters('otfw_variation_title', $variation->get_title(), $variation);

                }





                $unit = get_option('otfw_unit_attribute_to_use', '');

                if (isset($formatted_attributes[$unit]) && !is_null($formatted_attributes[$unit])) {

                    $unit_ = $formatted_attributes[$unit];

                } else {
                     $formatted_attributes_parent = $this -> get_formatted_attributes($product);

                     if (isset($formatted_attributes_parent[$unit]) && !is_null($formatted_attributes_parent[$unit])) {

                    $unit_ = $formatted_attributes_parent[$unit];

                }else{




                    $unit_ = '';
                }
                }


                $item = array(

                'quantity_input_pre' => $this->get_quantity_html($variation),

                'id' => $variation_id,

                  'type_pre' => $variation->get_type(),

                  'cart_item_hash_pre' => $hash,

                 'in_cart_quantity_pre' => $this->cart->get_item_quantity($hash),

                'permalink_pre' => get_the_permalink(),

                'on_sale_pre' =>  $variation->is_on_sale() ,

                'in_stock_pre' => self::otfw_in_stock($variation),

                'stock_pre' => $variation->get_stock_quantity(),

                'manage_stock_pre' => self::check_if_manage_stock($variation),

                'parent_manage_stock_pre' => self::parent_manage_stock($variation),

                'cat_id_pre' => (array) $cat_id_simple,

                'max_to_purchase_pre' => $variation->get_max_purchase_quantity(),


            );

            if(self::$in_array['unit_price_pre']  ) $item['unit_price_pre'][] =  round($batch_to_use['unit_price_pre'], 2) ;
            if(self::$in_array['package_size_pre']  ) $item['package_size_pre'][] =  $batch_to_use['batch_size_pre'] ;


if(self::$in_array['unit_price_currency_pre'] && $batch_to_use['unit_price_pre'] > 0 ) $item['unit_price_currency_pre'][] =  wc_price($batch_to_use['unit_price_pre']);
if(self::$in_array['price_currency_pre']  ) $item['price_currency_pre'][] = $variation ->get_price_html();

if(self::$in_array['weight_pre']  ) $item['weight_pre'][] =  $variation->get_weight() ;
if(self::$in_array['shipping_class_pre']  ) $item[ 'shipping_class_pre'][] = $variation->get_shipping_class();
if(self::$in_array['tax_class_pre']  ) $item['tax_class_pre'][] =  $variation->get_tax_class();
if(self::$in_array['package_price_pre']  ) $item['package_price_pre'][] =  (float) $price;
if(self::$in_array['dimensions_pre']  ) $item['dimensions_pre'][] =  self::otfw_get_product_dimensions($variation);
if(self::$in_array['currency_pre']  ) $item['currency_pre'][] =  get_woocommerce_currency_symbol();
if(self::$in_array['unit_pre'] && isset($unit_)  ) $item['unit_pre'][] =  $unit_;
if(self::$in_array['short_description_pre']  ) $item['short_description_pre'][] =  self::get_vari_description($variation, $product);
if(self::$in_array['title_pre']  ) $item['title_pre'][] =   $title;
if(self::$in_array['tax_pre']  ) $item['tax_pre'][] =  $this->get_product_tax_rate($variation) ;

if(self::$in_array['sku_pre']  ) $item['sku_pre'][] =  $variation->get_sku() ;
if(self::$in_array['product_tags_pre']  ) $item['product_tags_pre'][] = implode(" ", $this->get_all_tags($product)) ;
if(self::$in_array['image_pre']  ) $item['image_pre'][] =  array(

                    'src' => $variation->get_image()

                )  ;
if(self::$in_array['categories_pre']  ) $item['categories_pre'][] =  implode(' ' , $this -> get_cat_names( $cat_id_simple) );



             if(get_option('otfw_is_premium','no') == 'yes'){

              $keys1  =  $this -> get_the_tags($product);

             }else{
                 $keys1  = array();

             }
               $item_attr      = $formatted_attributes;

            $item           = array_merge($item_attr, $item, $keys1);

            $filtered_flipped = self::get_config_filtered_flipped('true_name');

            $product_list44 = array_intersect_key($item, $filtered_flipped );

            $product_list4  = array_replace($filtered_flipped, $product_list44);



            foreach ($product_list4 as $key => $value) {

                if (!array_key_exists($key, $item)) {

                    $product_list4[$key] = "";

                }

            }

                $product_list5 = array(

                    "nr1",

                    "nr2",

                    "nr3",

                    "nr4",

                    "nr5",

                    "nr6",

                    "nr7",

                    "nr8",

                    "nr9",

                    "nr10",

                    "nr11",

                    "nr12",

                    "nr13",

                    "nr14",

                    "nr15",

                    "nr16"

                );

                $product_list6 = self::otfw_combine_arr($product_list5, $product_list4);

                $product_list7 = array_merge($product_list6, $item);

                $products[]    = apply_filters('otfw_loop_single_item', $product_list7, $variation);

            }

            global $wp_query, $post;

            $next_page = html_entity_decode(get_next_posts_page_link($wp_query->max_num_pages));

            $next_page = $next_page ? add_query_arg('otfw-ajax', '1', $next_page) : '';

            $next_page = apply_filters('otfw_next_page', $next_page);

            $data      = compact('next_page', 'products');

            return apply_filters('otfw_loop_products', ($data));




        }




        public function get_hash($product, $variation_id = null, $cart_item_data = array()) {

            $id   = $variation_id ? $variation_id : $product->get_id();

            $atts = null;

            if ($variation_id) {

                $variation = new WC_Product_Variation($variation_id);

                $atts      = $variation->get_variation_attributes();

            }

            return apply_filters('otfw_hash',  $this->cart->get_id2($product, $variation_id, $atts, $cart_item_data), $product, $variation_id);

        }

        public function get_price2($product = null) {

            $product = $product ? $product : wc_get_product();

            if (class_exists('WC_Dynamic_Pricing')) {

                return WC_Dynamic_Pricing::instance()->on_get_price($product->get_price(), $product, true);

            }

           
            return wc_get_price_to_display( $product );

        }

        public function get_quantity_html($product) {

            if (!$product->is_in_stock()) {

                $availability      = $product->get_availability();

                $availability_html = empty($availability['availability']) ? '' : '<span class="stock ' . esc_attr($availability['class']) . '">' . esc_html($availability['availability']) . '</span>';

                $html              = apply_filters('woocommerce_stock_html', $availability_html, $availability['availability'], $product);

                return $html;

            }

            //remove_all_filters('woocommerce_quantity_input_min');
            //remove_all_filters('woocommerce_quantity_input_max');
            //remove_all_filters('woocommerce_quantity_input_args');

            $args    = array(

                'min_value' => apply_filters('woocommerce_quantity_input_min', 0, $product),

                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product),

                'input_value' => '0'

            );
          
            add_filter('woocommerce_quantity_input_args', array(
                $this,
                'quantity_input_args')
            , 99999, 2);
             
            $html    = woocommerce_quantity_input($args, $product, false);

            $html .= '<input type="hidden" name="product_id" value="' . esc_attr($product->get_id()) . '" />';

            if ($product->is_type('variation')) {

                $html .= '<input type="hidden" name="variation_id" value="' . esc_attr($product->get_id()) . '" />';

                foreach ($product->get_variation_attributes() as $attr_name => $attr_value) {

                    $html .= '<input type="hidden" name="variation_atts[' . sanitize_title($attr_name) . ']" value="' . $attr_value . '">';

                    if ('attribute_type' == $attr_name) {

                        $html .= '<input type = "hidden" name = "attribute_type" value = "' . $attr_value . '" >';

                    }

                }

            }

            $html .= '<input type="hidden" name="update_cart" value="1">';

            return apply_filters('otfw_quantity_html', $html);

        }
         public function quantity_input_args($args, $product) {
            

            $id = $product->get_id();

            if ($product->is_type('variation')) {

                $hash       = $this->get_hash($product, $product->get_id());

                $product_id = $product->get_parent_id();

            } else {

                $hash       = $this->get_hash($product, null);

                $product_id = $product->get_id();

            }

            $in_cart = $this->cart->get_item_quantity($hash);
            $args['input_value'] = $in_cart;
            
            
            return $args;
        }

       

       



     

       

        // Tax

        public function get_product_tax_rate($product_) {

            $_tax              = new WC_Tax();

            $product_tax_class = $product_->get_tax_class();

            $tax               = $_tax->get_rates($product_tax_class);

            if (isset($tax)) {

                reset($tax);

                $first_key = key($tax);

                if (array_key_exists($first_key, $tax) && !is_null($tax[$first_key])) {

                    if (!is_null($tax)) {

                        foreach ($tax as $tax_) {

                            if (isset($tax_['rate'])) {

                                $tax_rate = round($tax_['rate']) . '%';

                                break;

                            }

                        }

                        if (!isset($tax_rate)) {

                            $tax_rate = "";

                        }

                    }

                } else {

                    $tax_rate = "";

                }

            } else {

                $tax_rate = "";

            }

            return $tax_rate;

        }

        public function otfw_get_product_dimensions($product) {

            $dims             = $product->get_dimensions(false);

            $dimension_string = implode(' x ', array_filter(array_map('wc_format_localized_decimal', $dims)));

            $dim              = wc_format_dimensions($product->get_dimensions(false));

            if (!empty($dimension_string)) {

                return $dim;

            } else {

                return '';

            }

        }

        public function otfw_in_stock($product) {

            if ($product->is_in_stock()) {

                return '<i class="fas fa-check-circle fa-lg"></i>';

            } else {

                return '';

            }

        }

        public function check_if_manage_stock($variation) {

            if ($variation->managing_stock() == true || $variation->managing_stock() == 'parent') {

                return true;

            } else {

                return false;

            }

        }

        public function parent_manage_stock($variation) {

            if ($variation->managing_stock() == 'parent') {

                return true;

            } else {

                return false;

            }

        }

        function otfw_combine_arr($a, $b) {

            $acount = count($a);

            $bcount = count($b);

            $size   = ($acount > $bcount) ? $bcount : $acount;

            $a      = array_slice($a, 0, $size);

            $b      = array_slice($b, 0, $size);

            return array_combine($a, $b);

        }

        public function get_vari_description($variation,$product) {



            if(empty($variation->get_description())){



                return $product->get_short_description();

            }else {



                return $variation->get_description();

            }



        }

    }

}
