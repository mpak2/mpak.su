<? if(array_key_exists('null', $_GET)): ?>
	<script>
		$(function(){
			$(".estimate a").live("click", function(){
				if(act = $(this).parents("[act]").attr("act")){
					$(".estimate").attr("act", "");
					estimate = $(this).attr("estimate");// alert(estimate);
					$.post("/<?=$arg['modname']?>/null", {estimate:estimate}, function(data){
						$(".estimate").find("span").text(data);
					});
				}
			}).live("mousemove", function(){
				if(act = $(this).parents("[act]").attr("act")){
					estimate = $(this).attr("estimate");// alert(estimate);
					$(this).parents("[act]").find("a").each(function(key, val){
						if($(val).attr("estimate") <= estimate){
							$(val).find("img").attr("src", "/<?=$arg['modname']?>:img/null/s.png");
						}else{
							$(val).find("img").attr("src", "/<?=$arg['modname']?>:img/null/ns.png");
						}
					});
				}
			}).live("mouseout", function(){
				if(act = $(this).parents("[act]").attr("act")){
					estimate = $(this).parents("[act]").attr("estimate");// alert(estimate);
					$(this).parents("[act]").find("a").each(function(key, val){
						if($(val).attr("estimate") > estimate){
							$(val).find("img").attr("src", "/<?=$arg['modname']?>:img/null/ns.png");
						}else{
							$(val).find("img").attr("src", "/<?=$arg['modname']?>:img/null/s.png");
						}
					});
				}
			});
		});
	</script>
	<div class="estimate" estimate="<?=$conf['tpl']['estimate']['sum']?>" act="1" style="white-space:nowrap;">
		<? for($i=1; $i<=5; $i++): ?>
			<a estimate="<?=$i?>" href="javascript: void(0);">
				<img src="/<?=$arg['modname']?>:img/null/<?=($conf['tpl']['estimate']['sum'] >= $i ? "" : "n")?>s.png">
			</a>
		<? endfor; ?>
		<span><?=(int)$conf['tpl']['estimate']['count']?>/<?=$conf['tpl']['estimate']['sum']?></span>
	</div>
<? else: ?>
<? endif; ?>