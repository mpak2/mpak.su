		$(function(){
			$(".estimate a").live("click", function(){
				if(act = $(this).parents("[act]").attr("act")){
					estimate = $(this).attr("estimate");// alert(estimate);
					$.post("/<?=$arg['modname']?>/null", {estimate:estimate}, function(data){
						if(isNaN(data)){ alert(data) }else{
							$(".estimate").attr("act", "").find("span").text(data);
						}
					});
				}
			}).live("mousemove", function(){
				if(act = $(this).parents("[act]").attr("act")){
					estimate = $(this).attr("estimate");// alert(estimate);
					$(this).parents("[act]").find("a").each(function(key, val){
						if($(val).attr("estimate") <= estimate)
							$(val).find("img").attr("src", "/<?=$arg['modname']?>:img/null/s.png");
					});
				}
			}).live("mouseout", function(){
				if(act = $(this).parents("[act]").attr("act")){
					$(this).parents("[act]").find("a img").attr("src", "/<?=$arg['modname']?>:img/null/ns.png");
				}
			});
		});