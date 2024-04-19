<?php
/*
Plugin Name: Custom Orders List Preview
*/

// Add custom column to orders list
// Remove billing column from orders list
add_filter('manage_woocommerce_page_wc-orders_columns', 'remove_billing_column');
function remove_billing_column($columns)
{
    unset($columns['billing_address']);
    unset($columns['billing_email']);
    return $columns;
}

// Add variation columns
add_filter('manage_woocommerce_page_wc-orders_columns', 'misha_order_variation_columns');

function misha_order_variation_columns($columns)
{
    // Add size and location columns
    $columns['order_size'] = __('Size', 'textdomain');
    $columns['order_location'] = __('Location', 'textdomain');

    return $columns;
}

// Populate variation columns
add_action('manage_woocommerce_page_wc-orders_custom_column', 'misha_populate_order_variation_columns', 25, 2);

function misha_populate_order_variation_columns($column_name, $order_id)
{
    // Check if the column is for variations
    if ($column_name === 'order_size' || $column_name === 'order_location') {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get order items
        $items = $order->get_items();

        // Loop through order items
        foreach ($items as $item_id => $item) {
            // Check if the item has variation data
            $variation_id = $item->get_variation_id();
            if ($variation_id) {
                // Get variation data
                $variation = wc_get_product($variation_id);

                // Get variation attributes
                $attributes = $variation->get_attributes();

                // Display variation data based on column name
                switch ($column_name) {
                    case 'order_size':
                        echo isset($attributes['size']) ? $attributes['size'] : '-';
                        break;
                    case 'order_location':
                        echo isset($attributes['location']) ? $attributes['location'] : '-';
                        break;
                }
            } else {
                echo '-';
            }
        }
    }
}
