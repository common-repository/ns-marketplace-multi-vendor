<?php
function ns_save_post(){
	/*Checking if user is logged in*/

	$user_id = wp_get_current_user()->ID;
	
	/*Get the inserted product title*/
	$ns_title = "New Product";
	if(isset($_POST["ns-product-name"])){
		$ns_title = sanitize_text_field($_POST["ns-product-name"]);
	}
	
	
	/*Get the inserted product short description*/
	//Add sold by and link to vendor page
	// $url = get_permalink( get_option('ns-vendor-public-page-account-id') );
	// $ns_short_desc_sold_by = __('Sold by ', 'ns-marketplace-multi-vendor');
	// $ns_short_desc_sold_by .= '<a href="'.$url.'?vendor-id='.$user_id.'">'.ns_get_vendor_public_name($user_id).'</a>';

	// $ns_short_desc = null;
	// if(isset($_POST["ns-short-desc-text"])){
	// 	$ns_short_desc = $_POST["ns-short-desc-text"];
	// 	if(!isset($_GET['product-id']))
	// 		$ns_short_desc .= PHP_EOL.$ns_short_desc_sold_by;
	// }else{
	// 	if(!isset($_GET['product-id']))
	// 		$ns_short_desc = PHP_EOL.$ns_short_desc_sold_by;
	// }
	$ns_short_desc = null;
	if(isset($_POST["ns-short-desc-text"])){
		$ns_short_desc = sanitize_text_field($_POST["ns-short-desc-text"]);
	}
	
	/*Get the inserted product post content*/	
	$ns_post_content = null;
	if(isset($_POST["ns-post-content-text"])){
		$ns_post_content = sanitize_text_field($_POST["ns-post-content-text"]);
	}
	
	/*If user wanna activate the reviews*/	
	$ns_is_reviews = "closed";
	if(isset($_POST["ns-comment-status"])){
		$ns_is_reviews = "open";
	}
	
	/*Get the menu order inserted by user*/
	$ns_menu_order = 0;
	if(isset($_POST["ns-menu-order"])){
		$ns_menu_order = sanitize_text_field($_POST["ns-menu-order"]);	
	}
	
	$post = array(
    'post_author' => $user_id,
    'post_content' => $ns_post_content,	
    'post_status' => "publish",
    'post_title' => $ns_title,
    'post_parent' => '',
    'post_type' => "product",
	'post_excerpt' => $ns_short_desc,
	'comment_status' => $ns_is_reviews,
	'menu_order' => $ns_menu_order,
	'post_name' => $ns_title,
);

$post_id = null;
	if(isset($_POST['ns-is-edit-prod'])){	
		/*Update already existing post*/
		$post['ID'] = sanitize_text_field($_POST['ns-is-edit-prod']);
		$post_id = wp_update_post($post, true);
	}	
	else{
		/*Create a new post*/
		$post_id = wp_insert_post( $post, true );
	}

	return $post_id;
}
?>