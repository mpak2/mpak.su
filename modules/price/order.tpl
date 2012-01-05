<h2>Мои заказы</h2>
<div>
	<? foreach($conf['tpl']['order'] as $k=>$v): $sum = 0; ?>
		<h3 style="margin-left:20px;"><?=date('d.m.Y H:i:s', $v['time'])?></h3>
		<? foreach($conf['tpl']['zakaz'] as $n=>$z): if($z['order_id'] != $v['id']) continue; ?>
			<div style="width:350px; float:left;"><?=$z['name']?></div>
			<div style="width:50px; float:left;"><?=$z['count']?></div>
			<div style="width:50px; float:left;"><?=$z['price']?> р.</div>
			<div style="width:50px; float:left; border-left:1px solid #bbb; text-align:center;"><?=$sm = $z['price']*$z['count']; $sum += $sm;?> р.</div>
			<div style="clear:both;"></div>
		<? endforeach; ?>
		<div style="border-top:1px solid #bbb; height:3px; width:50px; margin-top:5px; padding: 5px 0 0 450px;">
			<div style="width:50px; text-align:center; background-color:#eee;"><?=$sum?> p.</div>
		</div>
	<? endforeach; ?>
</div>