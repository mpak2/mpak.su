<?

$tpl['basket'] = qn("SELECT b.*, i.name, i.price AS price, (b.count*i.price) AS sum
	FROM {$conf['db']['prefix']}{$arg['modpath']}_basket AS b
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS i ON (b.index_id=i.id)
	WHERE b.basket_order_id=0 AND b.uid=". (int)$conf['user']['uid']
);

?>
	<div id="basket">
		<script>
			$(function(){
				$("#basket").on("add", function(event, basket){
					var length = $(event.delegateTarget).find("[basket_id][index_id="+basket.index_id+"]").length;
					if(length == 1){
						var count = parseInt($(event.delegateTarget).find("[basket_id][index_id="+basket.index_id+"]").find(".count b").text());
						var basket_id = $(event.delegateTarget).find("[basket_id][index_id="+basket.index_id+"]").attr("basket_id");
						count = count + parseInt(basket.count);
						$.post("/<?=$arg['modname']?>:ajax/class:basket", {id:basket_id, count:count}, function(data){
							if(isNaN(data)){ alert(data) }else{
								$(event.delegateTarget).find("[basket_id][index_id="+basket.index_id+"]").find(".count b").text(count);
							}
						});
					}else{
						$.post("/<?=$arg['modname']?>:ajax/class:basket", basket, $.proxy(function(data){
							if(isNaN(data)){ alert(data) }else{
								basket.id = data;
								var template = $(event.delegateTarget).find("script[type='text/template']").html();
								$.each(basket, function(key, val){
									template = template.split("${"+key+"}").join(val);
								});
								$(event.delegateTarget).find(".list").prepend(template);
							}
						}, this));
						$(event.delegateTarget).trigger("calc");
					}
				}).on("click", "a.del", function(){
					var basket_id = $(this).parents("[basket_id]").attr("basket_id");
					$.post("/<?=$arg['modname']?>:ajax/class:basket", {id:-basket_id}, $.proxy(function(data){
						if(isNaN(data)){ alert(data) }else{
							$(this).parents("[basket_id]").remove();
						}
					}, this));
				}).on("calc", function(event){
					var itogo = length = 0;
					setTimeout(function(){
						$(event.delegateTarget).find(".list [basket_id]").each(function(){
							var price = parseInt($(this).find(".price b").text());
							var count = parseInt($(this).find(".count b").text());
							length = length + count;
							itogo = itogo + count * price;
						}).length;
						$(event.delegateTarget).find(".basket_amount").text(length);
						$(event.delegateTarget).find(".basket_price").text(itogo);
					}, 1000);
				});
				$.when(
					$.each(<?=json_encode($tpl['basket'])?>, function(){
						$("#basket").trigger("add", this);
					})
				).done(function(){
					$("#basket").trigger("calc");
				});
			})
		</script>
		<span style="float:right; display:none;">
			<span class="count"><strong>0</strong> товар</span>
		</span>
		<h4>Корзина товаров</h4>
		
		<div class="list" style="padding:0 10px;">
			<script type="text/template">
				<div index_id="${index_id}" basket_id="${id}">
					<span style="float:right;">
						<a href="javascript:" class="del"><img src="/img/del.png"></a>
					</span>
 					<span class="count" style="float:right;"><b>${count}</b>&nbsp;шт.</span>
 					<span style="float:right;"><b>${price}</b>&nbsp;р.</span>
					<span class="title-item"><a href="/<?=$arg['modname']?>/${index_id}">${name}</a></span>
				</div>
			</script>
		</div><br />
		<p><a href="/<?=$arg['modname']?>:basket/">Перейти к оформлению заказа</a></p>
	</div>
