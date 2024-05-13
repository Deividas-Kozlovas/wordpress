jQuery(document).ready(function($) {
    // Function to print all tables
    $('#print-all-button').click(function() {
        var tables = $('.csv-order-table').map(function() {
            var category = $(this).prev('h2').text(); // Get category name
            var tableWithCategory = '<h2>' + category + '</h2>' + $(this)[0].outerHTML; // Add category name to table
            return tableWithCategory;
        }).get().join(''); // Join all tables together
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><link rel="stylesheet" type="text/css" href="' + csv_order_table_script_vars.stylesheet_url + '"></head><body>' + tables + '</body></html>');
        newWin.document.close();
    });
});
