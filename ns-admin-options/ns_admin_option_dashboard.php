<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) .'inc.php');

$link_sidebar = $ns_url_plugin_premium.'?ref-ns=2&campaign=NSMMV-nsclub&utm_source='.$ns_menu_label.'%20Sidebar&utm_medium=Sidebar%20dentro%20settings&utm_campaign='.$ns_menu_label.'%20Sidebar%20premium';
$link_bannerino = $ns_url_plugin_premium.'?ref-ns=2&campaign=NSMMV-bannerino&utm_source='.$ns_menu_label.'%20Bannerino&utm_medium=Bannerino%20dentro%20settings&utm_campaign='.$ns_menu_label.'%20Bannerino%20premium'; 
$link_bannerone = $ns_url_plugin_premium.'?ref-ns=2&campaign=NSMMV-bannerone&utm_source='.$ns_menu_label.'%20Bannerone&utm_medium=Bannerone%20dashboard&utm_campaign='.$ns_menu_label.'%20Bannerone%20premium'; 
$link_promo_theme = $ns_url_theme_promo.'?ref-ns=2&campaign=NSMMV-MisterCorporate&utm_source='.$ns_theme_promo_slug.'%20'.$ns_menu_label.'%20Sidebar&utm_medium=Sidebar%20'.$ns_theme_promo_slug.'%20dentro%20settings&utm_campaign='.$ns_theme_promo_slug.'%20'.$ns_menu_label.'%20Sidebar%20premium';
?>

	    <div class="verynsbigbox">
	    	<?php 
	    		/* *** BOX THEME PROMO *** */
				require_once( plugin_dir_path( __FILE__ ) .'ns_settings_box_theme_promo.php');

	    		/* *** BOX PREMIUM VERSION *** */
				require_once( plugin_dir_path( __FILE__ ) .'ns_settings_box_pro_version.php');

	    		/* *** BOX NEWSLETTER *** */
				//require_once( plugin_dir_path( __FILE__ ) .'ns_settings_box_newsletter.php');
			?>			
		</div>
		<div class="verynsbigboxcontainer">
				<div class="postbox nsproversionfbpx4wp">
					<h3 class="titprofbpx4wp"><?php echo $ns_full_name; ?></h3>
						<div class="colprofbpx4wp">
						<h2 class="titlefbpx4wp"><?php _e('How to use', $ns_text_domain); ?></h2><br><br>
						
							<iframe width="100%"" height="250" src="https://www.youtube.com/embed/videoseries?list=PLxHSoFRLMQcesAPOd2QHOliA7n759RP8Q" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
						<div class="colprofbpx4wp2">
							<div class="ns-container-title-arrow">
								<h2 class="titlefbpx4wp ns-hidepremiumfeature"><?php _e('Premium features', $ns_text_domain); ?></h2>
								<div class="ns-arrows-float-right">
									<div class="arrows"></div>
								</div>
							</div>
							<br><br>

							<h2 class="titlefbpx4wp2"><?php _e('Go Premium, get more features and support', $ns_text_domain); ?>:</h2><br><br>
							<?php _e('If you upgrade your plugin you will get one year of free updates and support
							through our website available 24h/24h. Upgrade and you\'ll have the advantage
							of additional features of the premium version.', $ns_text_domain); ?><br><br>
							<a id="fbp4wplinkpremiumboxpremium" class="button-primary" href="<?php echo $link_bannerone; ?>""><?php _e('Go Premium Now', $ns_text_domain); ?></a>
						</div>
				</div>				
				<a name="rac-table-anchor"></a>
				<div class="icon32" id="icon-options-general"><br /></div>
			</div>

		<div class="verynsbigboxcontainer">	

			<div class="icon32" id="icon-options-general"><br /></div>
			<h2 class="ns-ctbc-color"><?php echo $ns_full_name; ?> Settings</h2>
			<form method="post" action="options.php" >
	    		<?php /* *** BOX THEME PROMO *** */ ?>
				<?php require_once( plugin_dir_path( __FILE__ ).'ns_settings_custom.php'); ?>				
				<!-- <p><input type="submit" class="button-primary ns-ctbc-submit-button" id="submit" name="submit" value="<?php _e('Save Changes') ?>" /></p>		 -->
			</form>
		</div>
<!-- 		<div><p><?php #echo '<a href="'.$link_bannerino.'" target="_blank"><img src="'.plugin_dir_url( __FILE__ ).'img/'.$ns_slug.'-bannerino.png"></a>'; ?></p></div>
 -->





