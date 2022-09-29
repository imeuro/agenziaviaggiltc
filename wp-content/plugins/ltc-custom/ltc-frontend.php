<?php

/*****************************************
 * FRONTEND ENHANCEMENTS *
 *****************************************/


// [ FRONTEND ]
// add cart items number in menu after "shop"
/*
 add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
	
	if ( class_exists( 'WooCommerce' ) && ( !is_cart() && !is_checkout() ) ) {
		$cart_items = WC()->cart->get_cart_contents_count();

		if ( $args->theme_location == 'primary' && $cart_items > 0  ) {
			$items .= '<li id="ltc_cart_qty"><a title="Hai ' . $cart_items . ' elementi nel carrello" href="'. get_site_url(null, '/cart/', 'https') .'">' . $cart_items . '</a></li>';
		}

	}
    return $items;
}
*/

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
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_company']['label'] = 'Codice Fiscale';
    $fields['billing']['billing_company']['required'] = true;
    unset($fields['billing']['billing_address_2']);
    
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