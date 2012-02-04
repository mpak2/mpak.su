<? die; # МоиМашины

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
		"Количество фото"=>10,
/*		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);

?>
		<!-- Настройки блока -->
	<script src="/include/jquery/my/jquery.klesh.select.js"></script>
	<script>
		$(function(){
			<? foreach($klesh as $k=>$v): ?>
				<? if(gettype($v) == 'array'): ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(md5($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(md5($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if($_FILES[$arg['modpath']] && ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_img")){
	$id = mpql(mpqw("SELECT max(id) AS id FROM $tn"), 0, 'id')+1;
	if(is_file(mpopendir('include/'. $mpfn = mpfn($tn, 'img', $id, $arg['modpath'])))){
		mpqw($sql = "INSERT INTO $tn SET id=". (int)$id. ", time=". time(). ", kid=1, uid=". (int)$conf['user']['uid']. ", img=\"". mpquot($mpfn). "\"");
		echo $id;
	}else{
		echo 'file not exists';
	} exit;
}elseif($_POST['del']){
	mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_POST['del']. " AND uid=". (int)$conf['user']['uid']);
}else{
	$img = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE uid=". (int)$arg['uid']. " ORDER BY id DESC LIMIT ". (int)($param["Количество фото"] ?: 10)));
}

?>
<? if(!array_key_exists('null', $_GET)): ?>
	<script language="javascript">
		function ifld(obj){
			var id = $(obj).contents().find('body').html();
			if(id && !isNaN(id)){
				$(".imgpl").prepend("<div class=\"divimg\"><img class='mgimg' src=\"/<?=$arg['modpath']?>:<?=$arg['fn']?>/"+id+"/w:100/h:100/null/img.jpg\"></div>");
			}else if(id){ alert("error: "+id); }
		};
	</script>
	<style>
		.my {clear:both;}
		.mgimg {margin:3px;}
		.divimg {
			margin:3px;
			text-align:center;
			width:100px;
			height:100px;
			float:left;
		}
	</style>
	<? if($arg['uid'] == $conf['user']['uid']): ?>
		<form action="/blocks/<?=$arg['blocknum']?>/null" method="POST" target="<?=$arg['modpath']?>_if" enctype="multipart/form-data">
			Добавить фото: 
			<input type="file" name="<?=$arg['modpath']?>[img]" onchange="this.form.submit();">
		</form><iframe onload="javascript: ifld(this);" name="<?=$arg['modpath']?>_if" style="width:200px; height:200px; display:none;"></iframe>
	<? endif; ?>
		<span>
			<a href="/<?=$arg['modpath']?>">Все фото</a>
			<a href="/<?=$arg['modpath']?>/uid:<?=$arg['uid']?>">Мои <?=$cnt?> фото</a>
		</span>
<? endif; ?>
<!-- [settings:foto_lightbox] -->
<div class="imgpl" id="gallery" style="overflow:hidden;">
	<? if($img): ?>
		<? foreach((array)$img as $k=>$v): ?>
			<div class="divimg">
				<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>/w:600/h:500/null/img.jpg">
					<img class="mgimg" src="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>/w:100/h:100/null/img.jpg">
				</a>
			</div>
		<? endforeach; ?>
	<? endif; ?>
</div>

