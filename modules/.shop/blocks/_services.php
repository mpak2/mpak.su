<? die; # Документация

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc ORDER BY RAND() LIMIT 1";
$mdesc = mpql(mpqw($sql), 0);
//echo "<pre>"; print_r($mdesc); echo "</pre>";
echo <<<EOF
	<div class="mdesc_name" style="font-weight:bold; margin:3px; padding:3px; text-align:center; border: 1px solid gray;">{$mdesc['name']}</div>
	<div class="mdesc_name"><a href=/mdesc:desc/{$mdesc['id']}>{$mdesc['description']}</a></div>
EOF;

?>