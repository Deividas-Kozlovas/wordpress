jQuery(document).ready(function($) {

 	var otfw_ItemArray = [];


 	// function that make Width array
 	function otfw_makewidtharray() {
 		otfw_ItemArray = [];
 		$('#form_table_1 tbody tr .otfw_check_2 input').each(function() {

 			var id = $(this).data('id');
 			var label = $(this).attr('name');
			var status = $(this).prop('checked');


 			otfw_ItemArray.push({
 				ID: id,
 				Width: '',
 				label: label,
				status:status,
 			});

 		});
 	}





 	function otfw_save_main_options_ajax() {
 		if ($('#form_table_1 tbody tr').length) {
 			$('.button-primary.woocommerce-save-button[type=submit]').off("click").on("click", function() {
 				otfw_makewidtharray();

 				//Saving column Width to database options table
 				var i = 0;
 				var obj2 = {};
 				var obj = {};
 				otfw_ItemArray.forEach(function(item) {
 					var key = Object.keys(item)[0];
 					var key2 = Object.keys(item)[1];

 					obj[key] = item[key];
 					obj[[key2]] = item[key2];
 					obj2 = obj;
 					i++;
 				});
 				if (otfw_ItemArray && otfw_ItemArray.length !== 0) {

 					document.getElementById('otfw_table_config_id').value = JSON.stringify(otfw_ItemArray);

 				} else {
 					return;
 				}
 			});
 		} else {
 			return;
 		}
 	}

 	otfw_settings_1();
	otfw_save_main_options_ajax();

 	// Function that toggles the visibility of the table header settings in the "Settings Tab".
 	function otfw_settings_1() {
 		$('#f1').off('click').on('click', function(event, ui) {
 			$("#form_table_1").toggle();
 		});

		if (!$('#otfw_override_shop:checkbox').prop('checked') ) {
		$('#otfw_override_shop_show_cat').parents('tr').hide();
		$('#otfw_show_cat_desc').parents('tr').hide();



	}
	$('#otfw_override_shop:checkbox').on('change', function() {
		if (this.checked) {
			$('#otfw_override_shop_show_cat').parents('tr').fadeIn('slow');
			$('#otfw_show_cat_desc').parents('tr').fadeIn('slow');

		} else  {
			$('#otfw_override_shop_show_cat').parents('tr').fadeOut('slow');
			$('#otfw_show_cat_desc').parents('tr').fadeOut('slow');
		}
	});
 	}

 	// End of Document Ready
 });
