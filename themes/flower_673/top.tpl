<ul>
	<? foreach($menu as $k=>$v): if(get($v,'pid')) continue; ?>
		<li><a href="<?=get($v,'href')?>"><?=get($v,'name')?></a></li>
	<? endforeach; ?>
</ul>
