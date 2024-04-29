<?php
/*
Plugin Name: WooCommerce Hide Products for Non-Logged-In Users
Description: Hide products from non-logged-in users in WooCommerce, except those in a specific category. Administrators can view all products.
Version: 2.0
Author: Bellatoscana
*/

// Redirect users trying to access privatus klientas subcategories and products
add_action('template_redirect', 'redirect_privatus_klientas_access');

function redirect_privatus_klientas_access()
{
    // Check if the user is trying to access a category archive page
    if (is_tax('product_cat')) {
        $queried_object = get_queried_object();
        $parent_category_id = get_term_by('name', 'Privatus klientas', 'product_cat')->term_id;

        // Check if the accessed category is a subcategory of "Privatus klientas"
        if ($queried_object->parent == $parent_category_id) {
            // Check if the user is not logged in or not a "privatus_klientai" or administrator
            if (!is_user_logged_in() || (!in_array('privatus_klientai', wp_get_current_user()->roles) && !current_user_can('administrator'))) {
                wp_redirect(home_url()); // Redirect to home page or any other page you prefer
                exit;
            }
        }
    }

    // Check if the user is trying to access a single product page
    if (is_singular('product')) {
        // Get the product categories
        $product_categories = wp_get_post_terms(get_queried_object_id(), 'product_cat', array('fields' => 'ids'));

        // Get the category ID of "Privatus klientas"
        $parent_category_id = get_term_by('name', 'Privatus klientas', 'product_cat')->term_id;

        // Check if the product belongs to the "Privatus klientas" category
        if (in_array($parent_category_id, $product_categories)) {
            // Check if the user is not logged in or not a "privatus_klientai" or administrator
            if (!is_user_logged_in() || (!in_array('privatus_klientai', wp_get_current_user()->roles) && !current_user_can('administrator'))) {
                wp_redirect(home_url()); // Redirect to home page or any other page you prefer
                exit;
            }
        }
    }
}

// Hook to filter WooCommerce product query
add_action('woocommerce_product_query', 'hide_products_and_categories_for_non_privatus_klientai');

function hide_products_and_categories_for_non_privatus_klientai($q)
{
    // Check if user is logged in
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        // Check if the user has the 'privatus_klientai' role or is an administrator
        if (in_array('privatus_klientai', $user->roles) || current_user_can('administrator')) {
            // If the user is 'privatus_klientai' or an administrator, remove the product category filter
            $q->set('tax_query', array());
        } else {
            // If the user is logged in but not a privatus_klientai or administrator, filter out products in the "Privatus klientas" category and its subcategories
            $category_ids = get_term_children(get_term_by('name', 'Privatus klientas', 'product_cat')->term_id, 'product_cat');
            $category_ids[] = get_term_by('name', 'Privatus klientas', 'product_cat')->term_id;
            if (!empty($category_ids)) {
                // Exclude all subcategories of "Privatus klientas"
                $q->set('tax_query', array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'term_id',
                        'terms'    => $category_ids,
                        'operator' => 'NOT IN',
                    ),
                ));
            }
        }
    } else {
        // If the user is not logged in, filter out products in the "Privatus klientas" category and its subcategories
        $category_ids = get_term_children(get_term_by('name', 'Privatus klientas', 'product_cat')->term_id, 'product_cat');
        $category_ids[] = get_term_by('name', 'Privatus klientas', 'product_cat')->term_id;
        if (!empty($category_ids)) {
            // Exclude all subcategories of "Privatus klientas"
            $q->set('tax_query', array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category_ids,
                    'operator' => 'NOT IN',
                ),
            ));
        }
    }
}





add_filter('wp_nav_menu_objects', 'remove_privatiems_klientams_menu_item', 10, 2);

function remove_privatiems_klientams_menu_item($items, $args)
{
    if (!is_user_logged_in() || (!in_array('privatus_klientai', wp_get_current_user()->roles) && !current_user_can('administrator'))) {
        foreach ($items as $key => $item) {
            // Check if it's the menu item "Privatiems klientams"
            if ($item->ID == 6767) {
                // Remove the menu item and its children
                unset($items[$key]);
                // Remove its children recursively
                remove_submenu_items($item->ID, $items);
            }
        }
    }
    return $items;
}

function remove_submenu_items($parent_id, &$items)
{
    foreach ($items as $key => $item) {
        if ($item->menu_item_parent == $parent_id) {
            unset($items[$key]);
            // Recursively remove its children
            remove_submenu_items($item->ID, $items);
        }
    }
}
