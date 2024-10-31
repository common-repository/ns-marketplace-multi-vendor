<?php
function ns_add_gallery_images($post_id){
	$images_ids = null;
	if(isset($_POST["ns-image-from-list"])){
		$images_ids = sanitize_text_field($_POST["ns-image-from-list"]);
		update_post_meta( $post_id, '_product_image_gallery', $images_ids );
	}
}

function ns_add_images_to_gallery(){

	if ( $_FILES ) { 
		$files = $_FILES["ns-add-prod-frontend-add-img-gallery"];  
		foreach ($files['name'] as $key => $value) {            
			if ($files['name'][$key]) { 
				$file = array( 
					'name' => sanitize_file_name($files['name'])[$key],
					'type' => $files['type'][$key], 
					'tmp_name' => $files['tmp_name'][$key], 
					'error' => $files['error'][$key],
					'size' => $files['size'][$key]
				); 
				// $_FILES = array ("ns-add-prod-frontend-add-img-gallery" => $file); 
				// foreach ($_FILES as $file => $array) {              
				// 	$newupload = ns_add_multiple_images_handle_attachment($file, 0); 
				// }
			} 
		} 
    }
	return true;

}

function ns_add_multiple_images_handle_attachment($file_handler,$post_id,$set_thu=false) {
  // check to make sure its a successful upload
  if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  $attach_id = media_handle_upload( $file_handler, $post_id );

  return $attach_id;
}
?>