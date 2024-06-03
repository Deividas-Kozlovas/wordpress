<?php
/*
Plugin Name: Print from WooCommerce Orders separately
Description: Print selected orders separately from WooCommerce and change their status to "gaminama".
Version: 1.0
Author: Bellatoscana
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('manage_posts_extra_tablenav', 'add_spauzdinti_button_separately', 20, 1);

function add_spauzdinti_button_separately($which)
{
    if ('shop_order' === get_current_screen()->post_type && 'top' === $which) {
        echo '<div class="alignleft actions" style="display:inline-block; margin-left: 10px;">';
        echo '<button type="button" id="print-button" class="button">Spauzdinti individualiai</button>';
        echo '</div>';
    }
}

add_action('admin_enqueue_scripts', 'enqueue_spauzdinti_button_script_separately');

function enqueue_spauzdinti_button_script_separately($hook_suffix)
{
    $screen = get_current_screen();
    if ('edit-shop_order' === $screen->id) {
        wp_enqueue_script('custom-script-separately', plugin_dir_url(__FILE__) . 'script-separately.js', array('jquery'), '1.1', true);
        wp_enqueue_style('custom-style-separately', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0', 'all');
        wp_localize_script('custom-script-separately', 'ajax_object_separately', array('ajax_url' => admin_url('admin-ajax.php')));
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
                'comments' => $order->get_customer_note(), // Fetch the order comments
                'items' => array()
            );

            foreach ($order->get_items() as $item_id => $item) {
                $product_id = $item->get_product_id();
                $product = wc_get_product($product_id);
                $category_names = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
                $category = implode(', ', $category_names);

                // Remove ", Verslo partneris" and ", Pirkėjas" from the category
                $category = str_replace(array(', Verslo partneris', ', Pirkėjas', ', Parduotuvėms', 'Parduotuvėms,'), '', $category);

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
