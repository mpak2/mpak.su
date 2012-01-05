<script type="text/javascript">
 	$(document).ready(function(){
 		$('li a[href$='+$.url.attr('path')+']').addClass('selected');
 	});
</script>
<ul>
	<? foreach($menu as $k=>$v): if($v['pid']) continue; ?>
		<li><a href="<?=$v['link']?>"><span><?=$v['name']?></span></a></li>
	<? endforeach; ?>
</ul>
