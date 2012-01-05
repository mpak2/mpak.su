<ul id="mainlevel-nav">
	<? foreach($menu as $k=>$v): ?>
		<li><a href="<?=$v['link']?>" class="mainlevel-nav" ><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
