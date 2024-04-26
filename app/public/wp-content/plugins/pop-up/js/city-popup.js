jQuery(document).ready(function($) {
    // Display the pop-up on page load
    $(window).on('load', function() {
        // Your pop-up HTML goes here
        var popupHtml = '<div class="city-popup-overlay"></div><div class="city-popup"><h2>Pasirinkite atsiemimo vieta</h2><button id="city-kaunas" class="city-button">Kaunas</button><button id="city-vilnius" class="city-button">Vilnius</button><button id="city-palanga" class="city-button">Palanga</button></div>';
        $('body').append(popupHtml);

        // Handle button clicks
        $('.city-button').on('click', function() {
            var selectedCity = $(this).text().trim(); // Get the text of the clicked button
            // Pass the selected city to the function
            removeVariationBasedOnCity(selectedCity);
            // Remove the pop-up
            $('.city-popup').remove();
            $('.city-popup-overlay').remove();
        });
    });

    // Function to remove variation based on city
    function removeVariationBasedOnCity(city) {
        // Target the row containing the selected city attribute label
        // and remove the entire row
        $('.variations_form tbody .value').each(function() {
            var label = $(this).prev('th').find('label').text().trim();
            if (label === city) {
                $(this).closest('tr').remove(); // Remove the entire row
            }
        });
    }
});
