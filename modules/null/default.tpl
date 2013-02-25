<? if($v = $conf['tpl'][ $arg['fn'] ][ $_GET['id'] ]): ?>
	<div style="overflow:hidden;">
		<!-- [settings:foto_lightbox] -->
		<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}&where[id]={$v['id']}")?>
		<span style="float:right;"><a href="/<?=$arg['modname']?>:<?=$arg['fe']?>">Вернуться назад</a></span>
		<div style="float:right;">
			<img src="/<?=$arg['modname']?>:img/<?=$v['id']?>/tn:<?=$arg['fn']?>/w:220/h:200/null/img.jpg">
		</div>
		<h1><?=$v['name']?></h1>
		<div style="margin-top:15px;"><?=$v['description']?></div>
		<div><?=$v['text']?></div>
		<div id="gallery" class="imgs">
			<? foreach($tpl['imgs'] as $i): ?>
				<div style="float:left; width:100px; height:100px; padding:3px;">
					<a href="/<?=$arg['modname']?>:img/<?=$i['id']?>/tn:<?=$arg['fn']?>_imgs/fn:img/w:600/h:500/null/img.jpg" title="<?=$i['name']?>">
						<img src="/<?=$arg['modname']?>:img/<?=$i['id']?>/tn:<?=$arg['fn']?>_imgs/fn:img/w:100/h:100/null/img.jpg" title="<?=$i['name']?>" alt="<?=$i['name']?>">
					</a>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<?=$conf['settings']['comments']?>
<? else: ?>
	<? foreach($conf['tpl'][ $arg['fn'] ] as $k=>$v): ?>
		<div>
			<h2><a href="/<?=$arg['modname']?><?=($arg['fn'] != 'index' ? ":{$arg['fe']}" : "")?>/<?=$v['id']?>/<?=mpue($v['name'])?>"><?=$v['name']?></a></h2>
			<div style="margin-top:15px;"><?=$v['description']?></div>
		</div>
	<? endforeach; ?>
<? endif; ?>