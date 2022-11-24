<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Woocommerce to Squalomail: clients list sync</title>
	<style type="text/css">
		html,body {
			margin: 0;
			padding: 0;
			font: 14px/22px Arial, Helvetica, sans-serif;
			color: #4d4d4d;
			background: #f5f5f5;
		}
		body {
			width: 100%;
			min-height: 100vh;
			max-width: 400px;
			margin: 0 auto;
			overflow-x: hidden;
			background: #fff;
			padding: 20px;
			box-sizing: border-box;
			line-break: anywhere;
		}
		#b64c {
			width: 100%;
			line-break: anywhere;
			overflow-y: scroll;
			height: 300px;
			display: inline-block;
			padding: 10px 15px 10px 5px;
			margin: 20px 0;
			box-sizing: border-box;
			border: 1px solid #999;
			border-radius: 3px;
			font-size: 11px;
			line-height: 14px;
			text-align: justify;
			background: #fff;
		}
	</style>
</head>
<body>


<?php 
include './ltc-export-data.php';

echo '[Sync db] starting sync...  <br>';
echo "[Sync db] service url: $api_url <br> <br>";

echo "[Sync db] retrieving data from csv: $csv_filename <br>";


$handle = fopen($csv_filename, "rb");
$contents = stream_get_contents($handle);
// print_r($contents);
$b64_contents = base64_encode($contents);
// echo 'kjsnbfkwrjw_____<br>';
echo "[Sync db] encoding content: <br>";
echo "<span id=\"b64c\">$b64_contents</span>";
fclose($handle);
echo "[Sync db] data encoded, calling API: <br>";

$SQMkey = '01ngKDBQUQUnkcy6QITwW9Gyek7sZq9G';

// API CALL
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.squalomail.com/v1/import-recipients-async',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "apiKey": "'.$SQMkey.'",
    "autogenerateNames": "false",
    "listIdsToAdd": ["1"],
    "overwriteData": "1",
    "clearPreviousListIds": "false",
    "importAsDisabled": "false",
    "base64EncodedFile": "'.$b64_contents.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo "[Sync db] sync operation ended, response : <br>";

print_r($response);
$jresponse = json_decode($response);
echo " <br> <br><b>http status: $jresponse->httpStatusCode</b> <br> <br>"
 ?>


</body>
</html>