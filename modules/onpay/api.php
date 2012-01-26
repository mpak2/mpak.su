<? die;

mp_require_once("/modules/{$arg['modpath']}/onpay/onpay_functions.php");

header("Content-Type: text/xml");
$db_link = $conf['db']['conn'];
echo $api = process_api_request();

if($fn = mpopendir("modules/{$arg['modpath']}/post.dump")){
	ob_start();
	var_export($_GET); echo "\n\n";
	var_export($_POST);
	$dump = ob_get_contents();
	ob_clean();

	file_put_contents($fn, "$dump\n\n$api");
}


?>