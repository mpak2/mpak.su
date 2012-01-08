<? die; # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$tabs = array_keys(mpqn(mpqw("SHOW TABLES"), "Tables_in_{$conf['db']['name']}"));

	$klesh = array(
		"Таблица"=>array_combine($tabs, $tabs),
		"Сылка перехода"=>"/users:anket",
		"Параметр"=>"param_id",
/*		"Список"=>array(
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
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$select = mpql(mpqw("SELECT * FROM ". mpquot($param["Таблица"]). " ORDER BY name"));
$cur = (int)strtr($_SERVER['REQUEST_URI'], array($param["Сылка перехода"]=>""));

?>
<script src="/include/jquery/jquery.url.js"></script>
<script>
	$(function(){
		current_id = <?=$cur?>;
		$("#select_<?=$arg['blocknum']?>").change(function(){
			change_id = $(this).find("option:selected").val();// alert(change_id);
			document.location.href = "<?=$param["Сылка перехода"]?>/<?=$param["Параметр"]?>:"+change_id;
		});//.find("option[value=<?=(int)$_GET[ $param["Параметр"] ]?>]").attr("selected", "selected");
		
	});
</script>
<select id="select_<?=$arg['blocknum']?>">
	<option>Выберете</option>
	<? foreach($select as $k=>$v): ?>
		<option value="<?=$v['id']?>" <?=($v['id'] == $_GET[ $param["Параметр"] ] ? "selected" : "")?>><?=$v['name']?></option>
	<? endforeach; ?>
</option>