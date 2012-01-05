<script>
 	$(document).ready(function(){
 		$('ul.menu li a[href$='+$.url.attr('path')+']').addClass('active');
 	});
</script>
<ul class="menu">
	<? foreach($menu as $k=>$v):  if($v['pid']) continue; ?>
		<li><a href="<?=$v['link']?>"><span><span><?=$v['name']?></span></span></a></li>
	<? endforeach; ?>
</ul>
