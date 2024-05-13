<?php
/*
Plugin Name: CSV Order Table
Description: Display CSV order data in a table format.
Version: 1.0
Author: Your Name
*/

// Add a custom menu item to the admin menu
function custom_admin_menu()
{
    add_menu_page(
        'Pridėti užsakymą',
        'Pridėti užsakymą',
        'manage_options',
        'prideti-uzsakyma',
        'say_hi_page_content'
    );
}
add_action('admin_menu', 'custom_admin_menu');

// Function to display content for the "Say Hi" page
function say_hi_page_content()
{
    echo '<div class="wrap">';
    echo '<h2>Product List</h2>';

    // Get all products
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1
    );
    $products = new WP_Query($args);

    if ($products->have_posts()) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Category</th><th>Dydžiai</th><th>Pickup Location</th></tr></thead>';
        echo '<tbody>';
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            echo '<tr>';
            // ID
            echo '<td>' . get_the_ID() . '</td>';
            // Image
            echo '<td>';
            if (has_post_thumbnail()) {
                echo get_the_post_thumbnail(get_the_ID(), array(50, 50)); // Adjust image size here
            } else {
                echo 'No Image';
            }
            echo '</td>';
            // Name
            echo '<td>' . get_the_title() . '</td>';
            // Price
            $price_from = wc_price($product->get_variation_price('min'));
            $price_to = wc_price($product->get_variation_price('max'));
            echo '<td>' . $price_from . ' - ' . $price_to . '</td>';
            // Category
            $categories = get_the_terms(get_the_ID(), 'product_cat');
            $category_names = array();
            if ($categories && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $category_names[] = $category->name;
                }
                echo '<td>' . implode(', ', $category_names) . '</td>';
            } else {
                echo '<td>No Category</td>';
            }
            // Sizes (Attribute: Dydžiai)
            echo '<td>';
            $sizes = wc_get_product_terms(get_the_ID(), 'pa_dydziai', array('fields' => 'names'));
            if ($sizes) {
                echo '<select>';
                echo '<option value="">Select Size</option>'; // Add default option
                foreach ($sizes as $size) {
                    echo '<option>' . $size . '</option>';
                }
                echo '</select>';
            } else {
                echo 'No Sizes';
            }
            echo '</td>';
            // Pickup Location (Attribute: Atsiėmimo vieta)
            echo '<td>';
            $locations = wc_get_product_terms(get_the_ID(), 'pa_atsiemimo-vieta', array('fields' => 'names'));
            if ($locations) {
                echo '<select>';
                echo '<option value="">Select Location</option>'; // Add default option
                foreach ($locations as $location) {
                    echo '<option>' . $location . '</option>';
                }
                echo '</select>';
            } else {
                echo 'No Pickup Locations';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No products found.</p>';
    }

    // Add a button
    echo '<br>';
    echo '<button type="button" class="button-primary">Save</button>';

    echo '</div>';
}
