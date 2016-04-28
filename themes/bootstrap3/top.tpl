<ul class="nav navbar-nav">
	<? foreach($menu as $index): ?>
		<li><a href="<?=$index['href']?>"><?=$index['name']?></a></li>
	<? endforeach; ?>
</ul>
