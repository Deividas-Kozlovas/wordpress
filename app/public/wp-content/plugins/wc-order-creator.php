<?php
/*
Plugin Name: Woocommerce Order Creator
Description: Table to add orders to woocommerce
Version: 1.2
Author: Bellatoscana
*/

// Add a custom menu item to the admin menu
function custom_admin_menu()
{
    add_menu_page(
        'Užsakyti',
        'Užsakyti',
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
        'taxonomy' => 'pa_atsiemimo-vieta',
        'hide_empty' => false,
    ]);

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
            $product_data[] = $product;
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

    echo '<form action="" method="post">';
    echo '<div style="display: flex; align-items: center; justify-content: space-between;">';
    echo '<div>';
    echo '<select id="selected_location" name="selected_location" required>';
    foreach ($all_locations as $location_slug => $location_name) {
        echo '<option value="' . $location_slug . '">' . $location_name . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '<div>';
    echo '<input type="text" id="searchInput" name="search" placeholder="Ieškoti produkto" oninput="searchTable()" style="margin-bottom: 10px;">';
    echo '</div>';
    echo '</div>';
    echo '<table class="wp-list-table widefat fixed striped" id="product_table">';
    echo '<thead><tr><th>ID</th><th>Produktas</th><th>Kategorija</th><th>Dydis</th><th>Kiekis</th><th>Prideti</th></tr></thead>';
    echo '<tbody>';
    foreach ($product_data as $product) {
        $has_pa_dydziai = false;
        $sizes = wp_get_post_terms($product->get_id(), 'pa_dydziai', ['fields' => 'all']);
        if ($sizes) {
            $has_pa_dydziai = true;
        }

        echo '<tr data-product-id="' . $product->get_id() . '">';
        echo '<td>' . $product->get_id() . '</td>';
        echo '<td>' . $product->get_name() . '</td>';
        echo '<td>' . implode(', ', wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names'])) . '</td>';
        echo '<td>';
        if ($has_pa_dydziai) {
            echo '<select class="product-size" name="size[' . $product->get_id() . '][]">';
            foreach ($sizes as $size) {
                echo '<option value="' . $size->slug . '">' . $size->name . '</option>';
            }
            echo '</select>';
        }
        echo '</td>';
        echo '<td><input type="number" name="quantity[' . $product->get_id() . '][]" min="0" value="0" style="width: 60px;"></td>';
        if ($has_pa_dydziai) {
            echo '<td><button type="button" class="add-product" data-product-id="' . $product->get_id() . '">+</button></td>';
        } else {
            echo '<td></td>'; // Empty cell for products without the `pa_dydziai` attribute
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<br><button type="submit" name="submit_order" class="button-primary">Užsakyti</button>';
    echo '</form>';
    echo '</div>';

    wp_enqueue_script('search', plugins_url('search.js', __FILE__), array(), '1.0', true);
    wp_enqueue_script('add-products', plugins_url('add-product.js', __FILE__), array(), '1.0', true);
    wp_enqueue_script('dynamic-pricing', plugins_url('dynamic-pricing.js', __FILE__), array('jquery'), '1.0', true);
    echo '<script type="text/javascript">
    var sizeLocationPrice = ' . json_encode($size_location_price) . ';
    </script>';
}

function find_matching_variation_id($product, $size, $location)
{
    $variations = $product->get_available_variations();
    foreach ($variations as $variation) {
        $attributes = $variation['attributes'];
        if ($attributes['attribute_pa_dydziai'] == $size && $attributes['attribute_pa_atsiemimo-vieta'] == $location) {
            return $variation['variation_id'];
        }
    }
    return false;
}

function process_order()
{
    $selected_location = sanitize_text_field($_POST['selected_location']);
    $order = wc_create_order();

    foreach ($_POST['quantity'] as $product_id => $quantities) {
        foreach ($quantities as $index => $quantity) {
            if ($quantity > 0) {
                $product = wc_get_product($product_id);
                $size = isset($_POST['size'][$product_id][$index]) ? sanitize_text_field($_POST['size'][$product_id][$index]) : '';
                $location = isset($_POST['location'][$product_id][$index]) ? sanitize_text_field($_POST['location'][$product_id][$index]) : $selected_location;
                $variation_id = find_matching_variation_id($product, $size, $location);

                if ($variation_id) {
                    $variation = new WC_Product_Variation($variation_id);
                    $product_name = $product->get_name();

                    $order->add_product($variation, $quantity, [
                        'variation_id' => $variation_id,
                        'variation' => [
                            'attribute_pa_dydziai' => $size,
                            'attribute_pa_atsiemimo-vieta' => $location
                        ],
                        'name' => $product_name,
                        'meta' => [
                            'Dydžiai' => $size,
                            'Atsiemimo vieta' => $location
                        ]
                    ]);
                } else {
                    $order->add_product($product, $quantity, [
                        'meta' => [
                            'Atsiemimo vieta' => $location
                        ]
                    ]);
                }
            }
        }
    }

    $order->update_status('wc-processing', 'Order status changed to vykdomas.');
    $order->save();
    wp_redirect(admin_url('admin.php?page=add-order'));
    exit;
}
