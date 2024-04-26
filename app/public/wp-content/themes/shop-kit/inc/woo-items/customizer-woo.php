<?php

/**
 * Shop Kit  Theme Customizer
 *
 * @package Shop Kit 
 */

if (!function_exists('shop_kit_sanitize_image')) :
    function shop_kit_sanitize_image($input)
    {
        /* default output */
        $output = '';
        /* check file type */
        $filetype = wp_check_filetype($input);
        $mime_type = $filetype['type'];
        /* only mime type "image" allowed */
        if (strpos($mime_type, 'image') !== false) {
            $output = $input;
        }
        return $output;
    }
endif;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shop_kit_woo_customize_register($wp_customize)
{

    //select sanitization function
    function shop_kit_woo_sanitize_select($input, $setting)
    {
        $input = sanitize_key($input);
        $choices = $setting->manager->get_control($setting->id)->choices;
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
    $theme_info = wp_get_theme();
    $theme_domain = $theme_info->get('TextDomain');
    if ($theme_domain == 'shop-kit') {
        $shop_items = '4';
    } else {
        $shop_items = '3';
    }

    $wp_customize->add_section(
        'shop_kit_general',
        array(
            'title'    => __('Shop Kit  General Settings', 'shop-kit'),
            'priority' => 5,
            'panel'    => 'woocommerce',
        )
    );
    $wp_customize->add_setting('shop_kit_head_cart_show', array(
        'default'        => 'all',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_head_cart_show', array(
        'label'      => __('Display Header Shopping Cart Icon', 'shop-kit'),
        'description'     => __('You can show or hide shop cart icon.', 'shop-kit'),
        'section'    => 'shop_kit_general',
        'settings'   => 'shop_kit_head_cart_show',
        'type'       => 'checkbox',
    ));

    $wp_customize->add_setting('shop_kit_breadcrump_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  1,
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_breadcrump_show', array(
        'label'      => __('Display Shop Breadcrumb', 'shop-kit'),
        'description'     => __('You can show or hide shop breadcrumb.', 'shop-kit'),
        'section'    => 'shop_kit_general',
        'settings'   => 'shop_kit_breadcrump_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_breadcrump_position', array(
        'default'        => 'left',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_breadcrump_position', array(
        'label'      => __('Products Breadcrumb Position', 'shop-kit'),
        'section'    => 'shop_kit_general',
        'settings'   => 'shop_kit_breadcrump_position',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Left', 'shop-kit'),
            'center' => __('Center', 'shop-kit'),
            'right' => __('Right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_breadcrump_color', array(
        'default' => '#222',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_breadcrump_color',
            array(
                'label' => __('Breadcrump Text Color', 'shop-kit'),
                'section' => 'shop_kit_general'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_breadcrump_bgcolor', array(
        'default' => '#ededed',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_breadcrump_bgcolor',
            array(
                'label' => __('Breadcrump Background Color', 'shop-kit'),
                'section' => 'shop_kit_general'
            )
        )
    );


    $wp_customize->add_setting('shop_kit_products_pagination', array(
        'default'        => 'center',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_products_pagination', array(
        'label'      => __('Products Pagination Position', 'shop-kit'),
        'section'    => 'shop_kit_general',
        'settings'   => 'shop_kit_products_pagination',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Left', 'shop-kit'),
            'center' => __('Center', 'shop-kit'),
            'right' => __('Right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_pagitext_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pagitext_color',
            array(
                'label' => __('Pagination Text Color', 'shop-kit'),
                'section' => 'shop_kit_general'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pagibg_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pagibg_color',
            array(
                'label' => __('Pagination Background Color', 'shop-kit'),
                'section' => 'shop_kit_general'
            )
        )
    );
    $wp_customize->add_section(
        'shop_kit_shop_banner',
        array(
            'title'    => __('Shop Page Banner', 'shop-kit'),
            'priority' => 6,
            'panel'    => 'woocommerce',
        )
    );
    $wp_customize->add_setting('shop_kit_shopbanner_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       => '',
        'sanitize_callback' => 'absint',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_shopbanner_show', array(
        'label'      => __('Display Shop Page banner', 'shop-kit'),
        'description'     => __('You can show or hide shop page banner.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_shopbanner_show',
        'type'       => 'checkbox',
        'priority'       => 5,
    ));
    // Side menu profile image
    $wp_customize->add_setting('shop_kit_shopb_img', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => shop_kit_sanitize_image('shop_kit_shopb_img'),
        'transport' => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'shop_kit_shopb_img', array(
        'label' => __('Upload Shop Image', 'shop-kit'),
        'description' => __('You can upload shop image by this option', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_shopb_img',
        'mime_type' => 'image',

    )));

    $wp_customize->add_setting('shop_kit_banner_subtext', array(
        'default' =>  '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_subtext', array(
        'label'      => __('Shop Banner Subtitle', 'shop-kit'),
        'description'     => __('Enter your home image title here. The title only show in home page', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_subtext',
        'type'       => 'text',

    ));

    $wp_customize->add_setting('shop_kit_banner_title', array(
        'default' =>  '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_title', array(
        'label'      => __('Shop Banner Title', 'shop-kit'),
        'description'     => __('Enter your shop banner title. Leave empty if don\'t show the title.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_title',
        'type'       => 'text',
    ));
    $wp_customize->add_setting('shop_kit_banner_desc', array(
        'default' =>  '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_desc', array(
        'label'      => __('Shop Banner Description', 'shop-kit'),
        'description'     => __('Enter your shop banner description. Leave empty if don\'t show the title.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_desc',
        'type'       => 'textarea',
    ));
    $wp_customize->add_setting('shop_kit_banner_btn', array(
        'default' =>  esc_html__('Shop Now', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_btn', array(
        'label'      => __('Shop Banner Button text', 'shop-kit'),
        'description'     => __('Enter your shop banner button text.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_btn',
        'type'       => 'text',
    ));
    $wp_customize->add_setting('shop_kit_banner_url', array(
        'default' =>  '#',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_url',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_url', array(
        'label'      => __('Shop Banner Button url', 'shop-kit'),
        'description'     => __('Enter your shop banner button url. Leave empty if you don\'t want to use the button.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_url',
        'type'       => 'url',
    ));
    $wp_customize->add_setting('shop_kit_bannerbtn_color', array(
        'default' => '#fff',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_bannerbtn_color',
            array(
                'label' => __('Button Color', 'shop-kit'),
                'section' => 'shop_kit_shop_banner'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_bannerbtn_bgcolor', array(
        'default' => '#ee434e',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_bannerbtn_bgcolor',
            array(
                'label' => __('Button Background Color', 'shop-kit'),
                'section' => 'shop_kit_shop_banner'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_text_position', array(
        'default'        => 'left',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_text_position', array(
        'label'      => __('Text Position', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_text_position',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Left', 'shop-kit'),
            'center' => __('Center', 'shop-kit'),
            'right' => __('Right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_bannertext_color', array(
        'default' => '#fff',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_bannertext_color',
            array(
                'label' => __('Banner Text Color', 'shop-kit'),
                'section' => 'shop_kit_shop_banner'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_banner_overlay', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_banner_overlay', array(
        'label'      => __('Show banner overlay? ', 'shop-kit'),
        'description' => __('You can show or hide banner overlay.', 'shop-kit'),
        'section'    => 'shop_kit_shop_banner',
        'settings'   => 'shop_kit_banner_overlay',
        'type'       => 'checkbox',

    ));

    //End shop page banner

    $wp_customize->add_section(
        'shop_kit_shop',
        array(
            'title'    => __('Shop Kit  Settings', 'shop-kit'),
            'priority' => 6,
            'panel'    => 'woocommerce',
        )
    );


    $wp_customize->add_setting('shop_kit_shop_container', array(
        'default'        => 'container',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_shop_container', array(
        'label'      => __('Shop Container type', 'shop-kit'),
        'description' => __('You can set standard container or full width container for shop. ', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_shop_container',
        'type'       => 'select',
        'choices'    => array(
            'container' => __('Standard Container', 'shop-kit'),
            'container-fluid' => __('Full width Container', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_shop_layout', array(
        'default'        => 'rightside',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_shop_layout', array(
        'label'      => __('Select Shop Layout', 'shop-kit'),
        'description' => __('Right and Left sidebar only show when shop sidebar widget is available. ', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_shop_layout',
        'type'       => 'select',
        'choices'    => array(
            'rightside' => __('Right Sidebar', 'shop-kit'),
            'leftside' => __('Left Sidebar', 'shop-kit'),
            'fullwidth' => __('Full Width', 'shop-kit'),
        ),
    ));


    $wp_customize->add_setting('shop_kit_shop_title', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  esc_html__('Shop', 'shop-kit'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_shop_title', array(
        'label'      => __('Shop Page Title', 'shop-kit'),
        'description'     => esc_html__('Enter your shop page title. Leave empty if you don\'t want the title.', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_shop_title',
        'type'       => 'text',
    ));
    $wp_customize->add_setting('shop_kit_title_position', array(
        'default'        => 'center',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_title_position', array(
        'label'      => __('Title Position', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_title_position',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Left', 'shop-kit'),
            'center' => __('Center', 'shop-kit'),
            'right' => __('Right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_titlecolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_titlecolor',
            array(
                'label' => __('Shop Title Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_ftwidget_position', array(
        'default'        => 'center',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_ftwidget_position', array(
        'label'      => __('Shop Page Top Widget Position', 'shop-kit'),
        'description'      => __('Set filter widget from widget section for fiilter shop page products. You can set posotion filiter items by this opiton.', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_ftwidget_position',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Left', 'shop-kit'),
            'center' => __('Center', 'shop-kit'),
            'right' => __('Right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_ftwidget_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_ftwidget_color',
            array(
                'label' => __('Shop Top Widget Text Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_ftwidget_hvcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_ftwidget_hvcolor',
            array(
                'label' => __('Shop Top Widget Text Hover Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_ftwidget_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_ftwidget_bgcolor',
            array(
                'label' => __('Shop Top Widget Background Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_resultcount', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_resultcount', array(
        'label'      => __('Show Result Count? ', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_resultcount',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_porder', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_porder', array(
        'label'      => __('Show Products Order? ', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_porder',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_shop_column', array(
        'default'        => $shop_items,
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_shop_column', array(
        'label'      => __('Set Product Per row', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_shop_column',
        'type'       => 'select',
        'choices'    => array(
            '5' => __('Five Products', 'shop-kit'),
            '4' => __('Four Products', 'shop-kit'),
            '3' => __('Three Products', 'shop-kit'),
            '2' => __('Two Products', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_shop_style', array(
        'default'        => '1',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_woo_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_shop_style', array(
        'label'      => __('Select Products Style', 'shop-kit'),
        'section'    => 'shop_kit_shop',
        'settings'   => 'shop_kit_shop_style',
        'type'       => 'select',
        'choices'    => array(
            '1' => __('Style One', 'shop-kit'),
            '2' => __('Style Two', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_product_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_product_bgcolor',
            array(
                'label' => __('Products Background Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_ptitle_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_ptitle_color',
            array(
                'label' => __('Products Title Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_prating_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_prating_color',
            array(
                'label' => __('Products Rating Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pprice_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pprice_color',
            array(
                'label' => __('Products Price Color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pbtn_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pbtn_bgcolor',
            array(
                'label' => __('Products button background color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pbtn_hvbgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pbtn_hvbgcolor',
            array(
                'label' => __('Products button hover background color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pbtn_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pbtn_color',
            array(
                'label' => __('Products button color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_pbtn_hvcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_pbtn_hvcolor',
            array(
                'label' => __('Products button hover color', 'shop-kit'),
                'section' => 'shop_kit_shop'
            )
        )
    );
    /*Single product page options*/
    $wp_customize->add_section(
        'shop_kit_single_product',
        array(
            'title'    => __('Single Product Settings', 'shop-kit'),
            'priority' => 10,
            'panel'    => 'woocommerce',
        )
    );
    $wp_customize->add_setting('shop_kit_ptitle_fsize', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_ptitle_fsize', array(
        'label'      => __('Font Size', 'shop-kit'),
        'description'     => __('Set single product title font size.', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_ptitle_fsize',
        'type'       => 'number',

    ));
    $wp_customize->add_setting('shop_kit_sptitle_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_sptitle_color',
            array(
                'label' => __('Title Color', 'shop-kit'),
                'section' => 'shop_kit_single_product'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_srating_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_srating_show', array(
        'label'      => __('Show Rating ', 'shop-kit'),
        'description' => __('You can show or hide single product rating. Rating only show when rating available.', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_srating_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_sdesc_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_sdesc_show', array(
        'label'      => __('Show Short description ', 'shop-kit'),
        'description' => __('You can show or hide single product short description.', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_sdesc_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_sku_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_sku_show', array(
        'label'      => __('Show SKU', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_sku_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_spcat_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_spcat_show', array(
        'label'      => __('Show Categories ', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_spcat_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_sptag_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_sptag_show', array(
        'label'      => __('Show Tags', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_sptag_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_sptab_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_sptab_show', array(
        'label'      => __('Show Tab', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_sptab_show',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_sprelated_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_sprelated_show', array(
        'label'      => __('Show Related Products', 'shop-kit'),
        'section'    => 'shop_kit_single_product',
        'settings'   => 'shop_kit_sprelated_show',
        'type'       => 'checkbox',
    ));
    /*Woocommerce checkout options*/
    $wp_customize->add_setting('shop_kit_checkout_lastname', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_checkout_lastname', array(
        'label'      => __('Show Last Nama Field', 'shop-kit'),
        'section'    => 'woocommerce_checkout',
        'settings'   => 'shop_kit_checkout_lastname',
        'type'       => 'checkbox',
        'priority' => 5,
    ));
    $wp_customize->add_setting('shop_kit_checkout_email', array(
        'default'        => 'required',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_checkout_email', array(
        'label'      => __('Email field', 'shop-kit'),
        'section'    => 'woocommerce_checkout',
        'settings'   => 'shop_kit_checkout_email',
        'type'       => 'select',
        'choices'    => array(
            'required' => __('Required', 'shop-kit'),
            'optional' => __('Optional', 'shop-kit'),
            'hide' => __('Hidden', 'shop-kit'),
        ),
        'priority'       => 7,
    ));
    $wp_customize->add_setting('shop_kit_checkout_postcode', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_checkout_postcode', array(
        'label'      => __('Show Postcode / ZIP', 'shop-kit'),
        'section'    => 'woocommerce_checkout',
        'settings'   => 'shop_kit_checkout_postcode',
        'type'       => 'checkbox',
        'priority' => 7,
    ));
}
add_action('customize_register', 'shop_kit_woo_customize_register');
