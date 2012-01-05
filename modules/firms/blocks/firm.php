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

$firms = mpql(mpqw("SELECT id.*, s.name AS sname FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_sobst AS s ON id.sobst_id=s.id WHERE uid=". (int)$arg['uid']));

?>
<div style="margin:10px;">
	<a id="addfirm" href="/<?=$arg['modpath']?>:edit/tn:index">Добавить</a>
	<span style="float:right;"><a href="/<?=$arg['modpath']?>">Список фирм</a></span>
</div>
<div>
	<? foreach($firms as $k=>$v): ?>
		<div style="clear:both;">
			<div style="float:right;"><a href="/<?=$arg['modpath']?>/<?=$v['id']?>">Подробнее</a></div>
			<div style="float:right; margin-right:5px;"><a href="/<?=$arg['modpath']?>:edit/tn:index/<?=$v['id']?>">Редакт</a></div>
			<div style="float:right; margin-right:5px;"><a onclick="javascript: if (confirm('Вы уверенны?')){return obj.href;}else{return false;}" href="/<?=$arg['modpath']?>:edit/tn:index/del:<?=$v['id']?>">Удалить</a></div>
			<div style="float:left; width:50px;"><?=$v['sname']?></div>
			<span style="font-weight:bold;"><?=$v['name']?></span>
			<span><?=$v['sity']?></span>
		</div>
	<? endforeach; ?>
</div>