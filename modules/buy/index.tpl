<? if($v = $conf['tpl']['index'][ $_GET['id'] ]): ?>
	<!-- [settings:foto_lightbox] -->
	<div class="LeftColl">
		<div class="BreadCrumbs">
			<a href="/">Главная</a> /
			<a href="/<?=$arg['modpath']?>:type">Типы</a> /
			<a href="/<?=$arg['modname']?>/type_id:<?=$v['type_id']?>"><?=$tpl['type'][ $v['type_id'] ]['name']?></a> /
			<span><?=$v['name']?></span>
		</div>
		<h1><?=$v['name']?></h1> 
		
		<script>
			$(function(){
				$(".add_to_cart_button").click(function(){
					basket_counter = $(".basket_counter").text();
					basket_counter = parseInt(basket_counter)+1;
					count = parseInt($(this).attr("count"))+1;
					$(this).attr("count", count);
					$(".basket_counter").text(basket_counter);
					$.post("/<?=$arg['modpath']?>:basket/index_id:<?=$v['id']?>/count:"+count+"/null", {temp:1}, function(data){
//						alert(count);
					});
				});
			});
		</script>
		<div class="ProductPage">
			<!-- // ProductPageHeader // -->
			<div class="ProductPageHeader">
			<div class="ProductPageImage">
				<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/w:300/h:180/null/img.jpg" alt="">
			</div>
			<div class="ProductPageAddtocart">
				<span class="ProductPagePrice"><?=$v['price']?> руб.</span>
				<a class="AddToCart add_to_cart_button" count="<?=(int)$tpl['orders']['count']?>" href="javascript:return false;">Добавить в корзину</a>
			</div>
			<br clear="all">
			</div>
			<h2>Описание</h2>
			<div><?=nl2br($v['description'])?></div>
			<h2>Характеристики</h2>
			<div><?=$v['characteristics']?></div>
		</div>
	</div>
<? elseif(true || $_GET['type_id']): ?>
	<div class="BreadCrumbs">
		<a href="/">Главная</a> /
		<a href="/<?=$arg['modname']?>:type">Товары и услуги</a> /
		<? if($_GET['type_id']): ?>
			<span><a href="/<?=$arg['modname']?>/type_id:<?=$_GET['type_id']?>"><?=$tpl['type'][ $_GET['type_id'] ]['name']?></a></span> /
		<? endif; ?>
		<? if($_GET['manufacturers_id']): ?>
			<span><a href="/<?=$arg['modname']?>/manufacturers_id:<?=$_GET['manufacturers_id']?>"><?=$tpl['manufacturers'][ $_GET['manufacturers_id'] ]['name']?></a></span> /
		<? endif; ?>
	</div>
	<? if($tpl['type'][ $_GET['type_id'] ]['manufacturers'] && !$_GET['manufacturers_id']): ?>
		<div style="overflow:hidden; margin-bottom:10px;">
			<? foreach($tpl['manufacturers'] as $k=>$v): ?>
				<div style="float:left; width:50%;"><a href="/<?=$arg['modname']?>/type_id:<?=$_GET['type_id']?>/manufacturers_id:<?=$v['id']?>"><?=$v['name']?></a></div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
		<h1><?=$tpl['type'][ $_GET['type_id'] ]['name']?></h1>
		<div><?=$tpl['mpager']?></div>

		<div class="CatalogRow">
			<? foreach($tpl['index'] as $v): ?>
				<div class="CatalogItem">
					<a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=mpue($v['name'])?>" class="CatalogItemTitle"><?=$v['name']?></a>
					<div class="CatalogItemImage"><a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=mpue($v['name'])?>"><img width="96" height="56" src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:120/h:100/null/img.jpg" alt=""></a></div>
					<div class="CatalogItemPrice">Цена: <span><?=$v['price']?>р.</span></div>
					<a class="CatalogItemBuy" href="/<?=$arg['modpath']?>/<?=$v['id']?>/<?=mpue($v['name'])?>">Подробнее</a>    
				</div>
			<? endforeach; ?>
		</div>
		<div><?=$tpl['mpager']?></div>
<? else: ?>
	<form method="post">
		<script>
			$(function(){
				$(".buy").mousedown(function(){
					count = $(this).parents("[index_id]").find(".count").val();// alert(count);
					index_id = $(this).parents("[index_id]").attr("index_id");// alert(index_id);
					document.location.href = "/<?=$arg['modname']?>:корзина/товар:"+index_id+"/количество:"+count;
				});
			});
		</script>
		<div id="price_index">
			<h2 style="margin-left:60px; color:blue;"><?=$t['name']?></h2>
			<div><?=$conf['tpl']['mpager']?></div>
			<div style="margin:5px; overflow:hidden; color:#BCBCBC;" index_id="<?=$v['id']?>">
				<div style="width:250px; float:left;">
					<?=($v['price_id'] ? "Типоразмер" : "Название")?>
				</div>
				<? if($v['price_id']): ?>
					<span>Склад</span>
				<? endif; ?>
				<div style="width:50px; font-weight:bold; float:left;"><?=$v['weight']?></div>
				<? if($arg['access'] > 1): ?>
					<div style="width:150px; float:right;">
						Количество.
					</div>
				<? endif; ?>
				<div id="price" style="width:180px; float:right;">
					<span>
						Цена за шт.
					</span>
				</div>
			</div>
			<? foreach($conf['tpl']['index'] as $k=>$v): ?>
				<div style="margin:5px; overflow:hidden;" index_id="<?=$v['id']?>">
					<div style="width:250px; font-weight:bold; float:left;">
						<a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=str_replace('%', '%25', $v['name'])?>" style="color:#4AB647;"><?=$v['name']?></a>
					</div>
					<span><?=$v['count']?></span>
					<div style="width:50px; font-weight:bold; float:left;"><?=$v['weight']?></div>
					<? if($arg['access'] > 1): ?>
						<div style="width:150px; float:right;">
							<input class="count" type="text" style="width:40px; text-align:center;" value="<?=($v['price_id'] ? 4 : 1)?>">
							<input class="buy" type="button" value="Заказать">
						</div>
					<? endif; ?>
					<div id="price" style="width:180px; font-weight:bold; float:right;">
						<div style="float:left; width:70px;">
							<?=$v['price']?>&nbsp;р.
						</div>
						<? if($v['price_id']): ?>
							<div>
								<?=number_format(4*$price, 0)?> р.
							</div>
						<? endif; ?>
					</div>
				</div>
			<? endforeach; ?>
			<div><?=$conf['tpl']['mpager']?></div>
		</div>
	</form>
<? endif; ?>