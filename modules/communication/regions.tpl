<? if($_GET['id']): ?>
	<h1><?=$conf['tpl']['region'][0]['name']?></h1>
<? else: ?>
	<ul>
		<? foreach($conf['tpl']['region'] as $k=>$v): ?>
			<li>(<?=$v['num']?>) <a href="/<?=$arg['modpath']?>/region_id:<?=$v['id']?>"><?=$v['name']?></a></li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
