<?php
/*
Plugin Name: NS Marketplace Multi Vendor
Description: This plugin allows you to create your vendor
Version: 1.1.4
Author: NsThemes
Author URI: http://www.nsthemes.com
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ns-marketplace-multi-vendor
Domain Path: /i18n
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 
 * @author        PluginEye
 * @copyright     Copyright (c) 2019, PluginEye.
 * @version         1.0.0
 * @license       https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * PLUGINEYE SDK
*/

require_once('plugineye/plugineye-class.php');
$plugineye = array(
    'main_directory_name'       => 'ns-marketplace-multi-vendor',
    'main_file_name'            => 'ns-marketplace-multi-vendor.php',
    'redirect_after_confirm'    => 'admin.php?page=ns-marketplace-multi-vendor%2Fns-admin-options%2Fns_admin_option_dashboard.php',
    'plugin_id'                 => '245',
    'plugin_token'              => 'NWNmZmI4OTg4ZjZmYjNjYjgxYzcyZTNjNDRhOWM2YTJmZGE5NDk1MmFiMzRlMGIwYTg5YjBlN2NmYjYzMzBlZDM3MWEwZWRhNjM2YmM=',
    'plugin_dir_url'            => plugin_dir_url(__FILE__),
    'plugin_dir_path'           => plugin_dir_path(__FILE__)
);

$plugineyeobj245 = new pluginEye($plugineye);
$plugineyeobj245->pluginEyeStart();      


/*========================================================*/
/*					   REQUIRE FILES				      */
/*========================================================*/
require_once( plugin_dir_path( __FILE__ ).'ns-marketplace-multi-vendor-options.php');
require_once( plugin_dir_path( __FILE__ ).'ns-admin-options/ns-admin-options-setup.php');
/*vendor pages*/
require_once( plugin_dir_path( __FILE__ ).'ns-vendor/ns-marketplace-multi-vendor-functions.php');
require_once( plugin_dir_path( __FILE__ ).'ns-vendor/ns-marketplace-multi-vendor-ajax-functions.php');
require_once( plugin_dir_path( __FILE__ ).'ns-vendor/ns-marketplace-multi-vendor-db-table.php');
require_once( plugin_dir_path( __FILE__ ).'ns-vendor/ns-marketplace-multi-vendor-orders-functions.php');
require_once( plugin_dir_path( __FILE__ ).'ns-vendor/ns-marketplace-multi-vendor-account.php');

require_once( plugin_dir_path(__FILE__).'ns-frontend-add-product-premium/ns-frontend-add-product-add-cat-product-ajax.php');
require_once( plugin_dir_path(__FILE__).'ns-frontend-add-product-premium/ns-frontend-add-product-add-to-gallery-ajax.php');


/*========================================================*/
//  frontend CSS.			          
/*========================================================*/
function ns_mmv_css( $hook ) { 
    wp_enqueue_style('ns-mmv-style-custom', plugin_dir_url( __FILE__ ). '/ASSETS/CSS/style.css');
    wp_enqueue_style( 'all-min', plugin_dir_url( __FILE__ ). '/ASSETS/CSS/all.min.css', array(), '1.0.0' ); 
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_style( 'ns-apf-option-css-a-page', plugin_dir_url( __FILE__ ) . '/ASSETS/CSS/ns-apf-css-front-page.css');
    if(is_page(get_option('nns-vendor-become-vendor-id'))){
        wp_enqueue_style( 'ns-multi-vendor-table-media-query', plugin_dir_url( __FILE__ ) . '/ASSETS/CSS/ns-multi-vendor-table.css');
        wp_enqueue_style( 'ns-multi-vendor-datepicker', plugin_dir_url( __FILE__ ) . '/ASSETS/CSS/ns-mmv-datepicker.css');
    }
    if(is_page(get_option('nns-vendor-become-vendor-id'))){
        wp_enqueue_style( 'ns-multi-vendor-registration', plugin_dir_url( __FILE__ ) . '/ASSETS/CSS/ns-multi-vendor-registration.css');
        wp_enqueue_style( 'ns-multi-vendor-table-media-query', plugin_dir_url( __FILE__ ) . '/ASSETS/CSS/ns-multi-vendor-table.css');
    }
}
add_action( 'wp_enqueue_scripts', 'ns_mmv_css' );

   

/*========================================================*/
//  frontend JS: 	          
/*========================================================*/
function ns_mmv_js( $hook ) {
    //wp_enqueue_script( 'ns-apf-option-js-page', plugin_dir_url( __FILE__ ) . '/ASSETS/JS/ns-apf-js-front-page.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'ns-mmv-js-custom', plugin_dir_url( __FILE__ ). '/ASSETS/JS/ns-marketplace-multi-vendor-ajax-functions.js', array(), '1.0.0', true );
    wp_localize_script( 'ns-mmv-js-custom', 'ns_delete_product', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_localize_script( 'ns-mmv-js-custom', 'ns_edit_product', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_localize_script( 'ns-mmv-js-custom', 'ns_registration_as_vendor', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_localize_script( 'ns-mmv-js-custom', 'ns_set_as_shipped', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'ns-mmv-js-custom-registration', plugin_dir_url( __FILE__ ). '/ASSETS/JS/ns-marketplace-multi-vendor-account.js', array(), '1.0.0', true );
    if(is_page(get_option('nns-vendor-become-vendor-id'))){
        wp_enqueue_script( 'jquery-ui-datepicker' );
        //wp_enqueue_style( 'jquery-ui' ); 
        wp_localize_script( 'ns-mmv-js-custom', 'ns_order_filter', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
        wp_localize_script( 'ns-mmv-js-custom', 'ns_save_account_details', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    }
    if(is_page(get_option('ns-vendor-add-product-id'))){
        //add product frontend
        wp_enqueue_script( 'switch-over-type-of-products', plugins_url( '/ASSETS/JS/ns-switch-over-type-of-product.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'option-js-page', plugins_url( '/ASSETS/JS/ns-option-js-page.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'add-product-variations', plugins_url( '/ASSETS/JS/ns-add-product-variations.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'hide-n-show-divs', plugins_url( '/ASSETS/JS/ns-hide-n-show-divs.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'linked-products', plugins_url( '/ASSETS/JS/ns-linked-products.js', __FILE__ ), array('jquery'), false, true );

        wp_enqueue_script( 'add-img-to-gallery', plugins_url( '/ASSETS/JS/ns-add-img-to-gallery.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'add-custom-categories', plugins_url( '/ASSETS/JS/ns-add-custom-category.js', __FILE__ ), array('jquery'), false, true );
        
        wp_localize_script( 'add-img-to-gallery', 'ns_add_to_gallery_image', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
        wp_localize_script( 'add-custom-categories', 'ns_add_cat_product', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
    }
}
add_action( 'wp_enqueue_scripts', 'ns_mmv_js' );

/*========================================================*/
//      HOOK to create a custom table in db			          
/*========================================================*/
register_activation_hook( __FILE__, 'ns_marketplace_multi_vendor_orders_table' );

/*========================================================*/
//                  CREATE NEW ROLE			          
/*========================================================*/
function ns_mmv_add_roles_on_plugin_activation() {
    add_role( 'ns_mmv_vendor', 'Vendor',  array (
        'read' => true,
        'manage_product' => true,
        'edit_post' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'view_woocommerce_reports' => true,
        'assign_product_terms' => true,
        'upload_files' => true,
        'read_product' => true,
        'read_shop_coupon' => true,
        'edit_product' => true,
        'delete_product' => true,
        'edit_products' => true,
        'delete_products' => true,
      ));
}
register_activation_hook( __FILE__, 'ns_mmv_add_roles_on_plugin_activation' );
/*========================================================*/
//                   REDIRECT FUNCTION			          
/*========================================================*/
function ns_redirect_if_not_logged_in($url){
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';
    echo $string;
}

function ns_mmv_main_menu($menu_item_data){
    $menu_name = 'primary';
    $locations = get_nav_menu_locations();
    $menu_id = $locations[ $menu_name ] ;
    wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
}



/*========================================================*/
//  ADD VENDOR PAGES: on activation creat new vendor pages			          
/*========================================================*/
function ns_marketplace_multi_vendor_new_page(){
    if(!shortcode_exists('woocommerce_my_account')){
        $ns_mmv_vendor_account = 
            array(
                'post_title'    => 'My Account',
                'post_content'  => '[woocommerce_my_account]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
        $ns_result = wp_insert_post( $ns_mmv_vendor_account );
        if($ns_result != 0){
            update_option('woocommerce_myaccount_page_id', $ns_result);
            ns_mmv_main_menu( 
                array(
                    'menu-item-title' =>  __('My Account'),
                    'menu-item-url' => get_permalink($ns_result), 
                    'menu-item-status' => 'publish')
                );
        }
    }
    if(!get_option('ns-vendor-public-page-account-id')){
        
        $ns_mmv_vendor_account = 
            array(
                'post_title'    => 'Vendor Account Public Page',
                'post_content'  => '[ns-vendor-page-account]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
        $ns_result = wp_insert_post( $ns_mmv_vendor_account );
        if($ns_result != 0)
            update_option('ns-vendor-public-page-account-id', $ns_result);
    }

    if(!get_option('ns-vendor-products-dashboard-id')){
        
        $ns_mmv_vendor_account = 
            array(
                'post_title'    => 'Vendors Dashboard Products',
                'post_content'  => '[ns-vendor-products-dashboard]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
        $ns_result = wp_insert_post( $ns_mmv_vendor_account );
        if($ns_result != 0)
            update_option('ns-vendor-products-dashboard-id', $ns_result);
    }
    
    if(!get_option('ns-vendor-add-product-id')){
        
        $ns_mmv_vendor_account = 
            array(
                'post_title'    => 'Vendors Dashboard Add New Products',
                'post_content'  => '[ns-vendor-add-product]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
        $ns_result = wp_insert_post( $ns_mmv_vendor_account );
        if($ns_result != 0)
            update_option('ns-vendor-add-product-id', $ns_result);
    }
    
    if(!get_option('ns-vendor-become-vendor-id')){
        
        $ns_mmv_vendor_account = 
            array(
                'post_title'    => 'Vendors Dashboard Vendor Registration',
                'post_content'  => '[ns-vendor-become-vendor]',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page'
            );
        $ns_result = wp_insert_post( $ns_mmv_vendor_account );
        if($ns_result != 0)
            update_option('ns-vendor-become-vendor-id', $ns_result);
    }
}
add_action('admin_init', 'ns_marketplace_multi_vendor_new_page');


/*========================================================*/
//  ADD NEW MY ACCOUNT: Add New My Account Menu Page "My products"		          
/*========================================================*/
add_filter ( 'woocommerce_account_menu_items', 'ns_marketplace_multi_vendor_one_more_link' );
function ns_marketplace_multi_vendor_one_more_link( $menu_links ){
 
	// we will hook "anyuniquetext123" later
	
    if(!ns_mmv_is_vendor(get_current_user_id())){
        $new = array( 
            'ns_marketplace_multi_vendor_become_vendor'     => __('Become Vendor',  'ns-marketplace-multi-vendor')
        );
    }else{
        $new = array( 
            'ns_marketplace_multi_vendor_my_products'       => __('My Products',  'ns-marketplace-multi-vendor'),
            'ns_marketplace_multi_vendor_add_new_product'   => __('Add Product',  'ns-marketplace-multi-vendor'),
            'ns_marketplace_multi_vendor_become_vendor'     => __('Vendor Account',  'ns-marketplace-multi-vendor'));
    }
	// array_slice() is good when you want to add an element between the other ones
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ $new 
	+ array_slice( $menu_links, 1, NULL, true );
 
	return $menu_links;
}
/*========================================================*/
//  DEFINE "My products" URLs	          
/*========================================================*/
add_filter( 'woocommerce_get_endpoint_url', 'ns_marketplace_multi_vendor_hook_endpoint', 10, 4 );
function ns_marketplace_multi_vendor_hook_endpoint( $url, $endpoint, $value, $permalink ){
 
	if( $endpoint === 'ns_marketplace_multi_vendor_my_products' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink( get_option('ns-vendor-products-dashboard-id') );
    }
    if( $endpoint === 'ns_marketplace_multi_vendor_add_new_product' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink( get_option('ns-vendor-add-product-id') );
    }
    if( $endpoint === 'ns_marketplace_multi_vendor_become_vendor' ) {
		// ok, here is the place for your custom URL, it could be external
		$url = get_permalink( get_option('ns-vendor-become-vendor-id') );
	}
	return $url;
 
}




/*========================================================*/
//  SHORTCODE: [ns-vendor-page-account] 
/*========================================================*/
function ns_marketplace_multi_vendor_shortcode( $atts ){
    if(!is_admin()){
        //concat title page and products loop
        return ns_marketplace_multi_vendor_get_account_page().ns_marketplace_multi_vendor_dynamic_shortcode();
    }
}
add_shortcode( 'ns-vendor-page-account', 'ns_marketplace_multi_vendor_shortcode' );


/*========================================================*/
//  SHORTCODE: [ns-vendor-products-dashboard] 
/*========================================================*/
function ns_marketplace_multi_vendor_shortcode_products( $atts ){
    if(!is_admin()){
        if(!is_user_logged_in())
            ns_redirect_if_not_logged_in(wp_login_url());
        if(!ns_mmv_is_vendor(get_current_user_id()))
            ns_redirect_if_not_logged_in(get_permalink( get_option('ns-vendor-become-vendor-id') ));
        return ns_marketplace_multi_vendor_dashboard_products(get_current_user_id());
    }
}
add_shortcode( 'ns-vendor-products-dashboard', 'ns_marketplace_multi_vendor_shortcode_products' );

/*========================================================*/
//  SHORTCODE: [ns-vendor-add-product]
/*========================================================*/
function ns_marketplace_multi_vendor_shortcode_add_products( $atts ){
    if(!is_admin()){
        if(!is_user_logged_in())
            ns_redirect_if_not_logged_in(wp_login_url());
        if(!ns_mmv_is_vendor(get_current_user_id()))
            ns_redirect_if_not_logged_in(get_permalink( get_option('ns-vendor-become-vendor-id') ));
        require_once plugin_dir_path(__FILE__).'ns-frontend-add-product-premium/ns-frontend-add-product-page.php';
        return ns_add_prod();
    }
}
add_shortcode( 'ns-vendor-add-product', 'ns_marketplace_multi_vendor_shortcode_add_products' );


/*========================================================*/
//  SHORTCODE: [ns-vendor-become-vendor]
/*========================================================*/
function ns_marketplace_multi_vendor_shortcode_become_vendor( $atts ){
    if(!is_admin()){
        if(!is_user_logged_in())
            ns_redirect_if_not_logged_in(wp_login_url());
        if(!ns_mmv_is_vendor(get_current_user_id()))
            //defined in ns-marketplace-multi-vendor-account.php
            return ns_market_place_multi_vendor_registration();
        else 
            //defined in ns-marketplace-multi-vendor-account.php
            return ns_market_place_multi_vendor_account_settings_if_vendor();
    }
}
add_shortcode( 'ns-vendor-become-vendor', 'ns_marketplace_multi_vendor_shortcode_become_vendor' );


/*========================================================*/
//  PREVENT PAGE DELETING
/*========================================================*/
add_action( 'before_delete_post', 'ns_marketplace_multi_vendor_before_delete_post' );
function ns_marketplace_multi_vendor_before_delete_post( $postid ){
    
   
    // We check if the global post type isn't ours and just return
    global $post_type;   
    if ( $post_type != 'page' ) return;
    if ( $postid == get_option('ns-vendor-public-page-account-id'))
        delete_option('ns-vendor-public-page-account-id');
    if ( $postid == get_option('ns-vendor-products-dashboard-id'))
        delete_option('ns-vendor-products-dashboard-id');
    if ( $postid == get_option('ns-vendor-add-product-id'))
        delete_option('ns-vendor-add-product-id');
    if ( $postid == get_option('ns-vendor-become-vendor-id'))
        delete_option('ns-vendor-become-vendor-id');


    // My custom stuff for deleting my custom post type here
}

add_action('admin_footer', 'ns_mmv_add_rate_us_footer');
function ns_mmv_add_rate_us_footer(){
    if(isset($_GET['page']) && $_GET['page'] == 'ns-marketplace-multi-vendor/ns-admin-options/ns_admin_option_dashboard.php'){
        echo 
            '<div id="ns-wpfooter" role="contentinfo">
                <p id="footer-left" class="alignleft">
                    <span id="footer-thankyou">Please, rate us 
                        <a href="https://wordpress.org/support/plugin/ns-marketplace-multi-vendor/reviews/#new-post" target="_blank" style="text-decoration: none">
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </a>
                        on WP repository.
                    </span>	
                </p>
            </div>';
    }
}


/*========================================================*/
//  CUSTOM REGISTRATION FORM
/*========================================================*/
add_action( 'woocommerce_register_form', 'ns_marketplace_multi_vendor_custom_registration' );
function ns_marketplace_multi_vendor_custom_registration(){
    echo '
        <p>
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline" id="ns-become-vendor-registration-label">
                <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="ns-become-vendor-registration" type="checkbox" id="ns-become-vendor-registration" value="yes"> 
                <span>'.__('Do you want to become vendor?', 'ns-marketplace-multi-vendor').'</span>
            </label>
        </p>
        <div class="ns-mmv-reg-custom-fields">
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_password">'.__('Public Name', 'ns-marketplace-multi-vendor').'&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="ns-public-name-reg" id="ns-public-name-reg" autocomplete="on">
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="reg_password">'.__('PayPal Email', 'ns-marketplace-multi-vendor').'&nbsp;<span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="ns-paypal-email-reg" id="ns-paypal-email-reg" autocomplete="email">
            </p>
        </div>';
    // My custom stuff for deleting my custom post type here
}

/*========================================================*/
//  CHECK DATA FROM CUSTOM REGISTRATION FORM
/*========================================================*/
function ns_marketplace_multi_vendor_check_data_from_custom_registration($reg_errors) {
    
    if (isset($_POST['ns-become-vendor-registration']) && $_POST['ns-become-vendor-registration'] == 'yes') {
        if ( empty( $_POST['ns-public-name-reg'] ) ) {
            $reg_errors->add( 'empty required fields', __( 'Public Name Required', 'ns-marketplace-multi-vendor' ) );
        }
        if ( empty( $_POST['ns-paypal-email-reg'] ) ) {
            $reg_errors->add( 'empty required fields', __( 'PayPal Email Required', 'ns-marketplace-multi-vendor' ) );
        }
    }
	return $reg_errors;
}
add_action('woocommerce_registration_errors', 'ns_marketplace_multi_vendor_check_data_from_custom_registration');

/*========================================================*/
//  SAVE DATA FROM CUSTOM REGISTRATION FORM
/*========================================================*/
function ns_marketplace_multi_vendor_save_data_from_custom_registration($customer_id) {
    
    if (isset($_POST['ns-become-vendor-registration']) && $_POST['ns-become-vendor-registration'] == 'yes') {
        $ns_public_name_reg = sanitize_text_field($_POST['ns-public-name-reg']);
        $ns_paypal_email_reg = sanitize_email($_POST['ns-paypal-email-reg']);
        update_user_meta($customer_id, '_ns_is_vendor', 'yes');
        update_user_meta($customer_id, '_ns_vendor_public_name', $ns_public_name_reg);
        update_user_meta($customer_id, '_ns_vendor_paypal_email', $ns_paypal_email_reg);
        ns_market_place_multi_vendor_insert_new_vendor($customer_id);
        $args = array(
            'ID'    => $customer_id,
            'role'  => 'ns_vendor'
        );
        $result = wp_update_user($args);
    }
}
add_action('woocommerce_created_customer', 'ns_marketplace_multi_vendor_save_data_from_custom_registration');

function ns_mmv_add_sold_by_link(){
    global $post;
    $user_id= $post->post_author;
    $url = get_permalink( get_option('ns-vendor-public-page-account-id') );
	$ns_short_desc_sold_by = '<p>'.__('Sold by ', 'ns-marketplace-multi-vendor');
    $ns_short_desc_sold_by .= '<a href="'.$url.'?vendor-id='.$user_id.'">'.ns_get_vendor_public_name($user_id).'</a></p>';
    echo $ns_short_desc_sold_by;
    
}
add_action('woocommerce_before_add_to_cart_form', 'ns_mmv_add_sold_by_link');




/*========================================================*/
//                  INCLUDE text domain			          
/*========================================================*/
function ns_mmv_translate(){

    load_plugin_textdomain('ns-marketplace-multi-vendor',false, basename( dirname( __FILE__ ) ) .'/i18n/');
}
add_action('plugins_loaded','ns_mmv_translate');

/*========================================================*/
//                  ADD link premium			          
/*========================================================*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ns_mmv_add_action_links' );

function ns_mmv_add_action_links ( $links ) {	//TODO change link!
    $mylinks = array('<a id="nsraclinkpremium" href="https://www.nsthemes.com/product/marketplace-multi-vendor/?ref-ns=2&campaign=MMV-linkpremium" target="_blank">'.__( 'Premium Version', 'ns-marketplace-multi-vendor-account' ).'</a>');
    return array_merge( $links, $mylinks );
}

?>