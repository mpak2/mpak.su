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

$admin = array("zhiraf", "artfactor");

if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && array_search($_POST['theme'], $admin) !== null){
	$adm = $conf['settings']['theme/*:admin'];
	mpqw("UPDATE {$conf['db']['prefix']}blocks SET theme=\"". mpquot("!{$_POST['theme']}"). "\" WHERE theme LIKE \"". mpquot("!{$adm}"). "\"");
	mpqw("UPDATE {$conf['db']['prefix']}blocks SET theme=\"". mpquot("{$_POST['theme']}"). "\" WHERE theme LIKE \"". mpquot("{$adm}"). "\"");
	mpsettings('theme/*:admin', $_POST['theme']);
	mpsettings('theme/admin:*', $_POST['theme']);
	if($_POST['theme'] == 'artfactor'){
		mpqw("UPDATE mp_blocks SET file='admin/blocks/artfactor.php' WHERE file='admin/blocks/top.php'");
		mpqw("UPDATE mp_blocks SET enabled=0 WHERE file='admin/blocks/modlist.php'");
//		mpsettings('theme/admin:*', "");
	}else{
		mpqw("UPDATE mp_blocks SET file='admin/blocks/top.php' WHERE file='admin/blocks/artfactor.php'");
		mpqw("UPDATE mp_blocks SET enabled=1 WHERE file='admin/blocks/modlist.php'");
	}// header("Location: /admin"); exit;
	exit("Установлен {$_POST['theme']} админраздел");
};

?>
<script>
	$(function(){
		$("#admin_edit_<?=$arg['blocknum']?> input[type=button]").click(function(){
			theme = $("#admin_edit_<?=$arg['blocknum']?> select option:selected").val();// alert(theme);
			$("#admin_edit_<?=$arg['blocknum']?> select option:first").attr("selected", "selected");
			$.post("/blocks/<?=$arg['blocknum']?>/null", {theme:theme}, function(data){
				alert(data);
			});
		});
	});
</script>
<div id="admin_edit_<?=$arg['blocknum']?>">
	<select>
		<option></option>
		<? foreach($admin as $adm): ?>
			<option><?=$adm?></option>
		<? endforeach; ?>
	</select> <input type="button" value="Применить">
</div>