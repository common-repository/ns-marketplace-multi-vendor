<?php

function ns_mmv_update_vendors_db($product_author, $quantity, $price){
    global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name_vendor');

    $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE user_id = $product_author", OBJECT );
    // print_r($result);

    if($result != null ){
        $total_amount = $result[0]->total_amount + $quantity * $price;
        $percentage = get_option('ns-mmv-percentage');
        if(!$percentage)
            $percentage = 50;
        $total_amount_unpaid = $result[0]->total_amount_unpaid + ( $percentage / 100 * $quantity * $price);
        $wpdb->update( 
            $table_name, 
            array( 
                'total_amount'          => $total_amount,
                'total_amount_unpaid'   => $total_amount_unpaid
            ),
            array(
                'id'            => $result[0]->id
            )
        );
        return;
    }
}

function ns_mmv_insert_or_update_order_db($vendor_id, $product_id, $buyer_id, $quantity, $order_id, $price){
    global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name');
    // $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE order_id = $order_id", OBJECT );
    //         if($result != null) return;
   

    $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE buyer_id = $buyer_id AND vendor_id = $vendor_id AND order_id = $order_id AND status = 'WAIT'", OBJECT );
    // print_r($result);

    if($result == null ){
        $product_ids = serialize(array(array($product_id, $quantity, $price)));
        $date = new DateTime();
        $date = $date->getTimestamp();
        $wpdb->insert( 
            $table_name, 
            array( 
                'vendor_id'     => $vendor_id, 
                'product_ids'   => $product_ids,
                'status'        => 'WAIT', //WAIT waiting to ship 
                'buyer_id'      => $buyer_id,
                'order_id'      => $order_id,
                'creation_time' => $date
            ) 
        );
        return;
    }else{
        
        $products_array = unserialize($result[0]->product_ids);
        array_push($products_array, array($product_id, $quantity, $price));
        $wpdb->update( 
            $table_name, 
            array( 
                'product_ids'   => serialize($products_array)
            ),
            array(
                'id'            => $result[0]->id
            )
        );
        return;
    }
}

function ns_mmv_order_payment_complete( $order_id ){

    $ns_refresh = get_post_meta($order_id, '_ns_order_refresh_thank_you', true);
    if($ns_refresh != 'yes'){
        global $ns_refresh;
        $ns_refresh = 'on';
        // Getting an instance of the WC_Order object from a defined ORDER ID
        $order = wc_get_order( $order_id ); 
        //product authors array, needed to send email
        $productAuthors = array();
        // Iterating through each "line" items in the order
        foreach ($order->get_items() as $item_id => $item_data) {
            // Get an instance of corresponding the WC_Product object
            $product = $item_data->get_product();
            $quantity = $item_data->get_quantity();
            $product_id = $product->get_id(); // Get the product id
            $product_author = get_post_field( 'post_author', $product_id );
            array_push($productAuthors, $product_author);
            $buyer_id = $order->get_user_id();
            $price = $product->get_price();
            ns_mmv_insert_or_update_order_db($product_author, $product_id, $buyer_id, $quantity, $order_id, $price); 
            ns_mmv_update_vendors_db($product_author, $quantity, $price); 
        }
        add_post_meta($order_id, '_ns_order_refresh_thank_you', 'yes', true);
        $productAuthors = array_unique($productAuthors);
        require_once( plugin_dir_path( __FILE__ ).'email/ns-mmv-base-simple.php');
        foreach ($productAuthors as $productAuthor) {
            $email = ns_get_vendor_contact_email($productAuthor);
            if($email == '')
                $email = ns_get_vendor_paypal_email($productAuthor);
            $products = array();
            foreach ($order->get_items() as $item_id => $item_data) {
                // Get an instance of corresponding the WC_Product object
                $product = $item_data->get_product();
                $product_id = $product->get_id();
                $product_author = get_post_field( 'post_author', $product_id );
                if($product_author == $productAuthor){
                    $to_insert = array(
                        $product_id,
                        $item_data->get_quantity(),
                        $product->get_price()
                    );
                    array_push($products, $to_insert);
                }
            } 
            echo $email;
            wp_mail($email, 'New Order', ns_mmv_mail_template_base_simple($products, ns_get_vendor_public_name($product_author), $template_color = null, 'ciao'), array('Content-Type: text/html; charset=UTF-8'));
        }
        
       
    }
   

}
add_action( 'woocommerce_thankyou', 'ns_mmv_order_payment_complete', 10, 1);


function ns_mmv_get_orders_by_user_id($vendor_id, $status = null, $dateFrom = null, $dateTo = null, $return_type = 'OBJECT'){


    $vendor_id = sanitize_text_field($vendor_id);
    if($status != null)
        $status = sanitize_text_field($status);
    if($dateFrom != null)
        $dateFrom = sanitize_text_field($dateFrom);
    if($dateTo != null)
        $dateTo = sanitize_text_field($dateTo);

    global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name');
    
    if($status == null && $dateFrom == null && $dateTo == null){
        $time = time()-2.628e+6;
        //standard query only orders until 1 month ago
        $result = $wpdb->get_results( "
            SELECT * FROM {$table_name} 
            WHERE vendor_id = $vendor_id
            AND creation_time > $time
            ORDER BY order_id DESC", $return_type 
        );
        return $result;
    }

    if($status == 'All' && $dateFrom == null && $dateTo == null){
        $result = $wpdb->get_results( "
            SELECT * FROM {$table_name} 
            WHERE vendor_id = $vendor_id
            ORDER BY order_id DESC", $return_type 
        );
        return $result;
    }
    if($status != null && ($dateFrom == null || $dateTo == null)){
        
        $result = $wpdb->get_results( "
            SELECT * FROM {$table_name} 
            WHERE vendor_id = $vendor_id 
            AND status = '$status'
            ORDER BY order_id DESC", $return_type 
        );
        return $result;
    }
    $date = new DateTime($dateFrom);
    $dateFrom = $date->getTimestamp();
    $date = new DateTime($dateTo);
    $dateTo = $date->getTimestamp()+86399;
    if($status == 'All')
        $query_status = 'status != \'All\'';
    else
        $query_status = 'status = \''.$status.'\'';
    $query = "
    SELECT * FROM {$table_name} 
    WHERE vendor_id = $vendor_id 
    AND $query_status
    AND creation_time BETWEEN $dateFrom AND $dateTo
    ORDER BY order_id DESC";
    // AND creation_time > $dateFrom 
    // AND creation_time < $dateTo
    $result = $wpdb->get_results( $query, $return_type 
    );
    return $result;
}

function ns_set_order_as_shipped($order_id){
    $order_id = sanitize_text_field($order_id);
    global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name');
    $wpdb->update( 
        $table_name, 
        array( 
            'status'   => 'SHIPPED'
        ),
        array(
            'id'            => $order_id
        )
    );
    return;

}

?>