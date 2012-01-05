<? die; # Продавцам

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$conf['tpl']['cnt'] = (int)mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 AND uid=".(int)$conf['user']['uid']), 0, 'cnt');

echo <<<EOF
<div><a href=/{$arg['modpath']}:desc>Мой товар: {$conf['tpl']['cnt']}</a></div>
EOF;

?>