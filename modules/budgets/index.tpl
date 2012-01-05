<!-- [settings:foto_lightbox] -->
<div>
	<? foreach($conf['tpl']['budgets'] as $k=>$v): ?>
		<div style="overflow:hidden;">
			<div style="padding:10px;">
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">
					<?=$v['name']?>
				</a>
			</div>
			<div id="gallery" style="margin:10px; float:left;">
				<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:list/w:600/h:500/null/img.jpg">
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/tn:list/w:200/h:150/null/img.jpg">
				</a>
			</div>
			<div><?=$v['description']?></div>
		</div>
		<div id="gallery" style="overflow:hidden;">
			<? foreach($conf['tpl']['img'][$v['id']] as $n=>$z): ?>
			<div style="float:left; margin:5px;">
				<a title="<?=$z['description']?>" alt="<?=$z['description']?>" href="/<?=$arg['modpath']?>:img/<?=$z['id']?>/tn:img/w:600/h:500/null/img.jpg">
					<img src="/<?=$arg['modpath']?>:img/<?=$z['id']?>/tn:img/w:80/h:80/c:1/null/img.jpg">
				</a>
			</div>
			<? endforeach; ?>
		</div>
		<div style="text-align:right; border-bottom:1px dashed gray; padding:5px;">
			<div style="float:left;"><a href="<?=$v['link']?>"><!-- [settings:budgets_link_string] --></a></div>
			<? if($_GET['id']): ?>
				<a href="/<?=$arg['modpath']?>">К списку</a>
			<? else: ?>
				<a href="/<?=$arg['modpath']?>/<?=$v['id']?>">Подробнее</a>
			<? endif; ?>
		</div>
	<? endforeach; ?>
	<? if($_GET['id']): ?><!-- [settings:comments] --><? endif; ?>
</div>