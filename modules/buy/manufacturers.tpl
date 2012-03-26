<div>
	<? foreach($tpl['manufacturers'] as $k=>$v): ?>
		<div style="float:left;" class="LogosItem">
			<a href="/<?=$arg['modpath']?>/manufacturers_id:<?=$v['id']?>">
				<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:manufacturers/w:120/h:120/c:1/null/img.jpg">
			</a>
		</div>
	<? endforeach; ?>
</div>