<? die; # Облако

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

/*foreach(mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index LIMIT 10")) as $k=>$v){
	mpre($v);
}*/

//$read = mpql(mpqw($sql = "SELECT MAX(`read`) AS 'max', MIN(`read`) AS 'min' FROM {$conf['db']['prefix']}{$arg['modpath']}_obj"), 0);
//mpre($read);

$list = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY `read` DESC LIMIT 30"));
$min = $max = $list[0]['read'];
foreach($list as $k=>$v){
	$max = max($max, $v['read']);
	$min = min($min, $v['read']);
}

foreach($list as $k=>$v){
	$list[$k]['font-size'] = 60 + (int)(120 / $max * ($v['read']+0.001));
} shuffle($list);

?>
<div>
	<? foreach($list as $k=>$v): ?>
		<span title="<?=$v['read']?> просмотр" style="font-size:<?=$v['font-size']?>%;">
			<a href="/<?=$arg['modpath']?>:cat/<?=$v['id']?>">
				<?=$v['name']?>
			</a>
		</span>
	<? endforeach; ?>
</div>
