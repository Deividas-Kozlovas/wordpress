<?php
/*
Plugin Name: WooCommerce Orders Tables Customization
Description: Customize WooCommerce orders tables by adding custom columns.
Version: 1.0
Author: Bellatoscana
*/

// Add custom column to orders list
add_filter('manage_woocommerce_page_wc-orders_columns', 'add_custom_order_columns');
function add_custom_order_columns($columns)
{
    // Remove existing columns if needed
    unset($columns['order_date']);
    unset($columns['billing_address']);
    unset($columns['shipping_address']);
    unset($columns['origin']);
    unset($columns['pisol_time']);
    unset($columns['pisol_method']);

    // Add custom columns
    $columns['order_size'] = __('Dydžiai', 'textdomain');
    $columns['order_location'] = __('Atsiemimo vieta', 'textdomain');
    $columns['order_category'] = __('Kategorija', 'textdomain');
    $columns['custom_order_date'] = __('Užsakyta', 'textdomain');
    $columns['order_origin'] = __('Klientas', 'textdomain');


    return $columns;
}

// Populate the "Dydžiai" (Sizes) column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_size_column', 10, 2);

function populate_size_column($column_name, $order_id)
{
    if ($column_name === 'order_size') {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get order items
        $items = $order->get_items();

        // Initialize an empty array for sizes
        $sizes = array();

        // Loop through order items
        foreach ($items as $item_id => $item) {
            // Check if the item has variation data
            $variation_id = $item->get_variation_id();
            if ($variation_id) {
                // Get variation data
                $variation = wc_get_product($variation_id);

                // Get variation attributes
                $attributes = $variation->get_attributes();

                // Add size to the array
                if (isset($attributes['pa_dydziai'])) {
                    $sizes[] = strtoupper($attributes['pa_dydziai']);
                }
            }
        }

        // Remove duplicates and format data
        $sizes = array_unique($sizes);

        // Output sizes separated by commas
        if (!empty($sizes)) {
            echo implode(', ', $sizes);
        } else {
            echo '-';
        }
    }
}

// Populate the "Atsiemimo Vieta" (Location) column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_location_column', 10, 2);

function populate_location_column($column_name, $order_id)
{
    if ($column_name === 'order_location') {
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
                if (isset($attributes['pa_atsiemimo-vieta'])) {
                    $location_slug = $attributes['pa_atsiemimo-vieta'];
                    $location_term = get_term_by('slug', $location_slug, 'pa_atsiemimo-vieta');
                    if ($location_term) {
                        $locations[] = $location_term->name;
                    }
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


// Populate the "Category" column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_category_column', 10, 2);

function populate_category_column($column_name, $order_id)
{
    if ($column_name === 'order_category') {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get order items
        $items = $order->get_items();

        // Initialize an empty array for categories
        $categories = array();

        // Loop through order items
        foreach ($items as $item_id => $item) {
            // Get product ID
            $product_id = $item->get_product_id();

            // Get product categories
            $product_categories = get_the_terms($product_id, 'product_cat');

            // Loop through product categories
            if (!empty($product_categories)) {
                foreach ($product_categories as $category) {
                    // Check if the category is a subcategory (has a parent)
                    if ($category->parent != 0) {
                        $categories[] = $category->name;
                    }
                }
            }
        }

        // Remove duplicates and format data
        $categories = array_unique($categories);

        // Output categories separated by commas
        if (!empty($categories)) {
            echo implode(', ', $categories);
        } else {
            echo '-';
        }
    }
}

// Populate the "Date" column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_date_column', 10, 2);

function populate_date_column($column_name, $post_id)
{
    if ($column_name === 'custom_order_date') {
        // Get the order object
        $order = wc_get_order($post_id);

        // Get the order date
        $order_date = $order->get_date_created();

        // Check if the date is available
        if ($order_date) {
            // Set the locale to Lithuanian
            $locale = 'lt_LT';
            $dateFormatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::NONE,
                'Europe/Vilnius', // Set the correct timezone if needed
                IntlDateFormatter::GREGORIAN,
                'MMM dd, HH:mm'
            );

            // Format the date
            echo $dateFormatter->format($order_date->getTimestamp());
        } else {
            echo '-';
        }
    }
}

// Populate the "Origin" column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_origin_column', 10, 2);

function populate_origin_column($column_name, $post_id)
{
    if ($column_name === 'order_origin') {
        // Get the order object
        $order = wc_get_order($post_id);

        // Get the user ID from the order
        $user_id = $order->get_user_id();

        // Get the user object
        $user = get_userdata($user_id);

        // Check if user exists
        if ($user) {
            // Get the user roles
            $user_roles = $user->roles;

            // Display the user roles
            echo implode(', ', $user_roles);
        } else {
            echo '-';
        }
    }
}
