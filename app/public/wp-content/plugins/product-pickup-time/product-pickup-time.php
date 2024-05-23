<?php
/*
Plugin Name: Product Pickup Time
Description: Displays pickup times based on selected pickup location on the product page.
Version: 1.0
Author: Bellatoscana
*/

// Handle the AJAX request
add_action('wp_ajax_get_pickup_times', 'ppt_get_pickup_times');
add_action('wp_ajax_nopriv_get_pickup_times', 'ppt_get_pickup_times');
function ppt_get_pickup_times()
{
    $location = sanitize_text_field($_POST['location']);

    $pickup_times = array(
        'baltu-pr-18-silas-kaunas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'baranausko-g-26-silas-kaunas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'europos-pr-122-cechas-kaunas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'naugarduko-g-70-kepyklele-vilnius' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'savanoriu-pr-116-silas-kuanas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'svirbygalos-g-2-cechas-kaunas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'verkiu-g-31a-ogmios-miestas' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        'vytauto-g-98-palanga' => 'Pirmadienis 8:00-15:00, Antradienis 8:00-15:00',
        // Add other locations and their pickup times here
    );

    if (array_key_exists($location, $pickup_times)) {
        echo "Atsiemimo laikas:<br>" . str_replace(",", "<br>", $pickup_times[$location]);
    } else {
        echo '';
    }

    wp_die();
}

// Enqueue the script and localize the AJAX URL
add_action('wp_enqueue_scripts', 'ppt_enqueue_scripts');
function ppt_enqueue_scripts()
{
    if (is_product()) {
        wp_enqueue_script('ppt-script', plugin_dir_url(__FILE__) . 'ppt-script.js', array('jquery'), '1.1', true);
        wp_localize_script('ppt-script', 'ppt_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}
