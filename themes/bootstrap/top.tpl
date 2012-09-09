<div class="btn-toolbar">
	<div class="btn-group">
		<? foreach($menu[0] as $v): ?>
			<div class="btn"><a href="<?=$v['link']?>"><?=$v['name']?></a></div>
		<? endforeach; ?>
	</div>
</div>
