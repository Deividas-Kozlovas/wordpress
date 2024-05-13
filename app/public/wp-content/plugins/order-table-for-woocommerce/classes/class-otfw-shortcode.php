<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class OTFW_Shortcode {

	protected static $_instance = null;

	protected $is_active = false;

	public static $shortcode_order;

	public function __construct() {

		add_shortcode( 'ordertable', array( $this, 'shortcode' ) );



	}

	public static function instance() {
		if ( !is_admin() &&  is_null( self::$_instance )) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	public static function save_shortcode_order($cats_array){

	if(!isset(self::$shortcode_order)){


	self::$shortcode_order = $cats_array;

}
}
public static function get_shortcode_order(){

	return self::$shortcode_order;
}


	public function shortcode( $atts ) {

		if ( $this->is_active ) {
			return;
		}

		$this->is_active = true;

		$options = shortcode_atts( array(
			'categories'         => null,
			'tags'               => null,
			'limit'               => null,
			'orderby'               => null,

			'show_categories'               => null,
			'show_category_description' => null,



		), $atts );



		$orderby = empty($options['orderby']) ? '' : 'orderby="'.$options['orderby'].'"'  ;
		if(wc_clean($options['orderby']) == 'sku'){

		add_filter( 'woocommerce_get_catalog_ordering_args', array($this, 'sku_sorting') );
		$options['orderby'] == '';
		}
		$tags = empty($options['tags']) ? '' : 'tag="'.$options['tags'].'"'  ;
		$cats = empty ($options['categories']) ? false : 'category="'. $options['categories'] .'"' ;
		$show_cats_desc = empty ($options['show_category_description']) ? false :  $options['show_category_description']  ;
		$show_cats = empty ($options['show_categories']) ? false :  $options['show_categories']  ;
		$limit = empty ($options['limit']) ? '' : 'limit="'. $options['limit'] .'"' ;
		$paginate = '';
		if($limit !== ''){
		$paginate = 'paginate=true' ;
		}
		$cats_array = [];
				if($cats !== false ){
		$pieces = explode(",", $options['categories']);

		foreach ($pieces as $piece ){

			$category = get_term_by( 'slug', $piece, 'product_cat' );
			if($category !== false){
			$cat_id = $category->term_id;
			$cats_array[] = $cat_id;
			}else if($category === false){
				$category = get_term_by( 'name', $piece, 'product_cat' );
				$cat_id = $category->term_id;
			$cats_array[] = $cat_id;
			}

		}
				}
				if(  !empty(OTFW::get_categories_raw())){

					$cats2 = OTFW::get_categories_raw() ;
					$cats_array_2 = array_column($cats2,'cat_ID');


			// Fix for PHP 7.0.32-33 where array_column is broken
		if(empty($cats_array_2) && is_array($cats2) ):
				$cats_array_2 = array_map(function ($each) {
					return $each->cat_ID;
					}, $cats2);
				endif;

				$cats_array = array_intersect($cats_array, $cats_array_2 );
			}
		self::save_shortcode_order($cats_array);
		$locs = array(
					  'show_cats' => $show_cats,
					   'cats' => self::get_shortcode_order(),
					   'show_category_desc' => $show_cats_desc,
					  );


		wp_localize_script( 'otfw-order', 'otfw_short', $locs );
		ob_start();



		echo do_shortcode('[products '.$cats.' '.$tags.' '.$limit.' '.$paginate.' '.$orderby.']');
		$content = ob_get_clean();




		return $content;
	}


function sku_sorting( $args) {

		$args['orderby'] = 'meta_value';
		$args['order'] = 'asc';
		$args['meta_key'] = '_sku';

	return $args;
}

}
