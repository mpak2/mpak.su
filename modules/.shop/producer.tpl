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
			<div style="text-align:left;">
				<? if($_GET['id']): ?>
					<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>">В список</a>
					<div style="margin:10px;"><?=$v['url']?></div>
					<div style="margin:10px;"><?=$v['email']?></div>
					<div style="margin:10px;"><?=$v['tel']?></div>
					<div style="margin:10px;"><?=$v['rtel']?></div>
					<div style="margin:10px;"><?=$conf['tpl']['sity'][ $v['sity_id'] ]?></div>
					<div style="margin:10px;"><?=$v['addr']?></div>
					<div><?=$v['text']?></div>
				<? else: ?>
					<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>/<?=$v['id']?>">Подробная информация [<?=(int)$conf['tpl']['comments'][$v['id']]?>]</a>
				<? endif; ?>
			</div>
		<? endforeach; ?>
</div>
<? if($_GET['id']): ?>
	<!-- [settings:comments] -->
<? else: ?>
	<? mpager($conf['tpl']['cnt']); ?>
<? endif; ?>