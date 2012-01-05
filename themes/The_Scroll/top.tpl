<ul>
	<? foreach($menu as $k=>$v): if($v['pid']) continue; ?>
		<li><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
