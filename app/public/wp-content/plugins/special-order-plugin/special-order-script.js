jQuery(document).ready(function($) {
    $('#special_order_fields').hide(); // Hide the fields initially

    $('#special_order_checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('#special_order_fields').slideDown();
        } else {
            $('#special_order_fields').slideUp();
        }
    });
});
