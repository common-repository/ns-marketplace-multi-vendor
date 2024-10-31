<?php
function ns_add_image($post_id){

	$user_id = wp_get_current_user()->ID;

	if (!function_exists('wp_generate_attachment_metadata')){
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                require_once(ABSPATH . "wp-admin" . '/includes/media.php');
            }
			
	if ($_FILES['ns-thumbnail']['name']) {
		
		if ($_FILES['ns-thumbnail']['error'] !== UPLOAD_ERR_OK) {
			sanitize_file_name($_FILES['ns-thumbnail']['name']);
			return "upload error : " . $_FILES['ns-thumbnail']['error'];
		}

		$attach_id = media_handle_upload( 'ns-thumbnail', $post_id );
		update_post_meta( $post_id, '_thumbnail_id', $attach_id );
		return $attach_id;
	
	}
	return false;
			
}
?>