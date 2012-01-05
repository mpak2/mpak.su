<? die; # СписокИгр

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

//mpre(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat")));
$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));
$count = spisok($sql = "SELECT c.id, COUNT(*) AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c, {$conf['db']['prefix']}{$arg['modpath']}_list_cat AS lc WHERE c.id=lc.cid GROUP BY c.id");
echo "<ul>";
foreach($cat as $k=>$v){
	echo "<li><a href='/{$arg['modpath']}/cid:{$v['id']}'>{$v['name']}</a> [".(int)$count[$v['id']]."]</li>";
}
echo "</ul>";

?>