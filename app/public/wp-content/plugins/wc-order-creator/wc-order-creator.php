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

function enqueue_datepicker_assets()
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-datepicker-style', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_script('date-picker', plugins_url('date-picker.js', __FILE__), array('jquery', 'jquery-ui-datepicker'), '1.1', true);
}
add_action('admin_enqueue_scripts', 'enqueue_datepicker_assets');

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
    $stock_status = [];
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
                    $stock_status[get_the_ID()][$size][$location] = $variation_obj->is_in_stock();

                    if (!array_key_exists($location, $all_locations)) {
                        $all_locations[$location] = $variation_obj->get_attribute('pa_atsiemimo-vieta');
                    }
                }
            } else {
                $stock_status[get_the_ID()][''] = $product->is_in_stock();
                foreach ($all_locations as $location_slug => $location_name) {
                    $price = $product->get_price();
                    $size_location_price[get_the_ID()][''][$location_slug] = $price;
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
        echo '<option value="' . esc_attr($location_slug) . '">' . esc_html($location_name) . '</option>';
    }
    echo '</select>';
    echo '<input type="date" id="order_date" name="order_date">';
    echo '<button type="submit" name="submit_order" class="button-primary">Užsakyti</button>';
    echo '</div>';
    echo '<div>';
    echo '<input type="text" id="searchInput" name="search" placeholder="Ieškoti produkto">';
    echo '<select id="search-category" class="search-input">';
    echo '<option value="">Produkto kategorija</option>';
    $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));
    $unique_categories = [];
    foreach ($categories as $category) {
        if (!in_array($category->name, $unique_categories)) {
            echo '<option value="' . esc_html($category->name) . '">' . esc_html($category->name) . '</option>';
            $unique_categories[] = $category->name;
        }
    }
    echo '</select>';
    echo '<button type="button" id="clearSearch" style="margin-left: 10px; font-size: 17px; padding-bottom: 5px;">X</button>';
    echo '</div>';
    echo '</div>';

    echo '<label for="order_comments" class="">Pastabos&nbsp;<span class="optional">(nebūtinas)</span></label>';
    echo '<textarea id="order_comments" name="order_comments" rows="2" cols="50" style="width: 100%;"></textarea>';

    echo '<table class="wp-list-table widefat fixed striped" id="product_table">';
    echo '<thead><tr><th>ID</th><th>Produktas</th><th>Kategorija</th><th>Dydis</th><th>Kiekis</th><th>Prideti</th></tr></thead>';
    echo '<tbody>';
    foreach ($product_data as $product) {
        $has_pa_dydziai = false;
        $sizes = wp_get_post_terms($product->get_id(), 'pa_dydziai', ['fields' => 'all']);
        if ($sizes) {
            $has_pa_dydziai = true;
        }

        // Get the product categories and filter out "Parduotuvėms"
        $categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
        $filtered_categories = array_filter($categories, function ($category) {
            return $category !== 'Parduotuvėms';
        });

        echo '<tr data-product-id="' . $product->get_id() . '" data-max-clones="' . (count($sizes) - 1) . '" data-clone-count="0">';
        echo '<td>' . $product->get_id() . '</td>';
        echo '<td>' . $product->get_name() . '</td>';
        echo '<td>' . implode(', ', $filtered_categories) . '</td>';
        echo '<td>';
        if ($sizes) {
            echo '<select class="product-size" name="size[' . $product->get_id() . '][]">';
            foreach ($sizes as $size) {
                echo '<option value="' . $size->slug . '">' . $size->name . '</option>';
            }
            echo '</select>';
        }
        echo '</td>';

        echo '<td class="stock-status">Nebeturime</td>'; // Show directly as "Nebeturime"
        // Only add "Add" button if 'pa_dydziai' attribute exists
        if ($has_pa_dydziai) {
            echo '<td><button type="button" class="add-product" data-product-id="' . $product->get_id() . '">Pridėti</button></td>';
        } else {
            echo '<td></td>'; // Empty cell if no 'pa_dydziai'
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '<br><button type="submit" name="submit_order" class="button-primary">Užsakyti</button>';
    echo '</form>';
    echo '</div>';

    wp_enqueue_script('search', plugins_url('search.js', __FILE__), array(), '1.3', true);
    wp_enqueue_script('dynamic-pricing', plugins_url('dynamic-pricing.js', __FILE__), array(), '1.3', true);
    echo '<script type="text/javascript">
    var sizeLocationPrice = ' . json_encode($size_location_price) . ';
    var stockStatus = ' . json_encode($stock_status) . ';
    </script>';
}

function find_matching_variation_id($product, $size, $location)
{
    $variations = $product->get_available_variations();
    foreach ($variations as $variation) {
        $attributes = $variation['attributes'];
        if ($attributes['attribute_pa_dydziai'] == $size && (!isset($attributes['attribute_pa_atsiemimo-vieta']) || $attributes['attribute_pa_atsiemimo-vieta'] == $location)) {
            return $variation['variation_id'];
        }
    }
    return false;
}

function process_order()
{
    $selected_location = sanitize_text_field($_POST['selected_location']);
    $order_comments = sanitize_textarea_field($_POST['order_comments']);
    $order_date = sanitize_text_field($_POST['order_date']);

    $order = wc_create_order();

    // Get current user ID
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $order_total = 0;

    // Keep track of processed products to avoid duplicates
    $processed_products = [];

    foreach ($_POST['quantity'] as $product_id => $quantities) {
        foreach ($quantities as $index => $quantity) {
            if ($quantity > 0) {
                // Create a unique key for the product based on ID, size, and location
                $size = isset($_POST['selected_attributes'][$product_id]['size'][$index]) ? sanitize_text_field($_POST['selected_attributes'][$product_id]['size'][$index]) : '';
                $location = $selected_location;
                $product_key = $product_id . '|' . $size . '|' . $location;

                // Check if this product has already been processed
                if (!isset($processed_products[$product_key])) {
                    $processed_products[$product_key] = true;

                    $product = wc_get_product($product_id);
                    $variation_id = find_matching_variation_id($product, $size, $location);

                    if ($variation_id) {
                        $variation = new WC_Product_Variation($variation_id);
                        $product_name = $product->get_name();

                        $product_data = [
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
                        ];

                        $order->add_product($variation, $quantity, $product_data);

                        $order_total += $variation->get_price() * $quantity;
                    }
                }
            }
        }
    }

    // Add user ID to order meta data
    $order->update_meta_data('_order_made_by_user_id', $user_id);
    $order->save_meta_data(); // Save meta data explicitly

    if (!empty($order_comments)) {
        $order->set_customer_note($order_comments);
    }

    if (!empty($order_date)) {
        $order->update_meta_data('_order_date', $order_date);
    }

    $order->set_total($order_total);
    $order->update_status('wc-processing', 'Order status changed to vykdomas.');
    $order->save();

    wp_redirect(admin_url('admin.php?page=add-order'));
    exit;
}
