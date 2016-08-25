<? # Заголовка блока
################################# php код #################################

//if(array_key_exists('blocks', $_GET['m']) && array_key_exists('null', $_GET) && ($_GET['id'] == $arg['blocknum']) && $_POST){ exit(mpre($_POST)) };

################################# верстка ################################# ?> 
<? if($alias = get($_GET, "") /*&& ($index = rb("index", "alias", "[{$alias}]"))*/): ?>
	<div class="orders" style="position:fixed; top:100px; margin:0 auto; z-index:9; width:100%;">
<!--		<div class="bg" style="position:fixed;width:100%;height:100%;background-color:black;opacity:0.5;display:none;left:0;top:0;z-index:10"></div>-->
		<div class="order" style="background-color:white; width:320px; margin:0 auto; text-align:center; border:10px solid #ccc;">
			<div style="padding:20px;">
				<a class="close" style="float:right; padding:3px; border:1px solid #eee; cursor:pointer; border-radius:3px;"><img src="/img/del.png"></a>
				<form action="/themes:ajax/class:orders" method="post" style="background:none; border:0;">
					<script src="/include/jquery/jquery.iframe-post-form.js"></script>
					<img src="/themes:img/w:250/h:250/null/phone.jpg">
					<div style="bottom:0; margin-top:50px;">
						<input type="hidden" name="href" value="<?=$_SERVER['REQUEST_URI']?>">
						<input type="hidden" name="index_id" value="<?=get($conf, "user", "sess", "themes_index", "id")?>">
						<p><input type="text" name="name" required placeholder="Ваше имя"></p>
						<p><input type="text" name="tel" required placeholder="Контактный телефон"></p>
						<p><button type="submit">Оставить заявку</button></p>
					</div>
				</form>
			</div>
		</div>
	</div>
<? else: ?>
	<style>
		.orders input {
			width:100%;
		}
		.themes_orders > button {
			font-size: 18px;
			color: #ffffff;
			background: linear-gradient(#b1ce3b, #7dae23);
			border: 1px solid #728e21;
			border-top: 1px solid #83a425;
			border-bottom: 1px solid #5c731c;
			text-decoration: none;
		}
		.themes_orders input {padding:0 5px; width:100%; height:35px; border:1px solid #ddd; width:100%;}
		.themes_orders .order button {height:35px; background-color:red; border:0; color:white; border-radius:3px; font-weight:bold; font-size:20px; width:100%;}
		.themes_orders .order {
			text-align:center;
			background: #fff none repeat scroll 0 0;
			bottom: 0;
			box-shadow: 0 0 4px rgba(0, 0, 0, 0.2), 0 0 10px rgba(0, 0, 0, 0.1) inset;
			display: none;
			height: 400px;
			left: 0;
			line-height: 1.3;
			margin: auto;
/*			padding: 30px;*/
			position: fixed;
			right: 0;
			top: 0;
			width: 350px;
			z-index: 1000;
		}
	</style>
	<script sync>
		function order(){
			$("<"+"div"+">").load("/blocks/null/orders").appendTo("body");
			$(function(){
				$.getScript("/include/jquery/jquery.iframe-post-form.js", function(){
					$("body").on("click", ".orders a.close", function(e){
						$(e.currentTarget).parents(".orders").remove();
					}).find("form").iframePostForm({
						complete:function(data){
							try{if(json = JSON.parse(data)){
								console.log("json:", json);
								alert("Информация сохранена");
//								document.location.reload(true);
								$(".orders").remove();
							}}catch(e){if(isNaN(data)){ alert(data) }else{
								console.log("date:", data)
							}}
						}
					});
				});
			})
		}
	</script>
<? endif; ?>
