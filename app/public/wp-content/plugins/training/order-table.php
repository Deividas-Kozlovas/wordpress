<?php
/*
Plugin Name: WooCommerce Orders Tables Customization
Description: Customize WooCommerce orders tables by adding custom columns and display 50 orders per page.
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
    $columns['order_due_date'] = __('Pagaminti iki', 'textdomain'); // New column

    return $columns;
}

// Populate the "Dydžiai" (Sizes) column
add_action('manage_shop_order_posts_custom_column', 'populate_size_column', 10, 2);
function populate_size_column($column_name, $post_id)
{
    if ($column_name === 'order_size') {
        $order = wc_get_order($post_id);
        $items = $order->get_items();
        $sizes = array();

        foreach ($items as $item_id => $item) {
            $variation_id = $item->get_variation_id();
            if ($variation_id) {
                $variation = wc_get_product($variation_id);
                $attributes = $variation->get_attributes();
                if (isset($attributes['pa_dydziai'])) {
                    $sizes[] = strtoupper($attributes['pa_dydziai']);
                }
            }
        }

        $sizes = array_unique($sizes);
        echo !empty($sizes) ? implode(', ', $sizes) : '-';
    }
}

// Populate the "Atsiemimo Vieta" (Location) column
add_action('manage_shop_order_posts_custom_column', 'populate_location_column', 10, 2);
function populate_location_column($column_name, $post_id)
{
    if ($column_name === 'order_location') {
        $order = wc_get_order($post_id);
        $items = $order->get_items();
        $locations = array();

        foreach ($items as $item_id => $item) {
            $variation_id = $item->get_variation_id();
            if ($variation_id) {
                $variation = wc_get_product($variation_id);
                $attributes = $variation->get_attributes();
                if (isset($attributes['pa_atsiemimo-vieta'])) {
                    $location_slug = $attributes['pa_atsiemimo-vieta'];
                    $location_term = get_term_by('slug', $location_slug, 'pa_atsiemimo-vieta');
                    if ($location_term) {
                        $locations[] = $location_term->name;
                    }
                }
            }
        }

        $locations = array_unique($locations);
        echo !empty($locations) ? implode(', ', $locations) : '-';
    }
}

add_action('manage_shop_order_posts_custom_column', 'populate_category_column', 10, 2);
function populate_category_column($column_name, $post_id)
{
    if ($column_name === 'order_category') {
        $order = wc_get_order($post_id);
        $items = $order->get_items();
        $categories = array();

        foreach ($items as $item_id => $item) {
            $product_id = $item->get_product_id();
            $product_categories = get_the_terms($product_id, 'product_cat');
            if (!empty($product_categories)) {
                foreach ($product_categories as $category) {
                    if ($category->name !== 'Parduotuvėms') {
                        $categories[] = $category->name;
                    }
                }
            }
        }

        $categories = array_unique($categories);
        echo !empty($categories) ? implode(', ', $categories) : '-';
    }
}


// Populate the "Užsakyta" (Date) column
add_action('manage_shop_order_posts_custom_column', 'populate_date_column', 10, 2);
function populate_date_column($column_name, $post_id)
{
    if ($column_name === 'custom_order_date') {
        $order = wc_get_order($post_id);
        $order_date = $order->get_date_created();

        if ($order_date) {
            $locale = 'lt_LT';
            $dateFormatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::MEDIUM,
                IntlDateFormatter::NONE,
                'Europe/Vilnius',
                IntlDateFormatter::GREGORIAN,
                'MMM d, yyyy, HH:mm'
            );
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
    ];
}

// Populate the "Užsakovas" (Origin) column
add_action('manage_shop_order_posts_custom_column', 'populate_origin_column', 10, 2);
function populate_origin_column($column_name, $post_id)
{
    if ($column_name === 'order_origin') {
        $order = wc_get_order($post_id);
        $user_id = $order->get_user_id();

        if (!$user_id) {
            $user_id = $order->get_meta('_order_made_by_user_id');
        }

        if ($user_id) {
            $user = get_userdata($user_id);
            if ($user) {
                $user_roles = $user->roles;
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

                $user_role_names = array_map(function ($role) use ($role_names) {
                    return $role_names[$role] ?? $role;
                }, $user_roles);

                echo implode(', ', $user_role_names);
            } else {
                echo 'Pirkėjas';
            }
        } else {
            echo 'Pirkėjas';
        }
    }
}

// Populate the "Pagaminti iki" (Due Date) column
add_action('manage_shop_order_posts_custom_column', 'populate_due_date_column', 10, 2);
function populate_due_date_column($column_name, $post_id)
{
    if ($column_name === 'order_due_date') {
        $order = wc_get_order($post_id);
        $due_date = $order->get_meta('_order_date');

        if ($due_date) {
            echo date('Y-m-d', strtotime($due_date));
        } else {
            echo '-';
        }
    }
}

// Adjust the number of orders displayed per page in the WooCommerce admin
add_filter('edit_shop_order_per_page', 'bt_custom_wc_edit_orders_per_page', 20);
function bt_custom_wc_edit_orders_per_page($per_page)
{
    $per_page = 50;
    return $per_page;
}
