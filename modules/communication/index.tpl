<!-- [settings:foto_lightbox] -->
<? if($_GET['id']): ?>
	<div>
			<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
				<div style="overflow:hidden;">
					<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:130px;">
						<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
						</a>
					</div>
					<div style="margin:10px; font-weight:bold;"><?=$v['name']?></div>
					<div style="margin:10px;"><?=$v['description']?></div>
				</div>
				<div style="text-align:right;">
					<a href="/<?=$arg['modpath']?><?=($arg['fn'] == 'index' ? '' : ":{$arg['fn']}")?>/p:<?=(int)$_GET['p']?>">Вернуться</a>
				</div>
			<? endforeach; ?>
	</div>
	<div><!-- [settings:comments] --></div>
<? else: ?>
	<div>
			<? foreach($conf['tpl'][$arg['fn']] as $k=>$v): ?>
				<div style="overflow:hidden;">
					<div id="gallery" style="text-align:center; float:left; margin:3px 10px; width:130px;">
						<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:600/h:500/null/img.jpg">
							<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:120/h:100/null/img.jpg">
						</a>
					</div>
					<div><a href="/<?=$arg['modpath']?>/region_id:<?=$v['region_id']?>"><?=$conf['tpl']['region'][$v['region_id']]['name']?></a></div>
					<div style="margin:10px; font-weight:bold;"><?=$v['name']?></div>
					<div style="margin:10px;"><?=substr($v['description'], 0, 120)?></div>
				</div>
				<div style="text-align:right;">
					<a href="/<?=$arg['modpath']?><?=($arg['fn'] == 'index' ? '' : ":{$arg['fn']}")?>/p:<?=(int)$_GET['p']?>/<?=$v['id']?>">Комментарии</a>
				</div>
			<? endforeach; ?>
	</div>
	<div><? mpager($conf['tpl']['cnt']); ?></div>
<? endif; ?>