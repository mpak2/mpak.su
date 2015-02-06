<div class="pager">
	<script>
		$(function(){
			$(".pager").find("a[href='<?=$_SERVER['REQUEST_URI']?>']").addClass("active");
		})
	</script>
	<a style="font-size: 12px;" href="<?=$mpager['prev']?>">‹ назад</a>
	<? foreach(array_slice($mpager, 1, -1) as $name=>$pager): ?>
		&nbsp;<a href="<?=$pager?>"><?=$name+1?></a>
	<? endforeach; ?>
	&nbsp;<a style="font-size: 12px;" href="<?=$mpager['next']?>">вперед ›</a>
</div>