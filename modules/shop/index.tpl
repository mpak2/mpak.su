<? if($e = $tpl['index'][ $_GET['id'] ]): ?>
	<div>
		<? if($arg['access'] > 3): ?>
			<span style="float:right;">
				<a href="/?m[shop]=admin&r=mp_shop_index&where[id]=<?=$e['id']?>"><img src="/img/aedit.png"></a>
			</span>
		<? endif; ?>
		<h1><?=$e['name']?></h1>
		<div>
			<? if($v['img']): ?>
				<img src="/<?=$arg['modname']?>:img/<?=$e['id']?>/tn:index/fn:img/w:200/h:200/null/img.jpg" style="float:right; margin-left:10px;">
			<? endif; ?>
			<span style="float:right;"><b><?=$e['price']?></b> руб.</span>
			<div>Производитель: <a href="/"><?=$e['vendor']?></a></div>
			<div>Группа: <a href=""><?=$e['groups']?></a></div>
			<?=$e['description']?>
			<?=$e['text']?>
		</div>
		<div style="margin:10px; text-align:right;">
			<script>
				$(function(){
					$("input.basket").click(function(){
						$.post("/<?=$arg['modname']?>:basket/null", {index_id:<?=$e['id']?>, count:1}, function(data){
							if(isNaN(data)){ alert(data) }else{
								if(confirm("Товар добавлен в корзину. Перейти в корзину?")){
									document.location.href = "/<?=$arg['modname']?>:basket";
								}
							}
						});
					});
				});
			</script>
			<input class="basket" type="button" value="В корзину">
		</div>
	</div>
<? else: ?>
	<div><?=$tpl['mpager']?></div>
	<? foreach($tpl['index'] as $v): ?>
		<div style="overflow:hidden">
			<a href="/<?=$arg['modname']?>/<?=$v['id']?>"><h2><?=$v['name']?></h2></a>
			<? if($v['img']): ?>
				<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:index/fn:img/w:100/h:100/null/img.jpg" style="float:right; margin-left:10px;">
			<? endif; ?>
			<span style="float:right;"><b><?=$v['price']?></b> руб.</span>
			<div>Производитель: <a href="/"><?=$v['vendor']?></a></div>
			<div>Группа: <a href=""><?=$v['groups']?></a></div>
			<div><?=$v['description']?></div>
		</div>
	<? endforeach; ?>
	<div><?=$tpl['mpager']?></div>
<? endif; ?>
