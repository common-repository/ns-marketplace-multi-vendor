<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ns_marketplace_multi_vendor_get_account_page(){
    if(!isset($_GET['vendor-id']))
        ns_redirect_if_not_logged_in(home_url());
    $public_name = ns_get_vendor_public_name(sanitize_text_field($_GET['vendor-id']));
    return '
            <div class="ns-div-title-public-page"><h1>'.$public_name.' Shop</h1></div><br><br>
        ';
}

function ns_market_place_multi_vendor_registration(){
    $html_to_return = '

    <h2>'.__('REGISTRATION', 'ns-marketplace-multi-vendor').'</h2><br>
    <div>
        <div class="ns_form_vendor_registration ns-center">
            <input type="text" name="ns_user_name" placeholder="'.__('Public Name', 'ns-marketplace-multi-vendor').'" id="ns_user_name" class="input" />
            <input type="email" name="ns_user_email" placeholder="'.__('PayPal E-Mail', 'ns-marketplace-multi-vendor').'" id="ns_user_email" class="input"  />
            <div class="ns-success-msg ns-hide">
                <i class="fa fa-check"></i>
                '.__('Registration completed', 'ns-marketplace-multi-vendor').'
            </div>
            <div class="ns-error-msg ns-hide">
                <i class="fa fa-times-circle"></i>
                '.__('Check your input', 'ns-marketplace-multi-vendor').'
            </div>
            <img src="'.plugin_dir_url(__FILE__).'../img/loader.gif" width="40" id="ns-image-loader-registration" class="ns-center ns-hide">
            <input type="submit" value="Become Vendor" id="ns-submit-become-vendor-mmv" />
        </div>
    </div>
    ';

    return $html_to_return;
}
function ns_market_place_multi_vendor_return_vendor_obj_by_id($user_id){
    global $wpdb;
    $user_id = sanitize_text_field($user_id);
    $table_name = $wpdb->prefix . get_option( 'ns_mmv_db_name_vendor');
    $result = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE user_id = $user_id", OBJECT );
    if($result != null )
        return $result[0];
    return null;
}


function ns_market_place_multi_vendor_account_settings_if_vendor(){

    $html_to_return = '<h2>'.__('Your account', 'ns-marketplace-multi-vendor').'</h2>';
    //defined in ns-marketplace-multi-vendor-functions.php
    $html_to_return .= ns_market_place_multi_vendor_account_details(get_current_user_id());
    $vendor_obj = ns_market_place_multi_vendor_return_vendor_obj_by_id(get_current_user_id());
    //$total_amount_unpaid = ns_market_place_multi_vendor_get_total_amount_unpaid(get_current_user_id());
    if($vendor_obj){
        $html_to_return .= 
            '<div class="ns-mmv-account-details">
                <div class="ns-mmv-account-details-inputs ns-input-center">
                    <span>'.__('Your profit is fixed to: ', 'ns-marketplace-multi-vendor').get_option('ns-mmv-percentage', '50').'%'.'</h2><br>
                    <span>'.__('Total amount: ', 'ns-marketplace-multi-vendor').wc_price($vendor_obj->total_amount).'</h2><br>
                    <span>'.__('Total amount unpaid (your earn): ', 'ns-marketplace-multi-vendor').wc_price($vendor_obj->total_amount_unpaid).'</h2><br>
                </div>
            </div>';
    }
    $ns_mail_address = get_option('ns-mmv-contact-mail-address', '');
	if(!$ns_mail_address)
        $ns_mail_address = get_bloginfo('admin_email');
    $html_to_return .= 
        '<div class="ns-mmv-account-details">
            <div class="ns-mmv-account-details-inputs ns-input-center">
                <span>'.__('Any questions? Contact us at: ', 'ns-marketplace-multi-vendor').$ns_mail_address.'</h2><br>
            </div>
        </div>';
    //defined in ns-marketplace-multi-vendor-functions.php
    $html_to_return .= ns_market_place_multi_vendor_get_filters();
    //defined in ns-marketplace-multi-vendor-functions.php
    $html_to_return .= ns_market_place_multi_vendor_show_orders(get_current_user_id());
    return $html_to_return;
}

?>