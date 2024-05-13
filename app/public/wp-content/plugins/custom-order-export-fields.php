<?php
/*
Plugin Name: Add Custom Column to PDF Export
Description: Adds a custom column titled "Test" at the end of the table when exporting PDF.
Version: 1.0
Author: Your Name
*/

// Add filter to modify PDF export columns for orders
add_filter('woe_get_order_value_order_date', 'custom_order_pdf_export_add_test_column', 10, 3);

function custom_order_pdf_export_add_test_column($value, $order, $fieldname)
{
    // Check if the field is "test"
    if ($fieldname === 'test') {
        // Return the label for the custom column
        return 'Test';
    }

    // For other fields, return the default value
    return $value;
}

// Add filter to get the value for the custom column "Test" for orders
add_filter('woe_get_order_value_order_date', 'custom_order_pdf_export_get_test_value', 10, 3);

function custom_order_pdf_export_get_test_value($value, $order, $fieldname)
{
    // Check if the field is "test"
    if ($fieldname === 'test') {
        // Get the value for the "Test" column (customize this according to your logic)
        $test_value = 'Custom Test Value'; // For example, a static value

        // Return the custom test value
        return $test_value;
    }

    // For other fields, return the default value
    return $value;
}
