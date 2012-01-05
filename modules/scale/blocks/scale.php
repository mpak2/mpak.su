<? die; # Ты не прав

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$GLOBALS['conf']['db']['prefix']}blocks WHERE id = {$arg['confnum']}")))) $param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

//$sql = "SELECT *, qw.name AS qw, mess.description AS udescription FROM {$conf['db']['prefix']}{$arg['modpath']}_qw AS qw LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_mess AS mess ON qw.id=mess.qwid";
$scale = mpql(mpqw( "SELECT q.* FROM {$conf['db']['prefix']}{$arg['modpath']}_qw AS q, {$conf['db']['prefix']}{$arg['modpath']}_mess AS m WHERE q.id=m.qwid AND q.hide=0 ORDER BY RAND()"), 0);
$mess = mpql(mpqw( "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_mess WHERE qwid={$scale['id']} ORDER BY RAND()"), 0);

?>

<div style="font-style: italic;"><?=$scale['name']?></div>
<div style="text-align:right;"><a target=_blank href="<?=$scale['src']?>">Источник >>></a></div>
<div style="margin-top:5px;">
	<b><?=$mess['name']?></b>:
	<i><?=($mess['yes'] == 1 ? 'Согласен' : 'Не согласен')?>:</i>
	<a href="/scale/<?=$scale['id']?>"><?=substr($mess['description'], 0, 100). (strlen($mess['description']) > 100 ? '...' : '')?></a>
</div>