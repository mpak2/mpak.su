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
if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && array_key_exists("val", $_POST)){
	if(empty($_POST['val'])){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_redirect WHERE `to`=\"". mpquot($_POST['to']). "\"");
		exit();
	}else{
		mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_redirect",
			$w = array("to"=>$_POST['to']),
			$w += array("time"=>time(), "from"=>$_POST['val'], "uid"=>$conf['user']['uid']), $w
		); exit($_POST['redirect_id']);
	}
};

$seo = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_redirect WHERE `from`=\"". mpquot(urldecode($_SERVER['REQUEST_URI'])). "\" OR `to`=\"". mpquot(urldecode($_SERVER['REQUEST_URI'])). "\" LIMIT 1"), 0);

$to = $seo['id'] ? $seo['to'] : urldecode($_SERVER['REQUEST_URI']);

?>
<script src="/include/jquery/my/jquery.klesh.select.js"></script>
<script>
	$(function(){
		$(".klesh").klesh("/blocks/<?=$arg['blocknum']?>/null");
	});
</script>
<div>
	<div style="padding-left:18px;"><a href="<?=$to?>"><?=$to?></a></div>
	<div class="klesh" to="<?=$to?>" redirect_id="<?=$seo['id']?>"><?=$seo['from']?></div>
</div>