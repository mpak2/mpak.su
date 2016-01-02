<? # Верхнее

if(array_key_exists('confnum', $arg)){
	$block = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"), 0);
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['confnum']}"), 0, 'param'));

	if(substr($block['theme'], 0, 1) == '!'){
		$block['theme'] = mpql(mpqw("SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"theme\""), 0, 'value');
	}

	if(!empty($_POST)) $param = $_POST;
	echo "<div style=\"margin:10px;\">Текущее меню: <b>{$regions[$param]}</b>";
	echo "<form method=\"post\"><select name=\"menu\">";
	foreach(spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region") as $k=>$v){
		echo "<option value=\"$k\"".($k == $param['menu'] ? " selected=\"selected\"" : '').">$v</option>";
	}
	echo "</select><br />";
	echo "<br /><select name=\"tpl\"><option value='.'></option>";
	foreach(mpreaddir($fn = "themes/{$block['theme']}", 1) as $k=>$v){ if(substr($v, -4) != '.tpl') continue;
		echo "<option value=\"$v\"".($v == $param['tpl'] ? " selected=\"selected\"" : '').">$v</option>";
	}
	echo "</select><br /><br /><input type=\"submit\" value=\"Изменить\"></form></div>";

	mpqw($sql = "UPDATE {$conf['db']['prefix']}blocks_index SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}
$param = unserialize(mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks_index WHERE id = {$arg['blocknum']}"), 0, 'param'));

$menu = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE region_id=". (int)(is_numeric($param) ? $param : $param['menu'])." ORDER BY sort"));

if(is_array($param) && array_key_exists('tpl', $param)){
	$tpl = (!is_numeric($param['tpl'])) ? $param['tpl'] : "{$arg['fn']}.tpl";
	include mpopendir("themes/{$conf['settings']['theme']}/$tpl"); return;
}

?>

<ul style="list-style:none;">
	<? foreach(rb("index", "index_id", "id", 0) as $index): ?>
		<li>
			<? if($index['href']): ?><a class="menu" href='<?=$index['href']?>' title='<?=$index['description']?>'><? endif; ?>
				<?=$index['name']?>
			<? if($index['href']): ?></a><? endif; ?>
		</li>
		<ul>
			<? foreach(rb("index", "index_id", "id", $index['id']) as $index): ?>
				<li>
					<a class="submenu" href='<?=$index['href']?>' title='<?=$index['description']?>'>
						<?=$index['name']?>
					</a>
				</li>
			<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</ul>
