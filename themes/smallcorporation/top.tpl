<ul id="menu">
	<? foreach($menu as $k=>$v): ?>
		<li><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
