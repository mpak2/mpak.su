<?

$tpl['basket'] = qn("SELECT b.*, i.name, i.price AS price, (b.count*i.price) AS sum
	FROM {$conf['db']['prefix']}{$arg['modpath']}_basket AS b
	LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_items AS i ON (b.items_id=i.id)
	WHERE b.basket_order_id=0 AND b.uid=". (int)$conf['user']['uid']
);

?>
	<div id="basket">
		<script>
			$(function(){
				$("#basket").on("add", function(event, basket){
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
				}).on("click", "a.del", function(){
					var basket_id = $(this).parents("[basket_id]").attr("basket_id");
					$.post("/<?=$arg['modname']?>:ajax/class:basket", {id:-basket_id}, $.proxy(function(data){
						if(isNaN(data)){ alert(data) }else{
							$(this).parents("[basket_id]").remove();
						}
					}, this));
				});
				$.each(<?=json_encode($tpl['basket'])?>, function(){
					$("#basket").trigger("add", this);
				})
			})
		</script>
		<h4>Корзина товаров</h4>
		<div class="list" style="padding:0 10px;">
			<script type="text/template">
				<div basket_id="${id}">
					<span style="float:right;">
						<a href="javascript:" class="del"><img src="/img/del.png"></a>
					</span>
<!-- 					<span style="float:right;">${price}&nbsp;р.</span>-->
					<span class="title-item"><a href="/catalog/_item${items_id}.html">${name}</a></span>
				</div>
			</script>
		</div>
		<p><a href="/<?=$arg['modname']?>:basket/">Перейти к оформлению заказа</a></p>
	</div>
