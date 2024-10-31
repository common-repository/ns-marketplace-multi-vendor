function ns_mark_as_paid_function(vendor_id){
    if(confirm("Have you really paid all 'Total Amount Unpaid'?")){
		//$.ajax({.    ns-delteing-product
		//jQuery('.ns-div-vendor-product-'+ns_product_id).addClass('ns-deleting-product');
		// jQuery('.ns-div-vendor-product-'+ns_product_id).fadeTo('slow', 0.4);
		// jQuery('.ns-trash-vendor-product-'+ns_product_id).hide();
		// jQuery('.ns-loader-vendor-product-'+ns_product_id).show();
		
		jQuery.ajax({
			url: ns_mark_as_paid.ajax_url, 
			type : 'POST',
			data : {
				action : 'ns_marketplace_multi_vendor_ajax_mark_as_paid',
				ns_vendor_id : vendor_id
			},
			success: function(result){
				if(result == 'done'){
                    jQuery('#ns-unpaid-'+vendor_id).fadeOut('slow');
                    jQuery('#ns-unpaid-'+vendor_id).removeClass("ns-red");
                    jQuery('#ns-unpaid-'+vendor_id).addClass("ns-green");
                    jQuery('#ns-unpaid-'+vendor_id).html('<i class="fas fa-check ns-green fa-2x"></i>');
                    jQuery('#ns-unpaid-'+vendor_id).fadeIn('slow');
                }else{
					alert('Try again!');
				}
				
			}
		});
	}
}
jQuery(document).ready(function($) {
	
    // $('#ns-ctbc-checkbox').change(function () {
    //     if ($(this).is(":checked")) {
    //         $('#ns-show-if-checked').fadeIn('slow');
    //     } else {
    //         $('#ns-show-if-checked').fadeOut('slow');
    //     }
    // });

});


  