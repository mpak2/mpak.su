<script>
	$(function(){
		$(".price_del").click(function(){
			order_id = $(this).parents("[order_id]").attr("order_id");// alert(order_id);
			$.get("/<?=$arg['modname']?>:<?=$arg['fn']?>/null", {товар:order_id, количество:0}, function(data){
				if(isNaN(data)){ alert(data) }else{
					$("[order_id="+order_id+"]").hide();
				}
			});
		});
	});
</script>
<div>
	<div style="float:right;">
		<a href="/<?=$arg['modname']?>">
			Выбрать другой товар
		</a>
	</div>
	<h3>Корзина</h3>
	<ul style="margin-top:20px;">
		<? foreach($conf['price']['order'] as $k=>$v): ?>
			<li order_id="<?=$v['id']?>" style="overflow:hidden;">
				<div style="float:right; width:200px;">
					<div style="float:right;">
						<span class="price_del" style="cursor:pointer;"><img src="/img/del.png"></span>
					</div>
					<div style="float:left; width:100px;"><b><?=number_format($v['sum'], 0)?></b> р.</div>
					<div><b><?=$v['count']?></b> шт.</div>
				</div>
				<span>
					<a href="/<?=$arg['modname']?>/<?=$v['id']?>"><?=$v['name']?></a>
				</spam>
			</li>
		<? endforeach; ?>
	</ul>
	<div style="text-align:right;"><?=$sum?></div>
	<style>
		#order {margin:20px 0;}
		#order > div {margin-top:5px;}
		#order > div > div:first-child {float:left; width:200px; font-weight:bold;}
		#order > div > div input,textarea {width:40%;}
	</style>

	<script src="/include/jquery/jquery.iframe-post-form.js"></script>
	<script>
		$(function(){
			$("#basket").submit(function(){
				status = true;
				$(this).find("input").each(function(key, val){
					if($(val).val() == ""){
						$(val).css("background-color", "yellow");
						status = false;
					}
				});
				if(!status){
					alert("Не все обязательные поля заполнены!!!");
				}
				return status;
			}).iframePostForm({
				complete:function(data){
					$("#basket").html(data).css("text-align", "center");
				}
			});
			
		});
	</script>
	<form id="basket" method="post" action="/<?=$arg['modname']?>:<?=$arg['fe']?>/null" style="margin-top:20px;">
		<h3>Заказать</h3>
		<div id="order">
			<div>
				<div>* Имя:</div>
				<div><input type="text" name="name"></div>
			</div>
			<div>
				<div>* Адрес:</div>
				<div><input type="text" name="addr"></div>
			</div>
			<div>
				<div>* Телефон:</div>
				<div><input type="text" name="tel"></div>
			</div>
			<div>
				<div>Комментарии:</div>
				<div><textarea name="description"></textarea></div>
			</div>
		</div>
		<div>
			<input type="submit" value="Заказать">
		</div>
	</form>
</div>