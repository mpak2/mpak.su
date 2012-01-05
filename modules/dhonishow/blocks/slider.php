<? die; # Слайдер

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

?>
	<form class="fmparam" method="post" style="margin:10px;">
		<div>width: <input type="text" name="param[w]" value="<?=$param['w']?>"></div>
		<div>height: <input type="text" name="param[h]" value="<?=$param['h']?>"></div>
		<div>border-radius: <input type="text" name="param[r]" value="<?=$param['r']?>"></div>
		<div><input type="submit" value="Сохранить"></div>
	</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$conf['tpl']['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index LIMIT 20"));

?>

<link rel="stylesheet" href="/include/dhonishow/dhonishow.css" type="text/css" media="screen" />
<script src="/include/dhonishow/jquery.dhonishow.js" type="text/javascript"></script>

<div class="dhonishow true hide-buttons_true hide-alt_true autoplay_7 duration_1" style="height:<?=($param['h'] ?: "")?>;">
	<? foreach($conf['tpl']['index'] as $k=>$v): ?>
		<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:<?=($param['w'] ?: "")?>/h:<?=($param['h'] ?: "")?>/c:1/null/img.jpg" style="<?=($param['r'] ? "border-radius:{$param['r']};" : "")?>">
	<? endforeach; ?>
</div>