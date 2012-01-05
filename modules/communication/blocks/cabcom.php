<? die; # Нуль

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

$com = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC LIMIT 3"), 0);

?>
<div style="padding:5px;">
	<div style="margin:10px;">
		<a href="/<?=$arg['modpath']?>:add">Добавить объявление</a>
	</div>
	<div style="float:right;"><a href="/messages:send/uid:<?=$com['uid']?>">Связаться</a></div>
	<div style="font-weight:bold;"><a href="/<?=$arg['modpath']?>/<?=$com['id']?>"><?=$com['name']?></a></div>
	<div style="margin:5px 0;"><?=$com['description']?></div>
	<div style="float:right;"><?=$com['price']?> р.</div>
	<div><?=$com['contact']?></div>
</div>