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
<!-- <script type="text/javascript" src="/include/jquery/jquery.media.js"></script>
<script>
	$(function(){
		$('.bmedia').media();
	});
</script>
<div style="margin:10px;">
	<a class="bmedia {width:<?=$param['width'] ?: 320?>, height:<?=$param['height'] ?: 240?>}" href="/video:video/tn:files/<?=$v['id']?>/null/<?=basename($v['video'])?>"><?=$v['name']?></a>
</div> -->



<!--<object type="application/x-shockwave-flash" data="http://flv-mp3.com/i/pic/uflvplayer_500x375.swf" height="200" width="250">
	<param name="allowFullScreen" value="true" />
	<param name="allowScriptAccess" value="always" />
	<param name="movie" value="http://flv-mp3.com/i/pic/uflvplayer_500x375.swf" />
	<param name="FlashVars" value="way=http://<?=$conf['settings']['http_host']?>/themes/null/video/Len_taxi.flv&amp;swf=http://flv-mp3.com/i/pic/uflvplayer_500x375.swf&amp;w=250&amp;h=200&amp;pic=http://&amp;autoplay=0&amp;tools=1&amp;skin=gray&amp;volume=70&amp;q=&amp;comment=" />
</object>-->

<object type="application/x-shockwave-flash" data="http://flv-mp3.com/i/pic/uflvplayer_500x375.swf" height="221" width="260">
	<param name="bgcolor" value="#ddd" />
	<param name="allowFullScreen" value="true" />
	<param name="allowScriptAccess" value="always" />
	<param name="movie" value="http://flv-mp3.com/i/pic/uflvplayer_500x375.swf" />
	<param name="FlashVars" value="way=http://<?=$conf['settings']['http_host']?>/themes/null/video/Len_taxi.flv&amp;swf=http://flv-mp3.com/i/pic/uflvplayer_500x375.swf&amp;w=400&amp;h=340&amp;pic=http://www.pro-volgograd.ru/attachments/thm_20091102104821.jpg&amp;pic=http://&amp;autoplay=0&amp;tools=1&amp;skin=whitegrey&amp;volume=70&amp;q=&amp;comment=" />
</object>