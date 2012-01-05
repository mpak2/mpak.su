<ul>
	<? foreach($menu as $k=>$v): ?>
		<li class="page_item page-item-2"><a href="<?=$v['link']?>" title="<?=$v['name']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
