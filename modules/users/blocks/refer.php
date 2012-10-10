<? die; # Рефералы

if ((int)$arg['confnum']){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST){
		$param = array($_POST['param']=>$_POST['val'])+(array)$param;
		mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	} if(array_key_exists("null", $_GET)) exit;

	$klesh = array(
/*		($f = "Ширина")=>($param[ $f ] = $param[ $f ] ?: 200),
		($f = "Высота")=>($param[ $f ] = $param[ $f ] ?: 200),*/
		"Ссылка"=>array(
			0=>"Видна",
			1=>"Скрыта",
		),
/*		"Город"=>spisok("SELECT id, name FROM {$conf['db']['prefix']}users_sity ORDER BY name"),*/
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

if($_COOKIE['refer'] || ($_GET['refer'] && !$conf['user']['sess']['refer'] && ($_GET['refer'] != $conf['user']['uid']))){
	SetCookie("refer", max($_GET['refer'], $_COOKIE['refer']), time()+3600*24*10, "/");
//	mpqw("UPDATE {$conf['db']['prefix']}sess SET refer=". ($conf['user']['sess']['refer'] = (int)$_GET['refer']). " WHERE id=".(int)$arg['uid']);
}

require_once(mpopendir('include/idna_convert.class.inc'));
$IDN = new idna_convert();

$cnt = (int)mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE refer=". (int)$conf['user']['uid']), 0, 'cnt');
$ref = !$_GET['refer'] ? ($_SERVER['REQUEST_URI'] == '/' ? "users:reg/" : ''). (strpos($_SERVER['REQUEST_URI'], '?') ? "&refer=" : (substr($_SERVER['REQUEST_URI'], -1) == '/' ? '' : '/'). "refer:"). $arg['uid'] : '';
$lnk = "http://". $IDN->decode($_SERVER['HTTP_HOST']). urldecode($_SERVER['REQUEST_URI']). $ref;

?>
<? if(!$param['Ссылка']): ?>
	<div style="margin:10px 0; text-align:justify;"><!-- [settings:users_refer] --></div>
	<div><a href="<?=$lnk?>"><?=$lnk?></a></div>
	<? if($conf['user']['uname'] != $conf['settings']['default_usr']): ?>
		<div>Приглашенные: <?=$cnt?></div>
	<? endif; ?>
<? endif; ?>