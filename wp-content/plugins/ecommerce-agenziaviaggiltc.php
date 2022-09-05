<?php
/*
* Plugin Name: 				Ecommerce agenziaviaggiLTS
* Description: 				funzioni specifiche per l'ecommerce di agenziaviaggiLTC. Richiede i plugin woocommerce + product-code-for-woocommerce
* Author: 					Meuro
* Version: 					0.0.71
* Author URI: 				https://meuro.dev
* License: 					GPLv3 or later
* License URI:         		http://www.gnu.org/licenses/gpl-3.0.html
* Requires PHP: 	    	5.6
* Text Domain: 				ecommerce-lts
* Domain Path: 				/languages
*/

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
				$downloads[$attachment_id]['used_in_order']= $order_id;
				$downloads[$attachment_id]['folder']= $PDFfolder;

				$cart_item_dl->set_download_limit( 3 ); // can be downloaded only once
				$cart_item_dl->set_download_expiry( 7 ); // expires in a week

				// print_r( $downloads );
				update_post_meta( $cart_item_data['product_id'], '_product_code_second', $PDFprogressive+1 );

			}

			$cart_item_dl->set_downloads( $downloads );
			$cart_item_dl->save();


			// echo '<pre>$downloads: <br><br>';
			// print_r($downloads);
			// echo '</pre>';

			// update_post_meta( $cart_item_data['product_id'], '_product_code_second', $PDFprogressive+$item['quantity'] );

			if ($last_order_processed < $order_id) {
				//echo 'wwwwwww';
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



add_filter( 'woocommerce_email_attachments', 'attach_to_wc_emails', 10, 4);
function attach_to_wc_emails( $attachments, $email_id, $order, $wc_email ) {

	// Avoiding errors and problems
    if ( ! is_a( $order, 'WC_Order' ) || ! isset( $email_id ) || !$wc_email->is_customer_email() ) {
        return $attachments;
    }
  	$order_id 				= $order->get_order_number();
  	$downloads             	= $order->get_downloadable_items();
  	$unique_downloads 		= unique_multidim_array($downloads,'download_id');

  	// TODO: recuperare $PDFfolder!!!
  	// forse lo devo appendere a ogni singolo item di $downloads in fase di creazione
  	$PDFfolder = 'PDF39';

  	foreach ($unique_downloads as $download) {
  		// $attachments[] = ABSPATH . "/wp-content/uploads/woocommerce_uploads/".$order_id.".pdf";
  		// LOAD THE WC LOGGER
	   $logger = wc_get_logger();
	    
	   // LOG NEW PRICE TO CUSTOM "price-changes" LOG
	   $logger->info( wc_print_r( $download, true ) );
	   $logger->info( 'attachment url:'.$download['file']['file'] );
	 

  		$attachments[] = $download['file']['file'];
  		// $attachments[] = ABSPATH . "/wp-content/uploads/woocommerce_uploads/". $download['folder'] . "/" . $download['download_name'];
  	}

	return $attachments;
}


add_action('woocommerce_email_customer_details', 'email_order_user_meta', 30, 3 );
function email_order_user_meta( $order, $sent_to_admin, $plain_text ) {
  	$order_id 				= $order->get_order_number();
  	$downloads             	= $order->get_downloadable_items();
  	$unique_downloads 		= unique_multidim_array($downloads,'download_id');

  	if (!empty($unique_downloads)) :
		echo '<p><strong>codici biglietti acquistati:</strong><br>';
		foreach ($unique_downloads as $download) {
			$ticket_code = str_ireplace('.php', '', $download['download_name']);
			echo $ticket_code.'<br>';
		}
		echo '</p>';
	endif;
}


// remove jetpack Related Posts in woocommerce products page
function jetpackme_remove_related() {
    if ( class_exists( 'Jetpack_RelatedPosts' ) && is_product() ) {
        $jprp = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
 
        remove_filter( 'the_content', $callback, 40 );
    }
}
add_action( 'wp', 'jetpackme_remove_related', 20 );



// Remove product data tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;
}

// custom checkout fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_company']['label'] = 'Codice Fiscale';
    $fields['billing']['billing_company']['required'] = true;
    unset($fields['billing']['billing_address_2']);
    
    return $fields;
}


/**
 *Reduce the strength requirement on the woocommerce password.
 *
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
function reduce_woocommerce_min_strength_requirement( $strength ) {
  return 2;
}
add_filter( 'woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement' );





/*
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



// backend enhancements:
add_action( 'woocommerce_before_order_itemmeta', 'so_7241_before_order_itemmeta', 10, 3 );
function so_7241_before_order_itemmeta( $item_id, $item, $_product ){
	// TODO: 
	// mostrare i vari codici dei biglietti generati per questo specifico ordine
	echo "<p>bacon!</p>";
}