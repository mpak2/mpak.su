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
		exit($_POST['del']);
	}else{
		if($mpdbf = mpdbf($param["Таблица"], $_POST+array($param["Вторичный ключ"]=>$_GET[ $param["Вторичный ключ"] ]))){
			mpqw($sql = "INSERT INTO `". mpquot($param["Таблица"]). "` SET {$mpdbf}");
		} $associated = array(($id = mysql_insert_id())=>array("id"=>$id, $param["Вторичный ключ"]=>$_GET[ $param["Вторичный ключ"] ])+$_POST);
//		mpre($associated); exit;
	}
}else{
	$associated = mpqn(mpqw($sql = "SELECT * FROM ". mpquot($param["Таблица"]). " WHERE {$param["Вторичный ключ"]}=". (int)$_GET["id"]));
}

$f = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($param["Таблица"])), "Field");

$get = mpgt($_SERVER['REQUEST_URI']);
$m = $get['m'];

$modpath = array_pop(array_flip($m));
$fn = array_pop($m);

foreach(array_keys($f) as $v){
	if((substr($v, -3, 3) == "_id") && ($v != $param["Вторичный ключ"])){
		$tpl[$v] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}catalog_". substr($v, 0, strlen($v)-3)));
	}
}// mpre($tpl); exit;

?>
<script src="/include/jquery/jquery.iframe-post-form.js"></script>
<script>
	$(function(){
		$("#associated_<?=$arg['blocknum']?> form").iframePostForm({
			complete:function(data){
				html = $("<div />").html(data).find("#associated_<?=$arg['blocknum']?> li[index_id]").wrap("<div>").parent().html();
				if(html){
					$("#associated_<?=$arg['blocknum']?> ul").append(html);
				}else{
					alert(data);
				}
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
		<? foreach($associated as $v): ?>
			<li index_id="<?=$v['id']?>">
				<? foreach($v as $k=>$val): if(($k == $param["Вторичный ключ"]) || ($k == 'id')) continue; ?>
					<? if((substr($k, -3, 3) == "_id")): ?>
						<?=($tpl[$k][$val] ? "<span title='{$val}'>{$tpl[$k][$val]['name']}</span>" : $k)?>
					<? else: ?>
						<span><?=$val?></span>
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
		<? foreach($f as $k=>$v): if(($k == $param["Вторичный ключ"]) || ($k == 'id')) continue; ?>
			<? if((substr($k, -3, 3) == "_id") && $tpl[$k]): ?>
				<select name="<?=$k?>">
					<? foreach($tpl[$k] as $s): ?>
						<option value="<?=$s['id']?>"><?=$s['name']?></option>
					<? endforeach; ?>
				</select>
			<? else: ?>
				<span><input type="text" name="<?=$k?>"></span>
			<? endif; ?>
		<? endforeach; ?>
		<input type="submit" value="Добавить">
	</form>
</div>
