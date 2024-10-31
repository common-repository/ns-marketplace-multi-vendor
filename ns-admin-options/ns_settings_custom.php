<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php // PUT YOUR settings_fields name and your input // ?>
<div class="genRowNssdc">
<div class="ns-ctbc-section-container">
	
	<?php
	function page_tabs( $current = 'first' ) {
		$tabs = array(
			'settings'   => __( 'Settings', 'ns-marketplace-multi-vendor' ), 
			'vendors'   => __( 'Vendors', 'ns-marketplace-multi-vendor' ), 
			'orders'  => __( 'Orders', 'ns-marketplace-multi-vendor' )
		);
		$html = '<h2 class="">';
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? 'nav-tab-active' : '';
			$html .= '<a class="nav-tab ' . $class . '" href="?page=ns-marketplace-multi-vendor%2Fns-admin-options%2Fns_admin_option_dashboard.php&tab=' . $tab . '">' . $name . '</a>';
		}
		$html .= '</h2><br><br><hr>';
		echo $html;
	}
	// Code displayed before the tabs (outside)
	// Tabs
	$tab = ( ! empty( $_GET['tab'] ) ) ? esc_attr( $_GET['tab'] ) : 'settings';
	page_tabs( $tab );
	if ( $tab == 'settings' ) {
		// add the code you want to be displayed in the first tab
		echo ns_mmv_settings_tab(); 
		echo '<p><input type="submit" class="button-primary ns-ctbc-submit-button" id="submit" name="submit" value="'.__('Save Changes').'" /></p>';
	}else if ( $tab == 'vendors' ) {
		// add the code you want to be displayed in the first tab
		echo ns_market_place_multi_vendor_get_vendors();
	}else if ( $tab == 'orders' ){
		// add the code you want to be displayed in the second tab
		echo ns_market_place_multi_vendor_show_orders();
	}
	// Code after the tabs (outside)
		
	?>
	</div>		
		<!-- <label><?php _e('Coupon Name', $ns_text_domain) ?></label>
		<input type="text" name="ns-ctbc-coupon-name" id="ns-ctbc-coupon-name" class="ns-ctbc-input" value="<?php echo $ns_coupon_name; ?>"><br>
		
		<label><?php _e('PopUp Title', $ns_text_domain) ?></label>
		<input type="text" name="ns-ctbc-popup-title" id="ns-ctbc-popup-title" class="ns-ctbc-input" value="<?php echo $ns_popup_title; ?>"><br>

		<label><?php _e('PopUp Subtitle', $ns_text_domain) ?></label>
		<input type="text" name="ns-ctbc-popup-subtitle" id="ns-ctbc-popup-subtitle" class="ns-ctbc-input" value="<?php echo $ns_popup_subtitle; ?>"><br>
	

		<div class="ns-enable-privacy-div">
			
			<label class="ns-ctbc-container"><input class="ns-ctbc-checkbox" type="checkbox" name="ns-ctbc-enable-privacy-policy" id="ns-ctbc-checkbox" <?php echo $checked; ?>><span class="ns-ctbc-checkmark"></span></label>
			<label><?php _e('Enable Privacy Policy', $ns_text_domain) ?></label>
			<br><br>
			<div id="ns-show-if-checked" <?php if(get_option('ns-ctbc-enable-privacy-policy')!='on') echo 'style="display: none";';?>>
				<label><?php _e('URL Privacy Policy', $ns_text_domain) ?></label>
				<input type="text" name="ns-ctbc-popup-privacy-policy" id="ns-ctbc-popup-privacy-policy" class="ns-ctbc-input" value="<?php echo $ns_popup_privacy_policy; ?>"><br>
			</div>
		</div> -->
	

</div>

<?php 

function ns_mmv_settings_tab(){
	$ns_perc = get_option('ns-mmv-percentage', '50');
	$ns_mail_address = get_option('ns-mmv-contact-mail-address', '');
	if(!$ns_mail_address)
		$ns_mail_address = get_bloginfo('admin_email');
	
	?>
	<br>
	<div class="ns-mmv-settings-admin">
		<label><?php _e('Percentage (%):', 'ns-marketplace-multi-vendor') ?></label>
		<input type="number" name="ns-mmv-percentage" id="ns-mmv-percentage" required="required" min="1" max="100" class="ns-ctbc-input" value="<?php echo $ns_perc; ?>">
		<span class="description"><?php _e('The amount to be paid to each vendors.', 'ns-marketplace-multi-vendor') ?></span><br><br>
		
		<label><?php _e('Contact Mail Address:', 'ns-marketplace-multi-vendor') ?></label>
		<input type="email" name="ns-mmv-contact-mail-address" id="ns-mmv-contact-mail" required="required" class="ns-ctbc-input" value="<?php echo $ns_mail_address; ?>">
		<span class="description"><?php _e('Please enter the e-mail address to which you want to be contacted. ', 'ns-marketplace-multi-vendor') ?></span><br><br>
		
		<label><?php _e('Vendors can create new product categories?', 'ns-marketplace-multi-vendor') ?></label><br>
		<select name="ns_mmv_add_product_category" id="ns_mmv_add_product_category">
			<option value="choose"><?php _e('Choose', 'ns-marketplace-multi-vendor') ?></option>
			<option value="yes" <?php if(get_option('ns_mmv_add_product_category')=='yes') echo 'selected="selected"'; ?>><?php _e('Yes', 'ns-marketplace-multi-vendor') ?></option>
			<option value="no" <?php if(get_option('ns_mmv_add_product_category')=='no') echo 'selected="selected"'; ?>><?php _e('No', 'ns-marketplace-multi-vendor') ?></option>
		</select><br>
		<span class="description"><?php _e('Choose if vendors can create new product categories. Otherwise remember to create your custom categories!', 'ns-marketplace-multi-vendor') ?></span>
	</div>
	<?php //ns_mmv_add_product_category'); 
}

settings_fields('ns_mmv_options_group'); 
?>