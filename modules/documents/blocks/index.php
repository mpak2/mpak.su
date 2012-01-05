<? die; # Документы

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

$doc = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"));

//mpre($doc);

?>
<ul>
	<? foreach($doc as $k=>$v): ?>
		<li><a href="/<?=$arg['modpath']?>/<?=$v['id']?>/null/<?=$v['name']?>.<?=array_pop(explode('.', $v['document']))?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
