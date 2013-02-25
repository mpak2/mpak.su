<? die; # Слайдер

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$param['width'] = $param['width'] ?: 220;
$param['height'] = $param['height'] ?: 160;

$conf['tpl']['index'] = mpql(mpqw("SELECT d.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_img AS i ON d.id=i.desc_id GROUP BY d.id ORDER BY RAND() LIMIT 5"));

?>
<link rel="stylesheet" href="/include/dhonishow/dhonishow.css" type="text/css" media="screen" />
<script src="/include/dhonishow/jquery.dhonishow.js" type="text/javascript"></script>
<style>
	.dhonishow_module_alt {
		background-color:#fff;
		opacity:0.6;
		text-align:center;
	}
</style>
<div class="dhonishow autoplay_7 duration_1 align-alt_inside-top" style="width:<?=$param['width']?>px; height:<?=$param['height']?>px; background-color:#fff; background-repeat: no-repeat; background-image: url(/img/loading3.gif); background-position: center; height:160px;">
	<? foreach($conf['tpl']['index'] as $k=>$v): ?>
		<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">
			<img alt="<?=$v['name']?> цена <b><?=$v['price']?> руб.</b><?=($v['cnt'] > 1 ? " +{$v['cnt']} фото" : '')?>" src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:1/w:<?=$param['width']?>/h:<?=$param['height']?>/c:1/null/img.jpg">
		</a>
	<? endforeach; ?>
</div>