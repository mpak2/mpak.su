<? die; # Таблицы

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
} //$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"")) as $k=>$v){
	$m[$fn] = ($conf['settings']["{$arg['modpath']}_". $fn = substr($v["Tables_in_{$conf['db']['name']}"], strlen("{$conf['db']['prefix']}_{$arg['modpath']}"))] ?: $fn);
}

?>
<ul>
	<? foreach($m as $k=>$v): ?>
		<li><a href="/<?=$arg['modpath']?>:<?=$k?>"><?=$v?></a></li>
	<? endforeach; ?>
</ul>
