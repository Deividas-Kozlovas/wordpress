jQuery(document).ready(function($) {
    // Initialize datepicker with custom date formatting
    $('#search-date').datepicker({
        dateFormat: 'M d, yy',
        closeText: 'Uždaryti',
        prevText: '&#x3C;Atgal',
        nextText: 'Pirmyn&#x3E;',
        currentText: 'Šiandien',
        monthNames: ['Sausis', 'Vasaris', 'Kovas', 'Balandis', 'Gegužė', 'Birželis', 'Liepa', 'Rugpjūtis', 'Rugsėjis', 'Spalis', 'Lapkritis', 'Gruodis'],
        monthNamesShort: ['Sau', 'Vas', 'Kov', 'Bal', 'Geg', 'Bir', 'Lie', 'Rgp', 'Rgs', 'Spa', 'Lap', 'Gru'],
        dayNames: ['Sekmadienis', 'Pirmadienis', 'Antradienis', 'Trečiadienis', 'Ketvirtadienis', 'Penktadienis', 'Šeštadienis'],
        dayNamesShort: ['Sek', 'Pir', 'Ant', 'Tre', 'Ket', 'Pen', 'Šeš'],
        dayNamesMin: ['Se', 'Pi', 'An', 'Tr', 'Ke', 'Pe', 'Še'],
        weekHeader: 'SAV',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    });

    // Add search functionality
    $('#search-button').on('click', function() {
        var searchDate = $('#search-date').val().toLowerCase();
        var searchCategory = $('#search-category').val().toLowerCase();
        var searchLocation = $('#search-location').val().toLowerCase();
        var searchRole = $('#search-origin').val().toLowerCase();

        // Normalize the search date to match the expected format in the table
        function normalizeSearchDate(dateStr) {
            var dateParts = dateStr.split(' ');
            var monthMap = {
                'sau': 'saus.',
                'vas': 'vasa.',
                'kov': 'kova.',
                'bal': 'bala.',
                'geg': 'gegu.',
                'bir': 'birž.',
                'lie': 'liep.',
                'rgp': 'rugp.',
                'rgs': 'rugs.',
                'spa': 'spal.',
                'lap': 'lapk.',
                'gru': 'gruo.'
            };
            if (dateParts.length === 3) {
                var month = monthMap[dateParts[0].toLowerCase()];
                if (month) {
                    return month + ' ' + dateParts[1].replace(',', '') + ', ' + dateParts[2];
                }
            }
            return undefined; // Return undefined if there is a failure in parsing
        }

        var normalizedSearchDate = normalizeSearchDate(searchDate);

        $('table.wp-list-table tbody tr').each(function() {
            var orderDateTime = $(this).find('.column-custom_order_date').text().toLowerCase();
            var orderDateParts = orderDateTime.split(', ');
            var orderDate = orderDateParts[0] + ', ' + orderDateParts[1].split(' ')[0]; // Extract date part and remove time

            var orderCategory = $(this).find('.order_category.column-order_category').text().toLowerCase();
            var orderLocation = $(this).find('.order_location.column-order_location').text().toLowerCase();
            var orderRole = $(this).find('.order_origin.column-order_origin').text().toLowerCase();

            // Check if the order location attribute matches the search location
            var matchesLocation = searchLocation === "" || orderLocation.includes(searchLocation);

            // Check if the order category contains the search category
            var matchesCategory = searchCategory === "" || orderCategory.includes(searchCategory);

            // Check if the order role matches the search role
            var matchesRole = searchRole === "" || orderRole.includes(searchRole);

            if ((normalizedSearchDate === undefined || orderDate === normalizedSearchDate) &&
                matchesCategory &&
                matchesLocation &&
                matchesRole) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Add clear search functionality
    $('#clear-button').on('click', function() {
        $('#search-date').val('');
        $('#search-category').val('');
        $('#search-location').val('');
        $('#search-origin').val('');

        $('table.wp-list-table tbody tr').each(function() {
            $(this).show();
        });
    });
});