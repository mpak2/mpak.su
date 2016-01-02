<? die; # ПостЗапрос

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

if ($_POST){
	echo "<pre>"; print_r($_POST); echo "</pre>";
}

?>