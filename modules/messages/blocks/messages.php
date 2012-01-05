<? die; # Сообщения

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

if($arg['access'] > 1 || $conf['settings']['messages_display']){
	$count = mpql(mpqw("SELECT COUNT(*) as count FROM {$conf['db']['prefix']}messages WHERE addr={$conf['user']['uid']}"), 0, 'count');
	echo "<a href=\"/{$arg['modpath']}\">Входящие ($count)</a>";
}
$mess = mpql(mpqw("SELECT id, COUNT(*) as count FROM {$conf['db']['prefix']}messages WHERE addr={$conf['user']['uid']} AND open=0"), 0);
if ($mess['count']){
	echo " <a href=/messages/{$mess['id']} style=\"color: red; \">Новых: {$mess['count']}</a>";
}

?>