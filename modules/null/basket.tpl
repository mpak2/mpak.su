<? if(array_key_exists("order", $_GET)): ?>
	<div class="container">
		<center>
			<br>
				<strong>
					<font color="#b50101">Данные успешно отправлены. Спасибо за Ваш заказ.</font>
				</strong>
				<br><br><a href="/"> Перейти на главную страницу</a>
				<br><br><a href="/catalog/"> Перейти в каталог продукции</a>
			<br>
		</center>
	</div>
<? else: ?>
	<div class="order">
		<h2>Оформление заказа</h2>
		<form action="/<?=$arg['modname']?>:basket/order" method="post" id="orderForm">
			<input type="hidden" name="clientLogin" id="clientLogin" value="office@visier.ru">
			<input type="hidden" name="clientId" id="clientId" value="750">
			<script>
				$(function(){
					$(".order").on("click", "a.dell", function(event){
						var basket_id = $(this).parents("[basket_id]").attr("basket_id");
						console.log("basket_id:", basket_id);
						$.post("/<?=$arg['modname']?>:ajax/class:basket", {id:-basket_id}, function(data){
							if(isNaN(data)){ alert(data) }else{
								console.log("$(event.delegateTarget):", $(event.delegateTarget));
								$(event.delegateTarget).find("[basket_id="+basket_id+"]").remove();
							}
						})
					}).on("change", "[basket_id] input[type=text]", function(){
						var basket_id = $(this).parents("[basket_id]").attr("basket_id");
						var count = $(this).val();
						$.post("/<?=$arg['modname']?>:ajax/class:basket", {id:basket_id, count:count}, $.proxy(function(data){
							if(isNaN(data)){ alert(data) }else{
								$(this).css({"background-color":"lime"});
								setTimeout($.proxy(function(){
									$(this).css({"background-color":""});
								}, this), 300);
							}
						}, this))
					});
				});
			</script>
			<div class="table">
				<style>
					.order div.table .th > span {font-weight:bold; padding:10px 0;}
				</style>
				<div class="th">
					<span>Наименование</span>
<!--					<span>Цена</span>-->
					<span>Количество</span>
<!--					<span>Сумма</span>-->
					<span>Удалить</span>
				</div>
				<? foreach($tpl['basket'] as $basket): ?>
					<div basket_id="<?=$basket['id']?>">
						<span>
							<a href="/catalog/_item<?=$basket['items_id']?>.html">
								<?=$tpl['items'][ $basket['items_id'] ]['name']?>
							</a>
						</span>
<!--						<span><?=$tpl['items'][ $basket['items_id'] ]["price"]?> р.</span>-->
						<span><input class="kolvo" id="kolvo1" name="kolvo1" rel="1" type="text" value="<?=$basket['count']?>" style="max-width:40px;"></span>
<!--						<span><?=($tpl['items'][ $basket['items_id'] ]["price_{$tpl['clients']['price']}"]*$basket['count'])?> р.</span>-->
						<span>
							<a class="dell" href="javascript:"><img src="/img/del.png" rel="1"></a>
						</span>
					</div>
				<? endforeach; ?>
			</div>
			<div id="comment">Комментарий<br><textarea rows="2" name="name"></textarea></div>
			<input type="submit" id="submit" name="submit" value="Отправить заказ">
			<!--<input type="button" id="calculate" name="calculate" value="Пересчитать">-->
			<input type="hidden" id="itemCount" name="itemCount" value="1">
		</form>
	</div>
<? endif; ?>