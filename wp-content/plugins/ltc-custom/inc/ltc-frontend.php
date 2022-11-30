<?php

/*****************************************
 * FRONTEND ENHANCEMENTS *
 *****************************************/

// [ FRONTEND ]
// remove jetpack Related Posts in woocommerce products page
function jetpackme_remove_related() {
    if ( class_exists( 'Jetpack_RelatedPosts' ) && is_product() ) {
        $jprp = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
 
        remove_filter( 'the_content', $callback, 40 );
    }
}
add_action( 'wp', 'jetpackme_remove_related', 20 );



// [ FRONTEND ]
// Remove product data tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;
}

// [ FRONTEND ]
// Remove ‘Add to Cart’ Button in listings
add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );
function remove_add_to_cart_buttons() {
	if( is_product_category() || is_shop()) { 
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
	}
}


// [ FRONTEND ]
// custom checkout fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields', 0, 999 );
function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_company']['label'] = 'Codice Fiscale';
    $fields['billing']['billing_company']['required'] = true;
    unset($fields['billing']['billing_address_2']);
    return $fields;
}

// [ FRONTEND ]
// Aggiungi acuni campi al checkout a seconda della categoria prodotto (es. vacanze-studio)

function has_product_category_in_cart( $product_category ) {
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        // If any product category is found in cart items
        if ( has_term( $product_category, 'product_cat', $cart_item['product_id'] ) ) {
            return true;
        }
    }
    return false;
}
function startsWith ($string, $startString) {
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}


add_action('woocommerce_checkout_fields', 'LTC_enable_custom_checkout_fields');

function LTC_enable_custom_checkout_fields( $fields ) {
	$additional_fields = get_option('wc_fields_additional');
	
	$isEnabled = ( has_product_category_in_cart( array('vacanze-studio','form-lungo') ) ) ? 1 : 0;

	if(is_array($additional_fields)){ //se ci sono campi aggiuntivi
		foreach ($additional_fields as $addtlkey => $addtlfld) {
			if(startsWith($addtlkey,"vacanzestudio_")) { // e se cominciano con ...
				$addtlfld['enabled'] = $isEnabled;
				$fields['order'][$addtlkey] = $addtlfld;
			}
		}
	}

	// comunque mettimi le note in fondo
	$fields['order']['order_comments']['enabled'] = 1;
	$fields['order']['order_comments']['priority'] = 999;
	THWCFD_Utils::update_fields('additional', $fields['order']);

	// echo '<pre>';
	// print_r($fields['order']);
	// echo '</pre>';

	return $fields;
}



// [ FRONTEND ]
// prodotto obbligatoriamente con coupon ("convenzioni")
add_action( 'woocommerce_check_cart_items', 'mandatory_coupon_for_specific_items' );
function mandatory_coupon_for_specific_items() {
	$targeted_ids = array(13927); // The targeted product ids (in this array)
	$coupon_code = 'ltc'; // The required coupon code

	$coupons_entered = WC()->cart->get_applied_coupons();
	$coupon_prefix = [];

	foreach ($coupons_entered as $key=>$single_coupon_entered) {
		$short_coupon_entered = substr($single_coupon_entered, 0, 3);
		$coupon_prefix[] = strtolower($short_coupon_entered);
	}

	$coupon_applied = in_array( strtolower($coupon_code), $coupon_prefix );

	// Loop through cart items
	foreach(WC()->cart->get_cart() as $cart_item ) {
	// Check cart item for defined product Ids and applied coupon
		if( in_array( $cart_item['product_id'], $targeted_ids ) && ! $coupon_applied ) {
			wc_clear_notices(); // Clear all other notices

			// Avoid checkout displaying an error notice
			wc_add_notice( sprintf( 'Per acquistare "%s" è necessario inserire un codice promozionale.', $cart_item['data']->get_name() ), 'error' );
			break; // stop the loop
		}
	}
}


// [ FRONTEND ]
/* custom translation file:
 * Replace 'textdomain' with your plugin's textdomain. e.g. 'woocommerce'. 
 * File to be named, for example, yourtranslationfile-en_GB.mo
 * File to be placed, for example, wp-content/lanaguages/textdomain/yourtranslationfile-en_GB.mo
 */
add_filter( 'load_textdomain_mofile', 'load_custom_plugin_translation_file', 10, 2 );
function load_custom_plugin_translation_file( $mofile, $domain ) {
  if ( 'woocommerce' === $domain ) {
    $mofile = WP_LANG_DIR . '/'.$domain.'/ltc_woocommerce-' . get_locale() . '.mo';
  }
  return $mofile;
}