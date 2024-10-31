/////////////////////////////////////////////////////

function ns_delete_vendor_product(ns_product_id){
	if(confirm("Are you sure you want to delete this product?")){
		//$.ajax({.    ns-delteing-product
		//jQuery('.ns-div-vendor-product-'+ns_product_id).addClass('ns-deleting-product');
		jQuery('.ns-div-vendor-product-'+ns_product_id).fadeTo('slow', 0.4);
		jQuery('.ns-trash-vendor-product-'+ns_product_id).hide();
		jQuery('.ns-loader-vendor-product-'+ns_product_id).show();
		ns_mmv_wp_nonce = jQuery('#ns-mmv-wp-nonce-'+ns_product_id).val();
		jQuery.ajax({
			url: ns_delete_product.ajax_url, 
			type : 'POST',
			data : {
				action : 'ns_marketplace_multi_vendor_ajax_delete_product',
				ns_product_id : ns_product_id,
				ns_mmv_wp_nonce : ns_mmv_wp_nonce,
			},
			success: function(result){
				if(result == 'product deleted')
					jQuery('.ns-div-vendor-product-'+ns_product_id).fadeOut('slow');
				else{
					jQuery('.ns-div-vendor-product-'+ns_product_id).fadeTo('slow', 1);
					jQuery('.ns-loader-vendor-product-'+ns_product_id).hide();
					jQuery('.ns-trash-vendor-product-'+ns_product_id).show();
				}
			}
		});
	}
	
}

function ns_edit_vendor_product(ns_product_id, ns_current_user_id, ns_edit_page){

	//$.ajax({.    ns-delteing-product
	//jQuery('.ns-div-vendor-product-'+ns_product_id).addClass('ns-deleting-product');
	jQuery('.ns-div-vendor-product-'+ns_product_id).fadeTo('slow', 0.4);
	jQuery('.ns-edit-vendor-product-'+ns_product_id).hide();
	jQuery('.ns-loader-vendor-product-edit-'+ns_product_id).show();
	
	jQuery.ajax({
		url: ns_edit_product.ajax_url, 
		type : 'POST',
		data : {
			action : 'ns_marketplace_multi_vendor_ajax_edit_product',
			ns_product_id : ns_product_id,
			ns_current_user_id : ns_current_user_id,
		},
		success: function(result){
			if(result == 'product ready')
				window.location.replace(ns_edit_page + "?product-id=" + ns_product_id);
			else{
				jQuery('.ns-div-vendor-product-'+ns_product_id).fadeTo('slow', 1);
				jQuery('.ns-loader-vendor-product-'+ns_product_id).hide();
				jQuery('.ns-trash-vendor-product-'+ns_product_id).show();
			}
		}
	});
	
}

function ns_set_as_shipped_func(my_order_id, order_id){
	//jQuery('#ns-customer-details-'+order_id).fadeOut('fast');
	jQuery('#ns-order-details-submit').hide();
	jQuery('#ns-order-details-loader').show();
	jQuery.ajax({
		url: ns_set_as_shipped.ajax_url, 
		type : 'POST',
		data : {
			action : 'ns_marketplace_multi_vendor_ajax_set_as_shipped',
			my_order_id : my_order_id,
			order_id : order_id,
		},
		success: function(result){
			
			jQuery('#ns-order-details-loader').hide();
			jQuery('#ns-order-details-submit').show();
			if(result == 'done'){
				jQuery('#ns-customer-details-'+my_order_id).fadeOut('fast');
				//jQuery('#ns-exclamation-'+order_id).hide
				jQuery('#ns-exclamation-'+my_order_id).replaceWith('<i class="fas fa-check-circle fa-2x ns-green"></i>');
			}
		}
	});
}

/////////////////////////////////////////////////////
jQuery(document).ready(function($) {
	$("#ns-filter-orders-submit").click(function() {
		$("#ns-filter-orders-submit").hide();
		$("#ns-filter-loader").show();
		ns_radio_status = $(".ns_mmv_single_filters input[type='radio']:checked").val();
		ns_date_from = $('#ns-filter-orders-datepicker-from').val();
		if(ns_date_from == '') ns_date_from=null;
		ns_date_to = $('#ns-filter-orders-datepicker-to').val();
		if(ns_date_to == '') ns_date_to=null;

		//alert(ns_date_to);
		$.ajax({
			url: ns_order_filter.ajax_url, 
			type : 'POST',
			data : {
				action : 'ns_marketplace_multi_vendor_ajax_orders_filter',
				ns_radio_status : ns_radio_status,
				ns_date_from : ns_date_from,
				ns_date_to : ns_date_to
				
			},
			success: function(result){
				$("#ns-filter-loader").hide();
				$("#ns-filter-orders-submit").show();
				result = JSON.parse(result);
				if(result[0] == 'done'){
					$('.ns-table-scroll').remove();
					$('.ns-no-orders-to-show').remove();
					$('.ns_mmv_filters_container').after(result[2]);
				}else{
					alert('Check your input');
				}
			}
		});

	});

	//SAVE DETAILS ACCOUNT
	$("#ns-details-submit").click(function() {
		$("#ns-details-submit").hide();
		$("#ns-details-loader").show();
		ns_name = $("#ns-account-details-name").val();
		ns_surname = $("#ns-account-details-surname").val();
		ns_email = $("#ns-account-details-payment-email").val();
		ns_contact_email = $("#ns-account-details-contact-mail").val();
		ns_public_name = $("#ns-account-details-public-name").val();
		if(ns_name == '' || ns_surname == '' || ns_email == '' || ns_public_name == '' || ns_contact_email == ''){
			alert('Check your inputs, all fields are required!');
			$("#ns-details-loader").hide();
			$("#ns-details-submit").show();
		}	
		else{
			$.ajax({
				url: ns_save_account_details.ajax_url, 
				type : 'POST',
				data : {
					action : 'ns_marketplace_multi_vendor_ajax_save_account_details',
					ns_name : ns_name,
					ns_surname : ns_surname,
					ns_email : ns_email,
					ns_public_name : ns_public_name,
					ns_contact_email : ns_contact_email,
					
				},
				success: function(result){
					$("#ns-details-loader").hide();
					$("#ns-details-submit").show();
					if(result == 'done'){
						
					}else{
						alert('An error occurred, check your inputs!');
					}
				}
			});
		}
	});
	
	$("#ns-submit-become-vendor-mmv").click(function() {
		$("#ns_user_name").removeClass("ns-margin-error");
		$("#ns_user_email").removeClass("ns-margin-error");
		$(".ns-error-msg").hide();
		$(".ns-success-msg").hide();
		$("#ns-submit-become-vendor-mmv").hide();
		$("#ns-image-loader-registration").show();
		ns_public_name = $("#ns_user_name").val();
		ns_paypal_email = $("#ns_user_email").val();
		if(ns_public_name == ''){
			$("#ns_user_name").addClass("ns-margin-error");
			$(".ns-error-msg").show();
			$("#ns-image-loader-registration").hide();
			$("#ns-submit-become-vendor-mmv").show();
		}else if(ns_paypal_email == ''){
			$("#ns_user_email").addClass("ns-margin-error");
			$(".ns-error-msg").show();
			$("#ns-image-loader-registration").hide();
			$("#ns-submit-become-vendor-mmv").show();
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
					$("#ns-submit-become-vendor-mmv").show();
					if(result == 'done'){
						$("#ns-submit-become-vendor-mmv").prop('disabled', true);
						$(".ns-success-msg").show();
						$("#ns-image-loader-registration").show();
						setTimeout(function(){
							location.reload();
						  }, 3000);

					}else{
						$(".ns-error-msg").show();
					}
				}
			});
		}
	});
		
});


  