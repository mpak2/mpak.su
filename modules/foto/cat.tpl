<!-- [settings:foto_lightbox] -->
<div style="margin-top:20px;">
	<ul>
		<? foreach(rb("cat") as $cat): ?>
			<li style="display:inline; margin-left:10px;"><a href="/<?=$arg['modname']?>:cat/<?=$cat['id']?>"><?=$cat['name']?></a></li>
		<? endforeach; ?>
	</ul>
	<? if($cat = rb("cat", "id", $_GET['id'])): ?>
		<h1><?=$cat['name']?></h1>
		<div id="gallery">
			<? foreach(rb("imgs", "cat_id", "id", ($cat['id'] ?: rb("cat"))) as $imgs): ?>
				<span style="padding:5px; display:inline-block;">
					<a href="/foto:img/<?=$imgs['id']?>/tn:imgs/fn:img/w:600/h:800/null/img.png">
						<img src="/foto:img/<?=$imgs['id']?>/tn:imgs/fn:img/w:120/h:120/null/img.png">
					</a>
				</span>
			<? endforeach; ?>
		</div>
	<? endif; ?>
</div>
