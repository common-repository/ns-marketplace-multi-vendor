<?php
//Saving the upsell products
function ns_linked_products($post_id){
	if(isset($_POST["ns-linked-list"])){
		$to_explode = sanitize_text_field($_POST["ns-linked-list"]);		
		$linked_prod_ids_arr = explode(',', $to_explode);
		update_post_meta($post_id, '_upsell_ids', $linked_prod_ids_arr);
	}
}
?>