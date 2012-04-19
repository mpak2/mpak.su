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
		"Курс доллара"=>30,
		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),
		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
		"Таблица"=>array(""=>"")+array_combine($tab, $tab),
//		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
	);
	if($param["Таблица"]){
		$fn = mpqn(mpqw("SHOW COLUMNS FROM ". mpquot($param["Таблица"])), "Field");
	}
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
	<style>
		.param > div > span {display:inline-block; width:200px;}
		.param > div > span:first-child {text-align:right; padding-right:10px;}
	</style>
	<div class="param">
		<script>
			$(function(){
				
			});
		</script>
		<? foreach($fn as $k=>$v): ?>
			<div>
				<span><?=$k?></span>
				<span><div style="klesh" type="name">fn</div></span>
				<span><div style="klesh" type="type">type</div></span>
			</div>
		<? endforeach; ?>
	</div>
<? return;

} $param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$item = mpql(mpqw("SELECT * FROM ". mpquot($param["Таблица"]). " WHERE id=". (int)$_GET['id']), 0);

$get = mpgt($_SERVER['REQUEST_URI']);
$m = $get['m'];

$modpath = array_pop(array_flip($m));
$fn = array_pop($m);

?>
<style>
	.items_<?=$arg['blocknum']?> li span {display:inline-block;}
	.items_<?=$arg['blocknum']?> li div {margin-bottom:3px;}
	.items_<?=$arg['blocknum']?> li span:first-child {width:100px;}
	.items_<?=$arg['blocknum']?> li span:last-child {width:80%;}
</style>
<ul class="items_<?=$arg['blocknum']?>">
	<h1><?=$v['name']?></h1>
	<li>
		<? foreach($item as $k=>$v): ?>
			<div>
				<span><?=$k?></span>
				<span><input type="text" value="<?=$v?>" style="width:100%;"></span>
			</div>
		<? endforeach; ?>
	</li>
</ul>