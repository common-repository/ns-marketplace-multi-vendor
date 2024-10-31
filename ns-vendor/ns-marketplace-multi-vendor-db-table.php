<?php

$mmv_db_version = '1.0'; // rembember to change db version for future update. 
/*
"the activation function registered with register_activation_hook() is not called when a plugin is updated. 
So to run the above code after the plugin is upgraded, you need to check the plugin db version on another hook,
 and call the function manually if the the database version is old." Codex.
*/

function ns_marketplace_multi_vendor_orders_table() {
	global $wpdb;
	global $mmv_db_version;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$table_name = $wpdb->prefix . 'ns_mmv_db_table_orders';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "
	
		CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			vendor_id mediumint(9) NOT NULL,
			product_ids longtext NOT NULL,
			buyer_id mediumint(9) NOT NULL,
			order_id mediumint(9) NOT NULL,
			creation_time int(32) NOT NULL,
			status text,
			PRIMARY KEY  (id)
		) $charset_collate;";
	
	dbDelta( $sql );
	
	$table_name = $wpdb->prefix . 'ns_mmv_db_table_vendors';
	$sql = "
		CREATE TABLE $table_name (
			id mediumint(9) AUTO_INCREMENT,
			user_id mediumint(9) UNIQUE,
			total_amount float,
			total_amount_unpaid float,
			last_modified int(32) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;
	";
	add_option( 'ns_mmv_db_name', 'ns_mmv_db_table_orders' );
	add_option( 'ns_mmv_db_name_vendor', 'ns_mmv_db_table_vendors' );
	
	
	dbDelta( $sql );
	update_option( 'ns_mmv_db_version', $mmv_db_version );
}


$installed_ver = get_option( "ns_mmv_db_version" );

if ( $installed_ver != $mmv_db_version ) {
	ns_marketplace_multi_vendor_orders_table();	
}
?>