<?php 
// [ BACKEND ]
// export utenti coi campi che ci servono a noi

// * API prod (readonly)
$ck='ck_949470a85574c84b7a3cc662ca8f58cd7c7b3679';
$cs='cs_faf8293e8b36f6e0b41d49db552a5057a061d9f8';
$api_url = 'https://www.agenziaviaggiltc.it/wp-json/wc/v3/customers?consumer_key='.$ck.'&consumer_secret='.$cs.'&orderby=id&order=desc&per_page=30';
$json_filename = ABSPATH . 'wp-content/uploads/customer-data.json';
$csv_filename = ABSPATH . 'wp-content/uploads/customer-data.csv';
$csv_url =  'https://www.agenziaviaggiltc.it/wp-content/uploads/customer-data.csv';


// retrieves max last 6000 clients
// if not enough, raise that "20" in the for cycle

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
			$row['ID_WORDPRESS'] = $row['id'];
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
				// 'orderby' => 'date',
				// 'order' => 'DESC',
				'meta_value' => $row['ID'],
				'post_type' => 'shop_order',
				'post_status'    => 'completed'
				//'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing'),
		    ));
		    $orders_count = count($customer_orders);
		    $coupons = [];

			foreach($customer_orders as $order) :
				$orderID = $order->ID;
				$order = wc_get_order( $orderID );

				if( $order->get_used_coupons() ) {
					$coupons_count = count( $order->get_used_coupons() );
					$i = 1;
					foreach( $order->get_used_coupons() as $coupon) {
					    $coupons[] = $coupon;
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

			$row['NOTE'] = $row['order_notes'];
			unset($row['order_notes']);





			// echo '$header: '.$header;
		    if (empty($header)) {
		        $header = array_keys($row);
		        fputcsv($fp, $header);
		        $header = array_flip($header);
		        //print_r($header);
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