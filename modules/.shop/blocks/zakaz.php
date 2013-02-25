<? die; # Новые заказы

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$basket = mpqn(mpqw("SELECT b.*, basket_id FROM {$conf['db']['prefix']}{$arg['modpath']}_basket AS b INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_order AS o ON o.basket_id=b.id INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id INNER JOIN {$conf['db']['prefix']}users AS u ON d.uid=u.id WHERE b.close>0 AND u.id=". (int)$arg['uid']), 'basket_id');

if($basket){
	$desc = mpqn(mpqw($sql = "SELECT d.*, d.id AS did, o.basket_id, o.count FROM {$conf['db']['prefix']}{$arg['modpath']}_order AS o INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON o.desc_id=d.id WHERE o.basket_id IN (". implode(', ', array_keys($basket)). ")"), "basket_id", "did");
}

?>
<div>
	<? foreach($basket as $k=>$v): ?>
		<div style="border-bottom:1px dashed black;">
			<span>Заказ № <?=$v['basket_id']?></span>
			<span style="float:right;"><?=date('Y.m.d H:i:s', $v['time'])?></span>
		</div>
		<div style="margin:5px;">
		<? foreach($desc[ $v['basket_id'] ] as $n=>$m): ?>
			<div style="margin:1px;<?=($m['uid'] == $arg['uid'] ? "background-color:#bbb;" : '')?>">
				<span><a href="/<?=$arg['modpath']?>/<?=$m['id']?>"><?=$m['name']?></a></span>
				<span style="float:right;"><?=$m['count']?>шт.</span>
				<span style="float:right; margin-right:5px;"><?=$m['price']?>руб.</span>
			</div>
		<? endforeach; ?>
		</div>
	<? endforeach; ?>
</div>