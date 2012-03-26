<div>
	<? foreach($tpl['type'] as $v): ?>
		<div><a href="/<?=$arg['modpath']?>/type_id:<?=$v['id']?>"><?=$v['name']?></a></div>
	<? endforeach; ?>
</div>