<? die; # ПримерБлока

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

$cat = mpql(mpqw("SELECT {$arg['fn']}.*, COUNT(id.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS {$arg['fn']} LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON {$arg['fn']}.id=id.{$arg['fn']}_id GROUP BY {$arg['fn']}.id"));

?>
<ul>
	<? foreach($cat as $k=>$v): ?>
		<li>
			<a href="/<?=$arg['modpath']?>/<?=$arg['fn']?>:<?=$v['id']?>"><?=$v['name']?></a> [<?=$v['cnt']?>]
		</li>
	<? endforeach; ?>
</ul>
