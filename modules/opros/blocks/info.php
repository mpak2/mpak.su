<? die; # Заявки

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$status = array('0'=>'новый', '1'=>'обработка', '2'=>'отменен', '3'=>'выполнен');
$opros = mpql(mpqw("SELECT a.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}users AS u ON u.id=a.uid WHERE a.status<2 ORDER BY a.id DESC"));

?>
<div style="margin:10px;">
	<? foreach($opros as $k=>$v): ?>
		<div title="<=date('Y.m.d H:i:s', $v['time'])?>" alt="<?=date('Y.m.d H:i:s', $v['time'])?>" style="overflow:hidden;">
			<span><a href="/?m[opros]=admin&r=mp_opros_anket&where[id]=<?=$v['id']?>" style="color:red;">
				#<?=$v['id']?> <?=$status[$v['status']]?>
			</a></span>
			<span style="float:right;"><a href="/users/<?=$v['uid']?>"><?=($v['uid'] > 0 ? $v['uname'] : $conf['settings']['default_usr'])?></a></span>
		</div>
	<? endforeach; ?>
</div>