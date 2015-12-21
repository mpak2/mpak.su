<? # Верхнее

if ((int)$arg['confnum']){
	$block = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0);
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));

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
	foreach(mpreaddir($fn = "themes/{$block['theme']}", 1) as $k=>$v){ if(substr($v, -4) != '.tpl') continue;
		echo "<option value=\"$v\"".($v == $param['tpl'] ? " selected=\"selected\"" : '').">$v</option>";
	}
	echo "</select><br /><br /><input type=\"submit\" value=\"Изменить\"></form></div>";

	mpqw($sql = "UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}
$param = unserialize(mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$menu = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE region_id=". (int)(is_numeric($param) ? $param : $param['menu'])." ORDER BY sort"), 'index_id', 'id');

?>
<ul class="menu_<?=$arg['blocknum']?>" style="padding:5px;">
	<? foreach($menu[0] as $k=>$t): ?>
		<li>
			<? if($t['link']): ?><a href="<?=$t['link']?>"><? endif; ?>
				<?=$t['name']?>
			<? if($t['link']): ?></a><? endif; ?>
			<? if($menu[ $t['id'] ]): ?>
				<ul class="submenu_<?=$arg['blocknum']?>">
					<? foreach($menu[ $t['id'] ] as $v): ?>
						<li>
							<? if($v['link']): ?><a href="<?=$v['link']?>"><? endif; ?>
								<?=$v['name']?>
							<? if($v['link']): ?></a><? endif; ?>
						</li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ul>
