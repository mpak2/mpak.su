<div class="pager">
	<style>
		.pager a.numeric.active {font-weight:bold; border:1px solid blue; padding:2px;}
	</style>
	<script>
		$(function(){
			$(".pager").find("a[href='<?=urldecode($_SERVER['REQUEST_URI'])?>']").addClass("active");
		})
	</script>
	<a style="font-size: 12px;" href="<?=$mpager['first']?>">‹‹ начало</a>
	<a style="font-size: 12px;" href="<?=$mpager['prev']?>">‹ назад</a>
	<? foreach($mpager as $name=>$pager): ?>
		<? if(is_numeric($name)): ?>
			&nbsp;<a class="numeric" href="<?=$pager?>"><?=$name?></a>
		<? endif; ?>
	<? endforeach; ?>
	&nbsp;<a style="font-size: 12px;" href="<?=$mpager['next']?>">вперед ›</a>
	&nbsp;<a style="font-size: 12px;" href="<?=$mpager['last']?>">конец ››</a>
</div>