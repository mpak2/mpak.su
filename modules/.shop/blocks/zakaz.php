<? die; # Новые заказы

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}


if($arg['access'] >= 4){
	$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE 1=1 AND close=2";
	if($arg['access'] < 5) $sql .= " AND id IN (SELECT o.bid FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE d.uid=". (int)$conf['user']['uid']. ")";

	$basket = mpql(mpqw($sql));
	foreach($basket as $k=>$v){
		$v['date'] = date('Y.m.d H:i:s', $v['time']);
		echo <<<EOF
		<div><a title="{$v['date']}" alt="{$v['date']}" href="/?m[{$arg['modpath']}]=admin&r=4&where[id]={$v['id']}">{$v['sity']} [{$v['sum']}]</a></div>
EOF;
	}
}

?>