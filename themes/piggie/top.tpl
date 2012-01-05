<script>
 	$(document).ready(function(){
 		$('ul.menu li a[href$='+$.url.attr('path')+']').addClass('active');
 	});
</script>
<ul id="nav">
	<? foreach($menu as $k=>$v): ?>
		<li class="page_item"><a href="<?=$v['link']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
