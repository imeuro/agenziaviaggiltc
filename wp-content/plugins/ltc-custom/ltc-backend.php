<?php

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



// [ BACKEND ]
// export untenti coi campi che ci servono a noi

function jsonAPIToCSV($jfilename, $cfilename) {
    if (($json = file_get_contents($jfilename)) == false)
        die('Error reading json file from '.$jfilename.'...');

    echo '<style>#ltc_table {display: block; overflow-x: auto; white-space: nowrap;}#ltc_table tbody {display: table; width: 100%;}#ltc_table th {font-size: 0.85rem; font-weight: 700; padding: 8px 24px; background-color: #f8f9fa; border-bottom: 1px solid #e2e4e7; text-align: left;}#ltc_table tr { background-color: #fff; }#ltc_table tr:hover { background-color: #ddd; }#ltc_table td {font-size: 0.75rem; font-weight: 400; padding: 15px 24px; margin-bottom: 10px; border-bottom: 1px solid #e2e4e7; text-align: left;}div#ltc_section_header {display: flex; flex-flow: row wrap; align-items: center; justify-content: space-between; margin: 0 15px 15px 0;}</style>';

    if (isset($_GET['regenerate_csv']) && $_GET['regenerate_csv'] == 'true') {
		$data = json_decode($json, true);
		$fp = fopen($cfilename, 'w');
		$header = false;

		foreach ($data as $akey => $row) {


		    // don't need this data:
		    unset($row['date_created']);
		    unset($row['date_modified']);
		    unset($row['role']);
		    unset($row['shipping']);
		    unset($row['is_paying_customer']);
		    unset($row['avatar_url']);
		    unset($row['billing']['first_name']);
		    unset($row['billing']['last_name']);

		    unset($row['meta_data']);
		    unset($row['role']);
		    unset($row['_links']);
		    unset($row['collection']);

			// switch ( $akey ) {
			// 	case 'date_created_gmt' :
			// 		$header = 'Primo Accesso';
			// 		break ;
			// 	case 'date_modified_gmt' :
			// 		$header = 'Ultimo Accesso';
			// 		break ;
			// 	case 'first_name' :
			// 		$header = 'Nome';
			// 		break ;
			// 	case 'last_name' :
			// 		$header = 'Cognome';
			// 		break ;
			// }

			foreach ($row['billing'] as $key => $billing) {
		    	$row['billing_'.$key] = $billing;
			}

			unset($row['billing']);

			// echo '$header: '.$header;
		    if (empty($header)) {
		        $header = array_keys($row);
		        fputcsv($fp, $header);
		        $header = array_flip($header);
		    }
		    //print_r($header);
		    fputcsv($fp, array_merge($header, $row));
			//fputcsv($fp, $row);
		}
		fclose($fp);
	}
    return;
} 

function csvPreview($cfilename) {
	$row = 0;
	if (($handle = fopen($cfilename, "r")) !== FALSE) {

		echo '<table id="ltc_table" cellspacing="0" cellpadding="0">';
		echo '<tbody>';
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			echo "<tr>\n";
			for ($c=0; $c < $num; $c++) {
			    echo ($row==0) ? "<th>" : "<td>";
			    echo $data[$c];
			    echo ($c==0) ? "</th>\n" : "</td>\n";
			}
			echo "</tr>\n";
			$row++;
		}
		echo "</tbody></table>\n\n";
		fclose($handle);
	}
}

add_action( 'admin_menu', 'lts_add_admin_menu' );
add_action( 'admin_init', 'lts_settings_init' );


function lts_add_admin_menu(  ) { 

	add_menu_page( 'Ecommerce agenziaviaggiLTS - export utenti', 'Export Clienti', 'manage_options', 'lts-export-clienti', 'lts_options_page', 'dashicons-database-export', 70 );

}


function lts_settings_init(  ) { 

	register_setting( 'pluginPage', 'lts_settings' );

	add_settings_section(
		'lts_pluginPage_section', 
		__( 'Pagina di Esportazione dati Clienti', 'woocommerce' ), 
		'lts_settings_section_callback', 
		'pluginPage'
	);


}



function lts_settings_section_callback(  ) { 

	$csv_url =  get_site_url(null, '/wp-content/uploads/customer-data.csv', 'https');
	echo '<div id="ltc_section_header">';
	echo "<script>function GETcsv(){window.open('".$csv_url."');}</script>";
	echo __( '<p>Da questa pagina è possibile eseguire il download della lista clienti e relativi dettagli in formato CSV, importabile in excel.</p>', 'woocommerce' );
	echo '<div><input type="hidden" name="page" value="lts-export-clienti" />
	<input type="hidden" name="regenerate_csv" value="true" />
	<button type="submit" value="regenerate_csv" class="button button-large button-primary">1. Aggiorna Lista Clienti</button>
	<button type="button" onclick="GETcsv()" class="button button-large button-primary">2. Download CSV</button></div>';
	echo '</div>';


}


function lts_options_page(  ) { 

		?>
		<form action='./admin.php' method='GET' name="gencsv" >
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );

			// * prod
			$ck='ck_949470a85574c84b7a3cc662ca8f58cd7c7b3679';
			$cs='cs_faf8293e8b36f6e0b41d49db552a5057a061d9f8';
			$api_url = 'https://www.agenziaviaggiltc.it/wp-json/wc/v3/customers?consumer_key='.$ck.'&consumer_secret='.$cs;


			$response = wp_remote_get($api_url);
			// $responseData = json_encode($response['body']);
			$responseData = $response['body'];


			// echo '$responseData: <pre>'; 
			// print_r($responseData);
			// echo '</pre>';
			// curl_close($ch);
			$json_filename = ABSPATH . 'wp-content/uploads/customer-data.json';
			$csv_filename = ABSPATH . 'wp-content/uploads/customer-data.csv';
			$csv_url =  get_site_url(null, '/wp-content/uploads/customer-data.csv', 'https');
			file_put_contents($json_filename, $responseData);


			// convert to csv
			jsonAPIToCSV($json_filename, $csv_filename);

			// display csv preview
			csvPreview($csv_filename);

			echo '<p>csv file successfully generated. <a href="' . $csv_url . '" target="_blank">Click here to open it.</a><p>';

			?>

		</form>
		<?php

}
