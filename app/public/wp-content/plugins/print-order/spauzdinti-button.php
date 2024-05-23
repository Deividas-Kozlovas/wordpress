<?php
/*
Plugin Name: Print from WooCommerce Orders
Description: Print selected orders from WooCommerce and change their status to "gaminama".
Version: 1.3
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
        wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '3.5', true);
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

// AJAX handler to update order statuses
add_action('wp_ajax_update_order_statuses', 'update_order_statuses');

function update_order_statuses()
{
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
        wp_send_json_error('Invalid order IDs');
    }

    $order_ids = array_map('intval', $_POST['order_ids']);
    $new_status = 'wc-gaminama'; // Your custom status slug

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $order->update_status($new_status, 'Order status changed to gaminama by bulk action.');
        }
    }

    wp_send_json_success('Order statuses updated successfully.');
}

// Add custom order status
function register_custom_order_status()
{
    register_post_status('wc-gaminama', array(
        'label'                     => _x('Gaminama', 'Order status', 'text_domain'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Gaminama <span class="count">(%s)</span>', 'Gaminama <span class="count">(%s)</span>', 'text_domain')
    ));
}
add_action('init', 'register_custom_order_status');

// Add custom order status to order status list
function add_custom_order_status_to_wc_order_statuses($order_statuses)
{
    $new_order_statuses = array();

    // Insert new order status after "processing"
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-gaminama'] = _x('Gaminama', 'Order status', 'text_domain');
        }
    }

    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_custom_order_status_to_wc_order_statuses');
