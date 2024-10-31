<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_mmv_options()
{
	//COMMENTS
	
	add_option('ns-mmv-percentage', '');
	add_option('ns-mmv-contact-mail-address', '');
	add_option('ns_mmv_add_product_category', '');
}
register_activation_hook( __FILE__, 'ns_mmv_options');

function ns_mmv_register_options_group(){
	/*Field options*/
	//COMMENTS
	register_setting('ns_mmv_options_group', 'ns-mmv-percentage'); 
	register_setting('ns_mmv_options_group', 'ns-mmv-contact-mail-address'); 
	register_setting('ns_mmv_options_group', 'ns_mmv_add_product_category'); 

}
add_action ('admin_init', 'ns_mmv_register_options_group');

?>