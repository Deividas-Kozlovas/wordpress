<?php
/*
Plugin Name: Print from WooCommerce Orders
Description: Print selected orders from WooCommerce separately or collectively and change their status to "gaminama".
Version: 1.0
Author: Bellatoscana
*/

if (!defined('ABSPATH')) {
    exit;
}

// Hook to add custom buttons
add_action('manage_posts_extra_tablenav', 'add_spauzdinti_buttons', 20, 1);

function add_spauzdinti_buttons($which)
{
    if ('shop_order' === get_current_screen()->post_type && 'top' === $which) {
        echo '<div class="alignleft actions" style="display:inline-block;">';
        echo '<button type="button" id="together-button" class="button">Spauzdinti bendrai</button>';
        echo '</div>';
        echo '<div class="alignleft actions" style="display:inline-block; margin-left: 10px;">';
        echo '<button type="button" id="separately-button" class="button">Spauzdinti individualiai</button>';
        echo '</div>';

        // Add search fields
        echo '<div class="alignleft actions" style="display:inline-block; margin-left: 20px;">';

        // Date picker field
        echo '<label for="search-date">Date:</label>';
        echo '<input type="text" id="search-date" placeholder="Select Date">';

        // Category dropdown
        echo '<label for="search-category">Category:</label>';
        echo '<select id="search-category">';
        echo '<option value="">Select Category</option>';
        $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
        $unique_categories = [];
        foreach ($categories as $category) {
            if (!in_array($category->name, $unique_categories)) {
                echo '<option value="' . $category->name . '">' . $category->name . '</option>';
                $unique_categories[] = $category->name;
            }
        }
        echo '</select>';

        // Location dropdown
        echo '<label for="search-location">Location:</label>';
        echo '<select id="search-location">';
        echo '<option value="">Select Location</option>';
        $locations = get_terms(array('taxonomy' => 'pozymei', 'hide_empty' => false));
        foreach ($locations as $location) {
            echo '<option value="' . $location->name . '">' . $location->name . '</option>';
        }
        echo '</select>';

        // Origin dropdown
        echo '<label for="search-origin">Origin:</label>';
        echo '<select id="search-origin">';
        echo '<option value="">Select Origin</option>';
        // Add your origin options here
        echo '<option value="Parduotuvėms">Parduotuvėms</option>';
        echo '<option value="Verslo partneris">Verslo partneris</option>';
        echo '<option value="Pirkėjas">Pirkėjas</option>';
        echo '</select>';

        echo '<button type="button" id="search-button" class="button">Search</button>';
        echo '</div>';
    }
}




// Enqueue JavaScript and CSS for the button actions
add_action('admin_enqueue_scripts', 'enqueue_spauzdinti_buttons_script');

function enqueue_spauzdinti_buttons_script($hook_suffix)
{
    $screen = get_current_screen();
    if ('edit-shop_order' === $screen->id) {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        wp_enqueue_script('custom-script-together', plugin_dir_url(__FILE__) . 'script-together.js', array('jquery'), '1.1', true);
        wp_enqueue_script('custom-script-separately', plugin_dir_url(__FILE__) . 'script-separately.js', array('jquery'), '1.6', true);
        wp_enqueue_script('custom-script-search', plugin_dir_url(__FILE__) . 'script-search.js', array('jquery', 'jquery-ui-datepicker'), '1.4', true);

        wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0', 'all');
        wp_localize_script('custom-script-together', 'ajax_object_together', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_localize_script('custom-script-separately', 'ajax_object_separately', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}
// AJAX handler to fetch order details for bendrai
add_action('wp_ajax_fetch_order_details_bendrai', 'fetch_order_details_bendrai');

function fetch_order_details_bendrai()
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

    wp_send_json_success($order_details);
}

/// AJAX handler to fetch order details for individual orders
add_action('wp_ajax_fetch_order_details_separately', 'fetch_order_details_separately');

function fetch_order_details_separately()
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
                'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'customer_email' => $order->get_billing_email(),
                'customer_phone' => $order->get_billing_phone(),
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
