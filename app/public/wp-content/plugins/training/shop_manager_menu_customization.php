<?php
/*
Plugin Name: Shop Manager Menu Customization
Description: Customizes the admin menu for the shop_manager role to only show WooCommerce orders and custom "Užsakyti" menu items. Redirects shop managers to the orders page upon login and hides specific admin notices.
Version: 1.2
Author: Bellatoscana
*/

// Hook into the admin menu
add_action('admin_menu', 'customize_shop_manager_menu', 999);

// Customize the admin menu for shop managers
function customize_shop_manager_menu()
{
    if (!current_user_can('shop_manager')) {
        return;
    }

    global $menu, $submenu;

    // Remove all menu items
    $menu = [];
    $submenu = [];

    // Add WooCommerce menu item for orders only
    if (current_user_can('manage_woocommerce')) {
        add_menu_page(
            __('WooCommerce', 'woocommerce'),
            __('WooCommerce', 'woocommerce'),
            'manage_woocommerce',
            'woocommerce',
            '',
            'dashicons-cart',
            55
        );
        // Add WooCommerce orders submenu item
        $submenu['woocommerce'][] = array(__('Orders', 'woocommerce'), 'manage_woocommerce', 'edit.php?post_type=shop_order');
    }

    // Add custom "Užsakyti" menu item
    add_menu_page(
        'Užsakyti',
        'Užsakyti',
        'manage_woocommerce',
        'add-order',
        'custom_add_order_page',
        'dashicons-admin-generic',
        56
    );
}

// Custom callback function for "Užsakyti" menu item
function custom_add_order_page()
{
    echo '<div class="wrap">';
    echo '<h1>Užsakyti</h1>';
    echo '<p>Custom page content for creating orders.</p>';
    echo '</div>';
}

// Redirect shop managers to the orders page upon login
add_filter('login_redirect', 'redirect_shop_manager_after_login', 10, 3);

function redirect_shop_manager_after_login($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles) && in_array('shop_manager', $user->roles)) {
        return admin_url('edit.php?post_type=shop_order');
    }
    return $redirect_to;
}

// Hide specific admin notices
add_action('admin_notices', 'hide_specific_admin_notices', 100);

function hide_specific_admin_notices()
{
?>
    <style>
        .notice,
        .update-nag,
        .updated,
        .error,
        .is-dismissible {
            display: none !important;
        }
    </style>
<?php
}

// Hook to check capabilities on plugin activation
register_activation_hook(__FILE__, 'shop_manager_menu_customization_activate');

function shop_manager_menu_customization_activate()
{
    $role = get_role('shop_manager');
    if ($role) {
        $role->add_cap('manage_woocommerce');
        $role->add_cap('edit_products');
        $role->add_cap('manage_product_terms');
    }
}

// Hook to check capabilities on plugin deactivation
register_deactivation_hook(__FILE__, 'shop_manager_menu_customization_deactivate');

function shop_manager_menu_customization_deactivate()
{
    $role = get_role('shop_manager');
    if ($role) {
        $role->remove_cap('manage_woocommerce');
        $role->remove_cap('edit_products');
        $role->remove_cap('manage_product_terms');
    }
}
