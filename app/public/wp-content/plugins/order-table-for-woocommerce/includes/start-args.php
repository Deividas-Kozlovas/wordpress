<?php
if (!defined('ABSPATH')) {
    exit;
}
$pre_defiend_columns = array();

$start_args          = array(
    array(
        "ID" => 'otfw_item_0',
        "Width" => "0.15",
        "label" => "quantity input",
        'status' => true,
         'true_name' => "quantity_input_pre",
    ),
    array(
        "ID" => 'otfw_item_1',
        "Width" => "0.15",
        "label" => "image",
         'status' => true,
          'true_name' => "image_pre"
    ),
    array(
        "ID" => 'otfw_item_2',
        "Width" => "0.25",
        "label" => "title",
         'status' => true,
          'true_name' => "title_pre",
    ),
    array(
        "ID" => 'otfw_item_3',
        "Width" => "0.15",
        "label" => "package size",
         'status' => false,
          'true_name' => "package_size_pre"
    ),
    array(
        "ID" => 'otfw_item_4',
        "Width" => "0.15",
        "label" => "unit",
         'status' => false,
          'true_name' => "unit_pre",
    ),
 array(
        "ID" => 'otfw_item_5',
        "Width" => "0.15",
        "label" => "unit price & currency",
         'status' => false,
          'true_name' => "unit_price_currency_pre",
    ),

    array(
        "ID" => 'otfw_item_6',
        "Width" => "0.15",
        "label" => "price & currency",
         'status' => true,
          'true_name' => "price_currency_pre",
    ),
);
if (is_array(wc_get_attribute_taxonomy_names()) || is_object(wc_get_attribute_taxonomy_names())) {
    $a_custom_attributes = wc_get_attribute_taxonomy_names();
    $attr_option         = array();
    $calc_1              = array();
    foreach ($a_custom_attributes as $calc_2) {
        $calc_3 = wc_attribute_label($calc_2);
        $calc_1 += array(
            $calc_2 => ($calc_3)
        );
        $attr_option = $calc_1;
    }
    $attr_option += array(
            "None" => esc_html__("None", OTFW::TEXT_DOMAIN),
            );



}else {
      $attr_option = array(
            "None" => esc_html__("None", OTFW::TEXT_DOMAIN)
            );

}
$settings_args = array(
    array(
        'name' => esc_html__('User Visibility', OTFW::TEXT_DOMAIN),
        'type' => 'title',
        'desc' => esc_html__( 'Either show the Order Table at shop page or use shortcode [ordertable] on any page.', OTFW::TEXT_DOMAIN ),

        'id' => 'otfw_visibility'
    ),
    array(
        'name' => esc_html__('Override shop page', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_override_shop',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__( 'Show the Order Table at shop page?', OTFW::TEXT_DOMAIN ),

        'default' => 'no'
    ),
    array(
        'name' => esc_html__('Show products by category at shop page', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_override_shop_show_cat',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__( 'Show products sorted by their category title (Paging will be disabled)', OTFW::TEXT_DOMAIN ),

        'default' => 'no'
    ),
      array(
        'name' => esc_html__('Expand categories as default?', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_cats_expand',
        'default' => 'yes',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__( 'Choose if to expand categories as default.', OTFW::TEXT_DOMAIN ),


    ),
     array(
        'name' => esc_html__('Show category description?', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_show_cat_desc',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__( 'Show the category description below it´s title', OTFW::TEXT_DOMAIN ),

        'default' => 'no'
    ),
    array(
        'type' => 'sectionend',
        'id' => 'otfw_visibility'
    ),
    array(
        'name' => esc_html__('Style Settings', OTFW::TEXT_DOMAIN),
        'type' => 'title',
        'desc' => '',
        'id' => 'otfw_style'
    ),
    array(
        'name' => esc_html__('Choose Table Style', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_table_style',
        'default' => 'default',
        'type' => 'select',
        'options' => array(
            'default' => esc_html__('Standard Style', OTFW::TEXT_DOMAIN),
            'theme' => esc_html__('Use Theme Style', OTFW::TEXT_DOMAIN)
        ),
        'css' => 'max-width:200px;',
        'desc' => esc_html__('Choose the style of the Order Table?', OTFW::TEXT_DOMAIN)
    ),
    array(
        'type' => 'sectionend',
        'id' => 'otfw_style'
    ),
    array(
        'name' => esc_html__('Columns Settings', OTFW::TEXT_DOMAIN),
        'type' => 'title',
        'desc' => '',
        'id' => 'otfw_column_settings'
    ),
    array(
        'id' => 'otfw_batch_attribute_to_use',
        'name' => esc_html__('Product attribute to use as "Package Size".', OTFW::TEXT_DOMAIN),
        'desc' => esc_html__('Change product attribute to use as "number of Units per package" ', OTFW::TEXT_DOMAIN),
        'type' => 'select',
        'css' => 'max-width:200px;',
        'default' => 'None',
        'options' => ($attr_option),
        'desc_tip' => false
    ),
    array(
        'id' => 'otfw_unit_attribute_to_use',
        'name' => esc_html__('Attribute to use as "Product Unit".', OTFW::TEXT_DOMAIN),
        'desc' => esc_html__('Change attribute to use as "Product Unit". Product Unit could be kg, pcs, punnets, liters etc.', OTFW::TEXT_DOMAIN),
        'type' => 'select',
        'css' => 'max-width:200px;',
        'default' => '',
        'options' => ($attr_option),
        'desc_tip' => false
    ),
    array(
        'type' => 'sectionend',
        'id' => 'otfw_column_settings'
    ),
    array(
        'name' => esc_html__('Display Options', OTFW::TEXT_DOMAIN),
        'type' => 'title',
        'desc' => '',
        'id' => 'otfw_display_options'
    ),
    array(
        'name' => esc_html__('Confirm quantity input', OTFW::TEXT_DOMAIN),
        'desc_tip' => esc_html__('Check this to show a "checked" icon when an product is added.', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_confirm_input',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__('Confirm quantity input?', OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Show stock level varning', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_show_stocklevel',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__('Check to get a stock level varning with actual stock level if trying to exceed it. ', OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Title suffix', OTFW::TEXT_DOMAIN),
        'desc' => esc_html__('Add a suffix in the title for variational products.', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_show_attri_in_title',
        'css' => 'max-width:200px;',
        'std' => 'attr',
        'default' => 'attr',
        'type' => 'select',
        'options' => array(
            'no' => esc_html__('No suffix', OTFW::TEXT_DOMAIN),
            'desc' => esc_html__('Variational description', OTFW::TEXT_DOMAIN),
            'attr' => esc_html__('Variational attributes', OTFW::TEXT_DOMAIN)
        ),
        'desc_tip' => false
    ),
    array(
        'name' => esc_html__('Product Image Size', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_show_images',
        'default' => 'medium',
        'type' => 'select',
        'options' => array(
            'small' => esc_html__('Small Size', OTFW::TEXT_DOMAIN),
            'medium' => esc_html__('Normal Size', OTFW::TEXT_DOMAIN),
            'large' => esc_html__('Large Size', OTFW::TEXT_DOMAIN)
        ),
        'css' => 'max-width:200px;',
        'desc' => esc_html__('Choose size of image for products in the Order Table?', OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Table header & footer', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_table_header_sticky',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__('Make the table header and footer sticky?', OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Link product title to product page', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_link_title',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__("Should the product title be linked to it's product page?", OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Sale Badge', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_sale_badge',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__('Show the sale badge for on sale items?', OTFW::TEXT_DOMAIN)
    ),
    array(
        'name' => esc_html__('Hide the increment buttons', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_use_plugin_increment_css',
        'type' => 'checkbox',
        'css' => 'min-width:300px;',
        'desc' => esc_html__('Hide the increment buttons (+/-) in the quantity input field?', OTFW::TEXT_DOMAIN)
    ),
    array(
            'name' => __('Checkout button text', OTFW::TEXT_DOMAIN),
            'id' => 'otfw_checkout_string',
            'css' => 'max-width:200px;',
            'default' => '',
            'type' => 'text',
            'desc' => __('Override the default text for the checkout button.', OTFW::TEXT_DOMAIN)
        ),
    array(
            'name' => __('Packages label text', OTFW::TEXT_DOMAIN),
            'id' => 'otfw_package_string',
            'css' => 'max-width:200px;',
            'default' => '',
            'type' => 'text',
            'desc' => __('Override the default text of the "Packages" label.', OTFW::TEXT_DOMAIN)
        ),
    array(
        'type' => 'sectionend',
        'id' => 'otfw_display_options'
    ),
    array(
        'name' => esc_html__('Table Column Settings', OTFW::TEXT_DOMAIN),
        'type' => 'title',
        'desc' => esc_html__('This section will let you decide which columns to show and which heading titles to display and if you want to give them an alias.', OTFW::TEXT_DOMAIN),
        'id' => 'otfw_header_config'
    ),
    array(
        'type' => 'otfw_show_header',
        'id' => 'otfw_show_header'
    ),
    array(
        'name' => 'otfw_table_config',
        'type' => 'otfw_table_config',
        'id' => 'otfw_table_config'
    ),
    array(
        'type' => 'sectionend',
        'id' => 'otfw_header_config'
    )
);
