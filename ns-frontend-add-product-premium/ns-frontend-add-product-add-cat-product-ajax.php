<?php
/*Service for ajax call to add a custom category*/
add_action( 'wp_ajax_nopriv_ns_add_cat_product_function', 'ns_add_cat_product_function' );
add_action( 'wp_ajax_ns_add_cat_product_function', 'ns_add_cat_product_function' );
function ns_add_cat_product_function(){
	if(isset($_POST['name'])){
		
		$new_cat_name = sanitize_text_field($_POST['name']);
		$parent_cat = false;
		
		if(isset($_POST['parent']) && $_POST['parent'] != ''){
			$parent_cat = sanitize_text_field($_POST['parent']);
		}
		if(term_exists($new_cat_name, 'product_cat') == null){
			
			$args = '';
			if($parent_cat != false){
				$t = get_term_by('name', $parent_cat, 'product_cat');
				$args = array('parent' => $t ->term_id);
			}
			$arr_term_tax = wp_insert_term( $new_cat_name, 'product_cat',$args );
			update_term_meta($arr_term_tax['term_id'], 'product_count_product_cat', '0'); 
			echo $new_cat_name;
		}
		else{
			header('HTTP/1.1 500 Internal Server ');
			header('Content-Type: application/json; charset=UTF-8');
			die(json_encode(array('message' => 'Category Already Exist!')));
		}	
	}
	else{
		header('HTTP/1.1 500 Internal Server ');
        header('Content-Type: application/json; charset=UTF-8');
		die(json_encode(array('message' => 'ERROR')));
	}
	
	die();
}

?>