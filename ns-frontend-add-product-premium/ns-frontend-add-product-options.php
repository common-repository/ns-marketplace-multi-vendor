<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_add_prod_activate_set_options()
{
	 add_option('ns-code-add-prod-regular-price', '');
	 add_option('ns-code-add-prod-sale-price', '');
	 add_option('ns-code-add-prod-sku', '');
	 add_option('ns-code-add-prod-manage-stock', '');
	 add_option('ns-code-add-prod-stock-status', '');
	 add_option('ns-code-add-prod-sold-ind', '');
	 add_option('ns-code-add-prod-weight', '');
	 add_option('ns-code-add-prod-length', '');
	 add_option('ns-code-add-prod-width', '');
	 add_option('ns-code-add-prod-height', '');
	 add_option('ns-code-add-prod-linked', '');
	 add_option('ns-code-add-prod-attributes', '');
	 add_option('ns-code-add-prod-pur-note', '');
	 add_option('ns-code-add-prod-menu-ord', '');
	 add_option('ns-code-add-prod-reviews', '');
	 add_option('ns-code-add-prod-bubble-title', '');
	 add_option('ns-code-add-prod-cus-tab', '');
	 add_option('ns-code-add-prod-cus-tab-cont', '');
	 add_option('ns-code-add-prod-video', '');
	 add_option('ns-code-add-prod-top-content', '');
	 add_option('ns-code-add-prod-bottom-content', '');
	 add_option('ns-code-add-prod-variations', '');
	 add_option('ns-code-add-prod-post-content', '');
	 add_option('ns-code-add-prod-short-desc', '');
	 add_option('ns-code-add-prod-tags', '');
	 add_option('ns-code-add-prod-image', '');
	 add_option('ns-code-add-prod-categories', '');
	 add_option('ns-code-add-prod-gallery', '');
	 
	foreach (get_editable_roles() as $role_name => $role_info){
		 add_option('ns-choose-role-'.$role_name, ''); 
	}
}

register_activation_hook( __FILE__, 'ns_add_prod_activate_set_options');

function ns_add_prod_register_options_group(){
	/*Field options*/
    register_setting('ns_add_prod_options_group', 'ns-code-add-prod-regular-price'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-sale-price'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-sku'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-manage-stock'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-stock-status'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-sold-ind'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-weight'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-length'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-width'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-height');
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-linked');
    register_setting('ns_add_prod_options_group', 'ns-code-add-prod-attributes'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-pur-note'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-menu-ord'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-reviews'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-bubble-title'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-cus-tab'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-cus-tab-cont'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-video'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-top-content'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-bottom-content');
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-variations');
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-post-content'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-short-desc'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-tags'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-image'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-categories'); 
	register_setting('ns_add_prod_options_group', 'ns-code-add-prod-gallery'); 
	
	foreach (get_editable_roles() as $role_name => $role_info){
		register_setting('ns_add_prod_options_group_choose_user', 'ns-choose-role-'.$role_name); 
	}
	
}

add_action ('admin_init', 'ns_add_prod_register_options_group');

?>