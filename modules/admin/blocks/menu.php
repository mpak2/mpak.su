<? # Верхнее

if (array_key_exists('confnum', $arg)){
	$block = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"), 0);
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"), 0, 'param'));

	if(substr($block['theme'], 0, 1) == '!'){
		$block['theme'] = mpql(mpqw("SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"theme\""), 0, 'value');
	}

	if(!empty($_POST)) $param = $_POST;
	echo "<div style=\"margin:10px;\">Текущее меню: <b>{$regions[$param]}</b>";
	echo "<form method=\"post\"><select name=\"menu\"><option value=''></option>";
	foreach(spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region") as $k=>$v){
		echo "<option value=\"$k\"".($k == $param['menu'] ? " selected=\"selected\"" : '').">$v</option>";
	}
	echo "</select><br />";
	echo "<br /><select name=\"tpl\"><option value=''></option>";
	foreach(mpreaddir($fn = "themes", 1) as $t){
		$theme = mpopendir("themes/{$t}");
		foreach(mpreaddir($fn = "themes/". basename($theme), 1) as $k=>$v){ if(substr($v, -4) != '.tpl') continue;
			echo "<option value=\"{$t}/{$v}\"".("{$t}/{$v}" == $param['tpl'] ? " selected=\"selected\"" : '').">{$t}/{$v}</option>";
		}
	}
	echo "</select><br /><br /><input type=\"submit\" value=\"Изменить\"></form></div>";

	mpqw($sql = "UPDATE {$conf['db']['prefix']}blocks_index SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$param = mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['blocknum']}"), 0, 'param');
$menu = qn($sql = "SELECT *, href AS link FROM {$conf['db']['prefix']}menu_index WHERE region_id=". (int)(is_numeric($param) ? $param : $param['menu'])." ORDER BY sort");
echo aedit("/menu:admin/r:{$conf['db']['prefix']}menu_index?&where[region_id]=". (is_numeric($param) ? $param : $param['menu']));

?>
<ul class="menu_<?=$arg['blocknum']?>" style="padding-top:5px;">
	<? foreach(rb("{$conf['db']['prefix']}menu_index", "region_id", "id", (is_numeric($param) ? $param : $param['menu'])) as $index): ?>
		<li>
			<? if($index['href']): ?><a href="<?=$index['href']?>"><? endif; ?>
				<?=$index['name']?>
			<? if($index['href']): ?></a><? endif; ?>
		</li>
	<? endforeach; ?>
</ul>
