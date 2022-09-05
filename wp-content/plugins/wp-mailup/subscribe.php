<?php
/**
 * @package WP-MailUp
 * Subscribe request to xmlSubscribe.aspx
 */

/**
 * Response codes: (xmlSubscribe.aspx)
 * 0 - Operation completed with success
 * 1 - Generic error
 * 2 - Invalid email address or phone number
 * 3 - User already subscribed
 * Response codes: custom
 * 10 - terms and conditions not accepted 
 * 11 - other terms and conditions not accepted
 */

error_reporting(0);

if(@$_REQUEST['termsAccept'] != 'yes')
{
	exit('10');
}
if(@$_REQUEST['terms2Accept'] != 'yes')
{
	exit('11');
}
if(@$_REQUEST['token'] == 'subscribe')
{
	
	$url = @$_REQUEST['subsUrl'];
	if(trim($url) == '')
	{
		exit('1');
	}
	
	$post_data = '';
	
	$post_data .= "source=generic";
	
	if($_REQUEST['Email'])
	{
		$post_data .= "&Email=".$_REQUEST['Email'];
	}
	if($_REQUEST['sms'])
	{
		$post_data .= "&sms=".$_REQUEST['sms'];
	}
	if($_REQUEST['List'])
	{
		$post_data .= "&List=".$_REQUEST['List'];
	}
	if($_REQUEST['Group'])
	{
		$post_data .= "&Group=".$_REQUEST['Group'];
	}
	if($_REQUEST['Confirm'])
	{
		$post_data .= "&Confirm=".$_REQUEST['Confirm'];
	}
	if($_REQUEST['csvFldNames'])
	{
		$post_data .= "&csvFldNames=".$_REQUEST['csvFldNames'];
	}
	if($_REQUEST['csvFldValues'])
	{
		$post_data .= "&csvFldValues=".$_REQUEST['csvFldValues'];
	}
	$post_data .= "&retCode=1";
	$post_data .= "&source=generic";
	
	if(!function_exists('curl_init'))
	{
		exit(1);
	}
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url );
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec( $ch );
	
	$response = trim($response);
	if(($response != '0') && ($response != '1') && ($response != '2') && ($response != '3')&& ($response != '-1011'))
	{
		exit('1');
	}
	else
	{
		echo $response;
	}
}
?>