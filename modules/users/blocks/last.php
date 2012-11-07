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
}//$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));
//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$d = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}users WHERE reg_time>". (time()-60*60*24)), 0, 'cnt');
$w = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}users WHERE reg_time>". (time()-60*60*24*7)), 0, 'cnt');
$m = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}users WHERE reg_time>". (time()-60*60*24*30)), 0, 'cnt');
$users = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users ORDER BY id DESC LIMIT 10"));

?>
<div>
	<div syle="text-align:right;">
		Регистраций в
		<span>месяц: <b><?=$m?></b></span>
		<span>неделя: <b><?=$w?></b></span>
		<span>сутки: <b><?=$d?></b></span>
	</div>
	<div>
		<? foreach($users as $k=>$v): ?>
			<div style="overflow:hidden;">
				<span>
					<a href="/<?=$arg['modname']?>/<?=$v['id']?>">
						<?=$v['name']?>
					</a>
				</span>
				<span style="float:right;"><?=date('Y.m.d H:i:s', $v['reg_time'])?></span>
			</div>
		<? endforeach; ?>
	</div>
</div>