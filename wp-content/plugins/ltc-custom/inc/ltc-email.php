<?php 

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

  	if ( empty($downloads) ) {
        return $attachments;
    }

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

  	$logger->info( wc_print_r($downloads, true ) );
  	if ( empty($downloads) ) {
  		$logger->info( '==================' );
  		$logger->info( 'no downloads for order #'.$order_id );
        return;
    }

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

