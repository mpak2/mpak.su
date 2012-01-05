<? die; # Ссылки

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$conf['tpl']['sum'] = (int)mpql(mpqw("SELECT sum FROM {$conf['db']['prefix']}onpay_balances WHERE uid=".(int)$conf['user']['uid']), 0, 'sum');
$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE (uid=".(int)$conf['user']['uid']." OR sid=".(int)$conf['user']['sess']['id']. ") ORDER BY id DESC";
$conf['tpl']['basket'] = mpql(mpqw($sql));
$cbasket = count($conf['tpl']['basket']);

echo <<<EOF
<div><a href=/onpay>Баланс: {$conf['tpl']['sum']} <!-- [settings:onpay_currency] --></a></div>
<div><a href=/{$arg['modpath']}:basket>Список заказов $cbasket</a></div>
EOF;

?>