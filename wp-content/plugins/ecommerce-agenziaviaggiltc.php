<?php
/*
* Plugin Name: 				Ecommerce agenziaviaggiLTS
* Description: 				funzioni specifiche per l'ecommerce di agenziaviaggiLTC. Richiede i plugin woocommerce + product-code-for-woocommerce
* Author: 					Meuro
* Version: 					8.0
* Author URI: 				https://meuro.dev
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	5.6
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


defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

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



/*****************************************
 * BOOKING RELATED *
 *****************************************/


// [ BOOKING ]
// create and add download path ASAP after checkout completed
// based on:
// https://stackoverflow.com/questions/47747596/add-downloads-in-woocommerce-downloadable-product-programmatically

add_action('woocommerce_checkout_update_order_meta', 'before_checkout_create_order', 1, 2);
function before_checkout_create_order( $order_id, $data ) {
	GenerateDownloads_afterPayment( $order_id );
}

function GenerateDownloads_afterPayment( $order_id ) {
	///////////
	// **
	// TODO BETTER:
	// duplica ogni tanto i biglietti in $downloads
	// mi sembra che sia se compro più di un item, il secondo riporta anche i download per il primo.
	// ha a che fare sicuro con $downloads che dovrebbe essere svuotato quando passo al prossimo $product
	// forse ridichiarare $downloads = array() a inizio foreach di $items e contemporaneamente spostare $order->save(); dentro il foreach alla fine ?

	// per ora risolto con unique_multidim_array() 
	// per evitare doppi download della stesso item in fase di generazione:
	// in wp-content/themes/accelerate/woocommerce/emails/email-downloads.php#38
	///////////
	if ( ! $order_id )
        return;
    // Allow code execution only once 
    if( ! get_post_meta( $order_id, '_GenerateDownloads_done', true ) ) {

		$order = wc_get_order( $order_id );
		$items = $order->get_items();
		// $downloads = array();

		foreach ( $items as $item_id => $item ) {
			// echo '<pre>$item: <br><br>';
			// print_r($item->get_data());
			// echo '</pre>';

			$cart_item_data = $item->get_data();

			$product = wc_get_product($item->get_product_id());
			$PDFfolder = $product->get_sku();
			$PDFmatrix = get_post_meta($cart_item_data['product_id'],'_product_code', true);
			$last_order_processed = get_post_meta( $cart_item_data['product_id'], 'last_order_processed', true) != '' ? get_post_meta( $cart_item_data['product_id'], 'last_order_processed', true) : 0;
			

			$cart_item_dl = '';

			$cart_item_dl = wc_get_product($cart_item_data['product_id']);
			
			// vedere se ci sono già downloads per questo item, e preservarli!!
			// $cart_item_dl->get_downloads();
			// $older_downloads = $cart_item_dl->get_downloads();
			//print_r($older_downloads);

			
			// Virtual+Downloadable item : YES
			$cart_item_dl->set_virtual( true );
			$cart_item_dl->set_downloadable( true );

			for($k=0; $k<$item['quantity']; $k++) {

				$PDFprogressive = get_post_meta($cart_item_data['product_id'],'_product_code_second', true);
				// add leading zeroes...
				$PDFprogressive_000 = str_pad($PDFprogressive,3,"0", STR_PAD_LEFT);
				// $file_url = get_site_url(null, '/wp-content/uploads/woocommerce_uploads/PDF39/' . $PDFmatrix.'_'.$PDFprogressive_000.'.pdf', 'https');
				$file_url = get_site_url(null, '/wp-content/uploads/woocommerce_uploads/'. $PDFfolder . '/' . $PDFmatrix.'_'.$PDFprogressive_000.'.pdf', 'https');
				$attachment_id = md5( $file_url );

				// Creating a download with... yes, WC_Product_Download class
				$download = new WC_Product_Download();

				$download->set_name( $PDFmatrix.'_'.$PDFprogressive_000.'.pdf' );
				$download->set_id( $attachment_id );
				$download->set_file( $file_url );

				$downloads[$attachment_id] = $download;

				// $cart_item_dl->set_download_limit( 3 ); // can be downloaded only once
				// $cart_item_dl->set_download_expiry( 7 ); // expires in a week

				update_post_meta( $cart_item_data['product_id'], '_product_code_second', $PDFprogressive+1 );

			}

			$cart_item_dl->set_downloads( $downloads );
			$cart_item_dl->save();


			if ($last_order_processed < $order_id) {
				// aggiorno last_order_processed a ordine pagato
				update_post_meta ( $cart_item_data['product_id'], 'last_order_processed', $order_id );
			}

		}

		$order->update_meta_data( '_Order_Downloads', $downloads );

		// Flag the action as done (to avoid repetitions on reload for example)
		$order->update_meta_data( '_GenerateDownloads_done', true );
		$order->save();

	} else {
		$downloads = get_post_meta( $order_id, '_Order_Downloads', true );
	}

	return $downloads;
}


// [ BOOKING ]
// gestisce l'ordine cancellato:
// la quantità a amagazzino viene aggiornata in automatico, ma rimettiamo in vendita i biglietti 
add_action( 'woocommerce_order_status_cancelled', 'respawn_tickets', 
21, 1 );
function respawn_tickets( $order_id ) {

	$downloads 				= get_post_meta( $order_id, '_Order_Downloads', true );
	$unique_downloads 		= unique_multidim_array($downloads,'id');
	
	$order = wc_get_order( $order_id );
	$items = $order->get_items();
	$order_item = [];

	$respawned = 0;

	// LOAD THE WC LOGGER
	$logger = wc_get_logger();
	$logger->info( '==================' );
	$logger->info( "---> Status for order ".$order_id.": ".$order->get_status() );
	$logger->info( "---> tickets that were attached to order #".$order_id.": " );


	foreach ( $items as $item ) {
	  
	    $product = $item->get_product();
	    $productID = $item->get_product_id();

	    //ticket data i need later to rename file
		$order_item['ticket_folder'] 		= $product->get_sku();
		$order_item['ticket_matrix'] 		= get_post_meta($productID, '_product_code', true);
		$order_item['ticket_progressive'] 	= get_post_meta($productID, '_product_code_second', true );
		$order_item['ticket_stock_qty'] 	= $product->get_stock_quantity();
		$order_item['ticket_purchased'] 	= $item->get_quantity();


		for($t=1; $t<=$order_item['ticket_purchased']; $t++) {
			$order_item['ticket_canceled'] 	= str_pad(($order_item['ticket_progressive']-$t),3,"0", STR_PAD_LEFT);

			$order_item['ticket_respawned'] =  str_pad(($order_item['ticket_progressive']+$order_item['ticket_stock_qty']-$t+1),3,"0", STR_PAD_LEFT);

			$order_item['ticket_basepath'] 	= ABSPATH . '/wp-content/uploads/woocommerce_uploads/' . $order_item['ticket_folder'] . "/";

			if ( file_exists($order_item['ticket_basepath'].$order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf") ) {

				// reinserisco il biglietto aggiungendolo in fondo come numerazione
				copy($order_item['ticket_basepath'].$order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf", $order_item['ticket_basepath'].$order_item['ticket_matrix']."_".$order_item['ticket_respawned'].".pdf");

				// disabilito il biglietto dell'ordine annullato anteponendo un underscore
				rename($order_item['ticket_basepath'].$order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf", $order_item['ticket_basepath']."_".$order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf");


				// $logger->info( wc_print_r($order_item, true ) );
				$logger->info( $order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf --> ".$order_item['ticket_matrix']."_".$order_item['ticket_respawned'].".pdf" );

				$respawned++;

			} else {
				$logger->info( "".$order_item['ticket_matrix']."_".$order_item['ticket_canceled'].".pdf --> Errore! File non trovato." );
			}
		}

		//$logger->info( wc_print_r($order_item, true ) );

	}

	$logger->info( "Order #".$order_id." status changed to: " . $order->get_status() );

	$logger->info( $respawned . " of " . count($unique_downloads) . " tickets were respawned" );

	// too much.. viene cestinato in automatico dopo 1 giorno
	// $order->update_status('trash', 'Ordine annullato manualmente, biglietti rimessi in vendita.');


}



/*****************************************
 * EMAIL RELATED *
 *****************************************/


// [ EMAIL ]
// aggiungo pdf acquistati come allegato
add_filter( 'woocommerce_email_attachments', 'attach_to_wc_emails', 10, 4);
function attach_to_wc_emails( $attachments, $email_id, $order, $wc_email ) {

	// Avoiding errors and problems
    if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) || !$wc_email->is_customer_email() || $order->get_status() != 'completed' ) {
        return $attachments;
    }
  	$order_id 				= $order->get_order_number();
  	// $downloads             	= $order->get_downloadable_items();
  	$downloads             	= get_post_meta( $order_id, '_Order_Downloads', true );
  	$unique_downloads 		= unique_multidim_array($downloads,'id');

	// LOAD THE WC LOGGER
	$logger = wc_get_logger();
	$logger->info( '==================' );
	$logger->info( "---> Status for order ".$order_id.": ".$order->get_status() );
	$logger->info( "---> EMAIL ATTACHMENTS for order #".$order_id.": " );
	//$logger->info( wc_print_r($downloads, true ) );


  	foreach ($unique_downloads as $download) {

		$DL_path = parse_url($download["file"], PHP_URL_PATH);
		$DL_path = ltrim($DL_path, '/');

		$logger->info( wc_print_r(ABSPATH . $DL_path, true ) );

  		$attachments[] = ABSPATH . $DL_path;

  	}

	return $attachments;
}

// [ EMAIL ] 
// invia email "ordine completato" anche a admin
add_filter( 'woocommerce_email_recipient_customer_completed_order', 'your_email_recipient_filter_function', 10, 2);

function your_email_recipient_filter_function($recipient, $object) {
    $recipient = $recipient . ', booking@agenziaviaggiltc.it';
    return $recipient;
}

// [ EMAIL ] 
// *** TEMPORANEAMENTEH *** 
// invia tutte le email anche a me!!
function woo_cc_all_emails() {
  return 'Bcc: hello@meuro.dev' . "\r\n";
}
add_filter('woocommerce_email_headers', 'woo_cc_all_emails' );



// [ EMAIL ]
// aggiungo codici sconto utilizzati e cod.biglietto riservato
add_action('woocommerce_email_customer_details', 'email_order_user_meta', 30, 3 );
function email_order_user_meta( $order, $sent_to_admin, $plain_text ) {
  	$order_id 				= $order->get_order_number();
  	// $downloads             	= $order->get_downloadable_items();
  	$downloads             	= get_post_meta( $order_id, '_Order_Downloads', true );
  	$unique_downloads 		= unique_multidim_array($downloads,'id');


  	if($order->get_status() != 'cancelled') {
	  	// LOAD THE WC LOGGER
		$logger = wc_get_logger();
		$logger->info( '==================' );
		$logger->info( "---> Status for order ".$order_id.": ".$order->get_status() );
		$logger->info( "---> listing reserved tickets # for order ".$order_id.": " );
		// $logger->info( wc_print_r($downloads, true ) );


	  	if (!empty($unique_downloads)) :
	  		$ticket_count = count( $unique_downloads );
			echo '<p><strong>Biglietti acquistati (' . $ticket_count . '):</strong><br>';
			foreach ($unique_downloads as $download) {
				$ticket_code = str_ireplace('.pdf', '', $download['name']);
				echo $ticket_code.'<br>';

				$logger->info( wc_print_r($ticket_code, true ) );
			}
			echo '</p>';
		endif;

		if( $order->get_used_coupons() ) :
			$coupons_count = count( $order->get_used_coupons() );
			echo '<p><strong>Codici sconto utilizzati (' . $coupons_count . '):</strong><br>';
			$i = 1;
			$coupons_list = '';
			foreach( $order->get_used_coupons() as $coupon) {
			    echo $coupon.'<br>';
			}
			echo $coupons_list . '</p>';
		endif;
	}
}







/*****************************************
 * FRONTEND ENHANCEMENTS *
 *****************************************/


// [ FRONTEND ]
// add cart items number in menu after "shop"
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

    // unset( $tabs['description'] );      	// Remove the description tab
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








/*****************************************
 * BACKEND ENHANCEMENTS *
 *****************************************/

// [ BACKEND ]
// stampo codici biglietti per singolo ordine in lista ordini
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns)
{
    $reordered_columns = array();

    // Inserting columns to a specific location
    foreach( $columns as $key => $column){
        $reordered_columns[$key] = $column;
        if( $key ==  'order_status' ){
            // Inserting after "Status" column
            $reordered_columns['ticket_codes'] = __( 'Biglietti','theme_domain');
        }
    }
    return $reordered_columns;
}
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id )
{
    switch ( $column )
    {
        case 'ticket_codes' :
            // Get custom post meta data
            $downloads = get_post_meta( $post_id, '_Order_Downloads', true );
            if(!empty($downloads)){
            	echo '<p class="ticket_codes"><small>';
				foreach($downloads as $ticket) {
					//print_r($ticket);
					echo $ticket["name"].'<br/>';
				}
				echo '</small></p>';
            }
            // Testing (to be removed) - Empty value case
            else {
                echo '<small><em>no tickets (???)</em></small>';
            }

            break;


        // case 'gigi' :
        	//...
            // break;
    }
}




// [ BACKEND ]
// stampo codici biglietti e codici utilizzati in riepilogo ordine
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'ltc_PrintTicketNumber' );

function ltc_PrintTicketNumber( $order ){
	echo '<div class="clear"></div>';
	//print_r($order->get_id());
	$order_id = $order->get_id();
	$downloads = get_post_meta( $order_id, '_Order_Downloads', true );

	if ($downloads) {
		echo '<h3>Biglietti assegnati al cliente</h3>';
		echo '<p>';
		foreach($downloads as $ticket) {
			//print_r($ticket);
			echo $ticket["name"].'<br/>';
		}
		echo '</p>';
		echo '<div class="clear"></div>';
	}

	// eventuali codici sconto
	if( $order->get_used_coupons() ) {
    	$coupons_count = count( $order->get_used_coupons() );
		echo '<div class="clear"></div>';
        echo '<h3>Codici sconto utilizzati:</h3> ';
        $i = 1;
        echo '<p>';
        foreach( $order->get_used_coupons() as $coupon) {
	        echo $coupon;
	        if( $i < $coupons_count )
	        	echo ', ';
	        $i++;
        }
        echo '</p>';
    }	
}



// [ BACKEND ]
// password per gli utenti meno difficile

/**
* Reduce the strength requirement on the woocommerce password.
* 3 = Strong  ...  0 = Very Weak
*/
function reduce_woocommerce_min_strength_requirement( $strength ) {
  return 2;
}
add_filter( 'woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement' );