<? # АдминМодули

$cat = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}admin"));
$mod = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}modules_index ORDER BY priority DESC"), 'admin', 'id');

$modpath = array_search('admin', $_GET['m']);

?>
<ul class="nl ModulesList">
	<? foreach($cat as $n=>$c): ?>
		<li><h2><?=$c['name']?></h2></li>
		<? foreach(get($mod,  $c['id']) ?: array() as $k=>$v): if($conf['modules'][ $v['id'] ]['admin_access'] < 4) continue; ?>
			<li modpath="<?=$v['folder']?>" class="<?=($modpath == $v['folder'] ? "act" : "")?>">
				<a href="/<?=$v['folder']?>:admin"><?=$v['name']?></a>
			</li>
		<? endforeach; ?>
	<? endforeach; ?>
</ul>
