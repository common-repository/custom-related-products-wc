jQuery(document).ready(function(){

	function CRPQV_prettyPhoto_Load() {
	    jQuery("a.zoom").prettyPhoto({
	        hook: 'data-rel',
	        social_tools: false,
	        theme: 'pp_woocommerce',
	        horizontal_padding: 20,
	        opacity: 0.8,
	        deeplinking: false
	    });
	    jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
	        hook: 'data-rel',
	        social_tools: false,
	        theme: 'pp_woocommerce',
	        horizontal_padding: 20,
	        opacity: 0.8,
	        deeplinking: false,
	    });
	}


	jQuery('body').on('click','.evince_ocqkvwbtn',function() {
		jQuery('body').addClass("evince_qview_popup_body");
		jQuery('body').append('<div class="evince_loading"><img src="'+ object_name +'/images/'+ evince_qw_popup_loader +'" class="evince_loader"></div>');
		var loading = jQuery('.evince_loading');
		loading.show();

		var id = jQuery(this).data("id");
		var current = jQuery(this);
		jQuery.ajax({
			url:ajax_url,
			type:'POST',
			data:'action=evince_productsquick&popup_id_pro=' + id,
			success : function(response) {
				var loading = jQuery('.evince_loading');
				loading.remove();
				jQuery("#evince_qview_popup").css("display","block");
				jQuery("#evince_qview_popup").html(response);
				// Variation Form
                var form_variation = jQuery("#evince_qview_popup").find('.variations_form');
                form_variation.each( function() {
                    jQuery( this ).wc_variation_form();
                });
                
                //form_variation.trigger( 'check_variations' );
                //form_variation.trigger( 'reset_image' );
                CRPQV_prettyPhoto_Load();
			},
			error: function() {
				alert('Error occured');
			}
		});
	   return false;
    });


	var modal = document.getElementById("evince_qview_popup");
	var span = document.getElementsByClassName("evince_qview_close")[0];
	jQuery(document).on('click','.evince_qview_close',function(){
		jQuery("#evince_qview_popup").css("display","none");
		jQuery('body').removeClass("evince_qview_popup_body");
	});


	window.onclick = function(event) {
	  if (event.target == modal) {
		modal.style.display = "none";
		jQuery('body').removeClass("evince_qview_popup_body");
	  }
	}
});