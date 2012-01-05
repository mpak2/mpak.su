<!-- [settings:foto_lightbox] -->
<div>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div id="gallery" style="text-align:center; float:left; margin:3px 10px;">
					<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:600/h:500/null/img.jpg">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:index/w:120/h:100/null/img.jpg">
					</a>
				</div>
				<div style="margin:10px; font-weight:bold;"><?=$v['name']?></div>
				<div style="margin:10px;"><?=$v['description']?></div>
			</div>
			<div style="text-align:right;">
				<? if($_GET['id']): ?>
					<a href="/<?=$arg['modpath']?>">В список</a>
				<? else: ?>
					<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">Комментариев [<?=(int)$conf['tpl']['comments'][$v['id']]?>]</a>
				<? endif; ?>
			</div>
		<? endforeach; ?>
</div>
<? if($_GET['id']): ?>
	<!-- [settings:comments] -->
<? else: ?>
	<?=$conf['tpl']['mpager']?>
<? endif; ?>