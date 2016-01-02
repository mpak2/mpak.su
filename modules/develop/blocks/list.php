<? # Задачи

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

	echo <<<EOF
	<form method="post">
		Количество задач в блоке: <input type="text" name="param" value="$param"> <input type="submit" value="Сохранить">
	</form>
EOF;
	return;
}

$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
$tasks = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}develop_plan ORDER BY id DESC LIMIT ".(int)($param ? $param : 7)));

?>
<ul class="nl MyTickets">
<li class="all"><a href="/<?=$arg['modpath']?>">все задачи &rarr;</a></li>
<? foreach($tasks as $k=>$v): ?>
	<li><a href="/?m[<?=$arg['modpath']?>]=admin&where[id]=<?=$v['id']?>"><?=$v['plan']?></a></li>
<? endforeach; ?>
</ul>
