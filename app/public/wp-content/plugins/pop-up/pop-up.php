<?php
/*
Plugin Name: City Based Product
Description: Dynamically remove a product attribute based on user input.
Version: 1.0
Author: Your Name
*/

// Enqueue scripts and styles
function city_popup_enqueue_scripts()
{
    // Enqueue CSS
    wp_enqueue_style('city-popup-style', plugin_dir_url(__FILE__) . 'css/city-popup.css', array(), '1.0', 'all');

    // Enqueue JavaScript with jQuery dependency
    wp_enqueue_script('city-popup-script', plugin_dir_url(__FILE__) . 'js/city-popup.js', array('jquery'), '1.0', true);

    // Pass PHP variables to JavaScript
    wp_localize_script('city-popup-script', 'city_popup_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'), // URL to admin-ajax.php
    ));
}
add_action('wp_enqueue_scripts', 'city_popup_enqueue_scripts');

// Handle AJAX request to remove variation based on city
add_action('wp_ajax_remove_variation_based_on_city', 'remove_variation_based_on_city_ajax');
add_action('wp_ajax_nopriv_remove_variation_based_on_city', 'remove_variation_based_on_city_ajax');

function remove_variation_based_on_city_ajax()
{
    // Get the selected city
    $selected_city = $_POST['city'];

    // Log the selected city
    error_log('Selected city: ' . $selected_city);

    var_dump($selected_city);
    // Target the row containing the selected city attribute label
    // and remove the entire row
    echo "<script>
        jQuery(document).ready(function($) {
            $('.variations_form tbody .value').each(function() {
                var label = $(this).prev('th').find('label').text();
                if (label.trim() === '$selected_city') {
                    $(this).closest('tr').remove(); // Remove the entire row
                }
            });
        });
    </script>";

    // Always exit to avoid extra output
    wp_die();
}
