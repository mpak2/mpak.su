<!-- [settings:foto_lightbox] -->
<div style="text-align:right; margin-top:20px;">
		<a href="/<?=$arg['modname']?>:холст">
			Добавить свой рисонок
			<img src="/<?=$arg['modname']?>:img/w:30/h:30/null/brush.png">
		</a>
</div>
<div style="padding:20px;" id="gallery">
	<? foreach($tpl['index'] as $v): ?>
		<div style="width:110px; height:110px; float:left;">
			<a href="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/fn:img/w:600/h:500/null/img.jpg">
				<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/fn:img/w:100/h:100/null/img.jpg" style="border:1px solid gray;">
			</a>
		</div>
	<? endforeach; ?>
</div>