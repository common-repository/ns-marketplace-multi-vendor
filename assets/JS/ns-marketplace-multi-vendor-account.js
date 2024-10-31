function ns_open_order_details(order_id){
	jQuery('.ns-customer-details').fadeOut('fast');
	jQuery('#ns-customer-details-'+order_id).fadeIn('slow');
}
function ns_close_order_details(order_id){
	jQuery('#ns-customer-details-'+order_id).fadeOut('fast');
}

jQuery(document).ready(function($) {
	
	$("#ns-filter-orders-datepicker-from").click(function() {
		$(this).datepicker();
    	$(this).datepicker("show");
	});
	$("#ns-filter-orders-datepicker-to").click(function() {
		$(this).datepicker();
    	$(this).datepicker("show");
	});
	
	$("#ns-submit-become-vendor").click(function() {
		$("#ns_user_name").removeClass("ns-margin-error");
		$("#ns_user_email").removeClass("ns-margin-error");
		$(".ns-error-msg").hide();
		$(".ns-success-msg").hide();
		$("#ns-submit-become-vendor").hide();
		$("#ns-image-loader-registration").show();
		ns_public_name = $("#ns_user_name").val();
		ns_paypal_email = $("#ns_user_email").val();
		if(ns_public_name == ''){
			$("#ns_user_name").addClass("ns-margin-error");
			$(".ns-error-msg").show();
			$("#ns-image-loader-registration").hide();
			$("#ns-submit-become-vendor").show();
		}else if(ns_paypal_email == ''){
			$("#ns_user_email").addClass("ns-margin-error");
			$(".ns-error-msg").show();
			$("#ns-image-loader-registration").hide();
			$("#ns-submit-become-vendor").show();
		}else{
			$.ajax({
				url: ns_registration_as_vendor.ajax_url, 
				type : 'POST',
				data : {
					action : 'ns_marketplace_multi_vendor_ajax_registration',
					ns_public_name : ns_public_name,
					ns_paypal_email : ns_paypal_email
					
				},
				success: function(result){
					$("#ns-image-loader-registration").hide();
					$("#ns-submit-become-vendor").show();
					if(result == 'done'){
						$(".ns-success-msg").show();
					}
				}
			});
		}
	});
	$("#ns-become-vendor-registration").click(function() {
		if($(this).is(":checked")) {
			$(".ns-mmv-reg-custom-fields").show(300);
		} else {
			$(".ns-mmv-reg-custom-fields").hide(200);
		}
	});
		
});