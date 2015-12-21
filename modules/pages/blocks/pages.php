<? # Статьи

if ((int)$arg['confnum']){
	# Востановление параметров модуля
	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	if ($_POST['param']){
		$param = $_POST['param'];
//		print_r($_POST);
	}
	$value = $param ? $param : 3;
echo <<<EOF
<form method=post>
	<input type='text' name='param' value='$value'> <input type=submit>
</form>
EOF;
	# Сохранение параметров модуля
	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}
echo "<ul>";
if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['blocknum']}")))) $param = unserialize($res[0]['param']);
foreach(mpql(mpqw("SELECT * FROM {$GLOBALS['conf']['db']['prefix']}pages_index WHERE kid=1 ORDER BY RAND() LIMIT ".($param ? $param : 3)."")) as $k=>$v){
	echo "<li><a href=/pages/{$v['id']}>{$v['name']}</a>";
}
echo "</ul>";

?>
