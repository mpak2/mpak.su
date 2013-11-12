<!-- [settings:foto_lightbox] -->

<div style="margin: 10px;">
	<? foreach($tpl['cat'] as $k=>$v): ?>
	<?=($tmp++ ? '&bull;' : '')?>
	<a<?=($_GET['id'] == $k ? " class='activ'" : '')?> href=/<?=$arg['modpath']?>/cat:<?=$k?>>
		<?=$v['name']?>
	</a>
	<? endforeach; ?>
</div>
<? if($cat = $tpl['cat'][ $_GET['cat'] ]): ?>
	<h1><?=$cat['name']?></h1>
<? endif; ?>
<div id="gallery" style="overflow:hidden;">
	<? foreach($tpl['img'] as $k=>$v): ?>
		<div style="float:left; padding: 10px; min-height:115px;">
			<a title="<?=$v['description']?>" alt="<?=$v['description']?>" href="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:600/h:500/null/img.jpg">
				<img src=/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:350/h:90/null/img.jpg>
				<div><?=$v['description']?></div>
			</a>
		</div>
	<? endforeach; ?>
	<div style="clear:both;"><?=$conf['tpl']['mpager']?></div>
</div>