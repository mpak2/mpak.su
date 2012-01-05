<script>
	$(document).ready(function(){
		$('a[href$='+$.url.attr('path')+']').parent().addClass('selected');
	});
</script>
<ul>
	<? foreach($menu as $k=>$v):  if($v['pid']) continue; ?>
		<li><a href="<?=$v['link']?>" title="<?=$v['name']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
