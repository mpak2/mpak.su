<? # Нуль

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
		($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),
/*		"Список"=>array(
			1=>"Одын",
			2=>"Два",
		),*/
		"Категория"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_cat ORDER BY name"),
	);// mpre($klesh);

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

$files = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_files WHERE cat_id=".(int)$param['Категория']. " ORDER BY RAND() LIMIT 5"));

?>
<div class="dhonishow true hide-buttons_true hide-alt_true autoplay_4 duration_1" style="width:<?=$param['Ширина']?>px; height:<?=$param['Высота']?>px; padding:0; margin:0;">
	<link rel="stylesheet" href="/include/dhonishow/dhonishow.css" type="text/css" media="screen" />
	<script src="/include/dhonishow/jquery.dhonishow.js" type="text/javascript"></script>
	<? foreach($files as $v): ?>
		<a href="<?=$v['href']?>">
			<img src="/<?=$arg['modname']?>/<?=$v['id']?>/w:<?=(int)$param['Ширина']?>/h:<?=(int)$param['Высота']?>/c:<?=(int)$param['Кроп']?>/null/img.jpeg">
		</a>
	<? endforeach; ?>
</div>
