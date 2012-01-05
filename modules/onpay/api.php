<? die;

//include mpopendir("modules/{$arg['modpath']}/post.php");

function onpay($operation_id, $sum){
	global $conf;
	$operations = mpql(mpqw($sql = "SELECT * FROM mp_onpay_operations WHERE id=". ($operation_id)), 0);
	if($operations['bid'] && ($basket = mpql(mpqw("SELECT * FROM mp_offers_basket WHERE id=". (int)$operations['bid']), 0))){
		if($basket['close'] == 1){
			$order = mpql(mpqw($sql = "SELECT o.*, d.price AS dprice, d.uid AS duid, d.discount FROM mp_offers_order AS o LEFT JOIN mp_offers_desc AS d ON o.desc_id=d.id WHERE o.basket_id=". (int)$basket['id'])); echo "\n". $sql;
			foreach($order as $k=>$v){

				$s = $v['dprice']<0 ? $v['price']*$v['count'] : $v['count']*$v['dprice'];

				mpqw("INSERT INTO mp_onpay_balances SET uid=". (int)$v['duid']. ", date=NOW() ON DUPLICATE KEY UPDATE date=NOW()");

				mpqw($sql = "INSERT INTO mp_onpay_pay SET time=". (int)time(). ", uid=". (int)$v['duid']. ", sum=". (int)($s/100*(100-$v['discount'])). ", description=\"Оплата товара ". (int)$v['desc_id']. " в количестве ". (int)$v['count']. " счет номер ". (int)$operation_id. "\""); echo "\n". $sql;

				mpqw("INSERT INTO mp_onpay_balances SET uid=". (int)$operations['uid']. ", date=NOW() ON DUPLICATE KEY UPDATE date=NOW()");

				mpqw($sql = "INSERT INTO mp_onpay_pay SET time=". (int)time(). ", uid=". (int)$operations['uid']. ", sum=". (int)(-$s). ", description=\"Покупка товара ". (int)$v['desc_id']. " в количестве ". (int)$v['count']. " счет номер ". (int)$operation_id. "\""); echo "\n". $sql;

			} mpqw($sql = "UPDATE mp_offers_basket SET close=2 WHERE id=". (int)$basket['id']); echo "\n". $sql;
		}
	}
}

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