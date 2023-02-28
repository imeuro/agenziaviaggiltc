<?php 
// [ BACKEND ]
// export utenti coi campi che ci servono a noi
if ($_SERVER['SERVER_NAME'] == 'www.agenziaviaggiltc.it') :
	$ABSURL = 'https://www.agenziaviaggiltc.it/';
	//$ABSPATH = ABSPATH;
	$ABSPATH = '/home/customer/www/agenziaviaggiltc.it/public_html/';
elseif ($_SERVER['SERVER_NAME'] == 'meuro.dev') :
	$ABSURL = 'https://meuro.dev/agenziaviaggiltc/';
	$ABSPATH = '/home/pi/www_root/agenziaviaggiltc/';
elseif ($_SERVER['SERVER_NAME'] == 'localhost') :
	$ABSURL = 'https://localhost/agenziaviaggiltc/';
	$ABSPATH = '/Users/meuro/Sites/agenziaviaggiltc/';
endif;

// * API prod (readonly)
$ck='ck_949470a85574c84b7a3cc662ca8f58cd7c7b3679';
$cs='cs_faf8293e8b36f6e0b41d49db552a5057a061d9f8';
$api_url = $ABSURL . 'wp-json/wc/v3/customers?consumer_key='.$ck.'&consumer_secret='.$cs.'&orderby=id&order=desc&per_page=30';
$json_filename = $ABSPATH . 'wp-content/uploads/customer-data.json';
$csv_filename = $ABSPATH . 'wp-content/uploads/customer-data.csv';
$csv_url =  $ABSURL . 'wp-content/uploads/customer-data.csv';


// retrieves max last 6000 clients
// if not enough 🤭, raise that "20" in the for cycle

function retrieveAPIdata($endpoint,$force_regenerate) {
	global $json_filename, $csv_filename, $csv_url;
	if (( isset($_GET['regenerate_csv']) && $_GET['regenerate_csv'] == 'true' ) || $force_regenerate === true) {
		$responseBody='';
		for($i = 1; $i < 20; $i++) {
			$response = wp_remote_get($endpoint.'&page='.$i);
			$responseData = $response['body'];
			//print_r($responseData);
			if ($responseData == "[]") {
				break;
			}
			$responseBody .= $responseData;
			$responseBody = str_replace("][",",",$responseBody);
		}
		file_put_contents($json_filename, $responseBody);
	}
	//print_r($responseBody);
}

function jsonAPIToCSV($jfilename, $cfilename, $force_regenerate) {
    if (($json = file_get_contents($jfilename)) == false)
        die('Error reading json file from '.$jfilename.'...');

    if (( isset($_GET['regenerate_csv']) && $_GET['regenerate_csv'] == 'true' ) || $force_regenerate === true) {
		$data = json_decode($json, true);
		$fp = fopen($cfilename, 'w');
		$header = false;

		// qui devo ciclare, in tutti gli ordini del sito, i metadata e vedere quelli che cominciano con "vacanzestudio", in modo da poter predisporre una colonna per ogni campo del form
		// !! : forse posso fare query per ordini con uno specifico metadata che mi indicano presenza di "form lungo"

		$HeadersList = [];
		$loop = new WP_Query( array(
			'post_type'         => 'shop_order',
			'post_status'       =>  array_keys( wc_get_order_statuses() ),
			'posts_per_page'    => -1,
			'meta_key' 			=> '_Order_Flag',
			'meta_value' 		=> 'longform',
		) );

		// The Wordpress post loop
		if ( $loop->have_posts() ): 
			while ( $loop->have_posts() ) : 
				$loop->the_post();

				// The order ID
				$order_id = $loop->post->ID;

				// Get an instance of the WC_Order Object
				$order = wc_get_order($loop->post->ID);

				foreach( $order->get_meta_data() as $meta_data_obj ) {
					$meta_data_array = $meta_data_obj->get_data();

					$meta_key   = $meta_data_array['key']; // The meta key
					$meta_value = $meta_data_array['value']; // The meta value
					if ( startsWith($meta_key,'vacanzestudio_') ) {
						$HeadersList[] = $meta_data_array['key'];
					}
				}

			endwhile;

		wp_reset_postdata();

		endif;


		// ...
		// mi tiro fuori un array ($HeadersList) , e poi passo a creare le $row (da riga ~115) così ottengo le intestazioni della colonna
		
		// echo "<pre>ooo";
		// print_r($HeadersList);
		// echo "</pre>";
		// die();


		foreach ($data as $key => $row) {


			// don't need this data:
			unset($row['username']);
			unset($row['date_created']);
			unset($row['date_modified']);
			unset($row['date_created_gmt']);
			unset($row['date_modified_gmt']);
			unset($row['role']);
			unset($row['shipping']);
			unset($row['is_paying_customer']);
			unset($row['avatar_url']);
			unset($row['billing']['first_name']);
			unset($row['billing']['last_name']);
			unset($row['billing']['email']);

			unset($row['meta_data']);
			unset($row['role']);
			unset($row['_links']);
			unset($row['collection']);

			foreach ($row['billing'] as $key => $billing) {
				$row['billing_'.$key] = $billing;
			}
			unset($row['billing']);

		    //rename headers:
			$row['ID'] = $row['id'];
			unset($row['id']);
			$row['MAIL'] = $row['email'];
			unset($row['email']);
			$row['COGNOME'] = $row['last_name'];
			unset($row['last_name']);
			$row['NOME'] = $row['first_name'];
			unset($row['first_name']);
			$row['SESSO'] = $row['sex'];
			unset($row['sex']);
			$row['INDIRIZZO'] = $row['billing_address_1'].' '.$row['billing_address_2'];
			unset($row['billing_address_1']);
			unset($row['billing_address_2']);
			$row['CAP'] = $row['billing_postcode'];
			unset($row['billing_postcode']);
			$row['COMUNE'] = $row['billing_city'];
			unset($row['billing_city']);
			$row['PROVINCIA'] = $row['billing_state'];
			unset($row['billing_state']);
			$row['STATO'] = $row['billing_country'];
			unset($row['billing_country']);
			$row['TELEFONO'] = $row['billing_phone'];
			unset($row['billing_phone']);
			$row['CODICE FISCALE'] = strtoupper($row['billing_company']);
			unset($row['billing_company']);
			$row['DATA DI NASCITA'] = $row['birth_date'];
			unset($row['birth_date']);

			// Get all customer orders
			$customer_orders = get_posts(array(
				'numberposts' => -1,
				'meta_key' => '_customer_user',
				'meta_value' => $row['ID'],
				'post_type' => 'shop_order',
				'post_status'    => 'completed'
		    ));
		    $orders_count = count($customer_orders);
		    $coupons = [];
		    $questionario = [];

			foreach($customer_orders as $order) :
				$orderID = $order->ID;
				$order = wc_get_order( $orderID );

				// coupons
				if( $order->get_used_coupons() ) {
					$coupons_count = count( $order->get_used_coupons() );
					$i = 1;
					foreach( $order->get_used_coupons() as $coupon) {
					    $coupons[] = $coupon;
					}
				}
				// additional data
				if ( sizeof( $order->get_items() ) > 0 ) {
					foreach( $order->get_items() as $item ) {						

						$fullmeta = get_post_meta( $order->get_id());
						$questionario = array_filter($fullmeta, function($key) {
							return strpos($key, 'vacanzestudio_') === 0;
						}, ARRAY_FILTER_USE_KEY);

					}
				}
			endforeach;

			$unique_coupons = array_unique($coupons);
			$used_coupons = ' — ';
			if(!empty($unique_coupons)) {
				$used_coupons = '';
				foreach($unique_coupons as $unique_coupon) :
					$used_coupons .= $unique_coupon.' ';
				endforeach;				
			}

			$row['CODICE SCONTO'] = $used_coupons;

			$row['ACQUISTI'] = $orders_count;

			// comunque creo le colonne per i dati del longform
			foreach ($HeadersList as $heading) {
				$humanheading = strtoupper(str_replace('_',' ',$heading));
				$row[$humanheading] = ' - ';
			}
			$qval = ' - ';
			// poi se sono valorizzate, sovrascrivo il ' - ' con il valore
			if(!empty($questionario)) {
				$questionarii = '';
				foreach($questionario as $key => $value) :
					$humankey = strtoupper(str_replace('_',' ',$key));
					$qval = $value[0];
					$row[$humankey] = $qval;
				endforeach;	
			}
			// unset($row[$key]);



			// ste stronze non ne vogliono sapere proprio... 
			$row['NOTE'] = $row['customer_notes'];
			unset($row['customer_notes']);


			// echo '$header: '.$header;
			if (empty($header)) {
			    $header = array_keys($row);
			    fputcsv($fp, $header);
			    $header = array_flip($header);
			    // echo '<pre>qqq';
			    // print_r($header);
			    // echo '</pre>';
			}
		    
		    fputcsv($fp, array_merge($header, $row));
		}

		fclose($fp);
	}
    return;
} 

function csvPreview($cfilename) {
	$row = 0;
	if (($handle = fopen($cfilename, "r")) !== FALSE) {

		echo '<div id="ltc_table_container"><table id="ltc_table" cellspacing="0" cellpadding="0">';
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
		echo "</tbody></table></div>\n\n";
		fclose($handle);
	}
}

?>