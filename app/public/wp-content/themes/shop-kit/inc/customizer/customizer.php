<?php

/**
 * Shop Kit  Theme Customizer
 *
 * @package Shop Kit 
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer. 
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shop_kit_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    //select sanitization function
    function shop_kit_sanitize_select($input, $setting)
    {
        $input = sanitize_key($input);
        $choices = $setting->manager->get_control($setting->id)->choices;
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }

    $theme_info = wp_get_theme();
    $theme_domain = $theme_info->get('TextDomain');
    if ($theme_domain == 'shop-kit') {
        $topbar_default = '1';
    } else {
        $topbar_default = '';
    }

    // Typography section
    $wp_customize->add_section('shop_kit_typography', array(
        'title' => __('Shop Kit Theme typography', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('You can setup Shop Kit  theme typography by these options.', 'shop-kit'),
        'priority'       => 4,

    ));
    $wp_customize->add_setting('shop_kit_theme_fonts', array(
        'default'       => 'Poppins',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_theme_font',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_theme_fonts_control', array(
        'label'      => __('Select theme body Font', 'shop-kit'),
        'section'    => 'shop_kit_typography',
        'settings'   => 'shop_kit_theme_fonts',
        'type'       => 'select',
        'choices'    => array(
            'Poppins' => __('Poppins', 'shop-kit'),
            'Noto Serif' => __('Noto Serif', 'shop-kit'),
            'Roboto' => __('Roboto', 'shop-kit'),
            'Open Sans' => __('Open Sans', 'shop-kit'),
            'Lato' => __('Lato', 'shop-kit'),
            'Montserrat' => __('Montserrat', 'shop-kit'),
            'Crimson Text' => __('Crimson Text', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_font_size', array(
        'default' =>  '14',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_font_size_control', array(
        'label'      => __('Body font size', 'shop-kit'),
        'description'     => __('Default body font size is 14px', 'shop-kit'),
        'section'    => 'shop_kit_typography',
        'settings'   => 'shop_kit_font_size',
        'type'       => 'text',

    ));
    $wp_customize->add_setting('shop_kit_font_line_height', array(
        'default' =>  '24',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_font_line_height_control', array(
        'label'      => __('Body font line height', 'shop-kit'),
        'description'     => __('Default body line height is 24px', 'shop-kit'),
        'section'    => 'shop_kit_typography',
        'settings'   => 'shop_kit_font_line_height',
        'type'       => 'text',

    ));
    $wp_customize->add_setting('shop_kit_theme_font_head', array(
        'default'       => 'Noto Serif',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_theme_head_font',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_theme_font_head_control', array(
        'label'      => __('Select theme header Font', 'shop-kit'),
        'section'    => 'shop_kit_typography',
        'settings'   => 'shop_kit_theme_font_head',
        'type'       => 'select',
        'choices'    => array(
            'Poppins' => __('Poppins', 'shop-kit'),
            'Noto Serif' => __('Noto Serif', 'shop-kit'),
            'Roboto' => __('Roboto', 'shop-kit'),
            'Open Sans' => __('Open Sans', 'shop-kit'),
            'Lato' => __('Lato', 'shop-kit'),
            'Montserrat' => __('Montserrat', 'shop-kit'),
            'Crimson Text' => __('Crimson Text', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_font_weight_head', array(
        'default'       => '700',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_font_weight_head_control', array(
        'label'      => __('Site header font weight', 'shop-kit'),
        'section'    => 'shop_kit_typography',
        'settings'   => 'shop_kit_font_weight_head',
        'type'       => 'select',
        'choices'    => array(
            '400' => __('Normal', 'shop-kit'),
            '500' => __('Semi Bold', 'shop-kit'),
            '700' => __('Bold', 'shop-kit'),
            '900' => __('Extra Bold', 'shop-kit'),
        ),
    ));
    /*End typography section*/

    // Add shop kit top header section
    $wp_customize->add_section('shop_kit_topbar', array(
        'title' => __('Shop Kit Top bar', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit topbar options ', 'shop-kit'),
        'priority'       => 5,

    ));
    $wp_customize->add_setting('shop_kit_topbar_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  $topbar_default,
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_topbar_show', array(
        'label'      => __('Show header topbar? ', 'shop-kit'),
        'description' => __('You can show or hide header topbar.', 'shop-kit'),
        'section'    => 'shop_kit_topbar',
        'settings'   => 'shop_kit_topbar_show',
        'type'       => 'checkbox',

    ));
    $wp_customize->add_setting('shop_kit_topbar_mtext', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  esc_html__('Welcome to Our Website !', 'shop-kit'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_topbar_mtext', array(
        'label'      => __('Welcome text', 'shop-kit'),
        'description'     => esc_html__('Enter your website welcome text. Leave empty if you don\'t want the text.', 'shop-kit'),
        'section'    => 'shop_kit_topbar',
        'settings'   => 'shop_kit_topbar_mtext',
        'type'       => 'text',
    ));
    $wp_customize->add_setting('shop_kit_topbar_menushow', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_topbar_menushow', array(
        'label'      => __('Show header topbar menu? ', 'shop-kit'),
        'description' => __('You can show or hide topbar menu. You need to add menu from menu section for display menu.', 'shop-kit'),
        'section'    => 'shop_kit_topbar',
        'settings'   => 'shop_kit_topbar_menushow',
        'type'       => 'checkbox',

    ));

    $wp_customize->add_setting('shop_kit_topbar_search_item', array(
        'default'        => 'simple',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_topbar_search_item', array(
        'label'      => __('Select header topbar search type', 'shop-kit'),
        'description' => __('You can show two different way or hide topbar search. ', 'shop-kit'),
        'section'    => 'shop_kit_topbar',
        'settings'   => 'shop_kit_topbar_search_item',
        'type'       => 'select',
        'choices'    => array(
            'simple' => __('Simple Search', 'shop-kit'),
            'popup' => __('Popup Search', 'shop-kit'),
            'hide' => __('Hide Search', 'shop-kit'),
        ),
    ));
    // Add setting
    $wp_customize->add_setting('shop_kit_topbar_bg', array(
        'default' => '#ededed',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_topbar_bg',
            array(
                'label' => __('Topbar Background Color', 'shop-kit'),
                'section' => 'shop_kit_topbar'
            )
        )
    );
    // Add setting
    $wp_customize->add_setting('shop_kit_topbar_color', array(
        'default' => '#000',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_topbar_color',
            array(
                'label' => __('Topbar text Color', 'shop-kit'),
                'section' => 'shop_kit_topbar'
            )
        )
    );
    // Add setting
    $wp_customize->add_setting('shop_kit_topbar_hcolor', array(
        'default' => '#6b14fa',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_topbar_hcolor',
            array(
                'label' => __('Topbar link hover Color', 'shop-kit'),
                'section' => 'shop_kit_topbar'
            )
        )
    ); // topbar section end
    /*header image height*/

    /*
      * Logo position 
       */
    $wp_customize->add_setting('shop_kit_himg_height', array(
        'default'        => 'fixed',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
        //  'priority'       => 20,
    ));
    $wp_customize->add_control('shop_kit_himg_height', array(
        'label'      => __('Header image height', 'shop-kit'),
        'section'    => 'header_image',
        'settings'   => 'shop_kit_himg_height',
        'type'       => 'select',
        'choices'    => array(
            'fixed' => __('Fixed Height', 'shop-kit'),
            'auto' => __('Auto Height', 'shop-kit'),
            'amobile' => __('Auto height only small devices', 'shop-kit'),
        ),
    ));

    //logo width
    $wp_customize->add_setting('shop_kit_logo_width', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_logo_width_control', array(
        'label'      => __('Set Image Logo Width', 'shop-kit'),
        'description'     => __('Set your site logo Width.', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_logo_width',
        'type'       => 'number',
        'priority'       => 6,

    ));
    $wp_customize->add_setting('shop_kit_logo_height', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_logo_height_control', array(
        'label'      => __('Set Image Logo height', 'shop-kit'),
        'description'     => __('Set your site logo height. Leave empty for auto height.', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_logo_height',
        'type'       => 'number',
        'priority'       => 7,
    ));
    $wp_customize->add_setting('shop_kit_tagline_bgshow', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_tagline_bgshow', array(
        'label'      => __('Show tagline background? ', 'shop-kit'),
        'description' => __('You can show or hide header tagline background.', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_tagline_bgshow',
        'type'       => 'checkbox',

    ));
    $wp_customize->add_setting('shop_kit_logo_fontsize', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_logo_fontsize', array(
        'label'      => __('Site Title Font Size', 'shop-kit'),
        'description'     => __('Set your site title font size.', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_logo_fontsize',
        'type'       => 'number',

    ));
    $wp_customize->add_setting('shop_kit_desc_fontsize', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_desc_fontsize', array(
        'label'      => __('Set Site Tagline Font Size', 'shop-kit'),
        'description'     => __('Set your site tabline font size.', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_desc_fontsize',
        'type'       => 'number',
    ));

    /*
      * Logo position 
       */
    $wp_customize->add_setting('shop_kit_logo_position', array(
        'default'        => 'left',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
        //  'priority'       => 20,
    ));
    $wp_customize->add_control('shop_kit_logo_position', array(
        'label'      => __('Select Logo Position', 'shop-kit'),
        'section'    => 'title_tagline',
        'settings'   => 'shop_kit_logo_position',
        'type'       => 'select',
        'choices'    => array(
            'left' => __('Logo left', 'shop-kit'),
            'center' => __('Logo center', 'shop-kit'),
            'right' => __('Logo right', 'shop-kit'),
        ),
    ));

    // Header middle section
    $wp_customize->add_section('shop_kit_middle', array(
        'title' => __('Shop Kit Header Middle', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit header middle section ', 'shop-kit'),
        'priority'       => 6,

    ));
    $wp_customize->add_setting('shop_kit_hmiddle_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_hmiddle_show', array(
        'label'      => __('Show header Middle? ', 'shop-kit'),
        'description' => __('You can show or hide header middle section. And can show logo in menu bar', 'shop-kit'),
        'section'    => 'shop_kit_middle',
        'settings'   => 'shop_kit_hmiddle_show',
        'type'       => 'checkbox',

    ));
    $wp_customize->add_setting('shop_kit_hmiddle_height', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_hmiddle_height', array(
        'label'      => __('Set header middle section height', 'shop-kit'),
        'description'     => __('Set your header middle section height. Leave empty for auto height.', 'shop-kit'),
        'section'    => 'shop_kit_middle',
        'settings'   => 'shop_kit_hmiddle_height',
        'type'       => 'number',
    ));


    // Header middle section
    $wp_customize->add_section('shop_kit_main_menu', array(
        'title' => __('Shop Kit Main Menu', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit main menu section. You need to add menu from WordPress menu setup for display the menu manu ', 'shop-kit'),
        'priority'       => 6,

    ));
    $wp_customize->add_setting('shop_kit_main_menu_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_main_menu_show', array(
        'label'      => __('Show Main Menu section? ', 'shop-kit'),
        'description' => __('You can show or hide header main menu section.', 'shop-kit'),
        'section'    => 'shop_kit_main_menu',
        'settings'   => 'shop_kit_main_menu_show',
        'type'       => 'checkbox',

    ));
    $wp_customize->add_setting('shop_kit_menu_logo', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_menu_logo', array(
        'label'      => __('Show Logo in the Main Menu section? ', 'shop-kit'),
        'description' => __('You can show logo in the header main menu section.', 'shop-kit'),
        'section'    => 'shop_kit_main_menu',
        'settings'   => 'shop_kit_menu_logo',
        'type'       => 'checkbox',

    ));
    /*
      * menu position 
       */
    $wp_customize->add_setting('shop_kit_menu_position', array(
        'default'        => 'center',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
        //  'priority'       => 20,
    ));
    $wp_customize->add_control('shop_kit_menu_position', array(
        'label'      => __('Select Menu Position', 'shop-kit'),
        'section'    => 'shop_kit_main_menu',
        'settings'   => 'shop_kit_menu_position',
        'type'       => 'select',
        'choices'    => array(
            'flex-start' => __('Menu left', 'shop-kit'),
            'center' => __('Menu center', 'shop-kit'),
            'flex-end' => __('Menu right', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_menu_tbpadding', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_menu_tbpadding', array(
        'label'      => __('Menu top bottom padding', 'shop-kit'),
        'description'     => __('Add main menu top bottom padding.', 'shop-kit'),
        'section'    => 'shop_kit_main_menu',
        'settings'   => 'shop_kit_menu_tbpadding',
        'type'       => 'number',
    ));
    $wp_customize->add_setting('shop_kit_menu_fontsize', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',

    ));
    $wp_customize->add_control('shop_kit_menu_fontsize', array(
        'label'      => __('Menu item font size', 'shop-kit'),
        'description'     => __('Set menu item font size. Font size set by px', 'shop-kit'),
        'section'    => 'shop_kit_main_menu',
        'settings'   => 'shop_kit_menu_fontsize',
        'type'       => 'number',
    ));
    // Add setting
    $wp_customize->add_setting('shop_kit_menu_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_menu_color',
            array(
                'label' => __('Menu font Color', 'shop-kit'),
                'section' => 'shop_kit_main_menu'
            )
        )
    );
    // Add setting
    $wp_customize->add_setting('shop_kit_menubg_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_menubg_color',
            array(
                'label' => __('Menu Background Color', 'shop-kit'),
                'section' => 'shop_kit_main_menu'
            )
        )
    );
    // Add setting
    $wp_customize->add_setting('shop_kit_menudropbg_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_menudropbg_color',
            array(
                'label' => __('Menu dropdown Background Color', 'shop-kit'),
                'section' => 'shop_kit_main_menu'
            )
        )
    );

    //color section custom options    

    // Add setting
    $wp_customize->add_setting('shop_kit_titletag_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_titletag_bgcolor',
            array(
                'label' => __('Title tag background Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_header_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
        'priority'       => 2,

    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_header_color',
            array(
                'label' => __('Header tag Font Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_bodyfont_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_bodyfont_color',
            array(
                'label' => __('Body Font Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_contentbg_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_contentbg_color',
            array(
                'label' => __('Content Background Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_basic_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_basic_color',
            array(
                'label' => __('Theme Basic Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    //link color
    $wp_customize->add_setting('shop_kit_link_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_link_color',
            array(
                'label' => __('Theme Link Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    //link hover color
    $wp_customize->add_setting('shop_kit_linkhvr_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_linkhvr_color',
            array(
                'label' => __('Theme Link Hover Color', 'shop-kit'),
                'section' => 'colors'
            )
        )
    );
    // Add shop kit blog section
    $wp_customize->add_section('shop_kit_blog', array(
        'title' => __('Shop Kit Blog', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit theme blog options ', 'shop-kit'),
        'priority'       => 60,

    ));
    $wp_customize->add_setting('shop_kit_blog_container', array(
        'default'        => 'container',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blog_container', array(
        'label'      => __('Container type', 'shop-kit'),
        'description' => __('You can set standard container or full width container. ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blog_container',
        'type'       => 'select',
        'choices'    => array(
            'container' => __('Standard Container', 'shop-kit'),
            'container-fluid' => __('Full width Container', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_blog_layout', array(
        'default'        => 'rightside',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blog_layout', array(
        'label'      => __('Select Blog Layout', 'shop-kit'),
        'description' => __('Right and Left sidebar only show when sidebar widget is available. ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blog_layout',
        'type'       => 'select',
        'choices'    => array(
            'rightside' => __('Right Sidebar', 'shop-kit'),
            'leftside' => __('Left Sidebar', 'shop-kit'),
            'fullwidth' => __('Full Width', 'shop-kit'),
        ),
    ));

    $wp_customize->add_setting('shop_kit_blog_style', array(
        'default'        => 'grid',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blog_style', array(
        'label'      => __('Select Blog Style', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blog_style',
        'type'       => 'select',
        'choices'    => array(
            'grid' => __('Grid Style', 'shop-kit'),
            'style1' => __('List over Image', 'shop-kit'),
            'style2' => __('List Style', 'shop-kit'),
            'style3' => __('Classic Style', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_blog_readmore', array(
        'default' =>  esc_html__('Read More', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blog_readmore', array(
        'label'      => __('Posts Read More Text', 'shop-kit'),
        'description'     => __('You can change read more text by this settings', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blog_readmore',
        'type'       => 'text',

    ));

    $wp_customize->add_setting('shop_kit_blogdate', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blogdate', array(
        'label'      => __('Show Posts Date? ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blogdate',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_blogauthor', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_blogauthor', array(
        'label'      => __('Show Posts Author? ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_blogauthor',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_postcat', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_postcat', array(
        'label'      => __('Show Single Posts Categories? ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_postcat',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_posttag', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_posttag', array(
        'label'      => __('Show Single Posts tags? ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_posttag',
        'type'       => 'checkbox',
    ));
    $wp_customize->add_setting('shop_kit_post_comment', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_post_comment', array(
        'label'      => __('Show Single Posts comment? ', 'shop-kit'),
        'section'    => 'shop_kit_blog',
        'settings'   => 'shop_kit_post_comment',
        'type'       => 'checkbox',
    ));

    // Add shop kit page section
    $wp_customize->add_section('shop_kit_page', array(
        'title' => __('Shop Kit Page', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit theme Page options ', 'shop-kit'),
        'priority'       => 70,

    ));
    $wp_customize->add_setting('shop_kit_page_container', array(
        'default'        => 'container',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_page_container', array(
        'label'      => __('Page Container type', 'shop-kit'),
        'description' => __('You can set standard container or full width container for page. ', 'shop-kit'),
        'section'    => 'shop_kit_page',
        'settings'   => 'shop_kit_page_container',
        'type'       => 'select',
        'choices'    => array(
            'container' => __('Standard Page Container', 'shop-kit'),
            'container-fluid' => __('Full width Page Container', 'shop-kit'),
        ),
    ));
    $wp_customize->add_setting('shop_kit_page_layout', array(
        'default'        => 'rightside',
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'sanitize_callback' => 'shop_kit_sanitize_select',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_page_layout', array(
        'label'      => __('Select Page Layout', 'shop-kit'),
        'description' => __('Right and Left sidebar only show when sidebar widget is available. ', 'shop-kit'),
        'section'    => 'shop_kit_page',
        'settings'   => 'shop_kit_page_layout',
        'type'       => 'select',
        'choices'    => array(
            'rightside' => __('Right Sidebar', 'shop-kit'),
            'leftside' => __('Left Sidebar', 'shop-kit'),
            'fullwidth' => __('Full Width', 'shop-kit'),
        ),
    ));




    /*
* Footer setting section
*
*/
    // Add shop kit top header section
    $wp_customize->add_panel('shop_kit_footer_panel', array(
        //  'priority'       => 75,
        'title'          => __('Shop Kit footer settings', 'shop-kit'),
        'description'    => __('All Shop Kit theme footer settings in the panel', 'shop-kit'),
    ));
    $wp_customize->add_section('shop_kit_footer_top', array(
        'title' => __('Shop Kit Footer Top Settings', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit footer settings options ', 'shop-kit'),
        'panel'    => 'shop_kit_footer_panel',

    ));
    $wp_customize->add_setting('shop_kit_topfooter_show', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'theme_mod',
        'default'       =>  '1',
        'sanitize_callback' => 'absint',
        'transport'     => 'refresh',
    ));
    $wp_customize->add_control('shop_kit_topfooter_show', array(
        'label'      => __('Show Top Footer? ', 'shop-kit'),
        'description' => __('You can show or hide footer top section.The section only visible when you will set footer widget. ', 'shop-kit'),
        'section'    => 'shop_kit_footer_top',
        'settings'   => 'shop_kit_topfooter_show',
        'type'       => 'checkbox',

    ));
    //link hover color
    $wp_customize->add_setting('shop_kit_topfooter_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_topfooter_bgcolor',
            array(
                'label' => __('Footer top background color.', 'shop-kit'),
                'section' => 'shop_kit_footer_top'
            )
        )
    );
    //link hover color
    $wp_customize->add_setting('shop_kit_tftitle_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_tftitle_color',
            array(
                'label' => __('Footer Top Widget Title Color.', 'shop-kit'),
                'section' => 'shop_kit_footer_top'
            )
        )
    );
    //link hover color
    $wp_customize->add_setting('shop_kit_tftext_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_tftext_color',
            array(
                'label' => __('Footer Top Widget Text Color.', 'shop-kit'),
                'section' => 'shop_kit_footer_top'
            )
        )
    );
    //link hover color
    $wp_customize->add_setting('shop_kit_tflink_hovercolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_tflink_hovercolor',
            array(
                'label' => __('Footer Top Widget Link hover Color.', 'shop-kit'),
                'section' => 'shop_kit_footer_top'
            )
        )
    );
    // Footer section
    $wp_customize->add_section('shop_kit_footer', array(
        'title' => __('Shop Kit Footer Settings', 'shop-kit'),
        'capability'     => 'edit_theme_options',
        'description'     => __('The shop kit footer settings options ', 'shop-kit'),
        'panel'    => 'shop_kit_footer_panel',

    ));

    $wp_customize->add_setting('shop_kit_footer_bgcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_footer_bgcolor',
            array(
                'label' => __('Footer background color.', 'shop-kit'),
                'section' => 'shop_kit_footer'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_footer_color', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_footer_color',
            array(
                'label' => __('Footer text color.', 'shop-kit'),
                'section' => 'shop_kit_footer'
            )
        )
    );
    $wp_customize->add_setting('shop_kit_footerlink_hcolor', array(
        'default' => '',
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'refresh',
    ));
    // Add color control 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'shop_kit_footerlink_hcolor',
            array(
                'label' => __('Footer Link Hover color.', 'shop-kit'),
                'section' => 'shop_kit_footer'
            )
        )
    );




    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'shop_kit_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'shop_kit_customize_partial_blogdescription',
            )
        );
    }
}
add_action('customize_register', 'shop_kit_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function shop_kit_customize_partial_blogname()
{
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function shop_kit_customize_partial_blogdescription()
{
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function shop_kit_customize_preview_js()
{
    wp_enqueue_script('shop-kit-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), '20151215', true);
}
add_action('customize_preview_init', 'shop_kit_customize_preview_js');
