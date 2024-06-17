<?php
/*
Plugin Name: WooCommerce Hide "Parduotuvems" Products for Non-Authorized Users
Description: Hide products and categories with the 'parduotuvems' slug from non-logged-in users and users without appropriate roles.
Version: 1.1
Author: Bellatoscana
*/

// Custom function to check if the user has any of the specified roles
function custom_user_has_roles($roles)
{
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        return !empty(array_intersect($roles, $user->roles));
    }
    return false;
}

// Redirect users trying to access parduotuvems subcategories and products
add_action('template_redirect', 'custom_redirect_parduotuvems_access');

function custom_redirect_parduotuvems_access()
{
    if (is_tax('product_cat') || is_singular('product')) {
        $queried_object = get_queried_object();
        $parent_category = get_term_by('slug', 'parduotuvems', 'product_cat');
        if (!$parent_category) {
            return;
        }
        $parent_category_id = $parent_category->term_id;

        if (is_tax('product_cat')) {
            // Check if the accessed category is "parduotuvems" or its subcategory
            if ($queried_object->parent == $parent_category_id || $queried_object->term_id == $parent_category_id) {
                if (!custom_user_has_roles(['administrator', 'shop_manager'])) {
                    wp_redirect(home_url()); // Redirect to homepage
                    exit;
                }
            }
        }

        if (is_singular('product')) {
            // Check if the product belongs to the "parduotuvems" category
            $product_categories = wp_get_post_terms(get_queried_object_id(), 'product_cat', array('fields' => 'ids'));
            if (in_array($parent_category_id, $product_categories)) {
                if (!custom_user_has_roles(['administrator', 'shop_manager'])) {
                    wp_redirect(home_url()); // Redirect to homepage
                    exit;
                }
            }
        }
    }
}

// Hide products in the "parduotuvems" category from non-authorized users
add_action('woocommerce_product_query', 'custom_hide_parduotuvems_products');

function custom_hide_parduotuvems_products($q)
{
    $category = get_term_by('slug', 'parduotuvems', 'product_cat');
    if (!$category) {
        return;
    }
    $category_id = $category->term_id;

    if (!custom_user_has_roles(['administrator', 'shop_manager'])) {
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
