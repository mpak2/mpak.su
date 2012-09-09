<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>mpquot($_POST['val']))+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	foreach(ql("SHOW TABLES") as $k=>$v){
		$tables[$k] = $v["Tables_in_{$conf['db']['name']}"];
	}// $tables;

	$klesh = array(
		($f = "Условие")=>($param[ $f ] = $param[ $f ] ?: "index_id,img_id"),
//		($f = "Значение")=>($param[ $f ] = $param[ $f ] ?: "\$_GET[id]"),
/*		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),*/
		"Таблица"=>array_combine($tables, $tables),
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
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_FILES){
	if($img_id = mpfdk("{$param['Таблица']}", null, array("id"=>0))){
		if($fn = mpfn("{$param['Таблица']}", "file", $img_id)){
			mpfdk("{$param['Таблица']}", array("id"=>$img_id), null, $img = array("time"=>time(), "uid"=>$conf['user']['uid'], "img"=>$fn)+$_POST);
			$img = array($img_id=>array("id"=>$img_id)+$img);
		}else{
			mpqw("DELETE FROM `{$param['Таблица']}` WHERE id=". (int)$img_id);
		}
	}
}elseif($_POST['del']){
	mpqw("DELETE FROM `{$param['Таблица']}` WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_POST['del']);
	exit(''. $_POST['del']);
}else{
	$wr = array_intersect_key($_GET, array_flip(explode(",", $param["Условие"])));// mpre($wr);
	$img = mpqn(mpqw($sql = "SELECT * FROM `{$param['Таблица']}`". mpwr($param['Таблица'], $wr)));
}; $m = explode("_", $param['Таблица'], 3);

?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<? if(!array_key_exists("null", $_GET)): ?>
	<script>
		$(function(){
			$.fn.attrs = function() {
				var attributes = {}; 
				if(!this.length)
					return this;
				$.each(this[0].attributes, function(index, attr) {
					attributes[attr.name] = attr.value;
				}); 
				return attributes;
			}
			attrs = $("form.images_<?=$arg['blocknum']?>").parent().attrs(); console.log(attrs);
			$.each(attrs, function(key, val){
				$("<input>").attr("type", "hidden").attr("name", key).attr("value", val).appendTo("form.images_<?=$arg['blocknum']?>");
			});

			$("form.images_<?=$arg['blocknum']?>").iframePostForm({
				complete:function(data){
					if(html = $("<div>").html(data).find(".images").html()){
						$(".images_<?=$arg['blocknum']?> .images").append(html);
						$("form.images_<?=$arg['blocknum']?>").find("input[type=file]").val("");
					}else{
						alert(html);
					}
				}
			});
			$(".images_<?=$arg['blocknum']?> .images > div > div.del").live("click", function(){
				img_id = $(this).parents("[img_id]").attr("img_id"); console.log("del img_id:"+img_id);
				$.post("/blocks/<?=$arg['blocknum']?>/null", {del:img_id}, function(data){
					if(isNaN(data)){ alert(data) }else{
						$(".images_<?=$arg['blocknum']?> .images div[img_id="+img_id+"]").hide(300).remove();
					}
				});
			});
		});
	</script>
	<style>
		.images_<?=$arg['blocknum']?> .images > div {float:left; width:110px; height:110px; padding:5px; border:1px solid #eee; margin:2px; text-align:center; position:relative;}
		.images_<?=$arg['blocknum']?> .images > div > div.del {
			height:13px; width:11px; position:absolute; top:10px; right:10px; cursor:pointer; background-color:white; border-radius:4px;
			background-image:  url(/img/del.png);
		}
	</style>
<? endif; ?>
<form class="images_<?=$arg['blocknum']?>" method="post" action="/blocks/<?=$arg['blocknum']?>/null" enctype="multipart/form-data">
	<input type="file" name="file"> <input type="submit" value="Добавить">
	<div class="images">
		<? foreach($img as $i): ?>
			<div img_id="<?=$i['id']?>">
				<? if($conf['user']['uid'] == $i['uid']): ?><div class="del"></div><? endif; ?>
				<img src="/<?=$m[1]?>:img/<?=$i['id']?>/tn:<?=$m[2]?>/fn:img/w:100/h:100/null/img.jpg">
			</div>
		<? endforeach; ?>
	</div>
</form>