<?php

/**
 * Add inline css 
 *
 * 
 */
if (!function_exists('shop_kit_wooinline_css')) :
    function shop_kit_wooinline_css()
    {

        $style = '';


        $shop_kit_resultcount = get_theme_mod('shop_kit_resultcount', 1);
        if (empty($shop_kit_resultcount)) {
            $style .= 'p.woocommerce-result-count{display:none !important;}';
        }
        $shop_kit_porder = get_theme_mod('shop_kit_porder', 1);
        if (empty($shop_kit_porder)) {
            $style .= '.woocommerce .woocommerce-ordering{display:none !important;}';
        }

        $shop_kit_title_position = get_theme_mod('shop_kit_title_position', 'center');
        if ($shop_kit_title_position != 'left') {
            $style .= '.woocommerce .page-title,.woocommerce .term-description{text-align:' . $shop_kit_title_position . ' !important;}';
        }
        $shop_kit_titlecolor = get_theme_mod('shop_kit_titlecolor');
        if ($shop_kit_titlecolor) {
            $style .= '.woocommerce .page-title{color:' . $shop_kit_titlecolor . ' !important;}';
        }
        $shop_kit_product_bgcolor = get_theme_mod('shop_kit_product_bgcolor');
        if ($shop_kit_product_bgcolor) {
            $style .= '.woocommerce ul.products li.product, .woocommerce-page ul.products li.product{background:' . $shop_kit_product_bgcolor . ' !important;}';
        }
        $shop_kit_ptitle_color = get_theme_mod('shop_kit_ptitle_color');
        if ($shop_kit_ptitle_color) {
            $style .= '.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3{color:' . $shop_kit_ptitle_color . ' !important;}';
        }
        $shop_kit_prating_color = get_theme_mod('shop_kit_prating_color');
        if ($shop_kit_prating_color) {
            $style .= '.woocommerce .star-rating span:before{color:' . $shop_kit_prating_color . ' !important;}';
        }
        $shop_kit_pprice_color = get_theme_mod('shop_kit_pprice_color');
        if ($shop_kit_pprice_color) {
            $style .= '.woocommerce ul.products li.product .price{color:' . $shop_kit_pprice_color . ' !important;}';
        }
        $shop_kit_pbtn_bgcolor = get_theme_mod('shop_kit_pbtn_bgcolor');
        $shop_kit_pbtn_color = get_theme_mod('shop_kit_pbtn_color');
        if ($shop_kit_pbtn_bgcolor || $shop_kit_pbtn_color) {
            $style .= '.woocommerce a.button, .woocommerce a.added_to_cart, .woocommerce button.button,.woocommerce span.onsale{background:' . $shop_kit_pbtn_bgcolor . ' !important;color:' . $shop_kit_pbtn_color . ' !important}';
        }
        $shop_kit_pbtn_hvbgcolor = get_theme_mod('shop_kit_pbtn_hvbgcolor');
        $shop_kit_pbtn_hvcolor = get_theme_mod('shop_kit_pbtn_hvcolor');
        if ($shop_kit_pbtn_hvbgcolor) {
            $style .= '.woocommerce a.button:hover, .woocommerce a.added_to_cart:hover, .woocommerce button.button:hover, .woocommerce input.button:hover a.added_to_cart.wc-forward{background:' . $shop_kit_pbtn_hvbgcolor . ' !important;color:' . $shop_kit_pbtn_hvcolor . ' !important}';
        }
        $shop_kit_shopb_img = get_theme_mod('shop_kit_shopb_img');

        if ($shop_kit_shopb_img) {
            $shop_kit_shopb_img_url = wp_get_attachment_image_src($shop_kit_shopb_img, 'full');
            if ($shop_kit_shopb_img_url) {
                $style .= '.shop-kit-banner.bg-overlay{background:url(' . $shop_kit_shopb_img_url[0] . ')}';
            }
        }
        $shop_kit_bannertext_color = get_theme_mod('shop_kit_bannertext_color', '#fff');
        if ($shop_kit_bannertext_color != '#fff') {
            $style .= '.shop-kit-banner .bbanner-text h1,.shop-kit-banner .bbanner-text h4,.shop-kit-banner .bbanner-text p{color:' . $shop_kit_bannertext_color . ' !important}';
        }
        $shop_kit_bannerbtn_color = get_theme_mod('shop_kit_bannerbtn_color', '#fff');
        if ($shop_kit_bannerbtn_color != '#fff') {
            $style .= 'a.btn.xskit-btn{color:' . $shop_kit_bannerbtn_color . ' !important}';
        }
        $shop_kit_bannerbtn_bgcolor = get_theme_mod('shop_kit_bannerbtn_bgcolor', '#ee434e');
        if ($shop_kit_bannerbtn_bgcolor != '#ee434e') {
            $style .= 'a.btn.xskit-btn{background:' . $shop_kit_bannerbtn_bgcolor . ' !important}';
        }
        $shop_kit_products_pagination = get_theme_mod('shop_kit_products_pagination', 'center');
        if ($shop_kit_products_pagination != 'center') {
            $style .= '.woocommerce nav.woocommerce-pagination{text-align:' . $shop_kit_products_pagination . '}';
        }
        $shop_kit_ftwidget_color = get_theme_mod('shop_kit_ftwidget_color');
        if ($shop_kit_ftwidget_color) {
            $style .= '.shop-kit-products-filter ul li, .shop-kit-products-filter ul li a{color:' . $shop_kit_ftwidget_color . '}';
        }
        $shop_kit_ftwidget_bgcolor = get_theme_mod('shop_kit_ftwidget_bgcolor');
        if ($shop_kit_ftwidget_bgcolor) {
            $style .= '.shop-kit-products-filter ul{background:' . $shop_kit_ftwidget_bgcolor . ' !important}';
        }
        $shop_kit_ftwidget_hvcolor = get_theme_mod('shop_kit_ftwidget_hvcolor');
        if ($shop_kit_ftwidget_hvcolor) {
            $style .= '.shop-kit-products-filter ul li a:hover{color:' . $shop_kit_ftwidget_hvcolor . '}';
        }
        $shop_kit_breadcrump_color = get_theme_mod('shop_kit_breadcrump_color');
        $shop_kit_breadcrump_bgcolor = get_theme_mod('shop_kit_breadcrump_bgcolor');
        if ($shop_kit_breadcrump_color) {
            $style .= '.woocommerce .woocommerce-breadcrumb, .woocommerce .woocommerce-breadcrumb a{color:' . $shop_kit_breadcrump_color . ' !important}';
        }
        if ($shop_kit_breadcrump_bgcolor) {
            $style .= '.shop-kit-wbreadcrump{background:' . $shop_kit_breadcrump_bgcolor . ' !important}';
        }
        $shop_kit_pagitext_color = get_theme_mod('shop_kit_pagitext_color');
        $shop_kit_pagibg_color = get_theme_mod('shop_kit_pagibg_color');
        if ($shop_kit_pagitext_color || $shop_kit_pagibg_color) {
            $style .= '.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current{color:' . $shop_kit_pagibg_color . ' !important;background:' . $shop_kit_pagitext_color . ' !important}';
            $style .= '.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span{background:' . $shop_kit_pagibg_color . ' !important;color:' . $shop_kit_pagitext_color . ' !important}';
        }
        /*Single page style*/
        $shop_kit_sptitle_color = get_theme_mod('shop_kit_sptitle_color');
        if ($shop_kit_sptitle_color) {
            $style .= '.single-product .product_title{color:' . $shop_kit_sptitle_color . ' !important}';
        }
        $shop_kit_ptitle_fsize = get_theme_mod('shop_kit_ptitle_fsize');
        if ($shop_kit_ptitle_fsize) {
            $style .= '.single-product .product_title{font-size:' . $shop_kit_ptitle_fsize . 'px !important}';
        }
        $shop_kit_srating_show = get_theme_mod('shop_kit_srating_show', '1');
        if (empty($shop_kit_srating_show)) {
            $style .= '.single-product .woocommerce-product-rating{display:none}';
        }
        $shop_kit_sdesc_show = get_theme_mod('shop_kit_sdesc_show', '1');
        if (empty($shop_kit_sdesc_show)) {
            $style .= '.single-product .woocommerce-product-details__short-description{display:none}';
        }
        $shop_kit_sku_show = get_theme_mod('shop_kit_sku_show', '1');
        if (empty($shop_kit_sku_show)) {
            $style .= '.single-product .sku_wrapper{display:none}';
        }
        $shop_kit_spcat_show = get_theme_mod('shop_kit_spcat_show', '1');
        if (empty($shop_kit_spcat_show)) {
            $style .= '.single-product .posted_in{display:none}';
        }
        $shop_kit_sptag_show = get_theme_mod('shop_kit_sptag_show', '1');
        if (empty($shop_kit_sptag_show)) {
            $style .= '.single-product .tagged_as{display:none}';
        }
        $shop_kit_sptab_show = get_theme_mod('shop_kit_sptab_show', '1');
        if (empty($shop_kit_sptab_show)) {
            $style .= '.single-product .woocommerce-tabs{display:none}';
        }
        $shop_kit_sprelated_show = get_theme_mod('shop_kit_sprelated_show', '1');
        if (empty($shop_kit_sprelated_show)) {
            $style .= '.single-product .related.products{display:none}';
        }





        wp_add_inline_style('shop-kit-main', $style);
    }
    add_action('wp_enqueue_scripts', 'shop_kit_wooinline_css');
endif;
