<div class="navigation">
	<? foreach($menu as $k=>$v): ?>
		<a href="<?=$v['link']?>" title="<?=$v['name']?>"><?=$v['name']?> </a>
	<? endforeach; ?>
	<div class="clearer"><span></span>
</div>
