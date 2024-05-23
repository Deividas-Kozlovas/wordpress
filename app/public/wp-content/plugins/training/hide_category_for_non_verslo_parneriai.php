<?php
/*
Plugin Name: WooCommerce Hide Products for Non-Logged-In Users or Verslo parneriai
Description: Hide products from non-logged-in users in WooCommerce, except those in a specific category. Administrators can view all products.
Version: 2.0
Author: Bellatoscana
*/

// Custom function to check if the current user has any of the specified roles
function user_has_roles($roles)
{
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        return !empty(array_intersect($roles, $user->roles));
    }
    return false;
}

// Redirect users trying to access verslo_partneris subcategories and products
add_action('template_redirect', 'redirect_verslo_partneris_access');

function redirect_verslo_partneris_access()
{
    if (is_tax('product_cat') || is_singular('product')) {
        $queried_object = get_queried_object();
        $parent_category = get_term_by('slug', 'verslo_partneris', 'product_cat');
        if (!$parent_category) {
            return;
        }
        $parent_category_id = $parent_category->term_id;

        if (is_tax('product_cat')) {
            // Check if the accessed category is "Verslo partneris" or its subcategory
            if ($queried_object->parent == $parent_category_id || $queried_object->term_id == $parent_category_id) {
                if (!user_has_roles(['administrator', 'verslo_partneris', 'shop_manager'])) {
                    wp_redirect(home_url()); // Redirect to homepage
                    exit;
                }
            }
        }

        if (is_singular('product')) {
            // Check if the product belongs to the "Verslo partneris" category
            $product_categories = wp_get_post_terms(get_queried_object_id(), 'product_cat', array('fields' => 'ids'));
            if (in_array($parent_category_id, $product_categories)) {
                if (!user_has_roles(['administrator', 'verslo_partneris', 'shop_manager'])) {
                    wp_redirect(home_url()); // Redirect to homepage
                    exit;
                }
            }
        }
    }
}

// Hide products in the "Verslo partneris" category from non-logged-in users or those without the appropriate roles
add_action('woocommerce_product_query', 'hide_verslo_partneriai_products');

function hide_verslo_partneriai_products($q)
{
    $category = get_term_by('slug', 'verslo_partneris', 'product_cat');
    if (!$category) {
        return;
    }
    $category_id = $category->term_id;

    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (!user_has_roles(['administrator', 'verslo_partneris', 'shop_manager'])) {
            $tax_query = $q->get('tax_query') ? $q->get('tax_query') : array();
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category_id,
                'operator' => 'NOT IN',
            );
            $q->set('tax_query', $tax_query);
        }
    } else {
        $tax_query = $q->get('tax_query') ? $q->get('tax_query') : array();
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field' => 'term_id',
            'terms' => $category_id,
            'operator' => 'NOT IN',
        );
        $q->set('tax_query', $tax_query);
    }
}

// Remove "Verslo partneriams" menu item for non-logged-in users or those without the appropriate roles
add_filter('wp_nav_menu_objects', 'remove_verslo_partneris_menu_item', 10, 2);

function remove_verslo_partneris_menu_item($items, $args)
{
    if (!user_has_roles(['administrator', 'verslo_partneris', 'shop_manager'])) {
        foreach ($items as $key => $item) {
            // Check if it's the menu item "Verslo partneriams"
            if ($item->title == 'Verslo partneriams') {
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
