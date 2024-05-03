<?php
/*
Plugin Name: WooCommerce Order Location Search
Description: Adds a search field to the WooCommerce orders list to search orders by location.
Version: 1.0
Author: 
*/

// Add custom column to WooCommerce orders list
add_filter('manage_edit-shop_order_columns', 'add_custom_order_columns');
function add_custom_order_columns($columns)
{
    // Remove existing columns if needed
    unset($columns['order_date']);
    unset($columns['billing_address']);
    unset($columns['shipping_address']);
    unset($columns['order_origin']); // Corrected column key

    // Add custom columns
    $columns['order_size'] = __('Size', 'textdomain');
    $columns['order_location'] = __('Location', 'textdomain');
    $columns['order_category'] = __('Category', 'textdomain');
    $columns['custom_order_date'] = __('Date', 'textdomain');
    $columns['order_origin'] = __('Origin', 'textdomain');

    return $columns;
}

// Populate the "Location" column
add_action('manage_shop_order_posts_custom_column', 'populate_location_column', 10, 1);
function populate_location_column($column)
{
    global $post, $woocommerce, $the_order;

    // Get the order ID
    $order_id = $post->ID;

    if ($column === 'order_location') {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get order items
        $items = $order->get_items();

        // Initialize an empty array for locations
        $locations = array();

        // Loop through order items
        foreach ($items as $item_id => $item) {
            // Check if the item has variation data
            $variation_id = $item->get_variation_id();
            if ($variation_id) {
                // Get variation data
                $variation = wc_get_product($variation_id);

                // Get variation attributes
                $attributes = $variation->get_attributes();

                // Add location to the array
                if (isset($attributes['atsiemimo-vieta'])) {
                    $locations[] = $attributes['atsiemimo-vieta'];
                }
            }
        }

        // Remove duplicates and format data
        $locations = array_unique($locations);

        // Output locations separated by commas
        if (!empty($locations)) {
            echo implode(', ', $locations);
        } else {
            echo '-';
        }
    }
}

// Add custom search field for location
add_action('restrict_manage_posts', 'add_location_search_field');
function add_location_search_field($post_type)
{
    if ('shop_order' === $post_type) {
?>
        <input type="text" name="order_location_search" id="order_location_search" placeholder="<?php esc_attr_e('Search by location', 'textdomain'); ?>" value="<?php echo isset($_GET['order_location_search']) ? esc_attr($_GET['order_location_search']) : ''; ?>" />
<?php
    }
}

// Apply search filter for location
add_filter('request', 'apply_location_search_filter');
function apply_location_search_filter($vars)
{
    global $typenow;

    if ('shop_order' === $typenow && isset($_GET['order_location_search']) && !empty($_GET['order_location_search'])) {
        $vars['meta_key'] = 'order_location';
        $vars['meta_value'] = sanitize_text_field($_GET['order_location_search']);
    }

    return $vars;
}
