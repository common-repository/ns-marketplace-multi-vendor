<?php
function ns_save_categories($post_id){
	$ns_cat_array = array();
	
	$all_existent_cat = get_terms( array(
										'taxonomy' => 'product_cat',
										'hide_empty' => false,
									));	
							
	foreach($all_existent_cat as $cat_obj){		
		/*already saved categories*/
		$remove_spaces = str_replace(' ', '_', $cat_obj->name);
		if(isset($_POST[$remove_spaces])){
			$cat = sanitize_text_field($_POST[$remove_spaces]);
			array_push($ns_cat_array, $cat);
		}

		/*set product categories*/
		if($ns_cat_array){
			wp_set_object_terms($post_id, $ns_cat_array, 'product_cat');
		}
	
	}
	
}
?>