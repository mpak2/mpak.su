<? echo "<?xml version=\"1.0\"?>\n" ?>
<? $status=array(); ?>
<items>
<? foreach($conf['tpl']['opros'] as $k=>$v): ?>
	<item>
		<id><?=$v['id']?></id>
		<status><?=$conf['tpl']['status'][$v['status']]?></status>
		<price><?=$v['price']?></price>
		<currency><?=$v['currency']?></currency>
	</item>
<? endforeach; ?>
</items>