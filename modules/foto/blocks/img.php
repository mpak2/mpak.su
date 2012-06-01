<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,
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

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	if($_FILES){
		$id = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_img",
			null, array("time"=>time(), "uid"=>$conf['user']['uid'])
		); if($fn = mpfn($tn, "img", $id)){
			$mpdbf = mpdbf($tn, array_diff_key($_POST, array_flip(array("id", "uid", "time"))));
			mpqw($sql = "UPDATE $tn SET ". implode(", ", array("img = \"". mpquot($fn). "\"")). " WHERE id=". (int)$id);
			$dat = array($id=>array("id"=>$id));
		}else{
			mpqw("DELETE $tn WHERE id=". (int)$id);
		}
	}elseif($_POST['img_del']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['img_del']); exit(''. $_POST['img_del']);
	} 
}else{
	$dat = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE uid=". (int)$arg['uid']. " LIMIT 50"));
}// mpre($dat);

?>
	<!-- [Изображения] -->
	<? if(!array_key_exists("null", $_GET)): ?>
		<style>
			.img_<?=$arg['blocknum']?> > div {float:left;}
			.img_<?=$arg['blocknum']?> > div > div {position:relative; display:inline-block; margin:3px; width:100px; height:100px;}
			.img_<?=$arg['blocknum']?> > div > div > div {width:12px; height:12px; background: url(/img/del.png) yellow; position:absolute; top:5px; right:5px; cursor:pointer;}
		</style>
		<script src="/include/jquery/jquery.iframe-post-form.js"></script>
		<script>
			$(function(){
				$(".img_<?=$arg['blocknum']?> form").iframePostForm({
					complete:function(data){
						if(html = $("<div />").html(data).find(".img_<?=$arg['blocknum']?> > div").html()){
							$(".img_<?=$arg['blocknum']?> > div").append(html);
							$(".img_<?=$arg['blocknum']?> form input[type=file]").val("");
						}else{
							alert(data);
						}
					}
				});
				$(".img_<?=$arg['blocknum']?> div.del").live("click", function(){
					img_id = $(this).parents("[img_id]").attr("img_id");// alert(img_id);
					$.post("/blocks/<?=$arg['blocknum']?>/null", {img_del:img_id}, function(data){
						if(isNaN(data)){ alert(data) }else{
							$(".img_<?=$arg['blocknum']?> div[img_id="+img_id+"]").hide("slow");
						}
					});
				});
			});
		</script>
	<? endif; ?>
	<div class="img_<?=$arg['blocknum']?>">
		<form action="/blocks/<?=$arg['blocknum']?>/null" method="post" enctype="multipart/form-data">
			<input type="hidden" name="<?=$arg['fn']?>_id" value="<?=$a['id']?>">
			<input name="img" type="file"> <input type="submit" value="Добавить">
		</form>
		<div>
			<? foreach($dat as $v): ?>
				<div img_id="<?=$v['id']?>" style="float:left;">
					<div class="del"></div>
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:90/h:90/null/img.jpg">
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<!-- [/Изображения] -->
