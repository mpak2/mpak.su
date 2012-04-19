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
	mpre($_POST); exit;
};

$img = mpqn(mpqw($sql = "SELECT * FROM ". mpquot($param["Таблица"]). ($_GET["id"] ? " WHERE {$param["Вторичный ключ"]}=". (int)$_GET["id"] : "")));
//$mpager = mpager(mpql(mpqw("SELECT FOUND_ROWS()/15 AS cnt"), 0, 'cnt'));

$get = mpgt($_SERVER['REQUEST_URI']);
$m = $get['m'];

$modpath = array_pop(array_flip($m));
$fn = array_pop($m);

?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#img_<?=$arg['blocknum']?>").iframePostForm({
			complete:function(data){
				alert(data);
			}
		});
	});
</script>
<ul>
	<? foreach($img as $k=>$v): ?>
		<li><img src="/<?=$modpath?>:img/<?=$v['id']?>/tn:items_img/fn:img/w:120/h:100/null/img.jpg"></li>
	<? endforeach; ?>
</ul>
<div>
	<form id="img_<?=$arg['blocknum']?>" action="/blocks/<?=$arg['blocknum']?>/<?=$param["Вторичный ключ"]?>:<?=(int)$_GET["id"]?>/null" method="post" enctype="multipart/form-data">
		<input type="hidden" <?=$param["Вторичный ключ"]?>="<?=$_GET['id']?>">
		<input type="file" name="img">
		<input type="submit" value="Добавить">
	</form>
</div>
