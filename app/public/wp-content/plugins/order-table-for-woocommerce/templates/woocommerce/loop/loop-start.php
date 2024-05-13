<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="otfw">

<!--<button type="button" class="btn btn-default">Default</button> -->


    <span class="label" id="del_label"></span>
    <?php
    $style = get_option('otfw_set_the_width_manual','no') == 'yes' ? 'style="table-layout:fixed"' : '';
//do_action('otfw_before_table');
?>
   <table class="otfw-products" <?php echo $style ?> >
        <thead >
            <tr id="otfw_products_id">

                <?php
$otfw_show_header      = get_option('otfw_show_header');

$otfw_header_config    = get_option('otfw_header_config');
  OTFW::setVar();
$otfw_table_config    =   OTFW::getVar();

$otfw_table_config_ids = array_column($otfw_table_config, 'true_name');
$headings              = array();
if (is_array($otfw_table_config) ) {
    foreach ($otfw_table_config as $valuet) {
        if (!isset($otfw_show_header[$valuet['true_name']]) || 'on' == $otfw_show_header[$valuet['true_name']]) {
            if ($valuet['true_name'] == "image_pre") {

                if (array_key_exists($valuet['true_name'], $otfw_header_config) && !is_null($otfw_header_config[$valuet['true_name']]) && !'' == $otfw_header_config[$valuet['true_name']]) {
                    array_push($headings, $otfw_header_config[$valuet['true_name']]);
                } else {
                    array_push($headings, $valuet['label']);
                }
            } elseif ($valuet['true_name'] == "quantity_input_pre") {
                if (array_key_exists($valuet['true_name'], $otfw_header_config) && !is_null($otfw_header_config[$valuet['true_name']]) && '' != $otfw_header_config[$valuet['true_name']]) {
                    array_push($headings, $otfw_header_config[$valuet['true_name']]);
                } else {
                    array_push($headings, $valuet['label']);
                }
            } else {
                if (taxonomy_is_product_attribute($valuet['true_name'])) {
                    $attr = wc_attribute_label($valuet['true_name']);
                    if (isset($otfw_header_config[$valuet['true_name']]) && !is_null($otfw_header_config[$valuet['true_name']]) && !'' == $otfw_header_config[$valuet['true_name']]) {
                        array_push($headings, $otfw_header_config[$valuet['true_name']]);
                    } else {
                        array_push($headings, $attr);
                    }
                } else {
                    if (!isset($otfw_header_config[$valuet['true_name']])) {
                        array_push($headings, $valuet['label']);
                    } elseif (!is_null($otfw_header_config[$valuet['true_name']]) && !'' == $otfw_header_config[$valuet['true_name']]) {
                        array_push($headings, $otfw_header_config[$valuet['true_name']]);
                    } else {
                        array_push($headings, $valuet['label']);
                    }
                }
            }
        } else {
            array_push($headings, ' ');
        }
    }
}
?>
               <script>

                    otfw_counter = 0;

                </script>
                <?php

echo "<script type='text/javascript'> var otfw_headings =" . json_encode($headings, JSON_FORCE_OBJECT) . "; </script>";
echo "<script type='text/javascript'> var otfw_config =" . json_encode($otfw_table_config, JSON_FORCE_OBJECT) . "; </script>";
echo "<script type='text/javascript'> var otfw_style =" . json_encode(get_option('otfw_set_the_width_manual','no'), JSON_FORCE_OBJECT) . "; </script>";

if (is_array($otfw_table_config_ids)) {
    foreach ($otfw_table_config_ids as $valuet) {
?>
               <script>
                if(otfw_config[otfw_counter].status === true){
                    var paragraph = document.createElement('p');
                    var head_1 = document.createElement('th');
                    head_1.className = "otfw_desc_head";
                    head_1.setAttribute("id", 'otfw_desc_head_' + otfw_config[otfw_counter].true_name);
                    var t = document.createTextNode(otfw_headings[otfw_counter]);
                    paragraph.appendChild(t);
                    if(otfw_style == 'yes' ){
                    head_1.style.width = otfw_config[otfw_counter].Width * 100 + "%";
                    }
                    head_1.appendChild(paragraph);
                    document.getElementById("otfw_products_id").appendChild(head_1);

                }
                otfw_counter++;
                </script>
                <?php
    }
}
?> </tr>
        </thead>



      <!--  <tbody> -->
