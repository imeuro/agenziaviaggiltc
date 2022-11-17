<script>
	
	const key = '01ngKDBQUQUnkcy6QITwW9Gyek7sZq9G';


// (async () => {
//   const rawResponse = await fetch('http://api.squalomail.com/v1/', {
//     method: 'POST',
//     headers: {
//       'Accept': 'application/json',
//       'Content-Type': 'application/json'
//     },
//     body: JSON.stringify(
//     	{
//     		a: 1,
//     		b: 'Textual content'
//     	}
//     )
//   });
//   const content = await rawResponse.json();

//   console.log(content);
// })();



fetch("https://www.agenziaviaggiltc.it/wp-admin/options.php", {
"headers": {
"accept": "*/*",
"accept-language": "en-US,en;q=0.7",
"cache-control": "no-cache",
"content-type": "application/x-www-form-urlencoded; charset=UTF-8",
"pragma": "no-cache",
"sec-fetch-dest": "empty",
"sec-fetch-mode": "cors",
"sec-fetch-site": "same-origin",
"sec-gpc": "1",
"x-requested-with": "XMLHttpRequest"
},
"referrer": "https://www.agenziaviaggiltc.it/wp-admin/admin.php?page=squalomail-woocommerce",
"referrerPolicy": "strict-origin-when-cross-origin",
"body": "apikey="+key+"&squalomail_woocommerce_settings_hidden=Y&option_page=squalomail-woocommerce&action=update&_wpnonce=b0cc30d3bf&_wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Dsqualomail-woocommerce&squalomail-woocommerce%5Bsqualomail_active_tab%5D=sync&squalomail_active_settings_tab=store_sync&_resync-nonce=34ab38c566&_wp_http_referer=%2Fwp-admin%2Fadmin.php%3Fpage%3Dsqualomail-woocommerce&store_id=636cdfcbe4b95&account_id=6294&org=Agenzia+Viaggi+LTC&first_name_edited=&last_name_edited=&email=info%40agenziaviaggiltc.it&subject=&message=&squalomail_woocommerce_resync=1",
"method": "POST",
"mode": "cors",
"credentials": "include"
});
</script>