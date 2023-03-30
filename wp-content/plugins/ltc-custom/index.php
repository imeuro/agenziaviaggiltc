<?php
/*
* Plugin Name: 				LTC Ecommerce agenziaviaggi
* Description: 				funzioni specifiche per l'ecommerce di agenziaviaggiLTC. Richiede i plugin "Woocommerce", "Product code for Woocommerce", "Viva Wallet Standard Checkout" 
* Author: 					Meuro
* Version: 					13
* Author URI: 				https://meuro.dev
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	7.2
* Text Domain: 				ecommerce-lts
* Domain Path: 				/languages
*/


// SECTIONS:
// [ OUTILS ]
// [ BOOKING ]
// [ EMAIL ]
// [ FRONTEND ]
// [ BACKEND ]



/*****************************************
 * OUTILS *
 *****************************************/

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// check for plugin using plugin name
if(
	in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && 
	in_array('product-code-for-woocommerce/product-code-for-woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) &&
	in_array('viva-wallet-for-woocommerce/woocommerce-vivawallet-gateway.php', apply_filters('active_plugins', get_option('active_plugins')))

) { 
		//... you did good!
} else {
	function check_necessary_plugin_notice() {
	?>
	<div class="error">
		<p>
			<?php
			printf(
				esc_html__( 'ATTENZIONE: Il plugin "Ecommerce agenziaviaggiLTC" necessita "Woocommerce", "Product code for Woocommerce", "Checkout Field Editor for WooCommerce", "Viva Wallet Standard Checkout" siano installati e attivati', 'ecommerce-ltc' )
			);
			?>
		</p>
	</div>
	<?php
	}
	add_action( 'admin_notices', 'check_necessary_plugin_notice' );
}

if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
	function check_plugin_compatibility_notice() {
		?>
		<div class="error">
			<p>
				<?php
				printf(
					esc_html__( 'Ecommerce agenziaviaggiLTC requires at least PHP 5.6 to function properly. Please upgrade PHP.', 'ecommerce-lts' )
				);
				?>
			</p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'check_plugin_compatibility_notice' );
}



// Create multidimensional array unique for any single key index.
// stolen at: https://www.php.net/manual/en/function.array-unique.php#116302
function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

// i try to give self explanatory function names :P
function has_product_category_in_cart( $product_category ) {
	print_r('has_product_category_in_cart');
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        // If any product category is found in cart items
        if ( has_term( $product_category, 'product_cat', $cart_item['product_id'] ) ) {
        	print_r('true');
            return true;
        }
    }
    print_r('false');
    return false;
}
// if string starts with
function startsWith ($string, $startString) {
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}


/*****************************************
 * BOOKING RELATED *
 *****************************************/

include 'inc/ltc-booking.php';


/*****************************************
 * EMAIL RELATED *
 *****************************************/

include 'inc/ltc-email.php';


/*****************************************
 * FRONTEND ENHANCEMENTS *
 *****************************************/

include 'inc/ltc-frontend.php';


/*****************************************
 * BACKEND ENHANCEMENTS *
 *****************************************/

include 'inc/ltc-export-data.php';
include 'inc/ltc-backend.php';


function LTC_load_scripts($hook) {
	$LTC_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'assets/ltc-custom.js' ));
	wp_enqueue_script( 'custom_js', plugins_url( 'assets/ltc-custom.js', __FILE__ ), array(), $LTC_js_ver );
	wp_enqueue_style('ltc-custom-css', plugins_url('assets/ltc-custom.css',__FILE__));
}
add_action('wp_enqueue_scripts', 'LTC_load_scripts', 10);