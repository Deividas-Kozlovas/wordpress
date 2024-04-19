<?php
/*
Plugin Name: Admin Bar Red
Description: Makes the WordPress admin bar red.
Version: 1.0
Author: Your Name
*/

// Enqueue admin bar red CSS
function admin_bar_red_enqueue_styles()
{
    wp_enqueue_style('admin-bar-red-style', plugins_url('admin-bar-red.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'admin_bar_red_enqueue_styles');
