<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! defined( 'ADDPROD_NS_PLUGIN_DIR' ) )
    define( 'ADDPROD_NS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'ADDPROD_NS_PLUGIN_DIR_URL' ) )
    define( 'ADDPROD_NS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );




/* *** plugin options *** */
require_once( ADDPROD_NS_PLUGIN_DIR.'/ns-frontend-add-product-options.php');
// require_once( ADDPROD_NS_PLUGIN_DIR.'/ns-frontend-add-product-add-cat-product-ajax.php');
// require_once( ADDPROD_NS_PLUGIN_DIR.'/ns-frontend-add-product-add-to-gallery-ajax.php');

function ns_add_prod() { 
$args = array(
    'textarea_rows' => 15,
    'teeny' => true,
    'quicktags' => false
);


	/*INCLUDO LE FUNZIONI DI SALVATAGGIO ECC*/
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-save-post.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-save-bubble.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-save-categories.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-save-tags.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-add-image.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-linked-products.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-add-attributes.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-switch-over-variation-att.php');
	require_once( plugin_dir_path( __FILE__ ).'/inc/ns-add-product-frontend-premium-add-gallery-images.php');
				
						/*EDIT VARIABLES SECTION*/
			

	
$edit_id = null;
$is_edit = '';
if(isset($_GET['product-id']) && get_post_field( 'post_author', sanitize_text_field($_GET['product-id']) ) == get_current_user_id()){	
    
    //if we are viewing this page in edit product mode we need
	$edit_id = sanitize_text_field($_GET['product-id']);	    //to know the id of the post to modify, and display it in the hidden input below (ns-is-edit-prod).
																//in this way we can pass the id throgh $_POST;
																//gonna get and store all the metas already existing for that product,
																//in this way I can repopulate all the inputs with their previous values;
    $is_edit = 'yes';
	//External Product 
	$edit_external_url = get_post_meta($edit_id, '_product_url',true);
	$edit_external_btn_text = get_post_meta($edit_id, '_button_text',true);
	/****************/
																
	$edit_name = get_post($edit_id)->post_title;
	$edit_regular_price = get_post_meta($edit_id, '_regular_price',true);
	$edit_sale_price = get_post_meta($edit_id, '_sale_price', true);
	$edit_sku = get_post_meta($edit_id, 'sku', true);
	//$edit_manage_stock = get_post_meta($edit_id, '_manage_stock');
	$edit_stock_status = get_post_meta($edit_id, '_stock_status', true);
	$edit_sold_individually = '';
	$edit_weight = get_post_meta($edit_id, '_weight', true);
	$edit_length = get_post_meta($edit_id, '_length', true);
	$edit_width = get_post_meta($edit_id, '_width', true);
	$edit_height = get_post_meta($edit_id, '_height', true);
	$edit_purchase_note = get_post_meta($edit_id, '_purchase_note', true);
	$edit_menu_order = '';
	$edit_bubble_title = '';
	$edit_cus_tab_title = '';
	$edit_cus_tab_content = '';
	$edit_prod_video = '';
	$edit_prod_video_size = '';
	$edit_top_content = '';
	$edit_bottom_content = '';
	$edit_post_content = get_post($edit_id)->post_content;
	$edit_prod_short_desc = get_post($edit_id)->post_excerpt;
	$edit_image_from_list = get_post_meta($edit_id, '_product_image_gallery', true);
	$edit_image_id = get_post_meta($edit_id, '_thumbnail_id', true);
	//$edit_attr = get_post_meta($edit_id, '_product_attributes', true);
	

	
	$_product = wc_get_product( $edit_id );
	//print_r($_product);
	//if( $_product->is_type( 'variable' ) ) echo 'selected';
	//if( $_product->is_type( 'external' ) ) echo 'selected';
	$edit_attr = $_product->get_attributes();

	
	//img edit tag
	$img_edit_tag_html = wp_get_attachment_image( $edit_image_id );
	
	$edit_tags_term_arr = wp_get_post_terms( $edit_id, 'product_tag' );		//getting all the posts tags as array of WP_Term Object
	if(empty($edit_tags_term_arr)){
		$edit_tags_term_arr = null;
	}
	
	$edit_cat_term_arr_obj = wp_get_post_terms( $edit_id, 'product_cat' );		//getting all the posts categories as array of WP_Term Object
	
	$edit_cat_term_arr = Array();
	foreach($edit_cat_term_arr_obj as $term_obj){								//create a simplier array to check in faster way only the names of categories
		array_push($edit_cat_term_arr, $term_obj->name);
	}
	/*if(empty($edit_cat_term_arr_obj)){
		$edit_cat_term_arr = null;
	}*/

	
							/***********************/
	
}	if(isset($_POST['ns-add-prod-frontend-save-img-gallery'])){
		ns_add_images_to_gallery();
	}															
ob_start(); ?>
<div id="ns-container-add-product-frontend">
	<form name="form1" action="" method="post" class="" enctype="multipart/form-data">
		<input id="ns-is-edit" name="ns-is-edit-prod" type="hidden" value="<?php echo $edit_id; ?>"/>
		<div id="ns-product-data-container" class="ns-big-box">
			<div class="ns-center">
				<h2 class="ns-center"><span>Product Data </span></h2> <span type='button' id='ns-post-prod-data-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
			</div>			
			<div id="ns-product-data-inner-container" class="ns-border-margin">
				<!-- simple or variable product -->
				<div>
					<select id="ns-product-type" name="ns-product-type">
						<option value="simple">Simple product</option>
						<option value="variable" <?php if($is_edit == 'yes'){if( $_product->is_type( 'variable' ) ) echo ' selected'; }?>>Variable product</option>
						<option value="external-product" <?php if($is_edit == 'yes'){if( $_product->is_type( 'external' ) ) echo 'selected'; }?>>External Product/Affiliate</option>
					</select>
				</div>
				
				<div class="ns-left-list-data-container">
					<ul>
						<li id="ns-general" class="ns-active"><a href="#ns-prod-data" class="ns-link"> General</a></li>
						<li id="ns-inventory"><a href="#ns-prod-data" class="ns-link"> Inventory</a></li>
						<li id="ns-shipping" class="ns-external-to-remove"><a href="#ns-prod-data" class="ns-link"> Shipping</a></li>
						<li id="ns-linked-products"><a href="#ns-prod-data" class="ns-link"> Linked Products</a></li>
						<li id="ns-attributes"><a href="#ns-prod-data" class="ns-link"> Attributes</a></li>
						<li id="ns-advanced"><a href="#ns-prod-data" class="ns-link"> Advanced</a></li>
						<li id="ns-extra"><a href="#ns-prod-extra" class="ns-link"> Extra</a></li>
						<li id="ns-variation" <?php if($is_edit == 'yes'){if(!$_product->is_type( 'variable' ) ) echo 'class="ns-hidden"'; }else echo 'class="ns-hidden"';?>><a href="#ns-prod-variation" class="ns-link">Variations</a></li>
					</ul>
				</div>
				<div class="ns-prod-data-tab ns-general">
					<div>						
						<div><label>Product Name</label> <br><input class="ns-input-width" name="ns-product-name" id="ns-product-name" value="<?php if($is_edit == 'yes'){echo($edit_name);}else{echo'';} ?>" placeholder="Product name" type="text" required="true"></div>
						
						<div class="ns-external-product-url-container ns-hidden"><label>Product URL</label> <br><input class="ns-input-width" name="ns-external-product-url" id="ns-external-product-url" value="<?php if($is_edit == 'yes'){echo($edit_external_url);}else{echo'';} ?>" placeholder="http://" type="text" ></div>
						<div class="ns-external-product-url-container ns-hidden"><label>Button Text</label> <br><input class="ns-input-width" name="ns-external-btn-txt" id="ns-external-btn-txt" value="<?php if($is_edit == 'yes'){echo($edit_external_btn_text);}else{echo'';} ?>" placeholder="Buy product" type="text" ></div>
						
						<?php
							if(get_option('ns-code-add-prod-regular-price') != 'on'):
						?>
							<div><label>Regular price (<?php echo  get_woocommerce_currency_symbol();?>)</label> <br><input class="ns-input-width" name="ns-regular-price" id="ns-regular-price" value="<?php if($is_edit == 'yes'){echo($edit_regular_price);}else{echo'';} ?>" placeholder="" type="text" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places."></div>
						<?php endif; ?>
						
						<?php
							if(get_option('ns-code-add-prod-sale-price') != 'on'):
						?>
							<div><label>Sale price (<?php echo  get_woocommerce_currency_symbol();?>)</label> <br><input class="ns-input-width" name="ns-sale-price" id="ns-sale-price" value="<?php if($is_edit == 'yes'){echo($edit_sale_price);}else{echo'';} ?>" placeholder="" type="text" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places."></div>
						<?php endif; ?>
					</div>
				</div>
				<div class="ns-prod-data-tab ns-inventory ns-hidden">
					<div>
					
						<?php
							if(get_option('ns-code-add-prod-sku') != 'on'):
						?>
							<div>
								<label>SKU</label> <br><input class="ns-input-width" name="ns-sku" id="ns-sku" value="<?php if($is_edit == 'yes'){if(isset($edit_sku)){echo($edit_sku);}}else{echo'';} ?>" placeholder="" type="number">
							</div>
						<?php endif; ?>
						
						<?php
							if(get_option('ns-code-add-prod-manage-stock') != 'on'):
						?>
							<div class="ns-external-to-remove">
								<label>Manage Stock?</label><input name="ns-manage-stock" id="ns-manage-stock" class="ns-check-linked" value="no" type="checkbox"> <br><span class="ns-add-product-frontend-span-text">Enable stock management at product level</span>
							</div>
							
							<div id="ns-manage-stock-div" style="display: none;">
								<div>
									<label>Stock quantity</label><br><input class="" name="ns-stock" id="ns-stock" step="any" type="number"> 
								</div>
								<div class="">
								<label>Allow backorders?</label>
									<select id="ns-backorders" name="ns-backorders" class="">
										<option value="no">Do not allow</option>
										<option value="notify">Allow, but notify customer</option>
										<option value="yes" selected="selected">Allow</option>
									</select> 
								</div>
							</div>
						<?php endif; ?>	
						
						<?php
							if(get_option('ns-code-add-prod-stock-status') != 'on'):
						?>
							<div class="ns-external-to-remove">
								<label>Stock Status</label> <br>
								<select id="ns-stock-status" name="ns-stock-status" class="ns-input-width" >
									<option value="instock">In stock</option>
									<option value="outofstock">Out of stock</option>
								</select>
							</div>
						<?php endif; ?>	
						
						<?php
							if(get_option('ns-code-add-prod-sold-ind') != 'on'):
						?>
							<div class="ns-external-to-remove">
								<div style="margin-left: 0px;"><label>Sold individually </label><input class="checkbox ns-check-linked" name="ns-sold-individually" id="ns-sold-individually" value="yes" type="checkbox"> <br><span class="ns-add-product-frontend-span-text">Enable this to only allow one of this item to be bought in a single order</span></div>
							</div>
						<?php endif; ?>	
					</div>				
				</div>
				<div class="ns-prod-data-tab ns-shipping ns-hidden">
					
					<div class="">
						<?php
							if(get_option('ns-code-add-prod-weight') != 'on'):
						?>
							<div>
								<label>Weight (kg)</label><br>
								<input class="ns-input-width" name="ns-weight" id="ns-weight" placeholder="0" type="number" value="<?php if($is_edit == 'yes'){echo($edit_weight);}else{echo'';} ?>" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places.">
							</div>
						<?php endif; ?>	
						
						<div>
							<label>Dimensions (cm)</label>
							<div style="margin-left: 0px;">
								<?php
								if(get_option('ns-code-add-prod-length') != 'on'):
								?>
									<input class="ns-input-width" id="ns-product-length" placeholder="Length" size="6" name="ns-product-length"  type="number" value="<?php if($is_edit == 'yes'){echo($edit_length);}else{echo'';} ?>" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places.">
								<?php endif; ?>	
								
								<?php
								if(get_option('ns-code-add-prod-width') != 'on'):
								?>
									<br><input class="ns-input-width" placeholder="Width" size="6" id="ns-width" name="ns-width"  type="number" value="<?php if($is_edit == 'yes'){echo($edit_width);}else{echo'';} ?>" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places.">
								<?php endif; ?>	
								
								<?php
								if(get_option('ns-code-add-prod-height') != 'on'):
								?>
									<br><input class="ns-input-width" placeholder="Height" size="6" id="ns-height" name="ns-height"  type="number" value="<?php if($is_edit == 'yes'){echo($edit_height);}else{echo'';} ?>" pattern="[0-9]+([\.,][0-9]+)?" title="This should be a number with up to 2 decimal places.">
								<?php endif; ?>	
							</div>
						</div>					
					</div>
					
				</div>

				<div class="ns-prod-data-tab ns-linked-products ns-hidden">
					<?php
					//getting all the posts to loop over and display a list of product to link to
					if(get_option('ns-code-add-prod-linked') != 'on'){
						$ns_args = array(
						    'author'        =>  wp_get_current_user()->ID, 
							'post_status' => 'publish',
							'post_parent' => 0,
							'post_type'   => 'product',
							'posts_per_page' => - 1,
						);
						$related_posts = get_posts($ns_args);
						echo '<div id="ns-inner-linked"> <div><h3>Up-sells</h3><p class="ns-add-product-frontend-span-text">Up-sells are products which you raccomand instead of the currently viewed product</p></div>';
						foreach($related_posts as $rel_post){
							echo('<div>'.$rel_post->post_name.'<input class="checkbox ns-check-linked" name="linked#'.$rel_post->post_name.'" id="'.$rel_post->ID.'" type="checkbox"></div>');
						}				
						echo '</div>';
				    }; ?>	
					<input id="ns-linked-list" class="ns-link-class" name="ns-linked-list" type="hidden"/>
				</div>
				
				<div class="ns-prod-data-tab ns-attributes ns-hidden">
					<?php
					if(get_option('ns-code-add-prod-attributes') != 'on'):
					?>
						<div id="ns-inner-attributes">
							<select id="ns-attribute-taxonomy" name="ns-attribute-taxonomy" class="ns-attribute-taxonomy ns-input-width">
								<option value="ns-cus-prod-att">Custom product attribute</option>
								<!--<option id="ns-color-id" value="ns-color-att">color</option>-->
								<?php
								if ( $attribute_taxonomies = wc_get_attribute_taxonomies() ) {
									foreach ( $attribute_taxonomies as $tax ) {
										if ( $name = wc_attribute_taxonomy_name( $tax->attribute_name ) ) {

											$label = ! empty( $tax->attribute_label ) ? $tax->attribute_label : $tax->attribute_name;
											echo '<option id="ns-'.$label.'-id" value="ns-'.$label.'-att">'.$label.'</option>';
										}
									}
								}
								?>
							</select> 
							<br>
							<?php
								if($is_edit == 'yes'){
									if($edit_attr){
										// foreach($edit_attr as $key => $val){
										// 	$name = $val['name'];
										// 	if((strpos($name, 'pa_') !== false )){
										// 		$name = substr($val['name'], 3);
										// 	}
										// 	echo '<h5>'.$name.'</h5> <p><b>Value:</b> '.$val['value'].'</p><br>';
										// }
										wc_display_product_attributes($_product);
										
									}
								} 
									
							?>
							<br>
							<button id="ns-add-attribute-btn" type="button" class="button">Add</button>
							<input id="ns-attribute-list" name="ns-attribute-list" type="hidden" />
						</div>
					<?php endif; ?>	
					
				</div>
				<div class="ns-prod-data-tab ns-advanced ns-hidden">
					<?php
					if(get_option('ns-code-add-prod-pur-note') != 'on'):
					?>
						<div class="ns-external-to-remove">
							<label>Purchase note</label><textarea name="ns-purchase-note" id="ns-purchase-note" ></textarea>			
						</div>
					<?php endif; ?>	
					<?php
					if(get_option('ns-code-add-prod-menu-ord') != 'on'):
					?>
						<div>
							<label>Menu order</label><br><input class="ns-input-width" name="ns-menu-order" id="ns-menu-order" placeholder="" step="1" type="number">
						</div>
					<?php endif; ?>	
					<?php
					if(get_option('ns-code-add-prod-reviews') != 'on'):
					?>
						<div>
							<label>Enable reviews</label><input class="checkbox ns-check-linked" name="ns-comment-status" id="ns-comment-status" checked="checked" type="checkbox">				
						</div>
					<?php endif; ?>	
				</div>
				<div class="ns-prod-data-tab ns-extra ns-hidden">
					<div id="ns-wc-productdata-options-tab">
						<!--<div>
							<label>Custom Bubble</label>
							<select id="ns-bubble" name="ns-bubble" class="ns-input-width">
								<option value="" selected="selected">Disabled</option>
								<option value="&quot;yes&quot;">Enabled</option>
							</select>
						</div> -->
						<?php
						if(get_option('ns-code-add-prod-bubble-title') != 'on'):
						?>
							<div><label>Custom Bubble Title</label><br><input class="ns-input-width" name="ns-bubble-text" id="ns-bubble-text" value="" placeholder="NEW" type="text"></div>
						<?php endif; ?>	
						
						<?php
						if(get_option('ns-code-add-prod-cus-tab') != 'on'):
						?>
							<div><label>Custom Tab Title</label><br><input class="ns-input-width" value="" name="ns-custom-tab" id="ns-custom-tab" placeholder="" type="text"></div>
						<?php endif; ?>	
						
						<?php
						if(get_option('ns-code-add-prod-cus-tab-cont') != 'on'):
						?>
							<div><label>Custom Tab Content</label><textarea  id="ns-cus-tab-content" name="ns-cus-tab-content" class="" placeholder="Enter content for custom product tab here. Shortcodes are allowed"></textarea></div>
						<?php endif; ?>	
						
						<?php
						if(get_option('ns-code-add-prod-video') != 'on'):
						?>
							<div><div style="margin-left: 0px;"><label>Product Video</label><br><input id="ns-video" name="ns-video" class="" placeholder="https://www.youtube.com/watch?v=Ra_iiSIn4OI" type="text"><br><span class="ns-add-product-frontend-span-text">Enter a Youtube or Vimeo Url of the product video here. We recommend uploading your video to Youtube.</span></div></div>				
							<div><label>Product Video Size</label><br><input id="ns-video-size" name="ns-video-size" class="ns-input-width" placeholder="900x900" type="text"></div>
						<?php endif; ?>	
						
						<?php
						if(get_option('ns-code-add-prod-top-content') != 'on'):
						?>
							<div><label>Top Content</label><textarea id="ns-top-content" name="ns-top-content" placeholder="Enter content that will show after the header and before the product. Shortcodes are allowed"></textarea></div>
						<?php endif; ?>	
						
						<?php
						if(get_option('ns-code-add-prod-bottom-content') != 'on'):
						?>
							<div><label>Bottom Content</label><textarea id="ns-bottom-content" name="ns-bottom-content" placeholder="Enter content that will show after the product info. Shortcodes are allowed"></textarea></div>
						<?php endif; ?>	
					</div>
				</div>
				
				<div class="ns-prod-data-tab ns-variation ns-hidden">
					<?php
					if(get_option('ns-code-add-prod-variations') != 'on'):
					?>
					<div id="ns-inner-variation">
						<div>
							<div id="ns-message">
								<p>Before you can add a variation you need to add some variation attributes on the <strong>Attributes</strong> tab.</p>
								<p>
									<a class="button ns-left" href="https://docs.woocommerce.com/document/variable-product/" target="_blank">Learn more</a>
								</p>
							</div>
							<button id="ns-var-button" type="button" class="button ns-left ns-hidden" name="ns-var-button">Add Variation</button>	
						</div>
					</div>
					<?php endif; ?>	
				</div>
				<input id="ns-variation-list" name="ns-variation-list" type="hidden" value=""/>
			</div>
		</div>
		
		<?php
		if(get_option('ns-code-add-prod-post-content') != 'on'):
		?>
			<div id="ns-post-content" class="ns-big-box">
				<div>
					<h2 class="ns-center">Post Content</h2><span type='button' id='ns-post-content-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
				</div>
				<div id="ns-wp-post-content-div" class="ns-border-margin ns-padding-container">
					<p class="ns-add-product-frontend-span-text">Here you can add the complete description of your product</p>
					<textarea id="ns-post-content-text" name="ns-post-content-text" class="ns-display-block" ><?php if($is_edit == 'yes'){echo($edit_post_content);}else{echo '';} ?></textarea>
				</div>
			</div>
		<?php endif; ?>	
		
		<?php
		if(get_option('ns-code-add-prod-short-desc') != 'on'):
		?>
			<div id="ns-short-desc-container" class="ns-big-box">
				<div>
					<h2 class="ns-center">Product Short Description</h2><span type='button' id='ns-short-desc-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
				</div>
				<div id="ns-wp-editor-div" class="ns-border-margin ns-padding-container">
					<p class="ns-add-product-frontend-span-text">Here you can add a short description to your product</p>
					<textarea id="ns-short-desc-text" name="ns-short-desc-text" class="ns-display-block" ><?php if($is_edit == 'yes'){echo($edit_prod_short_desc);}else{echo'';} ?></textarea>
				</div>
			</div>
		<?php endif; ?>	
		
		<div class="ns-left ns-little-container">
		
			<?php
			if(get_option('ns-code-add-prod-tags') != 'on'):
			?>
				<div id="ns-product-tags" class="ns-little-box ns-margin-right">
					<div>
						<h2 class="ns-center">Product Tags</h2><span type='button' id='ns-prod-tags-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
					</div>
					<div id="ns-prod-tags-div" class="ns-padding-container ns-border-margin">
						<div><input id="ns-new-tag-product" name="ns-new-tag-product"  size="16" value="<?php if($is_edit == 'yes'){if(!empty($edit_tags_term_arr)){foreach($edit_tags_term_arr as $tag){echo($tag->name.',');}}}else{echo'';} ?>" type="text"></div>
						<div>
							<p class="ns-add-product-frontend-span-text">Separate Product Tags with commas</p>
						</div>
					</div>
				</div>
			<?php endif; ?>	
			
			<?php
			if(get_option('ns-code-add-prod-image') != 'on'):
			?>
				<div id="ns-image-container" class="ns-little-box">
					<div>
						<h2 class="ns-center">Product Image</h2><span type='button' id='ns-prod-image-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
					</div>
					<div id = "ns-image-container-0"class="ns-border-margin ns-padding-container">
						<div id="ns-image-container1">
							<?php 
								if($is_edit == 'yes'){
									echo $img_edit_tag_html;
								}
								else{
							?>
								<img id="ns-img-thumbnail" src="<?php echo(wc_placeholder_img_src()); ?>" />
							
							<?php
								}
							?>
						</div>
						<div class="ns-margin-top"><p><input type="file" name="ns-thumbnail" id="ns-thumbnail" /></p></div>
					</div>
				</div>
			<?php endif; ?>	
		</div>
		<div class="ns-left ns-little-container">
		
			<?php
			if(get_option('ns-code-add-prod-categories') != 'on'):
				//getting here all the categories already inserted by user or default ones
				$cat_args = array(
									'hierarchical' => 1,
									'hide_empty' => false,
									'taxonomy' => 'product_cat',
									'parent' => 0
									);
				$all_main_cat = get_categories($cat_args);/*get_terms( array(
										'taxonomy' => 'product_cat',
										'hierarchical' => 1,
										'hide_empty' => false,
										'parent' => 0
									));	*/			
			?>
				<div id="ns-product-categories" class="ns-little-box ns-margin-right">
					<div>
						<h2 class="ns-center">Product Categories</h2><span type='button' id='ns-prod-categories-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
					</div>
					<div id="ns-prod-cat-inner" class="ns-border-margin ns-padding-container">
						<div>
							<table id="ns-cat-din-table">
							<?php		
						
								foreach($all_main_cat as $cat_obj){		
									echo '<tr>';
									echo '<td><input type="checkbox" name="'.$cat_obj->name.'" class="ns-add-product-frontend-ca-checkbox" value="'. $cat_obj->name.'"/>'.$cat_obj->name;
									$parentargs = array(
									'hierarchical' => 1,
									'hide_empty' => false,
									'parent' => $cat_obj->term_id,
									'taxonomy' => 'product_cat'
									);
									$parentcats = get_categories($parentargs);
									/*echo'<pre>';print_r($parentcats); echo '</pre>';*/
									foreach ($parentcats as $cat_child){											
										$checked = '';
										if($is_edit == 'yes'){
											if(in_array($cat_child->name, $edit_cat_term_arr)){
												$checked = 'checked';
											}
										}											
										echo '<div class="ns-subcategory-list-div"><input type="checkbox" name="'.$cat_child->name.'" class="ns-add-product-frontend-ca-checkbox" value="'. $cat_child->name .'"'.$checked.'/>'.$cat_child->name.'</div>';			
										$parentargs2 = array(
										'hierarchical' => 1,
										'hide_empty' => false,
										'parent' => $cat_child->term_id,
										'taxonomy' => 'product_cat'
										);
										$parentcats2 = get_categories($parentargs2);
										/*echo'<pre>';print_r($parentcats); echo '</pre>';*/
										foreach ($parentcats2 as $cat_child2){											
											$checked = '';
											if($is_edit == 'yes'){
												if(in_array($cat_child2->name, $edit_cat_term_arr)){
													$checked = 'checked';
												}
											}											
											echo '<div class="ns-subcategory-list-2lv-div"><input type="checkbox" name="'.$cat_child2->name.'" class="ns-add-product-frontend-ca-checkbox" value="'. $cat_child2->name .'"'.$checked.'/>'.$cat_child2->name.'</div>';			
											$parentargs3 = array(
											'hierarchical' => 1,
											'hide_empty' => false,
											'parent' => $cat_child2->term_id,
											'taxonomy' => 'product_cat'
											);
											$parentcats3 = get_categories($parentargs3);
											/*echo'<pre>';print_r($parentcats); echo '</pre>';*/
											foreach ($parentcats3 as $cat_child3){											
												$checked = '';
												if($is_edit == 'yes'){
													if(in_array($cat_child2->name, $edit_cat_term_arr)){
														$checked = 'checked';
													}
												}											
												echo '<div class="ns-subcategory-list-3lv-div"><input type="checkbox" name="'.$cat_child3->name.'" class="ns-add-product-frontend-ca-checkbox" value="'. $cat_child3->name .'"'.$checked.'/>'.$cat_child3->name.'</div>';			
											}
										
										}
									
									}
									echo '</td>';
									echo '</tr>';
									
								}
							?>
								
							</table>						
						</div>
						<?php 
							if(get_option('ns_mmv_add_product_category') == 'yes')
								echo	'<button id="ns-myBtn-cat" class="button" type="button">Add new product category</button>'; 
						?>
					</div>
				</div>
			<?php endif; ?>	
			
			<?php
			if(get_option('ns-code-add-prod-gallery') != 'on'):
			?>
				<div id="ns-product-gallery" class="ns-little-box">
					<div>
						<h2 class="ns-center">Product Gallery</h2><span type="button" id='ns-prod-gallery-hide-show' class="dashicons dashicons-arrow-down ns-pointer"></span>
					</div>
					<div id="ns-prod-gallery-inner" class="ns-border-margin ns-padding-container">
						<div>
							<p class="ns-add-product-frontend-span-text">Add product gallery images</p>
							 <!-- Trigger/Open The Gallery Modal -->
							<button id="ns-myBtn" class="button ns-left" type="button">Open Gallery</button>
						</div>
					</div>
				</div>
			<?php endif; ?>	
		</div>
		<div style="text-align:center;">
			<button type="submit" class="button" name="submit" style="margin:0;">Save</button>	
		</div>		
</div>

<?php
if(get_option('ns-code-add-prod-gallery') != 'on'):
?>
	<input id="ns-image-from-list" name="ns-image-from-list" type="hidden" value="<?php if($is_edit == 'yes'){if(isset($edit_image_from_list)){echo($edit_image_from_list);}}else{echo'';} ?>" />
<?php endif; ?>	

<?php
if(get_option('ns-code-add-prod-attributes') != 'on'):
?>
	<input id="ns-attr-from-list" name="ns-attr-from-list" type="hidden" value="" />
	<input id="ns-attr-from-list-variation" name="ns-attr-from-list-variation" type="hidden" value="" />
	<input id="ns-attr-from-list-variation-custom" name="ns-attr-from-list-variation-custom" type="hidden" value="" />
	<input id="ns-attr-custom-names" name="ns-attr-custom-names" type="hidden" value="" />
<?php endif; ?>	

</form>	
				<?php //get all the images from wordpress
				if(get_option('ns-code-add-prod-attributes') != 'on'){
					
				
					$query_images_args = array(
						'post_type'      => 'attachment',
						'post_mime_type' => 'image',
						'post_status'    => 'inherit',
						'posts_per_page' => - 1,
					);

					$query_images = new WP_Query( $query_images_args );

					/*All the images are stored in $images, so then i can foreach on them and echo in <img> source*/
					$images = array();
				?>
				<!-- The Gallery Modal -->
				<div id="ns-myModal" class="ns-modal">
				  <!-- Gallery Modal content -->
				  <div class="ns-modal-content">
						<span class="ns-close">x</span>
						<form id="ns-add-img-from-gallery-form" name="ns-add-img-from-gallery-form"  method="post" enctype="multipart/form-data">
							<div class="ns-margin-top"><p><h4>Add images to your gallery (use CTRL + left click to select more images)</h4><input type="file" multiple name="ns-add-prod-frontend-add-img-gallery[]" id="ns-add-prod-frontend-add-img-gallery" /></p></div>
							<button type="submit" class="button ns-add-product-frontend-hidden" id="ns-add-prod-frontend-save-img-gallery" name="ns-add-prod-frontend-save-img-gallery"> Save </button>
							<img id="ns-loader-open-gallery" src="<?php echo plugin_dir_url(__FILE__).'../img/loader.gif';?>" width="40">
						</form>
						<!-- <h4>Select saved images</h4> -->
						<div class="ns-image-container">
						
						</div>
				  </div>
				</div>
				<?php } ?>	
				
				 <!-- The Category Modal -->
				
				<div id="ns-myModal-cat" class="ns-modal">
				 
					<div class="ns-modal-content">				
						<div class="ns-border-margin ns-padding-container">
							<span class="ns-close">x</span>
							<label>Enter a new category name</label>
							<br>
							<input id="ns-cus-cat-product" name="ns-cus-cat-product" class="ns-input-width" type="text" placeholder="Category Name">
							<br>
							<label>Parent category</label>
							<br>
							<?php
								$all_existent_cat = get_terms( array(
									'taxonomy' => 'product_cat',
									'hide_empty' => false,
								));	
							?>
							<select id="ns-cus-cat-parent-select" class="ns-input-width">
								<option value=''>-Parent Category-</option>
								<?php			
									foreach($all_existent_cat as $cat_obj){		
										echo '<option value="'.$cat_obj->name.'">'.$cat_obj->name.'</option>';
									}
								?>
							</select>
							<button type="button" class="button" id="ns-cus-cat-btn" >Save</button>	
						</div>
					</div>
				</div>
				
<?php
if(get_option('ns-code-add-prod-attributes') != 'on'):
?>
	<input id="ns-color-att-list"  type="hidden" value="<?php foreach(ns_get_all_color_terms() as $val){echo ($val.',');} ?>" />
<?php endif; ?>
<?php 
$ns_html_to_return = ob_get_clean();

	if(isset($_POST['submit']))
	{
			$ns_post_id = ns_save_product('yes');
		if(!$ns_post_id){			//error found, return empty html;
			echo '
						<div class="ns-add-prod-result">
							<span class="ns-add-prod-response" ><i class="fas fa-times-circle ns-red fa-2x ns-margin-right"></i>Error: cannot add product..</span>
						</div>';
			return  $ns_html_to_return;
		}
		else{
			if($_POST['ns-is-edit-prod']!=''){

				echo '
				<div class="ns-add-prod-result">
					<span class="ns-add-prod-response" ><i class="fas fa-check-circle fa-2x ns-green ns-margin-right" ></i>Your <a href="'.get_permalink($edit_id).'">&nbsp;product&nbsp;</a> has been updated.</span>
				</div>';
			}
			else{
				echo '
						<div class="ns-add-prod-result">
							<span class="ns-add-prod-response" ><i class="fas fa-check-circle fa-2x ns-green ns-margin-right" ></i>Your <a href="'.get_permalink($ns_post_id).'">&nbsp;product&nbsp;</a> has been added.</span>
						</div>';
			}
			
		}
	}
	
	return  $ns_html_to_return;
}  




/*******************************************************/
//									SAVE PRODUCT
/*******************************************************/
function ns_save_product($return_post_id = 'no'){ //modify in vendor, added $return_post_id variable
	/*Create a new post or update an existing one*/

	$post_id = ns_save_post();

	if(is_wp_error( $post_id )){
		return false;
	}


	/*Product data*/

	$external_url = null;
	if(isset($_POST["ns-external-product-url"])){
		$external_url = sanitize_text_field($_POST["ns-external-product-url"]);
	}

	$external_btn_txt = null;
	if(isset($_POST["ns-external-btn-txt"])){
		$external_btn_txt = sanitize_text_field($_POST["ns-external-btn-txt"]);
	}

	$regular_price = null;
	 if(isset($_POST["ns-regular-price"])){
		
		if(is_numeric( $_POST["ns-regular-price"] ) || $_POST["ns-regular-price"] == ''){
			$regular_price = sanitize_text_field($_POST["ns-regular-price"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
	}
	$sale_price = null;
	 if(isset($_POST["ns-sale-price"])){
		
		if(is_numeric( $_POST["ns-sale-price"] ) || $_POST["ns-sale-price"] == ''){
			$sale_price = sanitize_text_field($_POST["ns-sale-price"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
		
	 }
	$sku = null;
	 if(isset($_POST["ns-sku"])){
		 $sku = sanitize_text_field($_POST["ns-sku"]);
	 }
	 
	$manage_stock = null;
	$stock_quantity = null;
	$stock_back_orders = "no";
	 if(isset($_POST["ns-manage-stock"])){
		$manage_stock = sanitize_text_field($_POST["ns-manage-stock"]);
		if(is_numeric( $_POST["ns-stock"] ) || $_POST["ns-stock"] == ''){
			$stock_quantity = sanitize_text_field($_POST["ns-stock"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
		$stock_back_orders = sanitize_text_field($_POST["ns-backorders"]);
	 }

	 $stock_status = null;
	 if(isset($_POST["ns-stock-status"])){
		$stock_status = sanitize_text_field($_POST["ns-stock-status"]);
	 }
		
	$sold_individually = null; 
	if(isset($_POST["ns-sold-individually"])){
		$sold_individually = sanitize_text_field($_POST["ns-sold-individually"]);
	}

	$weight = null;
	 if(isset($_POST["ns-weight"])){
		if(is_numeric( $_POST["ns-weight"] ) || $_POST["ns-weight"] == ''){
			$weight = sanitize_text_field($_POST["ns-weight"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
	 }
	 
	$length = null;
	 if(isset($_POST["ns-product-length"])){
		if(is_numeric( $_POST["ns-product-length"] ) || $_POST["ns-product-length"] == ''){
			 $length = sanitize_text_field($_POST["ns-product-length"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
	 }
	 
	$width = null;
	 if(isset($_POST["ns-width"])){	
		if(is_numeric( $_POST["ns-width"] ) || $_POST["ns-width"] == ''){
			$width = sanitize_text_field($_POST["ns-width"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
	}

	$height = null;
	 if(isset($_POST["ns-height"])){	
		if(is_numeric( $_POST["ns-height"] ) || $_POST["ns-height"] == ''){
			$height = sanitize_text_field($_POST["ns-height"]);
		}
		else{
			wp_delete_post( $post_id, true );
			return false;
		}
	 }
	  
	 /* $shipping_class = null; 
	  if(isset($_POST["ns-product-shipping-class"])){
		$shipping_class = $_POST["ns-product-shipping-class"];
	  }*/
	  
	$purchase_note = null; 
	 if(isset($_POST["ns-purchase-note"])){
		$purchase_note = sanitize_text_field($_POST["ns-purchase-note"]);
	 }

	if($stock_status)
		update_post_meta( $post_id, '_stock_status', $stock_status);
	if($regular_price)
		update_post_meta( $post_id, '_regular_price',  $regular_price);
	if($sale_price)
		update_post_meta( $post_id, '_sale_price', $sale_price );
	if($purchase_note)
		update_post_meta( $post_id, '_purchase_note', $purchase_note  );

	//External Product
	if($external_url)
		update_post_meta( $post_id, '_product_url', $external_url  );
	
	if($external_btn_txt)
		update_post_meta( $post_id, '_button_text', $external_btn_txt  );

	if($external_url && $external_btn_txt){
		wp_set_object_terms($post_id, 'external', 'product_type');
	}
	/***************/

	update_post_meta( $post_id, '_featured', "no" );
	if($weight)
		update_post_meta( $post_id, '_weight', $weight );
	if($length)
		update_post_meta( $post_id, '_length', $length );
	if($width)
		update_post_meta( $post_id, '_width', $width );
	if($height)
		update_post_meta( $post_id, '_height', $height );
	if($sku)
		update_post_meta( $post_id, '_sku', $sku);

	update_post_meta( $post_id, '_sale_price_dates_from', "" );
	update_post_meta( $post_id, '_sale_price_dates_to', "" );

	if($sale_price)
		update_post_meta( $post_id, '_price', $sale_price );
	else
		update_post_meta( $post_id, '_price', $regular_price );
	if($sold_individually)
		update_post_meta( $post_id, '_sold_individually', $sold_individually );

	if($manage_stock == "yes"){
		update_post_meta( $post_id, '_manage_stock', $manage_stock );
		update_post_meta( $post_id, '_stock', $stock_quantity );
		update_post_meta( $post_id, '_backorders', $stock_back_orders );
	}

	update_post_meta( $post_id, '_visibility', 'visible' );
	update_post_meta( $post_id, 'total_sales', '0');

	 
	/*
	wp_set_object_terms( $post_id, 'Races', 'product_cat' );
	wp_set_object_terms($post_id, 'simple', 'product_type');
	update_post_meta( $post_id, '_downloadable', 'yes');
	update_post_meta( $post_id, '_virtual', 'yes');
	*/

	/*Bubbles*/
	ns_save_bubble($post_id);

	/*Categories*/
	ns_save_categories($post_id);

	/*Tags*/
	ns_save_tags($post_id);

	/*Images*/
	$ns_attachment_id = ns_add_image($post_id);

	//if($ns_attachment_id)
		

	/*Linked products*/
	ns_linked_products($post_id);

	/*Attributes*/
	ns_add_attributes($post_id);

	/*Variations*/
	ns_switch_over_variations_att($post_id);

	/*Gallery*/
	ns_add_gallery_images($post_id);
	if($return_post_id == 'no')
		return true;
	return $post_id;
}


/*Used to get all the colors already inserted by user*/
function ns_get_all_color_terms(){
	$term_array = Array();
	$term_list = get_terms( 'pa_color');
	foreach($term_list as $classTerm){
		array_push($term_array, $classTerm->name);
	}
	return $term_array;
}



