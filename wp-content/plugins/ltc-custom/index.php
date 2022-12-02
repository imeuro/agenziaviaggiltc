<?php
/*
* Plugin Name: 				LTC Ecommerce agenziaviaggi
* Description: 				funzioni specifiche per l'ecommerce di agenziaviaggiLTC. Richiede i plugin woocommerce + product-code-for-woocommerce
* Author: 					Meuro
* Version: 					9
* Author URI: 				https://meuro.dev
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	7.1
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
if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) && in_array('product-code-for-woocommerce/product-code-for-woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) { 
		//... you did good!
} else {
	function check_necessary_plugin_notice() {
	?>
	<div class="error">
		<p>
			<?php
			printf(
				esc_html__( 'ATTENZIONE: Il plugin "Ecommerce agenziaviaggiLTC" necessita che "Woocommerce" e "Product code for Woocommerce" siano installati e attivati', 'ecommerce-ltc' )
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
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        // If any product category is found in cart items
        if ( has_term( $product_category, 'product_cat', $cart_item['product_id'] ) ) {
            return true;
        }
    }
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

include 'ltc-booking.php';


/*****************************************
 * EMAIL RELATED *
 *****************************************/

include 'ltc-email.php';


/*****************************************
 * FRONTEND ENHANCEMENTS *
 *****************************************/

include 'ltc-frontend.php';


/*****************************************
 * BACKEND ENHANCEMENTS *
 *****************************************/

include 'ltc-backend.php';