<ul>
	<? foreach($menu as $k=>$v):  if(get($v,'pid')) continue; ?>
		<li><a href="<?=get($v,'href')?>" title="<?=get($v,'name')?>"><?=get($v,'name')?></a></li>
	<? endforeach; ?>
</ul>
