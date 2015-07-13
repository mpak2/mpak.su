<? if(${$arg['fe']} = rb($arg['fe'], "id", $_GET['id'])): ?>
	<div index_id="<?=${$arg['fe']}['id']?>" class="<?=$arg['modpath']?>_<?=$arg['fe']?>">
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
				}).on("basket", function(e, index_id){
					setTimeout(function(){
						var basket = $("#divShop.basket");
						var img = $("[index_id="+index_id+"]").find("img.img");
						var img2 = $(img).clone().appendTo($("body")).css({position:"absolute", "opacity":0.7, "z-index":1010, top:$(img).offset().top+"px", left:$(img).offset().left+"px"});
						$(img2).animate({top:$(basket).offset().top+"px", left:$(basket).offset().left+"px"}, function(){
							$(this).animate({"opacity":0}, function(){
								$(this).remove();
							});
						});
					}, 500);
				}).on("click", ".itembuy_but a", function(e){
					var index_id = $(e.currentTarget).parents("[index_id]").attr("index_id");
					$(e.currentTarget).trigger("ajax", ["basket_order", {index_id:index_id}, {index_id:index_id}, function(basket_order){
						$(e.currentTarget).trigger("ajax", ["basket_order", {id:basket_order.id}, {count:parseInt(basket_order.count)+1}, function(basket_order){
							$(e.currentTarget).trigger("basket", basket_order.index_id);
							console.log("basket_order:", basket_order);
						}])
					}])
				});
			})(jQuery, document.scripts[document.scripts.length-1])
		</script>
		<?=aedit("/?m[{$arg['modname']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={${$arg['fn']}['id']}")?>
		<h1><?=${$arg['fe']}['name']?></h1>
		<? if(${$arg['fe']}['img']): ?>
			<img class="img" src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:100/h:100/null/img.png" alt="<?=$index['name']?>">
		<? endif; ?>
		<div style="font-weight:bold; margin:10px 0;"><?=${$arg['fe']}['description']?></div>
		<div><?=$index['text']?></div>
		<div class="itembuy_but">
			<a href="javascript:void(0);">
				<img src="/<?=$arg['modname']?>:img/null/buy.png" alt="Купить '<?=$index['name']?>'" title="Купить '<?=$index['name']?>' " width="104" height="34">
			</a>
		</div>
	</div>
<? else: ?>
	<div id="list">
		<ul>
			<? foreach(rb("index", 20) as $index): ?>
				<li>
					<a href="/<?=$arg['modname']?>/<?=$index['id']?>">
						<img src="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:100/h:100/null/img.png" alt="<?=$index['name']?>">
					</a>
					<p><a href="/<?=$arg['modname']?>/<?=$index['id']?>"><?=$index['name']?></a></p>
					<small><?=$index['description']?></small>
					<div class="price">
						<strong><?=$index['price']?></strong> руб.
					</div>
				</li>
			<? endforeach; ?>
		</ul>
		<div><?=$tpl['pager']?></div>
	</div>

	<div><?=$tpl['mpager']?></div>
<? endif; ?>
