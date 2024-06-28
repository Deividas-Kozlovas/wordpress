
<?php
/*
Plugin Name: Print from WooCommerce Orders with search
Description: Print selected orders from WooCommerce separately or collectively and change their status to "gaminama", Orders search.
Version: 1.0
Author: Bellatoscana
*/

if (!defined('ABSPATH')) {
    exit;
}

// Hook to add custom buttons
add_action('manage_posts_extra_tablenav', 'add_spauzdinti_buttons', 20, 1);

function add_spauzdinti_buttons($which)
{
    if ('shop_order' === get_current_screen()->post_type && 'top' === $which) {
        echo '<div class="spauzdinti-buttons" style="display:inline-block;">';
        echo '<button type="button" id="together-button" class="button spauzdinti-button">Spauzdinti bendrai</button>';
        echo '</div>';
        echo '<div class="spauzdinti-buttons" style="display:inline-block;">';
        echo '<button type="button" id="separately-button" class="button spauzdinti-button">Spauzdinti individualiai</button>';
        echo '</div>';

        // Add search fields
        echo '<div class="alignleft actions search-fields-container" style="display:inline-block;">';

        // Date picker field
        echo '<input type="text" id="search-date" class="search-input" placeholder="Data">';

        // Category dropdown
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

        // Location dropdown
        echo '<select id="search-location" class="search-input">';
        echo '<option value="">Atsiemimo vieta</option>';
        $unique_locations = [];
        $products = wc_get_products(array(
            'status' => 'publish',
            'limit' => -1,
        ));
        foreach ($products as $product) {
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                $variation_obj = wc_get_product($variation['variation_id']);
                $location_slug = $variation['attributes']['attribute_pa_atsiemimo-vieta'] ?? '';
                if ($location_slug && !in_array($location_slug, $unique_locations)) {
                    $term = get_term_by('slug', $location_slug, 'pa_atsiemimo-vieta');
                    if ($term) {
                        echo '<option value="' . esc_html($term->name) . '">' . esc_html($term->name) . '</option>';
                        $unique_locations[] = $location_slug;
                    }
                }
            }
        }
        echo '</select>';

        // User Role dropdown
        echo '<select id="search-origin" class="search-input">';
        echo '<option value="">Užsakovas</option>';
        $role_names = [
            'Administratorius',
            'Autorius',
            'Pagalbininkas',
            'Pirkėjas',
            'Redaktorius',
            'Category Access',
            'Parduotuvės valdytojas',
            'Prenumeratorius',
            'Translator',
            'Verslo partneris',
            'SEO Editor',
            'SEO Manager'
        ];
        foreach ($role_names as $role_name) {
            echo '<option value="' . esc_html($role_name) . '">' . esc_html($role_name) . '</option>';
        }

        echo '</select>';

        echo '<button type="button" id="search-button" class="button search-button">Paieška</button>';
        // Add clear search button
        echo '<button type="button" id="clear-button" class="button search-button">Trinti paieška</button>';
        echo '</div>';
    }
}

// Enqueue JavaScript and CSS for the button actions
add_action('admin_enqueue_scripts', 'enqueue_spauzdinti_buttons_script');

function enqueue_spauzdinti_buttons_script($hook_suffix)
{
    $screen = get_current_screen();
    if ('edit-shop_order' === $screen->id) {
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        wp_enqueue_script('custom-script-together', plugin_dir_url(__FILE__) . 'script-together.js', array('jquery'), '2.5', true);
        wp_enqueue_script('custom-script-separately', plugin_dir_url(__FILE__) . 'script-separately.js', array('jquery'), '7.4', true);
        wp_enqueue_script('custom-script-search', plugin_dir_url(__FILE__) . 'script-search.js', array('jquery', 'jquery-ui-datepicker'), '4.3', true);

        wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0', 'all');
        wp_localize_script('custom-script-together', 'ajax_object_together', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_localize_script('custom-script-separately', 'ajax_object_separately', array('ajax_url' => admin_url('admin-ajax.php')));
    }
}
// AJAX handler to fetch order details for bendrai
add_action('wp_ajax_fetch_order_details_bendrai', 'fetch_order_details_bendrai');

function fetch_order_details_bendrai()
{
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
        wp_send_json_error('Invalid order IDs');
    }

    $order_ids = array_map('intval', $_POST['order_ids']);
    $order_details = array();

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $user_id = $order->get_user_id();

            if (!$user_id) {
                $user_id = $order->get_meta('_order_made_by_user_id');
            }

            $user_role = 'Pirkėjas'; // Default role if user is not found
            if ($user_id) {
                $user = get_userdata($user_id);
                if ($user) {
                    $user_roles = $user->roles;
                    $role_names = [
                        'administrator' => 'Administratorius',
                        'author' => 'Autorius',
                        'contributor' => 'Pagalbininkas',
                        'customer' => 'Pirkėjas',
                        'editor' => 'Redaktorius',
                        'kategorija' => 'Category Access',
                        'shop_manager' => 'Parduotuvės valdytojas',
                        'subscriber' => 'Prenumeratorius',
                        'translator' => 'Translator',
                        'verslo_partneris' => 'Verslo partneris',
                        'wpseo_editor' => 'SEO Editor',
                        'wpseo_manager' => 'SEO Manager'
                    ];
                    $user_role_names = array_map(function ($role) use ($role_names) {
                        return $role_names[$role] ?? $role;
                    }, $user_roles);
                    $user_role = implode(', ', $user_role_names);
                }
            }

            $order_date_meta = $order->get_meta('_order_date');

            $order_data = array(
                'id' => $order->get_id(),
                'date' => $order->get_date_created()->date('Y-m-d H:i:s'),
                'total' => $order->get_total(),
                'comments' => $order->get_customer_note(),
                'user_role' => $user_role,
                'order_date_meta' => $order_date_meta,
                'items' => array()
            );

            foreach ($order->get_items() as $item_id => $item) {
                $product_id = $item->get_product_id();
                $product = wc_get_product($product_id);
                $category_names = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
                $category = implode(', ', $category_names);

                // Remove ", Verslo partneris" and ", Pirkėjas" from the category
                $category = str_replace(array(', Verslo partneris', ', Pirkėjas', ', Parduotuvėms', 'Parduotuvėms,'), '', $category);

                $size = $item->get_meta('pa_dydziai', true);
                $location = $item->get_meta('pa_atsiemimo-vieta', true);
                $special_order_text = $item->get_meta(__('Papildoma informacija', 'woocommerce'), true);
                $special_order_files = $item->get_meta(__('Pridėtos nuotraukos', 'woocommerce'), true);

                $order_data['items'][] = array(
                    'name' => $item->get_name(),
                    'quantity' => $item->get_quantity(),
                    'total' => $item->get_total(),
                    'category' => $category,
                    'attributes' => array(
                        'size' => $size,
                        'location' => $location
                    ),
                    'special_order_text' => $special_order_text,
                    'special_order_files' => $special_order_files
                );
            }

            $order_details[] = $order_data;
        }
    }

    wp_send_json_success($order_details);
}


// AJAX handler to fetch order details for individual orders
add_action('wp_ajax_fetch_order_details_separately', 'fetch_order_details_separately');
add_action('wp_ajax_nopriv_fetch_order_details_separately', 'fetch_order_details_separately');

function fetch_order_details_separately()
{
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
        wp_send_json_error('Invalid order IDs');
    }

    $order_ids = array_map('intval', $_POST['order_ids']);
    $response = array('success' => false, 'data' => array());

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $user_id = $order->get_user_id();

            if (!$user_id) {
                $user_id = $order->get_meta('_order_made_by_user_id');
            }

            $user_role = 'Pirkėjas'; // Default role if user is not found
            if ($user_id) {
                $user = get_userdata($user_id);
                if ($user) {
                    $user_roles = $user->roles;
                    $role_names = [
                        'administrator' => 'Administratorius',
                        'author' => 'Autorius',
                        'contributor' => 'Pagalbininkas',
                        'customer' => 'Pirkėjas',
                        'editor' => 'Redaktorius',
                        'kategorija' => 'Category Access',
                        'shop_manager' => 'Parduotuvės valdytojas',
                        'subscriber' => 'Prenumeratorius',
                        'translator' => 'Translator',
                        'verslo_partneris' => 'Verslo partneris',
                        'wpseo_editor' => 'SEO Editor',
                        'wpseo_manager' => 'SEO Manager'
                    ];
                    $user_role_names = array_map(function ($role) use ($role_names) {
                        return $role_names[$role] ?? $role;
                    }, $user_roles);
                    $user_role = implode(', ', $user_role_names);
                }
            }

            $order_date_meta = $order->get_meta('_order_date');

            $order_data = array(
                'id' => $order->get_id(),
                'date' => $order->get_date_created()->date('Y-m-d H:i:s'),
                'customer_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'customer_email' => $order->get_billing_email(),
                'customer_phone' => $order->get_billing_phone(),
                'comments' => $order->get_customer_note(),
                'user_role' => $user_role,
                'order_date_meta' => $order_date_meta,
                'items' => array()
            );

            foreach ($order->get_items() as $item_id => $item) {
                $product_id = $item->get_product_id();
                $product = wc_get_product($product_id);
                $category_names = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'names'));
                $category = implode(', ', $category_names);

                // Clean the category name
                $category = str_replace(array(', Verslo partneris', ', Pirkėjas', ', Parduotuvėms', 'Parduotuvėms,'), '', $category);

                $size = $item->get_meta('pa_dydziai', true);
                $location = $item->get_meta('pa_atsiemimo-vieta', true);

                $special_order_text = $item->get_meta(__('Papildoma informacija', 'woocommerce'), true);
                $special_order_files = $item->get_meta(__('Pridėtos nuotraukos', 'woocommerce'), true);

                $order_item_data = array(
                    'name' => $item->get_name(),
                    'quantity' => $item->get_quantity(),
                    'category' => $category,
                    'attributes' => array(
                        'size' => $size,
                        'location' => $location
                    ),
                    'special_order_text' => $special_order_text,
                    'special_order_files' => $special_order_files
                );

                $order_data['items'][] = $order_item_data;
            }

            $response['data'][] = $order_data;
        }
    }

    $response['success'] = true;
    wp_send_json($response);
}







// AJAX handler to update order statuses
add_action('wp_ajax_update_order_statuses', 'update_order_statuses');

function update_order_statuses()
{
    if (!isset($_POST['order_ids']) || !is_array($_POST['order_ids'])) {
        wp_send_json_error('Invalid order IDs');
    }

    $order_ids = array_map('intval', $_POST['order_ids']);
    $new_status = 'wc-gaminama'; // Your custom status slug

    foreach ($order_ids as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $order->update_status($new_status, 'Order status changed to gaminama by bulk action.');
        }
    }

    wp_send_json_success('Order statuses updated successfully.');
}

// Add custom order status
function register_custom_order_status()
{
    register_post_status('wc-gaminama', array(
        'label'                     => _x('Gaminama', 'Order status', 'text_domain'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Gaminama <span class="count">(%s)</span>', 'Gaminama <span class="count">(%s)</span>', 'text_domain')
    ));
}
add_action('init', 'register_custom_order_status');

// Add custom order status to order status list
function add_custom_order_status_to_wc_order_statuses($order_statuses)
{
    $new_order_statuses = array();

    // Insert new order status after "processing"
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-gaminama'] = _x('Gaminama', 'Order status', 'text_domain');
        }
    }

    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_custom_order_status_to_wc_order_statuses');
