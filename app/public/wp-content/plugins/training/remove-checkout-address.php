<?php
/*
Plugin Name: Remove Required Billing Fields
Description: Removes the "required" attribute from billing fields in WooCommerce checkout.
Version: 1.0
Author: Your Name
*/

// Remove the "required" attribute from billing fields
add_filter('woocommerce_billing_fields', 'remove_required_billing_fields');

function remove_required_billing_fields($fields)
{
    foreach ($fields as $key => $field) {
        $fields[$key]['required'] = false;
    }
    return $fields;
}

/* WooCommerce: The Code Below Removes Checkout Fields / 
//  * add_filter(‘woocommerce_checkout_fields’ , ‘custom_override_checkout_fields’ ); function custom_override_checkout_fields( $fields ) { /unset($fields[‘billing’][‘billing_first_name’]);/ /unset($fields[‘billing’][‘billing_last_name’]);/ unset($fields[‘billing’][‘billing_company’]); unset($fields[‘billing’][‘billing_address_1’]); unset($fields[‘billing’][‘billing_address_2’]); unset($fields[‘billing’][‘billing_city’]); unset($fields[‘billing’][‘billing_postcode’]); unset($fields[‘billing’][‘billing_country’]); unset($fields[‘billing’][‘billing_state’]); unset($fields[‘billing’][‘billing_phone’]); unset($fields[‘order’][‘order_comments’]); /unset($fields[‘billing’][‘billing_email’]);/ /unset($fields[‘account’][‘account_username’]);/ /unset($fields[‘account’][‘account_password’]);/ /unset($fields[‘account’][‘account_password-2’]);*/
    return $fields;
}

// Removes Order Notes Title – Additional Information & Notes Field
add_filter( ‘woocommerce_enable_order_notes_field’, ‘__return_false’, 9999 );

// Remove Order Notes Field
add_filter( ‘woocommerce_checkout_fields’ , ‘remove_order_notes’ );

function remove_order_notes( $fields ) {
unset($fields[‘order’][‘order_comments’]);
return $fields;
}