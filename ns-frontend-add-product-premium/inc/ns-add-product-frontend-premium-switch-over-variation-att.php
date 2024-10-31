<?php
//used to know if the variation is a color or custom attribute
function ns_switch_over_variations_att($post_id){
	$variation_list_num = null;
	if(isset($_POST["ns-attr-from-list-variation"])){
		$explode = explode(',', sanitize_text_field($_POST["ns-attr-from-list-variation"]));	//getting how many colors for variations user has inserted
		$variation_list_num = sizeof($explode)-1;				//-1 cuz of the blank 
	}
	
	$variation_list_num_custom_attr = null;
	if(isset($_POST["ns-attr-custom-names"])){
		$explode = explode(',' , sanitize_text_field($_POST["ns-attr-custom-names"])); 			//getting how many cus attr for variations user has inserted
		$variation_list_num_custom_attr = sizeof($explode)-1;	//-1 cuz of the blank 
	}
	
	if($variation_list_num > 0){	//here we have a variations on colors
        ns_save_variations($post_id, true, $variation_list_num);
        
	}else if($variation_list_num_custom_attr > 0){	//here we have a variations on custom attributes
		ns_save_variations($post_id, false, $variation_list_num_custom_attr);
	}
}

function ns_save_variations($post_id, $is_color, $num_of_variation){
    $user_id = wp_get_current_user()->ID;
    $i = 0;			//index used to loop over variations

    //the below variables will update the 'main' product metas with variations information
    $ns_max_price_variation_id = null;
    $ns_min_price_variation_id = null;
    $ns_max_variation_price = 0;
    $ns_min_variation_price = 100000;
    $ns_max_variation_regular_price = 0;
    $ns_min_variation_regular_price = 100000;
    $ns_max_variation_sale_price = 0;
    $ns_min_variation_sale_price = 100000;
    $ns_max_regular_price_variation_id = null;
    $ns_min_regular_price_variation_id = null;
    $ns_min_sale_price_variation_id = null;
    $ns_max_sale_price_variation_id = null;
    
    for($i = 1; $i <= $num_of_variation; $i++){
        
        //just creating an incomplete post variation to get the id of the post and use it for next post update
        $variation_post_dummy = array(
            'post_author' => $user_id,
            'post_status' => "publish",
            'post_parent' => $post_id,				//setting the parent
            'post_type' => "product_variation",
        );
        $variation_post_id = wp_insert_post($variation_post_dummy);
        $post_title_complete = 'Variation #'.$variation_post_id.' of '.get_post($post_id)->post_title;
        $post_name_complete =  'product-'.get_post($post_id)->ID.'-variation';
        //Updating variation post to match woocommerce standard
        $variation_post_update = array(
              'ID'           => $variation_post_id,
              'post_title'   => $post_title_complete,
              'post_name' => $post_name_complete,
         );
         
        if(wp_update_post($variation_post_update)!= 0){
            //if the variations post has been correctly inserted, then lets get and insert the fields as post meta
            
            //variation image
            $var_image_id = null;
        
            if(isset($_FILES['ns-thumbnail-var'.$i])){
                if ($_FILES['ns-thumbnail-var'.$i]['name']) {
                    if ($_FILES['ns-thumbnail-var'.$i]['error'] !== UPLOAD_ERR_OK) {  
                        sanitize_file_name($_FILES['ns-thumbnail-var'.$i]['name']);
                        return "upload error : " . $_FILES['ns-thumbnail-var'.$i]['error'];
                    }			
                    $var_image_id = media_handle_upload( 'ns-thumbnail-var'.$i, $variation_post_id );
                    update_post_meta( $variation_post_id, '_thumbnail_id', $var_image_id);      
                }
            }
            
            
            $var_sku = null;
            if(isset($_POST['ns-variation-sku'.$i])){
                $var_sku = sanitize_text_field($_POST['ns-variation-sku'.$i]);
                update_post_meta( $variation_post_id, '_sku', $var_sku);
            }
            
            $var_reg_price = null;
            if(isset($_POST['ns-variation-regular-price'.$i])){
                $var_reg_price = sanitize_text_field($_POST['ns-variation-regular-price'.$i]);
                update_post_meta( $variation_post_id, '_regular_price', $var_reg_price);
                if($var_reg_price <= $ns_min_variation_regular_price){
                    $ns_min_variation_regular_price = $var_reg_price;
                    $ns_min_regular_price_variation_id = $variation_post_id;
                }
                if($var_reg_price >= $ns_max_variation_regular_price){
                    $ns_max_variation_regular_price = $var_reg_price;
                    $ns_max_regular_price_variation_id = $variation_post_id;
                }
            }
            
            $var_sale_price = null;
            if(isset($_POST['ns-variation-sale-price'.$i])){
                $var_sale_price = sanitize_text_field($_POST['ns-variation-sale-price'.$i]);
                update_post_meta( $variation_post_id, '_sale_price', $var_sale_price);
                if($var_sale_price <= $ns_min_variation_regular_price){
                    $ns_min_variation_sale_price = $var_sale_price;
                    $ns_min_sale_price_variation_id = $variation_post_id;
                    $ns_min_price_variation_id = $variation_post_id;	
                    $ns_min_variation_price = $var_sale_price;						
                }
                if($var_sale_price >= $ns_max_variation_regular_price){
                    $ns_max_variation_sale_price = $var_sale_price;
                    $ns_max_sale_price_variation_id = $variation_post_id;
                    $ns_max_price_variation_id = $variation_post_id;
                    $ns_max_variation_price = $var_sale_price;
                }
            }
            
            $var_stock_status = null;
            if(isset($_POST['ns-variation-stock-status'.$i])){
                $var_stock_status = sanitize_text_field($_POST['ns-variation-stock-status'.$i]);
                update_post_meta( $variation_post_id, '_stock_status', $var_stock_status);
            }
            
            $var_weight = null;
            if(isset($_POST['ns-variation-weight'.$i])){
                $var_weight = sanitize_text_field($_POST['ns-variation-weight'.$i]);
                update_post_meta( $variation_post_id, '_weight', $var_weight);
            }
            
            $var_len = null;
            if(isset($_POST['ns-variation-length'.$i])){
                $var_len = sanitize_text_field($_POST['ns-variation-length'.$i]);
                update_post_meta( $variation_post_id, '_length', $var_len);
            }
            
            $var_width = null;
            if(isset($_POST['ns-variation-width'.$i])){
                $var_width = sanitize_text_field($_POST['ns-variation-width'.$i]);
                update_post_meta( $variation_post_id, '_width', $var_width);
            }
            
            $var_height = null;
            if(isset($_POST['ns-variation-height'.$i])){
                $var_height = sanitize_text_field($_POST['ns-variation-height'.$i]);
                update_post_meta( $variation_post_id, '_height', $var_height);
            }
            
            $var_desc = null;
            if(isset($_POST['ns-variation-descritpion'.$i])){
                $var_desc = sanitize_text_field($_POST['ns-variation-descritpion'.$i]);
                update_post_meta( $variation_post_id, '_variation_description', $var_desc);
            }
            
            $var_downloadable = 'no';
            if(isset($_POST["ns-variation-downloadable".$i])){
                $var_downloadable = 'yes';
            }
            
            $var_virtual = 'no';
            if(isset($_POST["ns-variation-virtual".$i])){
                $var_virtual = 'yes';
            }
            
            update_post_meta( $variation_post_id, '_downloadable', $var_downloadable);
            update_post_meta( $variation_post_id, '_virtual', $var_virtual);
            
            //linking attribute
            
                $attr_val = null;
                if(isset($_POST['ns-variation-attributes'.$i])){
                    $attr_val = sanitize_text_field($_POST['ns-variation-attributes'.$i]);
                    update_post_meta($variation_post_id, 'attribute_pa_color', strtolower($attr_val));
                    
                }
                else
                    echo 'Need to select an attribute for this variation';
            
                $attr_val_cus = null;
                $attr_values;

                if(isset($_POST['ns-attribute-values'.($i-1)])){ //$i-1 cuz need to allineate with attribute input-> to change 
                    
                    $attr_values = sanitize_text_field($_POST['ns-attribute-values'.($i-1)]);

                    if(isset($_POST['ns-variation-custom-attributes'.$i])){
                        $attr_val_cus = sanitize_text_field($_POST['ns-variation-custom-attributes'.$i]);
                        update_post_meta($variation_post_id, 'attribute_'.$attr_val_cus, strtolower($attr_values));
                    }	
                    else
                        echo 'Need to select a custom attribute for this variation';
                }

            
            update_post_meta( $variation_post_id, '_price', $var_sale_price);

            

        }
        else 
            echo 'Variation post not inserted. ';
    }
    
    //updating now the calc variables to post
    update_post_meta( $post_id, '_max_price_variation_id', $ns_max_price_variation_id);
    update_post_meta( $post_id, '_min_price_variation_id', $ns_min_price_variation_id);
    update_post_meta( $post_id, '_max_variation_price', $ns_max_variation_price);
    update_post_meta( $post_id, '_min_variation_price', $ns_min_variation_price);
    update_post_meta( $post_id, '_max_variation_regular_price', $ns_max_variation_regular_price);
    update_post_meta( $post_id, '_min_variation_regular_price', $ns_min_variation_regular_price);
    update_post_meta( $post_id, '_max_variation_sale_price', $ns_max_variation_sale_price);
    update_post_meta( $post_id, '_min_variation_sale_price', $ns_min_variation_sale_price);
    update_post_meta( $post_id, '_max_regular_price_variation_id', $ns_max_regular_price_variation_id);
    update_post_meta( $post_id, '_min_regular_price_variation_id', $ns_min_regular_price_variation_id);
    update_post_meta( $post_id, '_min_sale_price_variation_id', $ns_min_sale_price_variation_id);
    update_post_meta( $post_id, '_max_sale_price_variation_id', $ns_max_sale_price_variation_id);
    
    update_post_meta( $post_id, '_price', $var_sale_price);
    //setting the terms to let woocommerce knows is a variation product
    wp_set_post_terms($post_id, 'variable', 'product_type');
    
    
}

?>