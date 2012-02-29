<? die; # Рефералы

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}
//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

if($_GET['refer'] && !$conf['user']['sess']['refer'] && ($_GET['refer'] != $conf['user']['uid'])){
	mpqw("UPDATE {$conf['db']['prefix']}sess SET refer=". ($conf['user']['sess']['refer'] = (int)$_GET['refer']). " WHERE id=".(int)$arg['uid']);
}// mpre($conf['user']['sess']);

require_once(mpopendir('include/idna_convert.class.inc'));
$IDN = new idna_convert();

$cnt = (int)mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE refer=". (int)$conf['user']['uid']), 0, 'cnt');
$ref = !$_GET['refer'] ? ($_SERVER['REQUEST_URI'] == '/' ? "users:reg/" : ''). (strpos($_SERVER['REQUEST_URI'], '?') ? "&refer=" : (substr($_SERVER['REQUEST_URI'], -1) == '/' ? '' : '/'). "refer:"). $arg['uid'] : '';
$lnk = "http://". $IDN->decode($_SERVER['HTTP_HOST']). urldecode($_SERVER['REQUEST_URI']). $ref;

?>
<div style="margin:10px 0; text-align:justify;"><!-- [settings:users_refer] --></div>
<div><a href="<?=$lnk?>"><?=$lnk?></a></div>
<? if($conf['user']['uname'] != $conf['settings']['default_usr']): ?>
	<div>Приглашенные: <?=$cnt?></div>
<? endif; ?>
