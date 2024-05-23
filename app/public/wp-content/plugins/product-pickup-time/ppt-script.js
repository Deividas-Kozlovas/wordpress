jQuery(document).ready(function($) {
    // Hide the table initially
    $('table.variations').hide();

    // When a location is selected
    $('select#pa_atsiemimo-vieta').on('change', function() {
        var selectedLocation = $(this).val();

        if (!selectedLocation) {
            $('#pickup-times').hide();
            $('table.variations').hide(); // Hide the table if no location is selected
            return;
        }

        $.ajax({
            url: ppt_vars.ajax_url,
            type: 'post',
            data: {
                action: 'get_pickup_times',
                location: selectedLocation
            },
            success: function(response) {
                if (response) {
                    $('#pickup-times').html(response.replace(/,/g, '<br>')).show();
                    $('table.variations').show(); // Show the table when a location is selected
                } else {
                    $('#pickup-times').hide();
                    $('table.variations').hide(); // Hide the table if the response is empty
                }
            }
        });
    });
});
