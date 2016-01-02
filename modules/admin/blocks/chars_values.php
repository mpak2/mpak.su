<? # Нуль

if(array_key_exists('confnum', $arg)){
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
//		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),
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
	if($_POST['del']){
		mpqw("DELETE FROM `". mpquot($param["Таблица"]). "` WHERE {$param["Вторичный ключ"]}=". (int)$_GET[ $param["Вторичный ключ"] ]. " AND id=". (int)$_POST['del']); exit($_POST['del']);
	}else{
		mpqw("INSERT INTO `". mpquot($param["Таблица"]). "` SET {$param["Вторичный ключ"]}=". (int)$_GET[ $param["Вторичный ключ"] ]. ", name=\"". $_POST['name']. "\"");
	}
	$associated = array(($id = mysql_insert_id())=>array("id"=>$id, "name"=>$_POST['name']));
}else{
	$associated = mpqn(mpqw($sql = "SELECT * FROM ". mpquot($param["Таблица"]). " WHERE {$param["Вторичный ключ"]}=". (int)$_GET["id"]));
}

$fields = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($param["Таблица"])), 'Field');

$fields = array_diff_key($fields, array_flip(array("id", $param["Вторичный ключ"]))); # Удаляем все служебные элементы

$pr = implode("_", array_slice(explode("_", $param["Таблица"]), 0, 2));
foreach($fields as $k=>$v){
	if(substr($k, -3, 3) == "_id"){
		$tpl[$k] = mpqn(mpqw($sql = "SELECT * FROM {$pr}_". substr($k, 0, strlen($k)-3)));
	}
}// mpre($tpl);

$get = mpgt($_SERVER['REQUEST_URI']);
$m = $get['m'];

$modpath = array_pop(array_flip($m));
$fn = array_pop($m);

?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#associated_<?=$arg['blocknum']?> form").iframePostForm({
			complete:function(data){
				html = $("<div />").html(data).find("#associated_<?=$arg['blocknum']?> li[index_id]").wrap("<div>").parent().html();
				$("#associated_<?=$arg['blocknum']?> ul").append(html);
			}
		});
		$("#associated_<?=$arg['blocknum']?> ul a.del").live("click", function(){
			index_id = $(this).parents("[index_id]").attr("index_id");
			$.post("/blocks/<?=$arg['blocknum']?>/theme:<?=$conf['settings']['theme']?>/<?=$param["Вторичный ключ"]?>:<?=$_GET['id']?>/null", {del:index_id}, function(data){
				if(isNaN(data)){ alert(data) }else{
					$("#associated_<?=$arg['blocknum']?> ul li[index_id="+index_id+"]").remove();
				}
			});
		});
	});
</script>
<div id="associated_<?=$arg['blocknum']?>">
	<ul>
		<? foreach($associated as $k=>$e): ?>
			<li index_id="<?=$v['id']?>">
				<? foreach($fields as $f=>$v): ?>
					<? if($tpl[$f]): ?>
						<span><?=$tpl[ $f ][ $e[ $f ] ]['name']?></span>
					<? else: ?>
						<span><?=$e[ $f ]?></span>
					<? endif; ?>
				<? endforeach; ?>
				<span style="float:right;">
					<a class="del" href="javascript: return false;">
						<img src="img/delete.png">
					</a>
				</span>
			</li>
		<? endforeach; ?>
	</ul>
	<form action="/blocks/<?=$arg['blocknum']?>/theme:<?=$conf['settings']['theme']?>/<?=$param["Вторичный ключ"]?>:<?=$_GET['id']?>/null" method="post">
			<? foreach($fields as $f=>$v): ?>
				<? if($tpl[$f]): ?>
					<select name="<?=$k?>">
						<? foreach($tpl[$f] as $v): ?>
							<option><?=$v['name']?></option>
						<? endforeach; ?>
					</select>
				<? else: ?>
					<input type="text" name="<?=$f?>">
				<? endif; ?>
			<? endforeach; ?>
		<input type="submit" value="Добавить">
	</form>
</div>
