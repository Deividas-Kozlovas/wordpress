<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;

$otfw_table_config = OTFW::getVar();
 $status  = array_column($otfw_table_config, 'status');
 $s = array_filter($status, function($v){

	return $v == true;


	});
$colspan_end = count( $s);

?>
<!-- </tbody> -->
<tbody class="otfw_table_spinner">
	<tr><td colspan="<?php echo  esc_attr($colspan_end) ?>">
<i id="otfw-spinner" class="fas fa-spinner fa-spin fa-3x"></i>
</td>
</tr>
</tbody>
<tfoot>
<tr>
	<td colspan="<?php echo  esc_attr($colspan_end) ?>" class="otfw_footer_total">

		<div class="otfw_label" >
		<h4>
			<?php echo OTFW::otfw_cart_items_total() ?>
		<span>
		</span>
		</h4>
		</div>
		<div class="otfw_review_button">
		<a href="<?php echo esc_url( wc_get_checkout_url() ) ?>"
			class="checkout-button button alt wc-forward" role="button" id ="otfw_checkout_button">

					 <?php
					 $checkout_string =  "" !== get_option('otfw_checkout_string','') ? get_option('otfw_checkout_string') : 'Proceed to checkout' ;
					 echo esc_html__( $checkout_string, 'woocommerce' ) ?></a>

		</div>
	</td>
</tr>
</tfoot>
</table>
</div>

<script>

	var OTFW_Items = <?php echo json_encode(OTFW::instance()->loop->get_loop_items(), JSON_HEX_QUOT | JSON_HEX_TAG) ?>;

</script>
