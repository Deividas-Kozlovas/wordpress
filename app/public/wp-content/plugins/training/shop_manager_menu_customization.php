<?php
/*
Plugin Name: Shop Manager Menu Customization
Description: Customizes the admin menu for the shop_manager role to only show WooCommerce-related items and custom "Produktai" and "Užsakyti" menu items.
Version: 1.0
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

    // Add WooCommerce menu items
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
        // Add WooCommerce submenu items
        $submenu['woocommerce'][] = array(__('Orders', 'woocommerce'), 'manage_woocommerce', 'edit.php?post_type=shop_order');
        $submenu['woocommerce'][] = array(__('Coupons', 'woocommerce'), 'manage_woocommerce', 'edit.php?post_type=shop_coupon');
        $submenu['woocommerce'][] = array(__('Reports', 'woocommerce'), 'view_woocommerce_reports', 'admin.php?page=wc-reports');
        $submenu['woocommerce'][] = array(__('Settings', 'woocommerce'), 'manage_woocommerce', 'admin.php?page=wc-settings');
        $submenu['woocommerce'][] = array(__('Status', 'woocommerce'), 'manage_woocommerce', 'admin.php?page=wc-status');
        $submenu['woocommerce'][] = array(__('Extensions', 'woocommerce'), 'manage_woocommerce', 'admin.php?page=wc-addons');
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

    // Add "Produktai" menu item with submenus
    add_menu_page(
        __('Produktai', 'woocommerce'),
        __('Produktai', 'woocommerce'),
        'edit_products',
        'edit.php?post_type=product',
        '',
        'dashicons-archive',
        57
    );
    $submenu['edit.php?post_type=product'][] = array(__('Visi produktai', 'woocommerce'), 'edit_products', 'edit.php?post_type=product');
    $submenu['edit.php?post_type=product'][] = array(__('Pridėti naują', 'woocommerce'), 'edit_products', 'post-new.php?post_type=product');
    $submenu['edit.php?post_type=product'][] = array(__('Kategorijos', 'woocommerce'), 'manage_product_terms', 'edit-tags.php?taxonomy=product_cat&post_type=product');
    $submenu['edit.php?post_type=product'][] = array(__('Žymos', 'woocommerce'), 'manage_product_terms', 'edit-tags.php?taxonomy=product_tag&post_type=product');
    $submenu['edit.php?post_type=product'][] = array(__('Požymiai', 'woocommerce'), 'edit_products', 'edit.php?post_type=product&page=product_attributes');
    $submenu['edit.php?post_type=product'][] = array(__('Atsiliepimai', 'woocommerce'), 'edit_products', 'edit.php?post_type=product&page=product-reviews');
}

// Custom callback function for "Užsakyti" menu item
function custom_add_order_page()
{
    echo '<div class="wrap">';
    echo '<h1>Užsakyti</h1>';
    echo '<p>Custom page content for creating orders.</p>';
    echo '</div>';
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
