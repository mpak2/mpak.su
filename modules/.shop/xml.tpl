<? echo "<?xml version=\"1.0\"?>\n" ?>
<items>
<? foreach((array)$conf['tpl']['basket'] as $k=>$v): ?>
	<item>
		<id><?=$v['id']?></id>
		<status><?=$conf['tpl']['status'][$v['close']]?></status>
		<price><?=$v['sum']?></price>
		<currency></currency>
	</item>
<? endforeach; ?>
</items>