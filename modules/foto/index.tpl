<!-- [settings:foto_lightbox] -->

<div style="margin-top:20px;">
	<ul>
		<? foreach(rb("cat") as $cat): ?>
			<li style="display:inline; margin-left:10px;"><a href="/<?=$arg['modname']?>:cat/<?=$cat['id']?>"><?=$cat['name']?></a></li>
		<? endforeach; ?>
	</ul>
	<div id="gallery" style="overflow:hidden;">
		<? foreach(rb("imgs", "cat_id", "id", ($_GET['cat_id'] ?: rb("cat"))) as $imgs): ?>
			<div style="float:left; padding: 10px; min-height:115px;">
				<a title="<?=$imgs['description']?>" alt="<?=$imgs['description']?>" href="/foto:img/<?=$imgs['id']?>/tn:imgs/fn:img/w:800/h:600/null/img.png">
					<img src="/foto:img/<?=$imgs['id']?>/tn:imgs/fn:img/w:120/h:120/null/img.png">
					<div><?=$imgs['description']?></div>
				</a>
			</div>
		<? endforeach; ?>
		<div style="clear:both;"><?=$conf['tpl']['mpager']?></div>
	</div>
</div>