<? die; # Корзина

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE close=0 AND (uid=".(int)$conf['user']['uid']." OR sid=".(int)$conf['user']['sess']['id'].")";
$conf['tpl']['basket'] = mpql(mpqw($sql), 0);

$conf['tpl']['order'] = mpql(mpqw($sql = "SELECT COUNT(*) as count, SUM(count*price) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.did=d.id WHERE o.bid=".(int)$conf['tpl']['basket']['id']), 0);
if($conf['tpl']['basket']['id']){
	echo <<<EOF
		Наименований: {$conf['tpl']['order']['count']}
	<p />На суммму {$conf['tpl']['order']['sum']}
	<div style="text-align:right;">
		<a href="/{$arg['modpath']}:order">Открыть</a>
	</div>
EOF;
}

?>