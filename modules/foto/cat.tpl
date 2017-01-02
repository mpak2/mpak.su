<!-- [settings:foto_lightbox] -->
<div style="margin-top:20px;">
	<ul>
		<? foreach(rb("cat") as $cat): ?>
			<li style="display:inline; margin-left:10px;">
				<h1 style="margin-top:10px;"><?=$cat['name']?></h1>
				<? foreach(rb("imgs", "cat_id", "id", $cat['id']) as $index): ?>
					<? if($index['img']): ?>
						<span style="padding:5px; display:inline-block;" class="gallery">
							<a href="/foto:img/<?=$index['id']?>/tn:index/fn:img/w:600/h:800/null/img.png">
								<img src="/foto:img/<?=$index['id']?>/tn:index/fn:img/w:100/h:150/null/img.png" title="<?=$index['name']?>">
							</a>
						</span>
					<? else: ?>
						<span style="padding:5px; display:inline-block;">
							<a href="/<?=$arg['modname']?>:file/<?=$index['id']?>/tn:index/fn:file/null/<?=$index['file']?>" title="<?=$index['name']?>">
								<img src="/<?=$arg['modname']?>:img/w:150/h:220/null/doc.png">
							</a>
						</span>
					<? endif; ?>
				<? endforeach; ?>
			</li>
		<? endforeach; ?>
	</ul>
</div>
