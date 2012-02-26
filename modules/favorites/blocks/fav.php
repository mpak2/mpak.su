<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		"Количество символов"=>10,
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
					$(".klesh_<?=strtr(base64_encode($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>", function(){
					}, <?=json_encode($v)?>);
				<? else: ?>
					$(".klesh_<?=strtr(base64_encode($k), array("="=>''))?>").klesh("/?m[blocks]=admin&r=mp_blocks&null&conf=<?=$arg['confnum']?>");
				<? endif; ?>
			<? endforeach; ?>
		});
	</script>
	<div style="margin-top:10px;">
		<? foreach($klesh as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div style="width:200px; float:left; padding:5px; text-align:right; font-weight:bold;"><?=$k?> :</div>
				<? if(gettype($v) == 'array'): ?>
					<div class="klesh_<?=strtr(base64_encode($k), array("="=>''))?>" param="<?=$k?>"><?=$v[ $param[$k] ]?></div>
				<? else: ?>
					<div class="klesh_<?=strtr(base64_encode($k), array("="=>''))?>" param="<?=$k?>"><?=($param[$k] ?: $v)?></div>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? return;

}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$fav = mpql(mpqw("SELECT id.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS u ON id.url_id=u.id WHERE id.fav>0 AND id.uid=". (int)$arg['uid']));

/*foreach($fav as $k=>$v){
	$get = mpgt($v['name']);// mpre($get);
	$res[ $modpath = $conf['modules'][ array_shift(array_keys($get['m'])) ]['modname'] ][ $get['id'] ] = array(
		"fav_id"=>$v['fav_id'],
		"id"=>$get['id'],
		"modpath" => $modpath,
		"name"=>array_pop(array_keys($get)),
	);
}// mpre($res);*/


?>
<script>
	$(function(){
		$("ul.<?=$arg['modpath']?>_list a.<?=$arg['modpath']?>_list").click(function(){
			fav_id = $(this).parents("[fav_id]").attr("fav_id");// alert(fav_id);
			$.post("/<?=$arg['modname']?>:float/null", {fav_id:fav_id}, function(data){
				if(isNaN(data)){ alert(data) }else{
					$("ul.<?=$arg['modpath']?>_list li[fav_id="+fav_id+"]").remove();
				}
			});
		});
	});
</script>
<ul class="<?=$arg['modpath']?>_list">
	<? foreach($fav as $k=>$v): ?>
		<li fav_id="<?=$v['id']?>">
			<span style="float:right;">
				<a class="<?=$arg['modpath']?>_list" href="javascript:return false;">Удалить из избранного</a>
			</span>
			<span><a href="<?=$v['name']?>"><?=$v['name']?></a></span>
		</li>
	<? endforeach; ?>
</ul>