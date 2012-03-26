<div style="margin:10px;">
	<? foreach($conf['tpl']['index'] as $k=>$v): ?>
		<div>
			<span><input cless="banner" type="checkbox"></span>
			<span><a target="blank" href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a></span>
		</div>
	<? endforeach; ?>
</div>