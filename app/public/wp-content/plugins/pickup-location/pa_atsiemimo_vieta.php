<?php
/*
Plugin Name: PA Atsiemimo Vieta Popup
Description: Displays a popup to choose pa_atsiemimo_vieta on page load and saves it in browser memory for 1 hour.
Version: 1.0
Author: Bellatoscana
*/

add_action('wp_enqueue_scripts', 'enqueue_role_selection_scripts');
function enqueue_role_selection_scripts()
{
    $plugin_url = plugin_dir_url(__FILE__);
    $version = '1.4.8'; // Change this version number whenever you update the JS or CSS files
    wp_enqueue_style('role-selection-style', $plugin_url . 'pa-atsiemimo-vieta-popup.css', array(), $version);
    wp_enqueue_script('role-selection-script', $plugin_url . 'pa-atsiemimo-vieta-popup.js', array('jquery'), $version, true);
}

add_action('wp_ajax_get_cart_items', 'get_cart_items');
add_action('wp_ajax_nopriv_get_cart_items', 'get_cart_items');

function get_cart_items()
{
    $cart_items = WC()->cart->get_cart();
    $items = array();

    foreach ($cart_items as $item_key => $item) {
        $product = wc_get_product($item['product_id']);
        $items[] = array(
            'product_id' => $item['product_id'],
            'product_name' => $product->get_name(),
            'quantity' => $item['quantity']
        );
    }

    wp_send_json_success($items);
}

add_action('wp_ajax_clear_cart', 'clear_cart');
add_action('wp_ajax_nopriv_clear_cart', 'clear_cart');

function clear_cart()
{
    WC()->cart->empty_cart();
    wp_send_json_success();
}
