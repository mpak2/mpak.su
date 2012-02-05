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
		echo $id = mpfdk($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}_img",
			null, array("img"=>"")
		); if($fn = mpfn($tn, "img", $id)){
			$mpdbf = mpdbf($tn, array("uid"=>$conf['user']['uid'])+array_diff_key($_POST, array_flip(array("id", "uid", "time"))));
			mpqw($sql = "UPDATE $tn SET ". implode(", ", array("img = \"". mpquot($fn). "\"", $mpdbf)). " WHERE id=". (int)$id);
		}else{
			mpqw("DELETE $tn WHERE id=". (int)$id);
		}
	}elseif($_POST['img_del']){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}_img WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['img_del']);
	} exit;
};

$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

?>
	<!-- [Изображения] -->
	<style>
		.img_<?=$arg['blocknum']?> > div {float:left; width:100px; height:100px;}
		.img_<?=$arg['blocknum']?> > div > div {position:relative; display:inline-block;}
		.img_<?=$arg['blocknum']?> > div > div > div {width:12px; height:12px; background: url(/img/del.png) yellow; position:absolute; top:5px; right:5px; cursor:pointer;}
	</style>
	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$(".img_<?=$arg['blocknum']?> form").iframePostForm({
				complete:function(data){
					$("<div><div>")
						.append("<img src=\"/<?=$arg['modpath']?>:img/"+data+"/tn:<?=$arg['fn']?>_img/w:90/h:90/null/img.jpg\">")
						.appendTo("<div img_id="+data+">").parent()
						.appendTo(".img_<?=$arg['blocknum']?>");
					$(".img_<?=$arg['blocknum']?> form input[type=file]").val("");
				}
			});
			$(".img_<?=$arg['blocknum']?> > div > div > div").live("click", function(){
				img_id = $(this).parents("[img_id]").attr("img_id");// alert(img_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {img_del:img_id}, function(data){
					if(isNaN(data)){ alert(data) }else{
						$(".img_<?=$arg['blocknum']?> > div[img_id="+img_id+"]").hide("slow");
					}
				});
			});
		});
	</script>
	<div class="img_<?=$arg['blocknum']?>">
		<form action="/blocks/<?=$arg['blocknum']?>/null" method="post" enctype="multipart/form-data">
			<input type="hidden" name="<?=$arg['fn']?>_id" value="<?=$a['id']?>">
			<input name="img" type="file"> <input type="submit" value="Добавить">
		</form>
		<? foreach((array)$img[$a['id']] as $k=>$v): ?>
			<div img_id="<?=$v['id']?>">
				<div>
					<div></div>
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>_img/w:90/h:90/null/img.jpg">
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<!-- [/Изображения] -->
