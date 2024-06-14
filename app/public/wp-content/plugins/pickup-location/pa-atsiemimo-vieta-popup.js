jQuery(document).ready(function($) {
    console.log("loading...");
    // Define locations grouped by cities
    var locations = {
        "Kaunas": [
            'Baltų pr. 18, Šilas, Kaunas',
            'Baranausko g. 26, Šilas, Kaunas',
            'Europos pr. 122, Cechas, Kaunas',
            'Savanorių pr. 116, Šilas, Kaunas',
            'Svirbygalos g. 2, Cechas, Kaunas'
        ],
        "Vilnius": [
            'Naugarduko g. 70, Kepyklėlė, Vilnius',
            'Verkių g. 31A, Ogmios miestas, Vilnius'
        ],
        "Palanga": [
            'Vytauto g. 98, Palanga'
        ]
    };

    function displayPopup() {
        var popupContent = '<div class="custom-popup-overlay"><div class="custom-popup-content">';
        popupContent += '<p>Pristatymą į namus galite užsisakyti per mūsų partnerius BOLT, WOLT arba užsisakius produkciją atsiimti mūsų kepyklėlėse:</p>';
        popupContent += '<p><strong>Pasirinkite artimiausią kepyklėlę:</strong></p>'; 

        $.each(locations, function(city, cityLocations) {
            popupContent += '<h3>' + city + '</h3><table>';
            $.each(cityLocations, function(index, location) {
                popupContent += '<tr><td><button class="custom-popup-button">' + location + '</button></td></tr>';
            });
            popupContent += '</table>';
        });

        popupContent += '</div></div>';
        $('body').append(popupContent);
        $('body').addClass('custom-blur-background no-scroll');

        $('.custom-popup-button').on('click', function() {
            var selectedLocation = $(this).text();
            localStorage.setItem('selectedLocation', selectedLocation);
            localStorage.setItem('locationTimestamp', new Date().getTime());

            // Clear WooCommerce cart via AJAX
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'clear_cart'
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Cart cleared successfully.');
                    } else {
                        console.log('Failed to clear the cart.');
                    }
                },
                error: function() {
                    console.log('Error in AJAX request.');
                }
            });

            // Fetch and handle cart items
            fetchCartItems(function(cartItems) {
                // Handle the cart items here
                console.log('Cart Items:', cartItems); // For example, you can log them to the console
                // You can process the cart items as needed here
            });

            // Redirect to home page if on a product page
            if (window.location.pathname.match(/^\/product\/[^\/]+\/$/)) {
                window.location.href = '/';
            }

            $('.custom-popup-overlay').fadeOut(function() {
                $(this).remove();
                $('body').removeClass('custom-blur-background no-scroll');
            });
            setSelectedLocation(selectedLocation);
        });
    }

    function setSelectedLocation(location) {
        var selectElement = $('#pa_atsiemimo-vieta');
        selectElement.find('option').each(function() {
            if ($(this).text() === location) {
                $(this).prop('selected', true);
            }
        });
        selectElement.prop('disabled', true); // Lock the selection
    }

    function checkAndDisplayPopup() {
        var selectedLocation = localStorage.getItem('selectedLocation');
        var timestamp = localStorage.getItem('locationTimestamp');
        var currentTime = new Date().getTime();
        var hours24 = 24 * 60 * 60 * 1000;

        if (selectedLocation && timestamp && (currentTime - timestamp < hours24)) {
            setSelectedLocation(selectedLocation);
            $('#pa_atsiemimo-vieta').prop('disabled', t0056b3rue); // Ensure it remains locked if already set
        } else {
            localStorage.removeItem('selectedLocation');
            localStorage.removeItem('locationTimestamp');
            displayPopup();
        }
    }

    function fetchCartItems(callback) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'get_cart_items'
            },
            success: function(response) {
                if (response.success) {
                    // Pass the cart items to the callback function
                    callback(response.data);
                } else {
                    console.log('Failed to fetch cart items.');
                }
            },
            error: function() {
                console.log('Error in AJAX request.');
            }
        });
    }

    checkAndDisplayPopup();

    $('#menu-item-9432 a').on('click', function(event) {
        event.preventDefault();
        localStorage.removeItem('selectedLocation');
        localStorage.removeItem('locationTimestamp');
        $('#pa_atsiemimo-vieta').prop('disabled', false); // Allow re-selection when explicitly showing the popup again
        displayPopup();
    });
});
