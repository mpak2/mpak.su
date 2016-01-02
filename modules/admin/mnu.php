<? # Верхнее

if(array_key_exists('confnum', $arg)){
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
	foreach(mpreaddir($fn = "themes", 1) as $t){
		$theme = mpopendir("themes/{$t}");
		if(strpos($theme, $_SERVER['HTTP_HOST'])){
			foreach(mpreaddir($fn = "themes/". basename($theme), 1) as $k=>$v){ if(substr($v, -4) != '.tpl') continue;
				echo "<option value=\"$v\"".($v == $param['tpl'] ? " selected=\"selected\"" : '').">{$t}/{$v}</option>";
			}// mpre(basename($theme));
		}
	}
	echo "</select><br /><br /><input type=\"submit\" value=\"Изменить\"></form></div>";

	mpqw($sql = "UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}
$param = unserialize(mpql(mpqw($sql = "SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$menu = qn($sql = "SELECT *, href AS link FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE region_id=". (int)(is_numeric($param) ? $param : $param['menu'])." ORDER BY sort");
if($conf['modules']['seo']){
	$redirect = qn("SELECT * FROM {$conf['db']['prefix']}seo_redirect");
	foreach(array_intersect_key(rb($menu, "href"), rb($redirect, "to")) as $m){
		$menu[ $m['id'] ]['href'] = rb($redirect, "to", array_flip(array($m['href'])), "from");
	}
} $menu = rb($menu, "index_id", "id");

?>
<ul class="menu_<?=$arg['blocknum']?>">
	<? foreach($menu[0] as $k=>$t): ?>
		<li>
			<? if($t['href']): ?><a href="<?=$t['href']?>"><? endif; ?>
				<?=$t['name']?>
			<? if($t['href']): ?></a><? endif; ?>
			<? if($menu[ $t['id'] ]): ?>
				<ul class="submenu_<?=$arg['blocknum']?>">
					<? foreach($menu[ $t['id'] ] as $v): ?>
						<li>
							<? if($v['href']): ?><a href="<?=$v['href']?>"><? endif; ?>
								<?=$v['name']?>
							<? if($v['href']): ?></a><? endif; ?>
						</li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? endforeach; ?>
</ul>
