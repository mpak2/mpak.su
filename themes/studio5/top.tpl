<ul id="menu">
	<script>
		$(function(){
			$("#menu li a[href='<?=urldecode($_SERVER['REQUEST_URI'])?>']").addClass("hover");
		});
	</script>
	<? foreach($menu[0] as $k=>$v): ?>
		<li><a href="<?=$v['href']?>"><?=$v['name']?></a></li>
	<? endforeach; ?>
</ul>
