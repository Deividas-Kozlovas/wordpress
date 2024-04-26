( function( $ ) {
	"use strict";

	jQuery(document).ready(function($){

	$('#primary-menu li.menu-item').addClass('menuhide');
	$('#primary-menu li.menu-item').on('click', function(){
	$(this).removeClass('menuhide');
	});

	$('.mini-toggle').on('click', function(){
	   $(this).parent().toggleClass('menushow');
	});
	$('.single-product input.qty').each(function () {
	  $(this).number();
	});

	$("#besearch").on('click', function(e){
			e.preventDefault();
          $('#bspopup').addClass('popup-box-on');
            });
          
            $("#removeClass").click(function () {
          $('#bspopup').removeClass('popup-box-on');
            });

	}); // document ready

	$.fn.shopKitAccessibleDropDown = function () {
		 var el = $(this);

			    /* Make dropdown menus keyboard accessible */

			  $("button.mini-toggle", el).focus(function() {
			        $(this).parents("li").addClass("befocus");
			  })/*.blur(function() {
			        $(this).parents("li").removeClass("befocus");
			  });*/
	}
	 $("#primary-menu").shopKitAccessibleDropDown();
	
}( jQuery ) );
