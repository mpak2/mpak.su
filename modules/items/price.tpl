<? foreach($conf['tpl']['price'] as $k=>$v): ?>
	<div style="clear:both;">
		<div style="float:left;"><?=$v['name']?></div>
		<div style="float:right;"><?=$v['price']?></div>
	</div>
<? endforeach; ?>