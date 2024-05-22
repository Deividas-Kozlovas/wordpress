<?php
/*
Plugin Name: Print from WooCommerce Orders
Description: Print selected orders from WooCommerce
Version: 1.2
Author: Bellatoscana
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Hook to add custom button
add_action('manage_posts_extra_tablenav', 'add_spauzdinti_button', 20, 1);

function add_spauzdinti_button($which)
{
    if ('shop_order' === get_current_screen()->post_type && 'top' === $which) {
        echo '<div class="alignleft actions" style="display:inline-block;">';
        echo '<button type="button" id="spauzdinti-button" class="button">Spauzdinti</button>';
        echo '</div>';
    }
}

// Enqueue JavaScript and CSS for the button action
add_action('admin_enqueue_scripts', 'enqueue_spauzdinti_button_script');

function enqueue_spauzdinti_button_script($hook_suffix)
{
    $screen = get_current_screen();
    if ('edit-shop_order' === $screen->id) {
        wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '2.7', true);
        wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'style.css', array(), '1.2', 'all');
        wp_localize_script('custom-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}

// AJAX handler to fetch order details
add_action('wp_ajax_fetch_order_details', 'fetch_order_details');

function fetch_order_details()
{
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
        wp_send_json_error('Invalid order IDs');
    }

    $order_ids = array_map('intval', $_POST['order_ids']);
    $order_details = array();

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $order_data = array(
                'id' => $order->get_id(),
                'date' => $order->get_date_created()->date('Y-m-d H:i:s'),
                'total' => $order->get_total(),
                'items' => array()
            );

            foreach ($order->get_items() as $item_id => $item) {
                $product_id = $item->get_product_id();
                $product = wc_get_product($product_id);
                $category_names = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
                $category = implode(', ', $category_names);

                // Remove ", Verslo partneris" and ", Pirkėjas" from the category
                $category = str_replace(array(', Verslo partneris', ', Pirkėjas'), '', $category);

                $attributes = wc_get_product_variation_attributes($item->get_variation_id());

                $order_data['items'][] = array(
                    'name' => $item->get_name(),
                    'quantity' => $item->get_quantity(),
                    'total' => $item->get_total(),
                    'category' => $category, // Cleaned category name
                    'attributes' => array(
                        'size' => isset($attributes['attribute_pa_dydziai']) ? $attributes['attribute_pa_dydziai'] : ''
                    )
                );
            }

            $order_details[] = $order_data;
        }
    }

    // Debugging: Check if categories are correctly fetched and cleaned
    error_log(print_r($order_details, true));

    wp_send_json_success($order_details);
}
