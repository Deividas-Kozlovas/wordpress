<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('OTFW_Settings')):
    function OTFW_Add_Tab() {
         /**
     * Class OTFW_Settings
     *
     * @since 1.0.0
     */
        class OTFW_Settings extends WC_Settings_Page {
            public function __construct() {
                $this->id    = 'otfwlayout';
                $this->label = esc_html__('Wholeale Order Table', OTFW::TEXT_DOMAIN);
                add_filter('woocommerce_settings_tabs_array', array(
                    $this,
                    'add_settings_page'
                ), 20);
                add_action('woocommerce_settings_' . $this->id, array(
                    $this,
                    'output'
                ));
                add_action('woocommerce_settings_save_' . $this->id, array(
                    $this,
                    'save'
                ));
                add_action('woocommerce_sections_' . $this->id, array(
                    $this,
                    'output_sections'
                ));

                add_action('woocommerce_admin_field_otfw_show_header', array(
                    $this,
                    'otfw_admin_field_otfw_show_header'
                ));


                add_action('admin_notices', array( $this, 'premium_admin_notice'));

            }
            public function get_sections() {
                $sections = array(
                    '' => esc_html__('Settings', OTFW::TEXT_DOMAIN),

                );
                return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
            }
            public function save() {
                global $current_section;
                $settings = $this->get_settings($current_section);
                WC_Admin_Settings::save_fields($settings);
            }
            public function output() {
                global $current_section;
                $settings = $this->get_settings($current_section);
                WC_Admin_Settings::output_fields($settings);
            }

             function premium_admin_notice(){
    if ( isset ($_GET['tab']) && $_GET['tab'] == 'otfwlayout' ) {
		if( mt_rand (1,2) == 1):
         echo '<div class="notice notice-info is-dismissible">
            <div class="otfw_premium">
            	<table>
                	<tbody><tr>
                    	<td width="70%">
                        	<p style="font-size:1.3em"><strong><i>Wholesale Order Table Premium </i></strong>provides more features</p>
                            <ul class="fa-ul" id="otfw_premium_ad">
								<li ><span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	Arrange the order of columns</li>
                            	<li ><span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	Set the columns relative width</li>
                                <li> <span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	Display table columns of...</li>
                                <li ><span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	Product Attributes...</li>
								<li ><span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	Product Tags...</li>
                                <li ><span class="fa-li" ><i class="fas fa-check" style="color:#64d4f7"></i></span>	SKUs, Dimensions, Weights, Tax rates etc.</li>



								 <li> If you really like the plugin, give us a <a target="_blank" rel="noopener noreferrer" href=" https://wordpress.org/support/plugin/order-table-for-woocommerce/reviews?rate=5#new-post"><span id="otfw_star_rating"><i class="fas fa-star"></i><i class="fas fa-star"></i>
								 <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span> five star rating.</a>  </li>
                            </ul>
                        </td>
                        <td>
                            <a target="_blank" rel="noopener noreferrer" href="http://wholesaleorderplugin.com/" class=" button_premium" ><p style="font-size:1.2em">Upgrade To Premium </p><p>Learn More <i class="fas fa-arrow-right"></i></p></a>
                             <a target="_blank" rel="noopener noreferrer" href="https://paypal.me/arosoftdonate" class=" button_donate_aro" ><p style="font-size:1em">Give a donation </p></a>

					    </td>
                    </tr>
                </tbody></table>
            </div>
         </div>';
		 endif;
		  echo '<div class="notice notice-success is-dismissible">
            <div class="otfw_premium">
            	<table>
                	<tbody><tr>
                    	<td width="70%">
                        	<p style="font-size:1.3em"><strong><i>Use shortcode [ordertable] to insert the Order Table at any page </i></strong></p>
                            <ul class="fa-ul" id="otfw_premium_ad">
								<li><span class="fa-li"><i class="fas fa-chevron-right" style="color:#34bf51"></i></span>To show products of certain categories use <strong>[ordertable categories="category A slug, category B slug"]</strong> </li>
								<li><span class="fa-li"><i class="fas fa-chevron-right" style="color:#34bf51"></i></span>To show products of certain tags use <strong>[ordertable tags="tag A, tag B"]</strong> </li>
								<li><span class="fa-li"><i class="fas fa-chevron-right" style="color:#34bf51"></i></span>To limit products per page use <strong>[ordertable limit=X]</strong> </li>
								<li><span class="fa-li"><i class="fas fa-chevron-right" style="color:#34bf51"></i></span><strong>New Feature!</strong> To show products sorted by their category title use <strong>[ordertable show_categories="true"]</strong> </li>
								<li><span class="fa-li"><i class="fas fa-chevron-right" style="color:#34bf51"></i></span><strong>New Feature!</strong> To show the category description along with the title add <strong> show_category_description="true"</strong> </li>


                            </ul>
                        </td>

                    </tr>
                </tbody></table>
            </div>
         </div>';
    }
}

            public function get_settings($current_section = '') {
                if ('second' == $current_section) {
                    $settings = apply_filters('otfw_section2_settings', array(
                        array(
                            'name' => esc_html__('Table Layout', OTFW::TEXT_DOMAIN),
                            'type' => 'title',

                            'id' => 'otfw_table_config'
                        ),
                        array(
                            'type' => 'otfwlayout',
                            'id' => 'otfwlayout'
                        ),
                        array(
                            'type' => 'sectionend',
                            'id' => 'otfw_table_config'
                        )
                    ));
                } else {
                    include(plugin_dir_path(__DIR__) . 'includes/start-args.php');
                    $settings = apply_filters('otfw_section1_settings', $settings_args);
                }
                return apply_filters('woocommerce_get_settings_' . $this->id, $settings, $current_section);
            }



            public function otfw_admin_field_otfw_show_header($value) {
                $otfw_header_config = get_option('otfw_header_config');
                $otfw_show_header   = get_option('otfw_show_header');
?>
       <button type="button" class="button-secondary" id="f1">Show/Hide Column Settings</button>
        <table  style="display:none" id="form_table_1" class="form-table otfw-admin-table">

    <?php
               OTFW::setVar();
                $otfw_table_config = OTFW::getVar();


		if (is_array($otfw_table_config) || is_object($otfw_table_config)){
                foreach ($otfw_table_config as $rr) {
                     if(true) {
                        $a = $rr['label'];
                        $status = $rr['status'];
						 $true_name = $rr['true_name'];
?>


<?php


?><tbody></td>

 <tr>    <th scope="row" class="titledesc" > Table Column: <p style="font-style: oblique"><?php
                        echo esc_html($rr['label']);
?></p></th>
 <td class="forminp forminp-checkbox otfw_check otfw_check_2"><fieldset>



 <label>

    <input type="checkbox"
           name="<?php echo esc_attr($rr['true_name']); ?>"
		    data-label="<?php echo esc_attr($rr['label']); ?>"

           data-status="<?php echo esc_attr($rr['status']); ?>"
           data-id="<?php echo esc_attr($rr['ID']); ?>"
<?php
                        if (!isset($status ) || true == $status ) {
                            echo 'checked';
                        } else {
                            echo '';
                        }
?>>
    Show column in Order Table</label></fieldset></td>
 <tr><td class="forminp forminp-text"><input type="text" name="otfw_header_config[<?php
                        echo esc_attr($true_name);
?>]" value="<?php

                        if (isset($otfw_header_config[$rr['true_name']] )  ) {

                             echo esc_attr($otfw_header_config[$rr['true_name']]);

                        }else{

							 echo esc_attr($a);
						}

?>" placeholder="Enter alias of title...">


 </td>
 <td class="forminp forminp-checkbox otfw_check"><fieldset>



 <label>
    <input type="hidden" name="otfw_show_header[<?php
                        echo esc_attr($true_name);
?>]"  value="off">
    <input type="checkbox" name="otfw_show_header[<?php
                        echo esc_attr($true_name);
?>]"   default="on" <?php
                        if (!isset($otfw_show_header[$true_name] ) || 'on' == $otfw_show_header[$true_name]  ) {
                            echo 'checked';
                        } else {
                            echo '';
                        }
?>>  Show column heading title</label></fieldset></td></tr> </tbody>

<?php
                    }
                }
			}
?>




         <input type="hidden" class="insert"  id="otfw_table_config_id" name="otfw_table_config" value="" >
       </table><?php
            }
        }
        $settings[] = new OTFW_Settings();
    return $settings;
    }
    add_filter('woocommerce_get_settings_pages', 'OTFW_Add_Tab');
endif;
