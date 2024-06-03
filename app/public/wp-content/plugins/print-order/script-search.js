jQuery(document).ready(function($) {
    // Initialize datepicker
    $('#search-date').datepicker({
        dateFormat: 'M d, yy'
    });

    // Add search functionality
    $('#search-button').on('click', function() {
        var searchDate = $('#search-date').val().toLowerCase();
        var searchCategory = $('#search-category').val().toLowerCase();
        var searchLocation = $('#search-location').val().toLowerCase();
        var searchOrigin = $('#search-origin').val().toLowerCase();

        $('table.wp-list-table tbody tr').each(function() {
            var orderDate = $(this).find('.column-custom_order_date').text().toLowerCase();
            var orderCategory = $(this).find('.column-order_category').text().toLowerCase();
            var orderLocation = $(this).find('.column-order_location').text().toLowerCase();
            var orderOrigin = $(this).find('.column-order_origin').text().toLowerCase();

            if ((searchDate === "" || orderDate.includes(searchDate)) &&
                (searchCategory === "" || orderCategory.includes(searchCategory)) &&
                (searchLocation === "" || orderLocation.includes(searchLocation)) &&
                (searchOrigin === "" || orderOrigin.includes(searchOrigin))) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
