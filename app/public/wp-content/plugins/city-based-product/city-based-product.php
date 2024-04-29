<?php
/*
Plugin Name: City Based Product
Description: Dynamically remove a product attribute based on user input.
Version: 1.0
Author: Your Name
*/
add_action('woocommerce_single_variation', 'remove_kaunas_variation_attribute', 10);

function remove_kaunas_variation_attribute()
{

?>
    <script>
        jQuery(document).ready(function($) {
            // Target the row containing the "kaunas" attribute label
            $('.variations_form tbody .value').each(function() {
                var label = $(this).prev('th').find('label').text();
                if (label === 'kaunas') {
                    $(this).closest('tr').remove(); // Remove the entire row
                }
            });
        });
    </script>
<?php
}
