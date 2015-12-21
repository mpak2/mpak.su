<? # Шаблон

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

echo "<select onchange=\"document.location='?theme='+options[selectedIndex].value;\">";
$themes = mpreaddir('themes');
foreach($themes as $k=>$v){
	if ($v == 'admin') continue;
	echo "<option". ($conf['settings']['theme'] == $v ? ' selected' : '').">$v</option>";
}
echo "</select>";
if(!empty($_GET['theme']) && array_search($_GET['theme'], $themes)){
	$sql = "UPDATE {$conf['db']['prefix']}settings SET value='{$_GET['theme']}' WHERE name='theme'";
	mpqw($sql);
	header("Location: /"); exit;
}

//$w = array( '1'=>'Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');
//echo $w[date('w')];

?>
