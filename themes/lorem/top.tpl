<? foreach($menu as $k=>$v): if($v['pid']) continue; ?>
	<a href="<?=$v['link']?>"><?=$v['name']?></a>
<? endforeach; ?>
