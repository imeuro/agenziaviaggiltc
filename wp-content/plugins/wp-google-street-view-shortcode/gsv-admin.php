<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_menu', 'wpgsv_menu');
function wpgsv_menu() {
        add_options_page( 
			__('Wordpress Google Street View Shortcode','wpgsv'),  // Page Title
			__('GSV Shortcode','wpgsv'),  // Menu Title
			'manage_options', // Capability
			'gsvadmin',  // Menu Slug
			'wpgsv_options' // Callable
		);
        add_action( "admin_init", 'wpgsv_scripts' );
        add_action( "admin_init", 'wpgsv_gmaps' );
        add_action( 'admin_init', 'wpgsv_settings' );
}

/**
 * Registers all of the plugin settings on admin_init
 */

function wpgsv_settings() {
	register_setting( 'wpgsv-settings-group', 'wpgsv-googlemapsapi' );
}

function wpgsv_options() {
//	add_action( 'wp_enqueue_scripts', 'wpgsv_gmaps' );
	global $wpdb;

	if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
	<div class="wrap">
<?php		
    echo '<h1>' . __('Wordpress Google Street View Shortcode','wpgsv') . "</h1>\n";
?>
		<form method="post" action="options.php">
<?php 
	settings_fields( 'wpgsv-settings-group' ); 
	do_settings_sections( 'wpgsv-settings-group' );
	$wpgsv_key = esc_attr( get_option('wpgsv-googlemapsapi') );
?>
			<table class="form-table">
		        <tr valign="top">
		        <th scope="row"><?php echo __('Google Maps API Key','wpgsv'); ?></th>
		        <td>
		        	<input type="text" name="wpgsv-googlemapsapi" style="width: 400px" value="<?php echo $wpgsv_key; ?>" onchange="destroyValid()" />
		        	<span id="checkapi"></span>
		        	<p class="description"><a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank"><?php echo __('Get an API Key','wpgsv') ?></a></i></p>
				</td>
		        </tr>
		    </table>
<?php
	$img = '//maps.googleapis.com/maps/api/streetview?size=20x20&location=32.755607226325,-97.33214383036301&fov=90&heading=146.0375056698473&pitch=0.9564023077946047&key=' . $wpgsv_key;
	echo '<img src="' . $img . '" width="1" height="1" id="checkimg" />';
	$wp3dm_key = get_option('wp3d_google_maps_api_server_key'); // If WP3D Models installed, borrow the key.
	$wpgmza_key = get_option('wpgmza_google_maps_api_key');
	if (strlen($wp3dm_key) > 5 || strlen($wpgmza_key) > 5) {
		echo "\t\t<hr />";
		echo "\t\t<h3>" . __('Google Maps API Keys Detected!','wpgsv') . "</h3>\n";
		echo "\t\t<table class=\"form-table\">\n";
		if (strlen($wp3dm_key) > 5 && $wp3dm_key) {
?>			
		        <tr valign="top">
			        <th scope="row"><?php echo __('WP3D Models','wpgsv'); ?></th>
					<td><input type="text" readonly="readonly" style="width: 400px" value="<?php echo $wp3dm_key ?>" /></td>
				<tr/>
<?php
		}
		if (strlen($wpgmza_key) > 5 && $wpgmza_key) {
?>
		        <tr valign="top">
			        <th scope="row"><?php echo __('WP Google Maps','wpgsv'); ?></th>
					<td><input type="text" readonly="readonly" style="width: 400px" value="<?php echo $wpgmza_key ?>" /></td>
				<tr/>

<?php
		}
		echo "\t\t</table>\n";
	}
	echo '<hr />';
	echo "<h2>" . __('Troubleshooting','wpgsv') . "</h2>\n";
	echo '<p>' . __('Your API Key must have access to the Google Maps Javascript API, Google Maps Image API and Google Maps Geolocator API.  These are usually enabled by default when registering a Google Maps API Key.','wpgsv') . '</p>';
	echo '<p>' . __('If maps appear, then disappear in the shortcode embed popup, your API key does not have the Google Maps JS API enabled.','wpgsv') . 
	'<a href="https://console.developers.google.com/apis/library/maps-backend.googleapis.com/" target="_blank">' .
	'<span class="dashicons dashicons-admin-links"></span></a></p>';
	echo '<p>' . __('If geolocation does not work in the shortcode embed popup, your API key does not have the Google Maps Geolocator API enabled.','wpgsv') . 
	'<a href="https://console.developers.google.com/apis/library/geocoding-backend.googleapis.com/" target="_blank">' .
	'<span class="dashicons dashicons-admin-links"></span></a></p>';
	'</p>';
	echo '<p>' . __('If thumbnails do not appear, your API key does not have the Google Maps Image API enabled.','wpgsv') . 
		'<a href="https://console.developers.google.com/apis/library/street-view-image-backend.googleapis.com/" target="_blank">' .
		'<span class="dashicons dashicons-admin-links"></span></a></p>';
	submit_button();
?>
		</form>
	     <script type="text/javascript">
		 // API Key Validator via Callback
			var isMapsApiLoaded = false;
			window.mapsCallback = function() {
				isMapsApiLoaded = true;
			}
			function destroyValid() {
				document.getElementById('checkapi').innerHTML = '<span style="color: #ff0000"><?php echo __('Save to re-validate','wpgsv') ?></span>';
			}
			function gsvcheckAPIKey(){
				var checkImg = document.getElementById('checkimg').naturalHeight;
				if (checkImg != 20) {
				}
				if (isMapsApiLoaded) {
					document.getElementById('checkapi').innerHTML = '<span style="color: #00ff00"><?php echo __('Valid API Key','wpgsv') ?>' + 
						(checkImg != 20 ? '</span> <span style="color: #ff0000"><?php echo __('Google Maps Image API is missing.',' wpgsv') ?>' : '') + '</span>';
				} else {
					document.getElementById('checkapi').innerHTML = '<span style="color: #ff0000"><?php echo __('Invalid API Key','wpgsv') ?></span>';
				}
				console.log(checkImg);
			}
			document.addEventListener("DOMContentLoaded", gsvcheckAPIKey);
	    </script>
	     <script src="//maps.google.com/maps?file=api&amp;key=<?php get_option('wpgsv-googlemapsapi') ?>&callback=mapsCallback" type="text/javascript"></script>
	</div>
<?php	
}

function wpgsv_screen_options() {
	$args = array(
		'label' => __('# of Columns (Max 4)', 'wpms'),
		'default' => 2,
		'max' => 4,
		'option' => 'wpgsv_admin_cols'
	);
	add_screen_option( 'per_page', $args );
}
function wpgsv_set_screen_option($status, $option, $value) {
	if ( 'wpms_admin_cols' == $option ) return $value;
	return $status;
}
add_filter('set-screen-option', 'wpgsv_set_screen_option', 10, 3);
