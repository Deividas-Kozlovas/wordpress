<?php
/*
*
* All customizer helper function here.
*
*/


//Sanitize theme style
if (!function_exists('shop_kit_cat_select_sanitize')) :
    function shop_kit_cat_select_sanitize($value)
    {
        $args = array(
            'orderby'    => 'count',
            'hide_empty' => 0,
        );
        $terms = get_terms('category', $args);

        $cat = array();
        $cat[] = 'all';
        if (!empty($terms) && !is_wp_error($terms)) :
            foreach ($terms as $term) :
                $cat[] = $term->slug;
            endforeach;
        endif;
        if (!in_array($value, $cat)) {
            $value = 'all';
        }
        return $value;
    }
endif;


if (!function_exists('shop_kit_sanitize_image')) :
    function shop_kit_sanitize_image($input)
    {
        /* default output */
        $output = '';
        /* check file type */
        $filetype = wp_check_filetype($input);
        $mime_type = $filetype['type'];
        /* only mime type "image" allowed */
        if (strpos($mime_type, 'image') !== false) {
            $output = $input;
        }
        return $output;
    }
endif;




//All sanitize function

//Sanitize sns position
if (!function_exists('shop_kit_sanitize_sns_position')) :
    function shop_kit_sanitize_sns_position($value)
    {
        if (!in_array($value, array('left_search', 'right_search'))) {
            $value = 'left_search';
        }
        return $value;
    }
endif;




if (!function_exists('shop_kit_img_logo_on')) :
    function shop_kit_img_logo_on()
    {
        if (has_custom_logo()) {
            return true;
        } else {
            return false;
        }
    }
endif;

//Sanitize sidebar options
if (!function_exists('shop_kit_sanitize_theme_font')) :
    function shop_kit_sanitize_theme_font($value)
    {
        if (!in_array($value, array('Poppins', 'Noto Serif', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Crimson Text'))) {
            $value = 'Poppins';
        }
        return $value;
    }
endif;
//Sanitize sidebar options
if (!function_exists('shop_kit_sanitize_theme_head_font')) :
    function shop_kit_sanitize_theme_head_font($value)
    {
        if (!in_array($value, array('Poppins', 'Noto Serif', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Crimson Text'))) {
            $value = 'Noto Serif';
        }
        return $value;
    }
endif;
