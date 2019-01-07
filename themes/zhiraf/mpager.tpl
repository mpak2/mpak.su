<div class="pager">
	<style>
		.pager a.numeric.active {color:#fe8e23; font-weight:bold; border:1px solid #aaa;}
	</style>
	<script>
		(function($, script){
			$(script).parent().one("init", function(e){
				setTimeout(function(){
					var request_uri = decodeURIComponent(location.pathname)+decodeURIComponent(location.search);
					if((a = $(e.delegateTarget).find("a[href='"+request_uri+"']")).length){
						$(a).addClass("active");
					}else{
						$(e.delegateTarget).find("a.numeric:first").addClass("active");
					}
//					$(".pager").find("a[href='"+request_uri+"']").addClass("active");
				}, 100)
			}).trigger("init")
		})(jQuery, document.scripts[document.scripts.length-1])
	</script>
	<a href="<?=$mpager['first']?>">‹‹ начало</a>
	<a href="<?=$mpager['prev']?>" rel="prev">‹ назад</a>
	<? foreach($mpager as $name=>$pager): ?>
		<? if(is_numeric($name)): ?>
			&nbsp;<a class="numeric" href="<?=$pager?>"><?=$name?></a>
		<? endif; ?>
	<? endforeach; ?>
	&nbsp;<a href="<?=$mpager['next']?>" rel="next">вперед ›</a>
	&nbsp;<a href="<?=$mpager['last']?>">конец ››</a>
</div>
