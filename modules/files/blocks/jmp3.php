<? # Нуль

if(array_key_exists('confnum', $arg)){
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
		),*/
		"Цвет фона"=>"000000",
		"Ширина"=>"100",
		"Цвет элементов"=>"0000ff",
		"Файл"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE name LIKE '%.mp3' ORDER BY name"),
		"Адрес"=>"",
		"Автостарт"=>array(""=>"По умолчанию", "true"=>"При запуске", "false"=>"Вручную"),
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

//$dat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} LIMIT 10"));

?>
<? if(empty($param["Адрес"]) || ($param["Адрес"] == $_SERVER['REQUEST_URI'])): ?>
	<script type="text/javascript" src="/include/jquery/jquery.jmp3.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".mp3[blocknum=<?=$arg['blocknum']?>]").jmp3({
				filepath: "/files/<?=$param["Файл"]?>/null/images/",	
				backcolor: "<?=($param["Цвет фона"] ?: "000000")?>",
				forecolor: "<?=($param["Цвет элементов"] ?: "0000ff")?>",
				width: <?=($param['Ширина'] ?: 100)?>,
				autoplay: <?=($param["Автостарт"] ?: "true")?>,
				showdownload: "true"
			});
		});
	</script>
	<span id="sound" class="mp3" blocknum="<?=$arg['blocknum']?>" mp3="img.mp3" style="margin-top:10px;"></span>
<? endif; ?>
