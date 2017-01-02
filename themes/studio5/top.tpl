<ul id="menu">
	<script>
		$(function(){
			$("#menu li a[href='<?=urldecode($_SERVER['REQUEST_URI'])?>']").addClass("hover");
		});
	</script>
	<? foreach($menu as $k=>$v): ?>
		<li><a href="<?=get($v,'href')?>" title="<?=get($v,'name')?>"><?=get($v,'name')?></a></li>
	<? endforeach; ?>
</ul>
