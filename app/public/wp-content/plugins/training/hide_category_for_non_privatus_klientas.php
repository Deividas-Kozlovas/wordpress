<?php
/*
Plugin Name: WooCommerce Hide Products for Non-Logged-In Users
Description: Hide products from non-logged-in users in WooCommerce, except those in a specific category.
Version: 1.0
Author: Your Name
*/

// Hook to filter WooCommerce product query
// add_action('woocommerce_product_query', 'hide_products_for_non_logged_in_users');

// function hide_products_for_non_logged_in_users($q)
// {
//     // Check if user is logged in
//     if (is_user_logged_in()) {
//         $user = wp_get_current_user();
//         // Check if the user has the 'customer' role
//         if (in_array('customer', $user->roles)) {
//             // If the user is a customer, remove the product category filter
//             $q->set('tax_query', array());
//         } else {
//             // If the user is logged in but not a customer, filter out products in the "Vienas" category
//             $category_id = get_term_by('name', 'Vienas', 'product_cat');
//             if ($category_id) {
//                 $q->set('tax_query', array(
//                     array(
//                         'taxonomy' => 'product_cat',
//                         'field'    => 'term_id',
//                         'terms'    => $category_id->term_id,
//                         'operator' => 'NOT IN',
//                     ),
//                 ));
//             }
//         }
//     } else {
//         // If the user is not logged in, filter out products in the "Vienas" category
//         $category_id = get_term_by('name', 'Vienas', 'product_cat');
//         if ($category_id) {
//             $q->set('tax_query', array(
//                 array(
//                     'taxonomy' => 'product_cat',
//                     'field'    => 'term_id',
//                     'terms'    => $category_id->term_id,
//                     'operator' => 'NOT IN',
//                 ),
//             ));
//         }
//     }
// }

// // Redirect non-logged-in users trying to access individual products in the specified category
// add_action('template_redirect', 'redirect_non_logged_in_users_from_products');

// function redirect_non_logged_in_users_from_products()
// {
//     // Check if the user is trying to access a single product page
//     if (is_singular('product')) {
//         $product_id = get_queried_object_id();
//         $product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

//         // Get the category ID of "Vienas"
//         $category_id = get_term_by('name', 'Vienas', 'product_cat');

//         // If the product is in the specified category and user is not logged in, or if user is a customer, redirect
//         if (!is_user_logged_in() || (in_array($category_id->term_id, $product_categories) && !current_user_can('customer'))) {
//             wp_redirect(home_url()); // Redirect to home page or any other page you prefer
//             exit;
//         }
//     }
// }
