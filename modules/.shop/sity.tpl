<!-- [settings:foto_lightbox] -->
<div>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden; padding:10px 100px;">
					<div id="gallery" style="text-align:center; float:right; margin:3px 10px;">
						<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:4/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:4/w:120/h:100/c:1/null/img.jpg">
						</a>
					</div>
				<div style="margin:10px; font-weight:bold;">
					<a href="/<?=$arg['modpath']?>/sity_id:<?=$v['id']?>">
						<?=$v['name']?> [<?=(int)$conf['tpl']['count'][ $v['id'] ]?>]
					</a>
				</div>
				<div><?=$v['description']?></div>
				<div>
					<? if($_GET['id']): ?>
						<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>">В список</a>
					<? endif; ?>
				</div>
			</div>
		<? endforeach; ?>
		<div><? mpager($conf['tpl']['cnt']); ?></div>
</div>