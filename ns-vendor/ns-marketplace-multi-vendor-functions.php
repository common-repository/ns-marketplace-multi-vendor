<?php

function ns_marketplace_multi_vendor_dynamic_shortcode(){
    
    if(!isset($_GET['vendor-id'])) return;
    $user_id = sanitize_text_field($_GET['vendor-id']);
    $args = array(
        'author'            =>  $user_id, 
        'fields'            => 'ids',
        'orderby'           =>  'post_date',
        'order'             =>  'ASC',
        'posts_per_page'    => -1,
        'post_type'         => 'product'
    );
    $current_user_posts = get_posts( $args );
    if(empty($current_user_posts)) return;
    //$total = count($current_user_posts);
    return do_shortcode('[products limit=12 columns="4" paginate=true ids="'.implode(", ", $current_user_posts).'"]');
   
}

function ns_marketplace_multi_vendor_dashboard_products($user_id){
    //get_products
    $args = array(
        'author'            =>  $user_id, 
        'orderby'           =>  'post_date',
        'order'             =>  'ASC',
        'posts_per_page'    =>  -1,
        'post_type'         => 'product'
    );
    $current_user_posts = get_posts( $args );
    $total = count($current_user_posts);
    if($total < 1)
        return '<h2>'.__('Total products: You still haven\'t products', 'ns-marketplace-multi-vendor').'</h2>';

    //echo '<img src="'.plugin_dir_url( __FILE__ ).'../img/plus.png'.'" width="70" class="'.get_the_ID().' ns-cursor" alt=""/>';
    //print table
    $html_to_return = '
        <div class="ns-table-scroll">
            <table class="ns-table-dashboard-products">
                <tr>
                    <th>'.__('Image', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Name', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Category', 'ns-marketplace-multi-vendor').'</th> 
                    <th>'.__('Price', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Edit', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Delete', 'ns-marketplace-multi-vendor').'</th>
                </tr>
                <tr>
                    <h2>'.__('Total products: ', 'ns-marketplace-multi-vendor').$total.'</h2>
                </tr>
        
    ';    
    foreach($current_user_posts as $post){

        if(get_the_post_thumbnail_url($post->ID, 'thumbnail'))
            $img_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        else
            $img_url =  wc_placeholder_img_src();
        $html_to_return .= '
                <tr class="ns-div-vendor-product-'.$post->ID.'">
                    <td><a href="'.get_permalink($post->ID).'"><img src="'.$img_url.'" class="img-responsive" width="150" alt=""/></a></td>
                    <td><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></td>
                    <td>'.wc_get_product_category_list($post->ID).'</td>
                    <td>'.wc_price(get_post_meta( $post->ID, '_price', true )).'</td>
                    <td><img src="'.plugin_dir_url( __FILE__ ).'../img/loader.gif'.'" width="40" class="ns-hide ns-loader-vendor-product-edit-'.$post->ID.'" alt=""/> <i class="far fa-edit fa-2x ns-coursor ns-edit-vendor-product-'.$post->ID.'" onclick="ns_edit_vendor_product('.$post->ID.','.get_current_user_id().', \''.get_permalink( get_option('ns-vendor-add-product-id')  ).'\');"></i></td>
                    <td><img src="'.plugin_dir_url( __FILE__ ).'../img/loader.gif'.'" width="40" class="ns-hide ns-loader-vendor-product-'.$post->ID.'" alt=""/> <i class="far fa-trash-alt fa-2x ns-coursor ns-trash-vendor-product-'.$post->ID.'" onclick="ns_delete_vendor_product('.$post->ID.');"></i>
                        <input type="hidden" id="ns-mmv-wp-nonce-'.$post->ID.'" value="'.wp_create_nonce( 'ns_delete_product' . $post->ID ).'">
                    </td>
                </tr>
                
            ';
        
    } 
    $html_to_return .= '</table></div>';       
     
    
    // global $post;
    // $paged = (get_query_var('paged')) ? get_query_var('paged') : 0;

    // $postsPerPage = 5;
    // $postOffset = $paged * $postsPerPage;

    // $args = array(
    //     'author'            =>  $user_id, 
    //     'orderby'           =>  'post_date',
    //     'order'             =>  'ASC',
    //     'offset'          => $postOffset,
    //     'posts_per_page'  => $postsPerPage,
    //     'post_type'         => 'product'
    // );

    // $postslist = new WP_Query( $args );
    // if ( $postslist->have_posts() ) :
    //     while ( $postslist->have_posts() ) : $postslist->the_post(); 
    //     if(get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'))
    //         $img_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
    //     else
    //         $img_url =  wc_placeholder_img_src();
    //         $html_to_return .= '
                    
    //                 <tr class="ns-div-vendor-product-'.get_the_ID().'">
    //                     <td><img src="'.$img_url.'" class="img-responsive" alt=""/></td>
    //                     <td>'.the_title("", "", FALSE).'</td>
    //                     <td>'.wc_get_product_category_list($postslist->ID).'</td>
    //                     <td>'.wc_price(get_post_meta( get_the_ID(), '_price', true )).'</td>
    //                     <td><i class="fas fa-edit fa-2x ns-coursor" onclick="ns_edit_vendor_product('.get_the_ID().','.get_current_user_id().');"></i></td>
    //                     <td><img src="'.plugin_dir_url( __FILE__ ).'../img/loader.gif'.'" width="40" class="ns-hide ns-loader-vendor-product-'.get_the_ID().'" alt=""/> <i class="far fa-trash-alt fa-2x ns-coursor ns-trash-vendor-product-'.get_the_ID().'" onclick="ns_delete_vendor_product('.get_the_ID().','.get_current_user_id().');"></i></td>
    //                 </tr>
                    
    //             ';
    //     endwhile;
    //     $html_to_return .= '<div class="ns-next-and-prev-div pagination">';
        
    //     $html_to_return .= previous_posts_link( '«' ); 
    //     //$html_to_return .= next_posts_link( '»', $postslist->max_num_pages-1 );
    //     $html_to_return .= '</div>';
    //     wp_reset_postdata();
    // endif;
    // $html_to_return .= '</table>';

    return $html_to_return;

}

function ns_get_vendor_public_name($user_id){
    return get_user_meta($user_id, '_ns_vendor_public_name', true);
}
function ns_get_vendor_paypal_email($user_id){
    return get_user_meta($user_id, '_ns_vendor_paypal_email', true);
}
function ns_get_vendor_name($user_id){
    return get_user_meta($user_id, '_ns_mmv_vendor_name', true);
}
function ns_get_vendor_surname($user_id){
    return get_user_meta($user_id, '_ns_mmv_vendor_surname', true);
}
function ns_get_vendor_contact_email($user_id){
    return get_user_meta($user_id, '_ns_mmv_vendor_contact_email', true);
}

function ns_mmv_is_vendor($user_id){
    if(get_user_meta($user_id, '_ns_is_vendor', true) == 'yes') 
        return true;
    return false;
}


function ns_market_place_multi_vendor_show_orders($vendor_id = null, $products = null, $all = null){

    if($vendor_id){ //if is called for single user
        $html_to_return = '<div class="ns-table-scroll">
            <table class="ns-table-dashboard-products">
                <tr>
                    <th>'.__('Order', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Items(Quantity)', 'ns-marketplace-multi-vendor').'</th> 
                    <th>'.__('Total', 'ns-marketplace-multi-vendor').'</th> 
                    <th>'.__('Date', 'ns-marketplace-multi-vendor').'</th>
                    <th>'.__('Shipped', 'ns-marketplace-multi-vendor').'</th>
                </tr>
        ';

        if(!$all){
            //return all products, defined in ns-marketplace-multi-vendor-oders-fucntions.php
            $products = ns_mmv_get_orders_by_user_id($vendor_id);
            if(empty($products)){
                return '<h2 class="ns-no-orders-to-show">'.__('No orders to show.', 'ns-marketplace-multi-vendor').'</h2>';
            }
        }else if(empty($products))
            return '<h2 class="ns-no-orders-to-show">'.__('No orders to show.', 'ns-marketplace-multi-vendor').'</h2>';
        foreach($products as $product){
            /* ++ Creating Items Line and total price ++ */
            $items_line = '';
            $total_price = 0;
            foreach(unserialize($product->product_ids) as $id){
                $items_line .= '<a href="'.get_permalink($id[0]).'">'.get_the_title($id[0]).'</a>('.$id[1].') ';
                $total_price += $id[2];
            }
            /* ++ End Items Line ++ */
            /* ++ Getting order data ++ */
            $order = new WC_Order($product->order_id);
            $order_date = $order->get_date_created()->date('Y-m-d H:i:s');
            /* ++ End Items Line ++ */
            $html_to_return .='
                <tr id="ns-mmv-'.$product->id.'" class="ns-mmv-orders">
                    <th>'.$product->id.'</th>
                    <th>'.$items_line.'</th> 
                    <th>'.wc_price($total_price).'</th> 
                    <th>'.date('Y-m-d H:i:s', $product->creation_time).'</th>';
                if($product->status != 'WAIT')
                    //$html_to_return .= '<th><i class="fas fa-check"></i></th>';
                        $html_to_return .= '<th><i class="fas fa-check-circle fa-2x ns-green"></i></th>
                    </tr>';
                else{
                    $html_to_return .= '<th><i id="ns-exclamation-'.$product->id.'"class="fas fa-exclamation fa-2x ns-red ns-pointer" onclick="ns_open_order_details('.$product->id.');"></i></th>';
                    $html_to_return .= '</tr>';
                    $html_to_return .= '<tr id="ns-customer-details-'.$product->id.'" class="ns-hide ns-customer-details"><td colspan=5>'.ns_mmv_mark_as_shipped($product->id, $product->buyer_id, $product->order_id).'</td></tr>';
                }
        }

        $html_to_return .= '</div></table>';
        return $html_to_return;


    }else{//if is called for show all vendor orders; ex. in backend
        global $wpdb;
        $customPagHTML     = "";
        $query             = "SELECT * FROM ".$wpdb->prefix . get_option("ns_mmv_db_name");
        $total_query     = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total             = $wpdb->get_var( $total_query );
        $items_per_page = 15;
        $page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset         = ( $page * $items_per_page ) - $items_per_page;
        $result         = $wpdb->get_results( $query . " ORDER BY order_id DESC LIMIT ${offset}, ${items_per_page}" );
        $totalPage         = ceil($total / $items_per_page);

        if($totalPage >= 1){
            $customPagHTML     =  
                '<div>
                    <table style="width: 100%">
                        <tr>
                            <th>'.__('Order', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Vendor', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Items(Quantity)', 'ns-marketplace-multi-vendor').'</th> 
                            <th>'.__('Total', 'ns-marketplace-multi-vendor').'</th> 
                            <th>'.__('Date', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Shipped', 'ns-marketplace-multi-vendor').'</th>
                        </tr>';
                        
                        foreach($result as $product){
                            
                            /* ++ Creating Items Line and total price ++ */
                            $items_line = '';
                            $total_price = 0;
                            foreach(unserialize($product->product_ids) as $id){
                                $items_line .= '<a href="'.get_permalink($id[0]).'">'.get_the_title($id[0]).'</a>('.$id[1].') ';
                                $total_price += $id[2];
                            }
                            /* ++ End Items Line ++ */
                            /* ++ Getting order data ++ */
                            $order = new WC_Order($product->order_id);
                            $order_date = $order->get_date_created()->date('Y-m-d H:i:s');
                            /* ++ End Items Line ++ */
                            $customPagHTML .='
                                <tr id="ns-mmv-'.$product->id.'" class="ns-mmv-orders ns-table-border">
                                    <th>'.$product->id.'</th>
                                    <th>'.ns_get_vendor_public_name($product->vendor_id).'</th>
                                    <th>'.$items_line.'</th> 
                                    <th>'.wc_price($total_price).'</th> 
                                    <th>'.date('Y-m-d H:i:s', $product->creation_time).'</th>';
                                if($product->status != 'WAIT')
                                    //$customPagHTML .= '<th><i class="fas fa-check"></i></th>';
                                        $customPagHTML .= '<th><i class="fas fa-check-circle fa-2x ns-green"></i></th>
                                    </tr>';
                                else{
                                    $customPagHTML .= '<th><i id="ns-exclamation-'.$product->id.'"class="fas fa-exclamation fa-2x ns-red"></i></th>';
                                    $customPagHTML .= '</tr>';
                                    //$customPagHTML .= '<tr id="ns-customer-details-'.$product->id.'" class="ns-hide ns-customer-details"><td colspan=5>'.ns_mmv_mark_as_shipped($product->id, $product->buyer_id, $product->order_id).'</td></tr>';
                                }
                        }
                    $customPagHTML .= '</table>'.paginate_links( array(
                    'base' => add_query_arg( 'cpage', '%#%' ),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $totalPage,
                    'current' => $page
                    )).
                    '<span> ( Page '.$page.' of '.$totalPage.' ) </span>
                </div>';
        }else{
            $customPagHTML = "<h2>".__('No orders to show.', 'ns-marketplace-multi-vendor')."</h2>";
        }
        return $customPagHTML;
    }

}

function ns_mmv_mark_as_shipped($my_order_id, $buyer_id, $order_id){
    	
    
    $order = new WC_Order($order_id);
    $customer_name = $order->get_formatted_billing_full_name();
    
    $billing_address_html = $order->get_formatted_billing_address(); // for printing or displaying on web page
    
    $shipping_address_html = $order->get_formatted_shipping_address(); // for printing or displaying on web page
   
    $html_to_return ='<div class="ns-container-order-details">
        <div class="ns-div-container-order-details">'.__('Customer: ', 'ns-marketplace-multi-vendor').'<br>'.$customer_name.'<br><br></div>
        <div class="ns-div-container-order-details">'.__('Billing address: ', 'ns-marketplace-multi-vendor').'<br>';
        
            if($billing_address_html == '') 
                $html_to_return .= __('No billing address set.', 'ns-marketplace-multi-vendor').'';
            else 
                $html_to_return .= $billing_address_html.'<br><br>';
        $html_to_return .='</div>';
        $html_to_return .='<div class="ns-div-container-order-details">';
            $html_to_return .= __('Shipping address:', 'ns-marketplace-multi-vendor').'<br>';
            if($shipping_address_html == '') 
                $html_to_return .= __('No shipping address set.', 'ns-marketplace-multi-vendor').'<br><br>';
            else 
                $html_to_return .= $billing_address_html.'<br><br>';
        $html_to_return .='</div>';
        $html_to_return .='<div class="ns-div-container-order-details">';
            $html_to_return .= __('Customer Email: ', 'ns-marketplace-multi-vendor').'<br>'.$order->get_billing_email().'<br><br>';
        $html_to_return .='</div>';
        $html_to_return .='<div class="ns-div-container-order-details">';
            $html_to_return .= __('Customer Phone: ', 'ns-marketplace-multi-vendor').'<br>'.$order->get_billing_phone().'<br><br>';
        $html_to_return .='</div>';
    $html_to_return .= '</div>';
    $html_to_return .= '<div>
            <img src="'.plugin_dir_url(__FILE__).'../img/loader.gif" width="40" id="ns-order-details-loader"class="ns-hide">
            <input type="submit" id="ns-order-details-submit" value="'.__('Mark as shipped', 'ns-marketplace-multi-vendor').'" onclick="ns_set_as_shipped_func('.$my_order_id.','.$order_id.');"></div>';
    $html_to_return .= '<div><i class="fas fa-angle-up fa-2x ns-pointer" onclick="ns_close_order_details('.$my_order_id.');"></i></div>';
    
    return $html_to_return;
}

function ns_market_place_multi_vendor_get_filters(){
    //'.__('', 'ns-marketplace-multi-vendor').'
    $html_to_return = '
        <div class="ns_mmv_filters_container">
            <div class="ns_mmv_single_filters">
                <div class="ns-center-div">
                    '.__('STATUS: ', 'ns-marketplace-multi-vendor').'<br>
                    <input type="radio" name="ns-status" value="All">'.__('All', 'ns-marketplace-multi-vendor').'<br>
                    <input type="radio" name="ns-status" value="SHIPPED">'.__('Shipped', 'ns-marketplace-multi-vendor').'<br>
                    <input type="radio" name="ns-status" value="WAIT">'.__('Not yet shipped', 'ns-marketplace-multi-vendor').'
                </div>
            </div>
            <div class="ns_mmv_single_filters">
                <div class="ns-center-div">
                    '.__('DATE: ', 'ns-marketplace-multi-vendor').'<br>
                    <input type="text" class="datepicker" id="ns-filter-orders-datepicker-from" name="ns-filter-orders-datepicker-from" value="" placeholder="From"/>
                    <input type="text" class="datepicker" id="ns-filter-orders-datepicker-to" name="ns-filter-orders-datepicker-to" value="" placeholder="To"/>
                </div>
            </div>
            <div class="ns_mmv_single_filters ns-input-center">
                <div class="ns-center-div">
                    <img id="ns-filter-loader" class="ns-hide" src="'.plugin_dir_url(__FILE__).'../img/loader.gif" width="40">
                    <input type="submit" id="ns-filter-orders-submit" value="'.__('Filter', 'ns-marketplace-multi-vendor').'"">
                </div>    
            </div>
        </div>

    ';
    return $html_to_return;
}

function ns_market_place_multi_vendor_insert_new_vendor($user_id){

    global $wpdb;
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name_vendor');
    $date = new DateTime();
    $date = $date->getTimestamp();
    $wpdb->insert( 
        $table_name, 
        array( 
            'user_id'               => $user_id,
            'total_amount'          => 0,
            'total_amount_unpaid'   => 0,
            'last_modified'         => $date
        ) 
    );
}

function ns_market_place_multi_vendor_get_vendors(){
    global $wpdb;
        $customPagHTML     = "";
        $query             = "SELECT * FROM ".$wpdb->prefix . get_option("ns_mmv_db_name_vendor");
        $total_query     = "SELECT COUNT(1) FROM (${query}) AS combined_table";
        $total             = $wpdb->get_var( $total_query );
        $items_per_page = 15;
        $page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset         = ( $page * $items_per_page ) - $items_per_page;
        $result         = $wpdb->get_results( $query . " ORDER BY last_modified DESC LIMIT ${offset}, ${items_per_page}" );
        $totalPage         = ceil($total / $items_per_page);

        if($totalPage >= 1){
            $customPagHTML     =  
                '<div>
                    <table style="width: 100%">
                        <tr>
                            <th>'.__('ID', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Name', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Contact Mail', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('PayPal Mail', 'ns-marketplace-multi-vendor').'</th>
                            <th>'.__('Total Amount', 'ns-marketplace-multi-vendor').'</th> 
                            <th>'.__('Total Amount Unpaid', 'ns-marketplace-multi-vendor').'</th> 
                            <th>'.__('Mark as paid', 'ns-marketplace-multi-vendor').'</th> 
                        </tr>';
                        
                        foreach($result as $vendor){
                            if($vendor->total_amount_unpaid > 0){
                                $ns_color_unpaid="ns-red";
                                $price = wc_price($vendor->total_amount_unpaid);
                            }else{
                                $ns_color_unpaid = "ns-green";
                                $price = '<i class="fas fa-check ns-green fa-2x"></i>';
                            }
                            $ns_contact_mail = ns_get_vendor_contact_email($vendor->user_id);
                            if(empty($ns_contact_mail))
                                $ns_contact_mail = ns_get_vendor_paypal_email($vendor->user_id);
                            $customPagHTML .='
                                <tr id="ns-mmv-vendor-'.$vendor->id.'" class="ns-mmv-orders ns-table-border">
                                    <th>'.$vendor->id.'</th>
                                    <th>'.ns_get_vendor_public_name($vendor->user_id).'</th>
                                    <th>'.$ns_contact_mail.'</th>
                                    <th>'.ns_get_vendor_paypal_email($vendor->user_id).'</th>
                                    <th>'.wc_price($vendor->total_amount).'</th> 
                                    <th><span id="ns-unpaid-'.$vendor->id.'" class="'.$ns_color_unpaid.'">'.$price.'<span></th>
                                    <th><i class="fas fa-comment-dollar fa-2x ns-cursor" onclick="ns_mark_as_paid_function('.$vendor->id.');"></i></th>
                                </tr>';
                                
                        }
                    $customPagHTML .= '</table>'.paginate_links( array(
                    'base' => add_query_arg( 'cpage', '%#%' ),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $totalPage,
                    'current' => $page
                    )).
                    '<span> ( Page '.$page.' of '.$totalPage.' ) </span>
                </div>';
        }else{
            $customPagHTML = "<h2>".__('No vendors to show.', 'ns-marketplace-multi-vendor')."</h2>";
        }
        return $customPagHTML;
}

function ns_market_place_multi_vendor_account_details(){
    $userId = get_current_user_id();
    $paypal_email = ns_get_vendor_paypal_email($userId);
    $public_name = ns_get_vendor_public_name($userId);
    $vendor_name = ns_get_vendor_name($userId);
    $vendor_surname = ns_get_vendor_surname($userId);
    $contact_email = ns_get_vendor_contact_email($userId);
    $html_to_return = '
        <div class="ns-mmv-account-details">
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('NAME: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-name" name="ns-account-details-name" value="'.$vendor_name.'" placeholder="Your name"/>
            </div>
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('SURNAME: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-surname" name="ns-account-details-surname" value="'.$vendor_surname.'" placeholder="Your surname"/>
            </div>    
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('METHOD PAYMENT: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-payment" name="ns-account-details-payment" value="PayPal" readonly/>   
            </div>  
        </div>
        <div class="ns-mmv-account-details">  
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('PAYPAL EMAIL: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-payment-email" name="ns-account-details-payment-email" value="'.$paypal_email.'"/>   
            </div>  
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('PUBLI NAME: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-public-name" name="ns-account-details-public-name" value="'.$public_name.'"/>   
            </div>  
            <div class="ns-mmv-account-details-inputs ns-input-center">
                '.__('CONTACT MAIL ADDRESS: ', 'ns-marketplace-multi-vendor').'<br>
                <input type="text" id="ns-account-details-contact-mail" name="ns-account-details-contact-mail" value="'.$contact_email.'" placeholder="Your preferred mail address"/>   
            </div> 
        </div>
        
        <div class="ns-mmv-account-details">   
            <div class="ns-mmv-account-details-inputs ns-input-center">
                <img id="ns-details-loader" class="ns-hide" src="'.plugin_dir_url(__FILE__).'../img/loader.gif" width="40">
                <input type="submit" id="ns-details-submit" name="ns-details-submit" value="'.__('Save', 'ns-marketplace-multi-vendor').'"">   
            </div>
        </div>
        
        
    ';
    // <div class="ns-mmv-account-details-inputs ns-input-center">
    //             <img id="ns-details-loader" class="ns-hide" src="'.plugin_dir_url(__FILE__).'../img/loader.gif" width="40">
    //             <input type="submit" id="ns-details-submit" value="'.__('Save', 'ns-marketplace-multi-vendor').'"">   
    //         </div> 
    return $html_to_return;
}

?>