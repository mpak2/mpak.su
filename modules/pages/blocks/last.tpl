<? # Нуль

if(array_key_exists('confnum', $arg)){
	$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"), 0, 'param'));
	if ($_POST) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param = $_POST['param'])."' WHERE id = {$arg['confnum']}");

echo <<<EOF
	<form method="post">
		<input type="text" name="param[list]" value="{$param['list']}"> <input type="submit" value="Сохранить">
	</form>
EOF;

	return;
}$param = unserialize(mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['blocknum']}"), 0, 'param'));

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){};

$last = mpql(mpqw("SELECT p.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS p LEFT JOIN {$conf['db']['prefix']}users AS u ON p.uid=u.id ORDER BY p.id DESC LIMIT ". ((int)$param['list'] ?: 5)));

?>
<ul>
	<? foreach($last as $k=>$v): ?>
		<li style="clear:both;">
			<div style="float:right; margin:0 0 5px 5px;">
				<a href="/<?=$arg['modname']?>/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname'] : $conf['settings']['default_usr']. $v['uid'])?></a>
			</div>
			<a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a>
		</li>
	<? endforeach; ?>
</ul>
<div style="text-align:right;"><a href="/статьи:правка">Добавить статью</a></div>
