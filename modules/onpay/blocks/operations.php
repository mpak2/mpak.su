<? # Нуль

if ((int)$arg['confnum']){
/*	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;*/

	return;
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//$uid = $_GET['id'] && array_key_exists('users', $_GET['m']) ? $_GET['id'] : $conf['user']['id'];
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$operations = mpql(mpqw("SELECT p.*, s.sum AS now, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS p LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_sum AS s ON p.uid=s.uid LEFT JOIN {$conf['db']['prefix']}users AS u ON p.uid=u.id WHERE p.status=1 ORDER BY date DESC LIMIT 10"));

?>
<? foreach($operations as $k=>$v): ?>
	<div>
		<span style="float:right;"><?=$v['sum']?>/<?=$v['now']?></span>
		<span><?=array_shift(explode(' ', $v['date']))?></span>
		<span><a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']?>"><?=$v['uname']?></a></span>
	</div>
<? endforeach; ?>
