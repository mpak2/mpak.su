<? die; # АдминМодули

if ((int)$arg['confnum']){
	# Востановление параметров модуля
//	if (count($res = mpql(mpqw("SELECT param FROM {$conf['db']['prefix']}blocks WHERE id = {$arg['confnum']}"))))
//		$param = unserialize($res[0]['param']);

	# Сохранение параметров модуля
//	if (count($param)) mpqw("UPDATE {$conf['db']['prefix']}blocks SET param = '".serialize($param)."' WHERE id = {$arg['confnum']}");
	return;
}

$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}admin"));
$mod = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}modules ORDER BY priority DESC"), 'admin', 'id');

$modpath = array_search('admin', $_GET['m']);

?>
<ul class="nl ModulesList">
	<? foreach($cat as $n=>$c): ?>
		<li><h2><?=$c['name']?></h2></li>
		<? foreach($mod[ $c['id'] ] as $k=>$v): if($conf['modules'][ $v['id'] ]['access'] < 4) continue; ?>
			<li modpath="<?=$v['folder']?>" class="<?=($modpath == $v['folder'] ? "act" : "")?>">
				<a href="/<?=$v['folder']?>:admin"><?=$v['name']?></a>
			</li>
		<? endforeach; ?>
	<? endforeach; ?>
</ul>