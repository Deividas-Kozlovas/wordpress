<?php
/*
Plugin Name: WooCommerce Order Location Search
Description: Adds a search field to the WooCommerce orders list to search orders by location.
Version: 1.0
Author: Your Name
*/

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
    global $typenow, $wpdb;

    if ('shop_order' === $typenow && isset($_GET['order_location_search']) && !empty($_GET['order_location_search'])) {
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => 'order_location', // Replace with your actual meta key
                'value' => sanitize_text_field($_GET['order_location_search']),
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'shipping_address_2', // If the location is stored in shipping address 2
                'value' => sanitize_text_field($_GET['order_location_search']),
                'compare' => 'LIKE'
            )
        );

        $vars['meta_query'] = $meta_query;
    }

    return $vars;
}
