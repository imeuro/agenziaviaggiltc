<?php 
/**
 * Plugin Name:   WP Google Street View Shortcode
 * Plugin URI:    https://www.metroplex360.com/wordpress-shortcode-for-google-street-view/
 * Description:   Easily embed Google Street View tours with click-to-start preview image and a pop-up.
 * Version:       0.5.7
 * Author:        Chris Hickman / Metroplex360.com
 * Author URI:    http://www.metroplex360.com
 * License:       GPL-2.0+
 * License URI:   http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:   wpgsv
 * Domain Path:   /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get WPGSV Google Maps API Key
$wpgsv_key = get_option('wpgsv-googlemapsapi');
if (strlen($wpgsv_key) < 5 || !$wpgsv_key) {
	if (is_admin()) {
		function my_error_notice() {
			$notice = __('WP Google Street View Shortcode: Please configure your Google Maps API Key', 'wpgsv');
    ?>
    <div class="error notice">
        <p><?php _e($notice . ' / <a href="options-general.php?page=gsvadmin">' . __( 'Settings' ) . '</a>', 'wpgsv' ); ?></p>
    </div>
    <?php
		}
	add_action( 'admin_notices', 'my_error_notice' );
	}
}

// Add Plugin Settings Links
function wpgsv_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=gsvadmin">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wpgsv_add_settings_link' );


// Register Scripts -- enqueued on demand.
function wpgsv_scripts() {
	wp_register_script( 'magnific', plugins_url( 'magnific.min.js', __FILE__ ), array('jquery'), false, true );
	wp_register_script( 'wpgsv-js', plugins_url( 'gsv-shortcode.js', __FILE__ ), array('jquery','magnific'), false, true );
 	wp_register_style( 'magnific', plugins_url( 'magnific.css', __FILE__ ));
	wp_register_style( 'wpgsv-css', plugins_url( 'gsv-shortcode.css', __FILE__ ) );
}

// Safely include Google Maps JS (Check for existing inclusion)
function wpgsv_gmaps() {
	global $wp_scripts; 
	global $wpgsv_key;
	$gmapsenqueued = false;
    foreach ($wp_scripts->registered as $key => $script) {
        if (preg_match('#maps\.google(?:\w+)?\.com/maps/api/js#', $script->src)) {
            $gmapsenqueued = true;
        }
    }
    if (!$gmapsenqueued) {
        wp_register_script('google-maps', '//maps.google.com/maps/api/js?sensor=false&key=' . $wpgsv_key, array('jquery'), false);
    }	
}

add_action( 'wp_enqueue_scripts', 'wpgsv_scripts' );
add_action( 'wp_enqueue_scripts', 'wpgsv_gmaps' );
// Admin
if (is_admin()) {
	include_once('gsv-admin.php');
}

	
add_shortcode('streetview', 'wpgsv_shortcode');
function wpgsv_shortcode( $atts ) {
	global $wpgsv_key;
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'magnific' );
	wp_enqueue_style( 'magnific' );
	wp_enqueue_script( 'wpgsv-js' );
	wp_enqueue_style( 'wpgsv-css' );
	extract( 
		shortcode_atts(
			array(
				'id' => NULL,
				'title' => NULL,
				'window' => NULL,
				'view' => 'thumb',
				'width' => '100%',
				'height' => '360',
				'lat' => '37.422000',
				'lng' => '-122.084057',
				'heading' => '126',
				'pitch' => '0', // ?
				'zoom' => '1'
			),
			$atts
		)
	);
	if ($view == 'thumb') {
		$img = '//maps.googleapis.com/maps/api/streetview?size=640x400&location=' . $lat . ',' . $lng . '&fov=90&heading=' . $heading . '&pitch=' . $pitch . '&key=' . $wpgsv_key;
		$link = plugins_url( 'gsv.php', __FILE__ ) . '?lat=' . $lat . '&lng=' . $lng . '&heading=' . $heading . '&pitch=' . $pitch . '&zoom=' . $zoom . '&key=' . $wpgsv_key;
		ob_start();
?>
		<div class="wpgsv-img"<?php 
		
		if ($id != NULL)
			echo ' id="' . $id . '"';
		?>>
			<a href="<?php echo $link ?>" class="gsv-overlay wpgsv-tour">
				<img src="<?php echo $img ?>" width="600" height="400" alt="" />
				<b>&#9658;</b>
                <i><?php _e('Explore Street View', 'wpgsv'); ?></i>
			</a>
		</div>
<?php
		if ($title != NULL) {
?>		
        <div class="wpgsv-info">
        	<span class="wpgsv-title"><a class="wpgsv-tour" href="<?php echo $link ?>"><?php echo $title ?></a></span>
        </div>
<?php
		}
		return ob_get_clean();
		
	}
	elseif ($view == 'embed') {
		
		// Enqueue Google Maps on Demand!
		wp_enqueue_script('google-maps');
		
		// Make sure width / height are valid and attach px if not %
		$width = str_replace("px","",$width);
		if (substr_count($width,'%') == 0)
			$width .= "px";
		$height = str_replace("px","",$height);
		if (substr_count($height,'%') == 0)
			$height .= "px";
			
		$id = str_replace('.','',$lat);
		ob_start();
?>		
		<div class="wpgsv-img">
			<div id="streetview_canvas_<?php echo $id; ?>" class="wpgsv-tour" style='width: <?php echo $width; ?>; height: <?php echo $height; ?>'></div>
			<script type='text/javascript'>
				document.addEventListener("DOMContentLoaded", function(){
					var myLatlng = new google.maps.LatLng('<?php echo $lat; ?>','<?php echo $lng; ?>');
					var panoramaOptions = {
						position: myLatlng,
						addressControl: false,
						pov: {
							heading: <?php echo $heading; ?>,
							pitch: <?php echo $pitch; ?>,
							zoom: <?php echo $zoom; ?>
						}
					}
					var panorama_<?php echo $id; ?> = new google.maps.StreetViewPanorama(document.getElementById('streetview_canvas_<?php echo $id; ?>'), panoramaOptions);
				});
			</script>
		</div>
<?php
		return ob_get_clean();
	}
}

// GSV Admin

// Adding media buttuns
add_action('media_buttons', 'wpgsv_addMediaButton', 99);
add_action('media_upload_wpgsv', 'wpgsv_iframe');

function wpgsv_addMediaButton() {
	global $post_ID, $temp_ID;
	$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$media_upload_iframe_src = "media-upload.php?post_id=" . $uploading_iframe_ID;
	$gsv_upload_iframe_src = apply_filters('media_upload_iframe_src', $media_upload_iframe_src . "&amp;type=wpgsv");
	echo "<a href='{$gsv_upload_iframe_src}&amp;tab=wpgsv&amp;TB_iframe=true&amp;width=600&amp;height=400' class='thickbox' title='Insert GSV Shortcode'>" .
		"<img src='" . plugins_url('pegman.png',__FILE__) . "' width='16' height='20' style='vertical-align: middle' alt='GSV Shortcode' /></a>\n";

}

function wpgsv_iframe() {
	wp_iframe('wpgsv_custom_box');
}

function wpgsv_custom_box() {
	global $wpgsv_key;
?>
	<div style="padding: 15px;">
		<h3 class="media-title">Find GSV Start Location and Embed</h3>
		<ol>
			<li>Use the searchbox or manual panning to find you location.</li>
			<li>Drag the pegman (<img src="<?php echo plugins_url('pegman.png',__FILE__) ?>" width="16" height="20" />) onto the map to open street view.</li>
			<li>Navigate to the desired start position for your tour.</li>
			<li>Click 'Insert Shortcode'</li>
		</ol>
		<input id="streetview_address" type="text" name="address" placeholder="Name / Location" onKeyDown="if(event.keyCode==13) streetview_findaddress()" />&nbsp;<input type="submit" name="geocode_button" value="Search" onclick="streetview_findaddress()"/>
		<div id="streetview_canvas" style="width: 620px; height: 300px"></div>
		<br/>
		<h3>Select View</h3>
		<select id="gview" name="gview" onchange="toggleOptions()">
			<option value="embed">Embedded Street View</option>
			<option value="thumb" selected="selected">Thumbnail with Overlay (Recommended)</option>
		</select>
		<div id="embedoptions" style="display: none">
			<h3>Embed Options</h3>
			<label for="width">Width: </label> <input type="text" id="width" value="100%" />
			<label for="height">Height: </label> <input type="text" id="height" value="480" /><br />
		</div>
		<p><button onclick="streetview_getthepov()">Insert Shortcode</button></p>
		<hr />
		Using Google Maps API: <?php echo $wpgsv_key ?>
<?php
	if ($wpgsv_key == 'AIzaSyAMF_0dZZ-0bELmh6nh8Ylxy9CpFOu3oqY')
	  echo ' (Default Key)';
?>
	</div>
	<!-- Inline script loading due to use in a media uploader modal -->
	<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $wpgsv_key ?>&sensor=false"></script>
	<script type="text/javascript">
		function toggleOptions() {
			var e = document.getElementById('gview');
			if (e.options[e.selectedIndex].value == 'embed') {
				document.getElementById('embedoptions').style.display = 'block';
			}
			else {
				document.getElementById('embedoptions').style.display = 'none';
			}
		}
		var map;
		var geocoder;
		function streetview_initialize() {
		  var center = new google.maps.LatLng(50, -50);
		  var mapOptions = {
			center: center,
			zoom: 2,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			streetViewControl: true
		  };
		  geocoder = new google.maps.Geocoder();
		  map = new google.maps.Map(document.getElementById("streetview_canvas"), mapOptions);
		}
		function streetview_getthepov() {
			var pano = map.getStreetView();
			var pov = pano.getPov();
			if (pos = pano.getPosition()) {
				var e = document.getElementById('gview');
				var embedcode = "[streetview view=\"" + e.options[e.selectedIndex].value + "\"";
				if (e.options[e.selectedIndex].value == 'embed') {
					embedcode += " width=\"" + document.getElementById('width').value + "\"" +
						" height=\"" + document.getElementById('height').value + "\"";
				}
				embedcode += " title=\"" + pano.location.description + "\"" +
					" lat=\""+pos.lat()+"\"" +
					" lng=\""+pos.lng()+"\"" +
					" heading=\""+pov.heading+"\"" +
					" pitch=\""+pov.pitch+"\"" +
					" zoom=\""+pov.zoom+"\"][/streetview]";
				top.send_to_editor(embedcode);
			} else {
				alert('Drag and drop the yellow icon to a place first!');
			}
		}
		streetview_initialize();
		function streetview_findaddress() {
			var address = document.getElementById("streetview_address").value;
			geocoder.geocode( { 'address': address}, function(results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				map.setZoom(18);
			  } else {
				alert("Geocode was not successful for the following reason: " + status);
			  }
			});
		}			
		// based on google's geocode example code	
	</script>
	<?php
}