<?php

//DELETING PRODUCT
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_delete_product', 'ns_marketplace_multi_vendor_ajax_delete_product' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_delete_product', 'ns_marketplace_multi_vendor_ajax_delete_product' );
function ns_marketplace_multi_vendor_ajax_delete_product(){
    if(!isset($_POST['ns_product_id'])){
        echo 'missing data';
        die;
    }   
    
    
    $ns_product_id = sanitize_text_field($_POST['ns_product_id']); 
    $ns_current_user_id = get_current_user_id();
    if (!wp_verify_nonce( $_POST['ns_mmv_wp_nonce'], 'ns_delete_product' . $ns_product_id)){
        echo 'You are not allowed';
        die;
    } 
    //checking if current user is post author
    $post_author_id = get_post_field( 'post_author', $ns_product_id);
    if($post_author_id != $ns_current_user_id){
        echo 'You are not allowed';
        die;
    }    
    
    //deleting product
    if(current_user_can('delete_posts', $ns_product_id)){
        wp_delete_post( $ns_product_id );
        echo 'product deleted';
    }else
        echo 'You are not allowed';
    die;
    
}

// function ns_marketplace_multi_vendor_ajax_delete_product(){
//     if(!isset($_POST['ns_product_id']) && !isset($_POST['ns_current_user_id'])){
//         echo 'missing data';
//         die;
//     }    
//     $ns_product_id = sanitize_text_field($_POST['ns_product_id']); 
//     $ns_current_user_id = sanitize_text_field($_POST['ns_current_user_id']);
//     //checking if current user is post author
//     $post_author_id = get_post_field( 'post_author', $ns_product_id);
//     if($post_author_id != $ns_current_user_id){
//         echo 'You are not allowed';
//         die;
//     }    
    
//     //deleting product
//     wp_delete_post( $ns_product_id );
//     echo 'product deleted';
//     die;
    
// }

//EDITING PRODUCT
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_edit_product', 'ns_marketplace_multi_vendor_ajax_edit_product' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_edit_product', 'ns_marketplace_multi_vendor_ajax_edit_product' );
function ns_marketplace_multi_vendor_ajax_edit_product(){
    if(!isset($_POST['ns_product_id']) && !isset($_POST['ns_current_user_id'])){
        echo 'missing data';
        die;
    }    
    $ns_product_id = sanitize_text_field($_POST['ns_product_id']); 
    $ns_current_user_id = sanitize_text_field($_POST['ns_current_user_id']);
    
    //checking if current user is post author
    $post_author_id = get_post_field( 'post_author', $ns_product_id );
    if($post_author_id != $ns_current_user_id){
        echo 'You are not allowed';
        die;
    }    
    echo 'product ready';
    die;
    
}

//ADD USERS AS VENDOR
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_registration', 'ns_marketplace_multi_vendor_ajax_registration' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_registration', 'ns_marketplace_multi_vendor_ajax_registration' );
function ns_marketplace_multi_vendor_ajax_registration(){
    //put your required fields here
    if(!isset($_POST['ns_public_name']) || !isset($_POST['ns_paypal_email'])){
        echo 'missing data';
        die;
    }    
    $ns_public_name = sanitize_text_field($_POST['ns_public_name']);
    $ns_paypal_email = sanitize_email($_POST['ns_paypal_email']);
    update_user_meta(get_current_user_id(), '_ns_is_vendor', 'yes');
    update_user_meta(get_current_user_id(), '_ns_vendor_public_name', $ns_public_name);
    update_user_meta(get_current_user_id(), '_ns_vendor_paypal_email', $ns_paypal_email);
    ns_market_place_multi_vendor_insert_new_vendor(get_current_user_id());
    $args = array(
        'ID'    => get_current_user_id(),
        'role'  => 'ns_vendor'
    );
    $result = wp_update_user($args);
    echo 'done';
    die;
    
}

//SET AS SHIPPED
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_set_as_shipped', 'ns_marketplace_multi_vendor_ajax_set_as_shipped' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_set_as_shipped', 'ns_marketplace_multi_vendor_ajax_set_as_shipped' );
function ns_marketplace_multi_vendor_ajax_set_as_shipped(){
    //put your required fields here
    if(!isset($_POST['my_order_id']) || !isset($_POST['order_id'])){
        echo 'missing data';
        die;
    }

    $my_order_id = sanitize_text_field($_POST['my_order_id']);
    $order_id = sanitize_text_field($_POST['order_id']);
    
    ns_set_order_as_shipped($my_order_id);
    delete_post_meta($order_id, '_ns_order_refresh_thank_you');
    //TODO set order to completed
    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
    echo 'done';
    die;
    
}
//ORDERS FILTER
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_orders_filter', 'ns_marketplace_multi_vendor_ajax_orders_filter' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_orders_filter', 'ns_marketplace_multi_vendor_ajax_orders_filter' );
function ns_marketplace_multi_vendor_ajax_orders_filter(){
    //put your required fields here
    if(!isset($_POST['ns_radio_status'])){
        echo json_encode(array('missing data'));
        die;
    }
    $ns_radio_status = sanitize_text_field($_POST['ns_radio_status']);
    $ns_date_from = sanitize_text_field($_POST['ns_date_from']);
    $ns_date_to = sanitize_text_field($_POST['ns_date_to']);
    //defined in ns_marketplace_multi_vendor_orders_functions
    $result = ns_mmv_get_orders_by_user_id(get_current_user_id(),$ns_radio_status, $ns_date_from, $ns_date_to, 'OBJECT');
    $table = ns_market_place_multi_vendor_show_orders(get_current_user_id(), $result, 'All');
    echo json_encode(array('done',$result, $table));
    die;
    
}

//ORDERS FILTER
add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_save_account_details', 'ns_marketplace_multi_vendor_ajax_save_account_details' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_save_account_details', 'ns_marketplace_multi_vendor_ajax_save_account_details' );
function ns_marketplace_multi_vendor_ajax_save_account_details(){
    //put your required fields here
    if( !isset($_POST['ns_name']) || !isset($_POST['ns_surname']) || 
        !isset($_POST['ns_contact_email']) || !isset($_POST['ns_email']) || 
        !isset($_POST['ns_public_name'])){
        echo 'missing data';
        die;
    }
    update_user_meta(get_current_user_id(), '_ns_mmv_vendor_name', sanitize_text_field($_POST['ns_name']));
    update_user_meta(get_current_user_id(), '_ns_mmv_vendor_surname', sanitize_text_field($_POST['ns_surname']));
    update_user_meta(get_current_user_id(), '_ns_vendor_paypal_email', sanitize_email($_POST['ns_email']));
    update_user_meta(get_current_user_id(), '_ns_vendor_public_name', sanitize_text_field($_POST['ns_public_name']));
    update_user_meta(get_current_user_id(), '_ns_mmv_vendor_contact_email', sanitize_email($_POST['ns_contact_email']));
    echo 'done';
    die;
    
}

add_action( 'wp_ajax_ns_marketplace_multi_vendor_ajax_mark_as_paid', 'ns_marketplace_multi_vendor_ajax_mark_as_paid' );
add_action( 'wp_ajax_nopriv_ns_marketplace_multi_vendor_ajax_mark_as_paid', 'ns_marketplace_multi_vendor_ajax_mark_as_paid' );
function ns_marketplace_multi_vendor_ajax_mark_as_paid(){
	global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name_vendor');
    $user_id = sanitize_text_field($_POST['ns_vendor_id']);
    $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE id = $user_id", OBJECT );
    if($result != null ){
        $wpdb->update( 
            $table_name, 
            array( 
                'total_amount_unpaid'   => '0'
            ),
            array(
                'id'            => $result[0]->id
            )
        );
    }
    echo 'done';
	die;
}

?>