<?php
/*Service to add the selected image to the gallery*/
add_action( 'wp_ajax_nopriv_ns_add_to_gallery_image', 'ns_add_to_gallery_image' );
add_action( 'wp_ajax_ns_add_to_gallery_image', 'ns_add_to_gallery_image' );
function ns_add_to_gallery_image(){
    if ( $_FILES ) { 
		$files = $_FILES["ns-add-prod-frontend-add-img-gallery"];  
		foreach ($files['name'] as $key => $value) {            
			if ($files['name'][$key]) { 
				//$files['name'] = sanitize_file_name($files['name']);
				$file = array( 
					'name' => $files['name'][$key],
					'type' => $files['type'][$key], 
					'tmp_name' => $files['tmp_name'][$key], 
					'error' => $files['error'][$key],
					'size' => $files['size'][$key]
				); 
				$_FILES = array ("ns-add-prod-frontend-add-img-gallery" => $file); 
				foreach ($_FILES as $file => $array) {              
                    // check to make sure its a successful upload
                    if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) __return_false();
					$file['name'] = sanitize_file_name($file['name']);
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

                    $attach_id = media_handle_upload( $file, $post_id );

                    echo '<div class="ns-inline-flex"><img src="'.wp_get_attachment_url( $attach_id ).'" id="'.$attach_id.'" /></div>';
				}
			} 
		} 
    }

    die();

}
?>