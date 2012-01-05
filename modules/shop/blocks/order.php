<? die; # Корзина

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$conf['tpl']['basket'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_basket WHERE close=0 AND uid=".(int)$conf['user']['uid']), 0);

$conf['tpl']['order'] = mpql(mpqw($sql = "SELECT COUNT(*) as count, SUM(count*price) AS sum FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id WHERE o.basket_id=".(int)$conf['tpl']['basket']['id']), 0);

?>
<? if($conf['tpl']['basket'] && $conf['tpl']['order']['count']): ?>
	Наименований: <b><?=$conf['tpl']['order']['count']?></b>
	<p />На суммму: <b><?=$conf['tpl']['order']['sum']?></b> рублей
	<div style="text-align:right;">
		<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>">Открыть</a>
	</div>
<? endif; ?>