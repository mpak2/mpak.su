<!-- [settings:foto_lightbox] -->
<div>
		<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
			<div style="overflow:hidden;">
				<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:150px;">
					<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:3/w:600/h:500/null/img.jpg">
						<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:3/w:120/h:100/null/img.jpg">
					</a>
				</div>
				<div style="margin:10px; font-weight:bold;">
					<a href="/<?=$arg['modpath']?>/pid:<?=$v['id']?>"><?=$v['name']?></a>
				</div>
				<div style="margin:10px;"><?=$v['description']?></div>
			</div>
			<div style="text-align:right;">
				<? if($_GET['id']): ?>
					<a href="/<?=$arg['modpath']?>">В список</a>
					<div style="margin:10px;"><?=$v['url']?></div>
					<div style="margin:10px;"><?=$v['tel']?></div>
				<? else: ?>
					<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>">Комментариев [<?=(int)$conf['tpl']['comments'][$v['id']]?>]</a>
				<? endif; ?>
			</div>
		<? endforeach; ?>
</div>
<? if($_GET['id']): ?>
	<!-- [settings:comments] -->
<? else: ?>
	<? mpager($conf['tpl']['cnt']); ?>
<? endif; ?>