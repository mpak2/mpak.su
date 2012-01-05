<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	$video = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files")); 
?>
		<form method="post" style="margin:10px;">
			<select name="param[id]">
				<? foreach($video as $k=>$v): ?>
					<option value="<?=$v['id']?>" <?=($param['id'] == $v['id'] ? "selected" : "")?>><?=$v['name']?></option>
				<? endforeach; ?>
			</select>
			<div>Ширина: <input type="text" name="param[width]" value="<?=$param['width']?>">
			<div>Высота: <input type="text" name="param[height]" value="<?=$param['height']?>"> <input type="submit" value="Сохранить"></div>
		</form>
<?

	return;
} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

$v = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE id=". (int)$param['id']. " LIMIT 1"), 0); //$dat

?>
<script type="text/javascript" src="/include/jquery/jquery.media.js"></script>
<script>
	$(function(){
		$('.bmedia').media();
	});
</script>
<div style="margin:10px;">
	<a class="bmedia {width:<?=$param['width'] ?: 320?>, height:<?=$param['height'] ?: 240?>}" href="/video:video/tn:files/<?=$v['id']?>/null/<?=basename($v['video'])?>"><?=$v['name']?></a>
</div>
