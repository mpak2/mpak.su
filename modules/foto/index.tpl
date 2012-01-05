<!-- [settings:foto_lightbox] -->

<div style="margin: 10px;">
	<? foreach($conf['tpl']['cat'] as $k=>$v): ?>
	<?=($tmp++ ? '&bull;' : '')?>
	<a<?=($_GET['id'] == $k ? " class='activ'" : '')?> href=/<?=$arg['modpath']?>/cat:<?=$k?>>
		<?=$v['name']?>
	</a>
	<? endforeach; ?>
</div>

<div id="gallery" style="overflow:hidden;">
	<? foreach($conf['tpl']['img'] as $k=>$v): ?>
	<div style="float:left; padding: 10px;">
		<a title="<?=$v['description']?>" alt="<?=$v['description']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
			<img src=/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:350/h:100/null/img.jpg>
			<div><?=$v['description']?></div>
		</a>
	</div>
	<? endforeach; ?>
	<div style="clear:both;"><?=$conf['tpl']['mpager']?></div>
</div>