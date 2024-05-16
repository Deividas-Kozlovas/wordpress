jQuery(document).ready(function ($) {
    $( '#wwp-cart-checkout-blocks-incompatibility-notice' ).on( 'click', '.notice-dismiss', function () {
        $( this ).closest('.notice').fadeOut("fast", function () {
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    action: "wwp_hide_cart_checkout_blocks_incompatibility_notice",
                    nonce: wwp_cart_checkout_blocks_incompatibility_notice_js_params.hide_notice_nonce
                },
                dataType: "json"
            });
        });

    })

    $( '#wwp-cart-checkout-blocks-incompatibility-notice' ).on( 'click', '#wwp-cart-checkout-blocks-switch-to-classic-button', function ( e ) {
        e.preventDefault();

        $_this   = $( this );
        $notice  = $( this ).closest('.notice');
        $spinner = $( this ).find('.spinner');

        $_this.attr('disabled', 'disabled');
        $spinner.show();
        $spinner.css('visibility', 'visible');

        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                action: "wwp_switch_to_classic_cart_checkout",
                nonce: wwp_cart_checkout_blocks_incompatibility_notice_js_params.switch_to_classic_nonce
            },
            dataType: "json",
            success: function ( response ) {
                if ( response.status === 'success' ) {
                    $notice.fadeOut("fast");
                }
            },
            complete: function () {
                $_this.removeAttr('disabled');
                $spinner.hide();
                $spinner.css('visibility', 'hidden');
            }
        });
    });
});