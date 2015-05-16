<div class="pager">
	<script>
		$(function(){
			$(".pager").find("a[href='<?=$_SERVER['REQUEST_URI']?>']").addClass("active");
		})
	</script>
	<a style="font-size: 12px;" href="<?=$mpager['first']?>">‹‹ начало</a>
	<a style="font-size: 12px;" href="<?=$mpager['prev']?>">‹ назад</a>
	<? foreach($mpager as $name=>$pager): ?>
		<? if(is_numeric($name)): ?>
			&nbsp;<a href="<?=$pager?>" style="font-weight:<?=($name == $_GET['p']+1 ? "bold" : "normal")?>;"><?=$name?></a>
		<? endif; ?>
	<? endforeach; ?>
	&nbsp;<a style="font-size: 12px;" href="<?=$mpager['next']?>">вперед ›</a>
	&nbsp;<a style="font-size: 12px;" href="<?=$mpager['last']?>">конец ››</a>
</div>