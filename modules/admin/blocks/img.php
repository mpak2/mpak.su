<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$tab = array_keys(mpqn(mpqw("SHOW TABLES"), 'Tables_in_c0e1bd4510d1ed2'));
	$klesh = array(
/*		"Количество символов"=>0,
		"Курс доллара"=>30,*/
		"Таблица"=>array(""=>"")+array_combine($tab, $tab),
		"Вторичный ключ"=>"",
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

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){
	$img_id = mpfdk($param["Таблица"],
		null, $w = array("time"=>time(), "uid"=>$conf['user']['uid'], $param["Вторичный ключ"]=>$_GET[ $param["Вторичный ключ"] ])
	);
	if($img_id && ($fn = mpfn($param["Таблица"], "img", $img_id))){
		mpqw("UPDATE ". mpquot($param["Таблица"]). " SET img=\"". mpquot($fn). "\" WHERE id=". (int)$img_id. " AND {$param["Вторичный ключ"]}=". (int)$_GET[ $param["Вторичный ключ"] ]);
	} $img = array($img_id=>array("id"=>$img_id));
}else{
	$img = mpqn(mpqw($sql = "SELECT * FROM ". mpquot($param["Таблица"]). " WHERE {$param["Вторичный ключ"]}=". (int)$_GET["id"]));
}

$m = explode("_", $param["Таблица"]);
$modpath = $m[1];

?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#img_<?=$arg['blocknum']?> form").iframePostForm({
			complete:function(data){
				if(html = $("<div />").html(data).find("li[img_id]").clone().wrap("<div>").parent().html()){
					$("#img_<?=$arg['blocknum']?> ul").append(html);
				}else{
					alert(data);
				}
			}
		});
	});
</script>
<style>
	#img_<?=$arg['blocknum']?> ul li {float:left; height:80px; width:80px; position:relative;}
</style>
<div id="img_<?=$arg['blocknum']?>">
	<ul>
		<? if($img) foreach($img as $k=>$v): ?>
			<li img_id="<?=$v['id']?>">
				<a href="javascript:return false;" style="position:absolute;"><img src="img/delete.png"></a>
				<img src="/<?=$modpath?>:img/<?=$v['id']?>/tn:items_img/fn:img/w:70/h:70/null/img.jpg">
			</li>
		<? endforeach; ?>
	</ul>
	<div>
		<form method="post" action="/blocks/<?=$arg['blocknum']?>/theme:<?=$conf['settings']['theme']?>/<?=$param["Вторичный ключ"]?>:<?=(int)$_GET["id"]?>/null" enctype="multipart/form-data">
			<input type="hidden" name="<?=$param["Вторичный ключ"]?>" value="<?=$_GET['id']?>">
			<input type="file" name="img">
			<input type="submit" value="Добавить">
		</form>
	</div>
</div>
