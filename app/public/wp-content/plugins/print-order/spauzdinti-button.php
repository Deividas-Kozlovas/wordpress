<?php
/*
Plugin Name: Spauzdinti Button for WooCommerce Orders
Description: Adds a "Spauzdinti" button to the WooCommerce orders list that prints "veikia".
Version: 1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Hook to add custom button
add_action('manage_posts_extra_tablenav', 'add_spauzdinti_button', 20, 1);

function add_spauzdinti_button($which)
{
    if ('shop_order' === get_current_screen()->post_type && 'top' === $which) {
        echo '<div class="alignleft actions">';
        echo '<button type="button" id="spauzdinti-button" class="button">Spauzdinti</button>';
        echo '</div>';
    }
}

// Enqueue JavaScript for the button action
add_action('admin_enqueue_scripts', 'enqueue_spauzdinti_button_script');

function enqueue_spauzdinti_button_script($hook_suffix)
{
    if ('edit.php' !== $hook_suffix || 'shop_order' !== get_current_screen()->post_type) {
        return;
    }

    wp_enqueue_script('spauzdinti-button-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), '1.0', true);
}
