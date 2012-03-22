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

$comments = mpqn(mpqw("SELECT t.*, url.name AS url_name FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS t LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS url ON t.url_id=url.id"));

?>
<div>
	<div style="margin-bottom:5px;">
		Последние / <a href="/<?=$arg['modname']?>">Все</a>
	</div>
	<? foreach($comments as $k=>$v): ?>
		<div>
			<div style="float:right"><?=date('d.m.Y H:i:s', $v['time'])?></div>
			<div><a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']?>"><?=$v['uname']?></a></div>
			<div><a href="<?=$v['uname']?>"><?=$v['text']?></a></div>
		</div>
	<? endforeach; ?>
</div>