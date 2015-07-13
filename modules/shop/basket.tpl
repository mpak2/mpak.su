<div class="text">
	<h1>В корзине:</h1>
	<div class="bask">
		<script>
			(function($, script){
				$(script).parent().on("ajax", "*", function(e, table, get, post, complete){
					var href = "/<?=$arg['modname']?>:ajax/class:"+table;
					$.each(get, function(key, val){
						href += "/"+ (key == "id" ? "" : key+ ":")+ val;
					});
					$.post(href, post, function(data){
						if(typeof(complete) == "function"){
							complete.call(e.currentTarget, data);
						}
					}, "json").fail(function(error) {
						alert(error.responseText);
					}); e.stopPropagation();
				}).on("click", "a.del", function(e){
					if(confirm("Подтвердите удаление")){
						var basket_order_id = $(e.currentTarget).parents("[basket_order_id]").attr("basket_order_id");
						$(e.currentTarget).trigger("ajax", ["basket_order", {id:basket_order_id}, {id:-basket_order_id}, function(basket_order){
							console.log("basket_order:", basket_order);
							document.location.reload(true);
						}])
					}
				}).on("change", "input[name=count]", function(e){
					var basket_order_id = $(e.currentTarget).parents("[basket_order_id]").attr("basket_order_id");
					var count = $(e.currentTarget).val();
					$(e.currentTarget).trigger("ajax", ["basket_order", {id:basket_order_id}, {count:count}, function(basket_order){
						document.location.reload(true);
					}])
				})
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody>
				<tr>
					<th>Фото товара </th>
					<th>Наименование товара</th>
					<th>Кол-во</th>
					<th>Цена</th>
					<th>Сумма</th>
					<th>Удалить</th>
				</tr>
				<? foreach(rb("basket_order", "basket_id", "id", 0) as $basket_order): ?>
					<? if($index = rb("index", "id", $basket_order['index_id'])): ?>
						<tr basket_order_id="<?=$basket_order['id']?>">
							<td class="foto"><img src="/shop:img/<?=$index['id']?>/tn:index/fn:img/w:65/h:65/null/img.png" alt="<?=$index['name']?>"></td>
							<td class="name">
								<a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a>
							</td>
							<td class="amount"><input type="text" name="count" value="<?=$basket_order['count']?>" size="3"></td>
							<td class="price"><span class="Requirement"><strong><?=$index['price']?></strong></span>&nbsp;руб.</td>
							<td class="amount"><span class="Requirement"><strong><?=$itogo[] = ($index['price']*$basket_order['count'])?></strong></span>&nbsp;руб.</td>
							<td class="remove"><a class="del" href="javascript:void(0);"><img src="/img/del.png"></a></td>
						</tr>
					<? endif; ?>
				<? endforeach; ?>
			</tbody>
		</table>
		<div class="itogo">
			Итого: 
			<span class="itogorub"> <span class="Requirement"><strong><?=array_sum($itogo)?></strong></span> руб.</span>
		</div>
	</div>
	<div class="clear"></div>
	<form>
		<div class="table">
			<div>
				<span>Ваш телефон</span>
				<span><input type="tel"></span>
			</div>
			<div>
				<span>Адрес доставки</span>
				<span><input type="addr"></span>
			</div>
		</div>
	</form>
</div>