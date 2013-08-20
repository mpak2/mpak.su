<ul class="nav navbar-nav">
	<? foreach($menu[0] as $m): ?>
		<li><a href="<?=$m['href']?>"><?=$m['name']?></a></li>
	<? endforeach; ?>
</ul>