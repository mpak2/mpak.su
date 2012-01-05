<? die; # ПоследниеКомменты

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$GLOBALS['conf']['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

//$comments = mpql(mpqw("SELECT t.*, u.url, SUBSTR(t.text, 1, 80) as text FROM {$conf['db']['prefix']}{$arg['modpath']}_url AS u, {$conf['db']['prefix']}{$arg['modpath']}_txt AS t WHERE u.id=t.uid ORDER BY id DESC LIMIT 10"));
//include(mpopendir("modules/{$arg['modpath']}/blocks/last.tpl"));

$comments = mpqn(mpqw("SELECT t.* FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS t LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS u ON t.uid=u.id"));

?>
<div>
	<? foreach($comments as $k=>$v): ?>
		<div>
			<div style="float:right"><?=date('d.m.Y H:i:s', $v['time'])?></div>
			<div><a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']?>"><?=$v['uname']?></a></div>
			<div><?=$v['text']?></div>
		</div>
	<? endforeach; ?>
	<div style="text-align:right;">
		<a href="/<?=$arg['modname']?>">Все комментарии</a>
	</div>
</div>