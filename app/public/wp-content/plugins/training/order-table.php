<?php
/*
Plugin Name: WooCommerce Orders Tables Customization
Description: Customize WooCommerce orders tables by adding custom columns.
Version: 1.0
Author: Bellatoscana
*/

// Add custom columns to WooCommerce orders list
add_filter('manage_edit-shop_order_columns', 'add_custom_order_columns', 20);
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
    $columns['order_origin'] = __('Užsakovas', 'textdomain');

    return $columns;
}

// Populate the "Dydžiai" (Sizes) column
add_action('manage_shop_order_posts_custom_column', 'populate_size_column', 10, 2);

function populate_size_column($column_name, $post_id)
{
    if ($column_name === 'order_size') {
        // Get the order object
        $order = wc_get_order($post_id);

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

                // Add size to the array and convert to uppercase
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
add_action('manage_shop_order_posts_custom_column', 'populate_location_column', 10, 2);

function populate_location_column($column_name, $post_id)
{
    if ($column_name === 'order_location') {
        // Get the order object
        $order = wc_get_order($post_id);

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

// Populate the "Kategorija" (Category) column
add_action('manage_shop_order_posts_custom_column', 'populate_category_column', 10, 2);

function populate_category_column($column_name, $post_id)
{
    if ($column_name === 'order_category') {
        // Get the order object
        $order = wc_get_order($post_id);

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

// Populate the "Užsakyta" (Date) column
add_action('manage_shop_order_posts_custom_column', 'populate_date_column', 10, 2);

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
                'MMM d, yyyy, HH:mm'
            );

            // Format the date
            echo $dateFormatter->format($order_date->getTimestamp());
        } else {
            echo '-';
        }
    }
}

// Role names mapping
function get_role_names()
{
    return [
        'administrator' => 'Administratorius',
        'editor' => 'Redaktorius',
        'author' => 'Autorius',
        'contributor' => 'Prisidedantis autorius',
        'subscriber' => 'Prenumeratorius',
        // Add other roles as needed
    ];
}

// Populate the "Klientas" (Origin) column
add_action('manage_shop_order_posts_custom_column', 'populate_origin_column', 10, 2);

// Populate the "Klientas" (Origin) column
add_action('manage_shop_order_posts_custom_column', 'populate_origin_column', 10, 2);

function populate_origin_column($column_name, $post_id)
{
    if ($column_name === 'order_origin') {
        // Get the order object
        $order = wc_get_order($post_id);

        // Get the user ID from the order
        $user_id = $order->get_user_id();

        // If user ID is not set, retrieve it from the order meta
        if (!$user_id) {
            $user_id = $order->get_meta('_order_made_by_user_id');
        }

        // Check if user ID is available
        if ($user_id) {
            // Get the user object
            $user = get_userdata($user_id);

            // Check if user exists
            if ($user) {
                // Get the user roles
                $user_roles = $user->roles;

                // Define role names mapping
                $role_names = [
                    'administrator' => 'Administratorius',
                    'author' => 'Autorius',
                    'contributor' => 'Pagalbininkas',
                    'customer' => 'Pirkėjas',
                    'editor' => 'Redaktorius',
                    'kategorija' => 'Category Access',
                    'shop_manager' => 'Parduotuvės valdytojas',
                    'subscriber' => 'Prenumeratorius',
                    'translator' => 'Translator',
                    'verslo_partneris' => 'Verslo partneris',
                    'wpseo_editor' => 'SEO Editor',
                    'wpseo_manager' => 'SEO Manager'
                ];

                // Map role slugs to role names
                $user_role_names = array_map(function ($role) use ($role_names) {
                    return $role_names[$role] ?? $role;
                }, $user_roles);

                // Display the user roles
                echo implode(', ', $user_role_names);
            } else {
                echo 'Pirkėjas';
            }
        } else {
            echo 'Pirkėjas';
        }
    }
}
