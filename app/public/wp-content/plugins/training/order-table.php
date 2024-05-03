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
    // Remove the existing date column
    unset($columns['order_date']);
    unset($columns['billing_address']);
    unset($columns['shipping_address']);
    unset($columns['order_origin']); // Corrected column key



    // Add the custom columns
    $columns['order_size'] = __('Size', 'textdomain');
    $columns['order_location'] = __('Location', 'textdomain');
    $columns['order_category'] = __('Category', 'textdomain');
    $columns['custom_order_date'] = __('Date', 'textdomain');
    $columns['order_origin'] = __('Origin', 'textdomain');

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
                if (isset($attributes['dydziai'])) {
                    $sizes[] = $attributes['dydziai'];
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

function populate_date_column($column_name, $order_id)
{
    if ($column_name === 'custom_order_date') {
        // Get the order object
        $order = wc_get_order($order_id);

        // Get the order date
        $order_date = $order->get_date_created();

        // Display the formatted date
        echo $order_date ? $order_date->format('M d, H:i') : '-';
    }
}

// Populate the "Origin" column
add_action('manage_woocommerce_page_wc-orders_custom_column', 'populate_origin_column', 10, 2);

function populate_origin_column($column_name, $order_id)
{
    if ($column_name === 'order_origin') {
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

            // Get the product
            $product = wc_get_product($product_id);

            // Get the product categories
            $product_categories = $product->get_category_ids();

            // If the product has categories, add parent categories to the categories array
            if (!empty($product_categories)) {
                foreach ($product_categories as $category_id) {
                    $category = get_term($category_id, 'product_cat');
                    if ($category && $category->parent == 0 && !in_array($category->name, $categories)) {
                        $categories[] = $category->name;
                    }
                }
            }
        }

        // Output categories separated by commas
        if (!empty($categories)) {
            echo implode(', ', $categories);
        } else {
            echo '-';
        }
    }
}
