<?php
/*
Plugin Name: CSV Order Table
Description: Display CSV order data in a table format with selection and quantity options, and export to CSV.
Version: 1.2
Author: Your Name
*/

// Add a custom menu item to the admin menu
function custom_admin_menu()
{
    add_menu_page(
        'Add Order',
        'Add Order',
        'manage_options',
        'add-order',
        'display_order_page_content'
    );
}
add_action('admin_menu', 'custom_admin_menu');


function display_order_page_content()
{
    echo '<div class="wrap">';
    echo '<h2>Product List</h2>';

    if (isset($_POST['submit_order'])) {
        process_order();
    }

    $location_terms = get_terms([
        'taxonomy'   => 'pa_atsiemimo-vieta',
        'hide_empty' => false,
    ]);
    // Initialize variables to store unique pickup locations and size-location-price mapping
    $all_locations = [];
    foreach ($location_terms as $term) {
        $all_locations[$term->slug] = $term->name;
    }
    $size_location_price = [];
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1
    );
    $products = new WP_Query($args);

    $product_data = [];
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            $product = wc_get_product(get_the_ID());
            $product_data[] = $product; // Store product objects for later use
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                foreach ($variations as $variation) {
                    $variation_obj = wc_get_product($variation['variation_id']);
                    $size = $variation['attributes']['attribute_pa_dydziai'] ?? '';
                    $location = $variation['attributes']['attribute_pa_atsiemimo-vieta'] ?? '';
                    $price = $variation_obj->get_price();
                    $size_location_price[get_the_ID()][$size][$location] = $price;

                    if (!in_array($location, $all_locations, true)) {
                        $all_locations[] = $location;
                    }
                }
            }
        }
    }
    wp_reset_postdata();

    // Form for product and attributes selection
    echo '<form action="" method="post">';
    echo '<div>';
    echo '<input type="text" name="customer_name" placeholder="Name" required>';
    echo '<input type="email" name="customer_email" placeholder="Email" required>';
    echo '<input type="text" name="customer_phone" placeholder="Phone Number" required>';
    echo '<select id="selected_location" required>';
    foreach ($all_locations as $location_slug => $location_name) {
        echo '<option value="' . $location_slug . '">' . $location_name . '</option>';
    }
    echo '</select>';
    echo '</div>';

    // Displaying products and their attributes in a table
    echo '<table class="wp-list-table widefat fixed striped" id="product_table">';
    echo '<thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Price</th><th>Sum</th><th>Category</th><th>Sizes</th><th>Quantity</th></tr></thead>';
    echo '<tbody>';
    foreach ($product_data as $product) {
        echo '<tr data-product-id="' . $product->get_id() . '">';
        echo '<td>' . $product->get_id() . '</td>';
        echo '<td>' . (has_post_thumbnail($product->get_id()) ? get_the_post_thumbnail($product->get_id(), array(50, 50)) : 'No Image') . '</td>';
        echo '<td>' . $product->get_name() . '</td>';
        echo '<td class="product-base-price">Select options</td>'; // Base price cell
        echo '<td class="product-total-price">-</td>'; // Total price cell, initialized to '-' or '0'
        echo '<td>' . implode(', ', wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names'])) . '</td>';
        echo '<td>';
        // Similar approach for sizes within the product loop
        if ($sizes = wp_get_post_terms($product->get_id(), 'pa_dydziai', ['fields' => 'all'])) {
            echo '<select class="product-size" name="size[' . $product->get_id() . ']">';
            foreach ($sizes as $size) {
                echo '<option value="' . $size->slug . '">' . $size->name . '</option>';
            }
            echo '</select>';
        }
        echo '</td>';
        echo '<td><input type="number" name="quantity[' . $product->get_id() . ']" min="0" value="0" style="width: 60px;"></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '<tfoot>';
    echo '<tr>';
    echo '<td colspan="6" style="text-align: right;">Total:</td>'; // Adjust colspan as needed to align correctly
    echo '<td id="total-quantity">0</td>'; // Cell for total quantity
    echo '<td id="total-price">0 €</td>'; // Cell for total price
    echo '</tr>';
    echo '</tfoot>';
    echo '</table>';
    echo '<br><button type="submit" name="submit_order" class="button-primary">Submit Order</button>';
    echo '</form>';
    echo '</div>';

    // At the end of your PHP script where you prepare the page content
    echo '<script type="text/javascript">
    var sizeLocationPrice = ' . json_encode($size_location_price) . ';
    </script>';

    echo '<script src="' . esc_url(plugins_url('dynamic-pricing.js', __FILE__)) . '"></script>';
}











// function process_order()
// {
//     if (isset($_POST['submit_order'])) {
//         $order_id = uniqid('order_');
//         $current_time = current_time('mysql');
//         $total_price = 0;
//         $line_items = [];
//         $customer_details = collect_customer_details(); // Collecting customer details from the form

//         foreach ($_POST['quantity'] as $product_id => $quantity) {
//             $quantity = intval($quantity);
//             if ($quantity > 0) {
//                 $product = wc_get_product($product_id);
//                 if (!$product) continue;

//                 $selected_size = $_POST['size'][$product_id] ?? 'Default Size'; // Make sure this is being submitted in the form
//                 $selected_location = $_POST['selected_location']; // Make sure this is being submitted in the form
//                 $product_price = $product->get_price() * $quantity;
//                 $total_price += $product_price;

//                 // Prepare line item details
//                 $line_items[] = [
//                     "name" => $product->get_name(),
//                     "size" => $selected_size,
//                     "location" => $selected_location,
//                     "quantity" => $quantity,
//                     "total_price" => wc_price($product_price),
//                     "subtotal" => wc_price($product_price * $quantity)
//                 ];
//             }
//         }

//         // Prepare CSV data
//         $csv_data = [
//             'order_id' => $order_id,
//             'order_date' => $current_time,
//             'total_price' => wc_price($total_price),
//             ...$customer_details,
//             'products' => $line_items
//         ];

//         // Generate CSV file
//         generate_csv($csv_data);
//     } else {
//         echo '<p>Error: Required fields are missing or incorrect.</p>';
//     }
// }

// function collect_customer_details()
// {
//     return [
//         'customer_name' => sanitize_text_field($_POST['customer_name']),
//         'customer_email' => sanitize_email($_POST['customer_email']),
//         'customer_phone' => sanitize_text_field($_POST['customer_phone'])
//         // Add other fields as necessary
//     ];
// }

// function generate_csv($data)
// {
//     $filename = 'order_details_' . date('Y-m-d_H-i-s') . '.csv';
//     $file_path = WP_CONTENT_DIR . '/uploads/' . $filename;
//     $file = fopen($file_path, 'w');

//     // Headers
//     fputcsv($file, array_keys($data));
//     // Data including line items
//     fputcsv($file, array_map(function ($item) {
//         return is_array($item) ? json_encode($item) : $item;
//     }, $data));

//     fclose($file);
//     echo '<p>Order processed and saved to <a href="' . content_url('/uploads/') . $filename . '">Download CSV</a>.</p>';
// }
