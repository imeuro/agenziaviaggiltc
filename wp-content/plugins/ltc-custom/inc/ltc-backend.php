<?php

/*****************************************
 * BACKEND ENHANCEMENTS *
 *****************************************/

// [ BACKEND ]
// stampo codici biglietti per singolo ordine in lista ordini
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column($columns) {
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
function custom_orders_list_column_content( $column, $post_id ) {
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
                echo '<small><em>nessun biglietto acquistato (???)</em></small>';
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

function ltc_PrintTicketNumber( $order ) {
	echo '<div class="clear"></div>';
	//print_r($order->get_id());
	$order_id = $order->get_id();
	$downloads = get_post_meta( $order_id, '_Order_Downloads', true );

	if ($downloads) {
		echo '<h3>Biglietti assegnati al cliente</h3>';
		echo '<p>';
		foreach($downloads as $ticket) {
			//print_r($ticket);
			$basepath 	= str_replace($ticket['name'],'',$ticket['file']);
			$sku		= str_replace(get_site_url().'/wp-content/uploads/woocommerce_uploads/','',$basepath);

			echo rtrim($sku,"/"). ' / ' .$ticket["name"].'<br/>';
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


// [ BACKEND ]
// export utenti coi campi che ci servono a noi
// vedi ./ltc-export-data.php

add_action( 'admin_menu', 'ltc_add_admin_menu' );
add_action( 'admin_init', 'ltc_settings_init' );
add_action( 'admin_enqueue_scripts', 'ltc_enqueue_css', 10 );




add_action('LTC_daily_action', 'refreshCSV');
function refreshCSV() {
	// retrieve data from API
	retrieveAPIdata($api_url, true);
	// convert to csv
	jsonAPIToCSV($json_filename, $csv_filename, true);
}
if ( ! wp_next_scheduled( 'LTC_daily_action' ) ) {
    wp_schedule_event( time(), 'minutes_10', 'LTC_daily_action' );
}



function ltc_add_admin_menu(  ) { 

	add_menu_page( 'Export Clienti', 'Export Clienti', 'manage_options', 'lts-export-clienti', 'lts_options_page', 'dashicons-database-export', 70 );

}

function ltc_enqueue_css( $hook_suffix ) {
	// print_r($hook_suffix);
    if( 'toplevel_page_lts-export-clienti' === $hook_suffix ) {       
        wp_enqueue_style('ltc-custom-css', plugins_url('../css/style.css',__FILE__));
    }
}

function ltc_settings_init() { 

	register_setting( 'pluginPage', 'lts_settings' );

	add_settings_section(
		'lts_pluginPage_section', 
		__( 'Pagina di Esportazione dati Clienti', 'woocommerce' ), 
		'lts_settings_section_callback', 
		'pluginPage'
	);


}

function lts_settings_section_callback() { 
	global $csv_url;

	date_default_timezone_set('Europe/Rome');
	$nextRun = date('j M H:i', wp_next_scheduled( 'LTC_daily_action' ));
	$disabled = (isset($_GET['regenerate_csv']) && $_GET['regenerate_csv'] == 'true') ? '' : ' disabled';
	echo '<div id="ltc_section_header">';
	echo "<script>function GETcsv(){window.open('".$csv_url."');}</script>";
	echo __( '<p class="page_spiega">Da questa pagina è possibile eseguire il download della lista clienti e relativi dettagli in formato CSV, importabile in excel.<br>Prossima generazione automatica: '.$nextRun.'</p>', 'woocommerce' );
	echo '<div class="page_agisci"><input type="hidden" name="page" value="lts-export-clienti" />
	<input type="hidden" name="regenerate_csv" value="true" />
	<button type="submit" value="regenerate_csv" class="button button-large button-primary dashicons-before dashicons-update">&nbsp;&nbsp;Aggiorna Lista Clienti</button>
	<button'.$disabled.' type="button" onclick="GETcsv()" class="button button-large button-primary dashicons-before dashicons-download">&nbsp;&nbsp;Download CSV</button></div>';
	echo '</div>';

}

function lts_options_page() { 
	global $api_url, $json_filename, $csv_filename, $csv_url;

		?>
		
		<form action='./admin.php' method='GET' name="gencsv" >
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );

			global $api_url;
			// retrieve data from API
			retrieveAPIdata($api_url, false);

			// convert to csv
			jsonAPIToCSV($json_filename, $csv_filename, false);

			// display csv preview
			csvPreview($csv_filename);

			echo '<p>csv file successfully generated. <a href="' . $csv_url . '" target="_blank">Click here to open it.</a><p>';

			?>

		</form>
		<?php

}

