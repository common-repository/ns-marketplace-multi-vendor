<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* *** *********************************************************************** *** */
/* *** REMEMBER TO CHANGE $ns_plugin_prefix IN  admin_enqueue_scripts FUNCTION *** */
/* *** *********************************************************************** *** */

require_once (plugin_dir_path( __FILE__ ) .'/ns_admin_vendor_page.php');
/* *** add menu page and add sub menu page *** */
add_action( 'admin_menu', function()  {
    add_menu_page( 'Marketplace', 'Marketplace', 'manage_options', plugin_dir_path( __FILE__ ) .'/ns_admin_option_dashboard.php', '', plugin_dir_url( __FILE__ ).'img/backend-sidebar-icon.png', 60);
	add_submenu_page(plugin_dir_path( __FILE__ ) .'/ns_admin_option_dashboard.php', 'How to install premium version', 'How to install premium version', 'manage_options', 'ns-rac-graph-stats'/*TODO*/, function(){wp_redirect('http://www.nsthemes.com/how-to-install-the-premium-version/'); exit; });
});


/* *** *********************************************************************** *** */
/* ***     REMEMBER TO CHANGE FUNCTION NAME WITH THE PREFIX OF THIS PLUGIN     *** */
/* *** *********************************************************************** *** */
/* *** add menu page and add sub menu page *** */
function nsmmv_preprocess_pages($value){
    global $pagenow;
    $page = (isset($_REQUEST['page']) ? $_REQUEST['page'] : false);
    if($pagenow=='admin.php' && $page=='join-the-club'){
        wp_redirect('https://www.nsthemes.com/join-the-club/');
        exit;
    }
}
add_action('admin_init', 'nsmmv_preprocess_pages');






/* *** add style *** */
add_action( 'admin_enqueue_scripts', function() {
	$ns_plugin_prefix = 'nsmmv';
	wp_enqueue_style('ns-'.$ns_plugin_prefix.'-option-css-page', plugin_dir_url( __FILE__ ) . 'css/ns-option-css-page.css');
    wp_enqueue_style('ns-'.$ns_plugin_prefix.'-option-css-a-page', plugin_dir_url( __FILE__ ) . 'css/ns-option-css-custom-page.css');
    wp_enqueue_script('ns-'.$ns_plugin_prefix.'-option-js-a-page', plugin_dir_url( __FILE__ ) . 'js/ns-option-js-backend.js', array('jquery'), '1.0.0', true );
    wp_localize_script('ns-'.$ns_plugin_prefix.'-option-js-a-page', 'ns_mark_as_paid', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_style( 'ns-'.$ns_plugin_prefix.'-all-min', plugin_dir_url( __FILE__ ). '../ASSETS/CSS/all.min.css', array(), '1.0.0' ); 
    
});


/* *** add frontend *** */
add_action( 'wp_enqueue_scripts', function() {
	$ns_plugin_prefix = 'nsmmv';
	wp_enqueue_script( 'ns-'.$ns_plugin_prefix.'-option-js-page-frontend', plugins_url( '/js/ns-option-js-frontend.js' , __FILE__ ), array( 'jquery' ) );
});
?>