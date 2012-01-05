<div>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden; float:left;">
				<div id="gallery" style="text-align:center; margin:3px 10px;">
					<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:edit/<?=$v['id']?>">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:200/h:80/null/img.jpg">
					</a>
				</div>
			</div>
		<? endforeach; ?>
		<div style="clear:both;"></div>
		<div>
			<? mpager($conf['tpl']['cnt']); ?>
		</div>
</div>