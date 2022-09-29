<?php

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
