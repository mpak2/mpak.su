<? die; # Нуль

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

$stat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"));

require_once(mpopendir('include/idna_convert.class.inc'));
$IDN = new idna_convert();

?>
<div>
	<? foreach($stat as $k=>$v): ?>
		<div>
			<a target=_blank href="<?=$IDN->decode($v['name'])?>"><?=$IDN->decode($v['name'])?></a>
			<span style="float:right;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
			<span style="float:right; margin-right:20px;"><?=$v['count']?></span>
		</div>
	<? endforeach; ?>
</div>
