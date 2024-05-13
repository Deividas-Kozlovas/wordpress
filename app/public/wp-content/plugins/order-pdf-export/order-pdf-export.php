<?php
/*
Plugin Name: Custom Order Export
Description: Adds a button to export orders to a CSV file.
Version: 1.0
Author: Your Name
*/

// Add the button to the top of the order list
add_action('manage_posts_extra_tablenav', 'admin_order_list_top_bar_button', 20, 1);
function admin_order_list_top_bar_button($which)
{
    global $typenow;

    if ('shop_order' === $typenow && 'top' === $which) {
?>
        <div class="alignleft actions custom">
            <form method="post" id="custom_export_form"> <!-- Changed ID to differentiate -->
                <button type="submit" style="height:32px;" class="button"><?php
                                                                            echo __('Export to CSV', 'woocommerce'); ?></button>
                <input type="hidden" name="action" value="custom_export_top">
                <?php wp_nonce_field('custom_export_nonce', 'custom_export_nonce'); ?>
            </form>
        </div>
    <?php
    }
}

// Function to generate CSV
function generate_order_csv()
{
    ob_start(); // Start output buffering to capture CSV content

    // Fetch all orders
    $orders = wc_get_orders(array(
        'status' => array('wc-completed', 'wc-processing', 'wc-on-hold'),
        'limit' => -1,
    ));

    // Output CSV headers
    $csv_output = "Order ID,Order Date\n";

    foreach ($orders as $order) {
        // Add order data to CSV content
        $csv_output .= $order->get_id() . ',' . $order->get_date_created()->format('Y-m-d H:i:s') . "\n";
        // Add more order data as needed
    }

    ob_end_flush(); // Flush the buffer and return its content
    return $csv_output;
}

// Handle the export action
add_action('admin_init', 'custom_export_top_callback');
function custom_export_top_callback()
{
    // Check if this is about export action
    if (isset($_POST['action']) && $_POST['action'] === 'custom_export_top') {
        // Check nonce
        if (!isset($_POST['custom_export_nonce']) || !wp_verify_nonce($_POST['custom_export_nonce'], 'custom_export_nonce')) {
            wp_die('Security check'); // Die if nonce check fails
        }

        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_die('Permission denied'); // Die if user doesn't have permission
        }

        // Generate CSV
        $csv_content = generate_order_csv();

        // Send CSV as a file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="woocommerce_orders.csv"');
        echo $csv_content;
        exit;
    }
}

// Enqueue jQuery
add_action('admin_enqueue_scripts', 'custom_order_export_enqueue_scripts');
function custom_order_export_enqueue_scripts($hook)
{
    if ('edit.php' != $hook) {
        return;
    }
    wp_enqueue_script('jquery');
}

// Display admin notice after export
add_action('admin_notices', 'custom_export_top_admin_notice');
function custom_export_top_admin_notice()
{
    if (isset($_GET['custom_exported']) && $_GET['custom_exported'] === '1') {
    ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo __('Orders exported successfully.', 'woocommerce'); ?></p>
        </div>
<?php
    }
}
?>
<script>
    jQuery(document).ready(function($) {
        $('#custom_export_form').submit(function(event) { // Use form ID for selection
            event.preventDefault();

            // Submit form
            this.submit();
        });
    });
</script>