<? # Основное

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));

	if(!empty($_POST['menu'])) $param = $_POST['menu'];
	$regions = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_region");
	echo "<div style=\"margin:10px;\">Текущее меню: <b>{$regions[$param]}</b></div>";
	echo "<div style=\"margin:10px;\"><form method=\"post\"><select name=\"menu\"><option></option>";
	foreach($regions as $k=>$v){
		echo "<option value=\"$k\"".($k == $param ? " selected" : '').">$v</option>";
	}
	echo "</select><span style=\"margin:10px;\"><input type=\"submit\" value=\"Изменить\"></span></div></form>";

	if (!empty($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$gname = array_flip($conf['user']['gid']);
$menu = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE region_id=".(int)$param." ORDER BY sort"));

?>

<ul style="list-style:none;">
	<? foreach($menu as $k=>$v): if($v['pid']) continue; ?>
		<li style="text-align:center;">
			<div><img src="/menu:img/<?=$v['id']?>/w:180/h:180/c:1/null/img.jpg"></div>
			<? if($v['href']): ?><a class="menu" href='<?=$v['href']?>' title='<?=$v['description']?>'><? endif; ?>
				<?=$v['name']?>
			<? if($v['href']): ?></a><? endif; ?>
			<div style="font-size:70%;line-height: 12px; text-align:center; margin:5px 0;"><?=$v['description']?></div>
		</li>
		<ul>
			<? foreach($menu as $n=>$z): if($v['id'] !=$z['pid']) continue; ?>
				<li>
					<a class="submenu" href='<?=$z['href']?>' title='<?=$z['description']?>'>
						<?=$z['name']?>
					</a>
				</li>
			<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</ul>
