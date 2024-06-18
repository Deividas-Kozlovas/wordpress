<?php
/*
Plugin Name: Special Order Plugin
Description: Adds a "Specialus uzsakymas" checkbox to the product page with additional fields for up to 7 images.
Version: 1.3
Author: Bellatoscana
*/

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'enqueue_special_order_scripts');
function enqueue_special_order_scripts()
{
    wp_enqueue_script('special-order-script', plugin_dir_url(__FILE__) . 'special-order-script.js', array('jquery'), '1.7', true);
    wp_enqueue_style('special-order-style', plugin_dir_url(__FILE__) . 'special-order-style.css', array(), '1.7');
}

// Add checkbox and additional fields to product page
add_action('woocommerce_before_add_to_cart_button', 'add_special_order_checkbox');
function add_special_order_checkbox()
{
?>
    <div id="special_order_container">
        <label for="special_order_checkbox">
            <input type="checkbox" id="special_order_checkbox" name="special_order_checkbox"> Specialus uzsakymas
        </label>
        <div id="special_order_fields" style="display: none;">
            <p>
                <label for="special_order_text">Papildoma informacija</label>z
                <input type="text" id="special_order_text" name="special_order_text" class="form-control">
            </p>
            <p>
                <label for="special_order_files">Pridėti nuotraukas (maks. 2MB kiekviena, iki 7)</label>
                <input type="file" id="special_order_files" name="special_order_files[]" class="form-control" accept="image/*" multiple>
            </p>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#special_order_checkbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#special_order_fields').show();
                } else {
                    $('#special_order_fields').hide();
                }
            });
        });
    </script>
<?php
}

// Save special order fields to cart item data
add_action('woocommerce_add_cart_item_data', 'save_special_order_fields', 10, 2);
function save_special_order_fields($cart_item_data, $product_id)
{
    if (isset($_POST['special_order_checkbox']) && $_POST['special_order_checkbox']) {
        $cart_item_data['special_order_checkbox'] = sanitize_text_field($_POST['special_order_checkbox']);
        if (isset($_POST['special_order_text'])) {
            $cart_item_data['special_order_text'] = sanitize_text_field($_POST['special_order_text']);
        }

        if (!empty($_FILES['special_order_files'])) {
            $uploaded_files = [];
            $file_count = count($_FILES['special_order_files']['name']);
            if ($file_count > 7) {
                $file_count = 7; // Limit to 7 files
            }
            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['special_order_files']['size'][$i] <= 2097152) { // 2MB in bytes
                    $upload = wp_upload_bits($_FILES['special_order_files']['name'][$i], null, file_get_contents($_FILES['special_order_files']['tmp_name'][$i]));
                    if (empty($upload['error'])) {
                        $uploaded_files[] = $upload['url'];
                    }
                }
            }
            if (!empty($uploaded_files)) {
                $cart_item_data['special_order_files'] = $uploaded_files;
            }
        }
    }
    return $cart_item_data;
}

// Display special order fields in cart and checkout
add_filter('woocommerce_get_item_data', 'display_special_order_fields', 10, 2);
function display_special_order_fields($item_data, $cart_item)
{
    if (isset($cart_item['special_order_checkbox']) && $cart_item['special_order_checkbox']) {
        $item_data[] = array(
            'name' => __('Specialus uzsakymas', 'woocommerce'),
            'value' => __('Taip', 'woocommerce'),
        );
        if (isset($cart_item['special_order_text'])) {
            $item_data[] = array(
                'name' => __('Papildoma informacija', 'woocommerce'),
                'value' => $cart_item['special_order_text'],
            );
        }
        if (isset($cart_item['special_order_files'])) {
            foreach ($cart_item['special_order_files'] as $file_url) {
                $item_data[] = array(
                    'name' => __('Pridėta nuotrauka', 'woocommerce'),
                    'value' => '<a href="' . esc_url($file_url) . '" target="_blank">' . basename($file_url) . '</a>',
                );
            }
        }
    }
    return $item_data;
}

// Save special order fields to order item meta data
add_action('woocommerce_checkout_create_order_line_item', 'save_special_order_fields_to_order', 10, 4);
function save_special_order_fields_to_order($item, $cart_item_key, $values, $order)
{
    if (isset($values['special_order_checkbox'])) {
        $item->add_meta_data(__('Specialus uzsakymas', 'woocommerce'), __('Taip', 'woocommerce'));
        if (isset($values['special_order_text'])) {
            $item->add_meta_data(__('Papildoma informacija', 'woocommerce'), $values['special_order_text']);
        }
        if (isset($values['special_order_files'])) {
            $item->add_meta_data('_special_order_files', $values['special_order_files']);
        }
    }
}

// Delete special order image files before order is permanently deleted
add_action('woocommerce_before_delete_order', 'delete_special_order_images', 10, 1);

function delete_special_order_images($order_id)
{
    // Get the order object
    $order = wc_get_order($order_id);
    if ($order) {
        // Iterate through each order item
        foreach ($order->get_items() as $item_id => $item) {
            // Get the file URLs from the item meta data
            $file_urls = $item->get_meta('_special_order_files', true);
            if ($file_urls) {
                foreach ((array) $file_urls as $file_url) {
                    // Convert URL to server file path
                    $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $file_url);
                    error_log('Attempting to delete file: ' . $file_path); // Debug log
                    if (file_exists($file_path)) {
                        if (unlink($file_path)) {
                            error_log('Deleted file: ' . $file_path); // Debug log
                        } else {
                            error_log('Failed to delete file: ' . $file_path); // Debug log
                        }
                    } else {
                        error_log('File not found: ' . $file_path); // Debug log
                    }
                }
            } else {
                error_log('No file URLs found for order item: ' . $item_id); // Debug log
            }
        }
    } else {
        error_log('Order not found: ' . $order_id); // Debug log
    }
}

// Hook into cart item removal to delete associated images
add_action('woocommerce_remove_cart_item', 'delete_special_order_images_on_cart_item_remove', 10, 1);

function delete_special_order_images_on_cart_item_remove($cart_item_key)
{
    // Get the cart item data
    $cart = WC()->cart->get_cart();
    if (isset($cart[$cart_item_key])) {
        $cart_item = $cart[$cart_item_key];

        // Check if the cart item has special order files
        if (isset($cart_item['special_order_files'])) {
            foreach ($cart_item['special_order_files'] as $file_url) {
                // Convert URL to server file path
                $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $file_url);
                error_log('Attempting to delete file: ' . $file_path); // Debug log
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        error_log('Deleted file: ' . $file_path); // Debug log
                    } else {
                        error_log('Failed to delete file: ' . $file_path); // Debug log
                    }
                } else {
                    error_log('File not found: ' . $file_path); // Debug log
                }
            }
        } else {
            error_log('No special order files found in cart item: ' . $cart_item_key); // Debug log
        }
    } else {
        error_log('Cart item not found: ' . $cart_item_key); // Debug log
    }
}

// Hook into cart emptied to delete associated images
add_action('woocommerce_cart_emptied', 'delete_special_order_images_on_cart_emptied');

function delete_special_order_images_on_cart_emptied()
{
    $cart = WC()->cart->get_cart();
    foreach ($cart as $cart_item_key => $cart_item) {
        if (isset($cart_item['special_order_files'])) {
            foreach ($cart_item['special_order_files'] as $file_url) {
                // Convert URL to server file path
                $file_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $file_url);
                error_log('Attempting to delete file: ' . $file_path); // Debug log
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        error_log('Deleted file: ' . $file_path); // Debug log
                    } else {
                        error_log('Failed to delete file: ' . $file_path); // Debug log
                    }
                } else {
                    error_log('File not found: ' . $file_path); // Debug log
                }
            }
        } else {
            error_log('No special order files found in cart item: ' . $cart_item_key); // Debug log
        }
    }
}
