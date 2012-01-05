<? if($r = $conf['tpl']['index'][ $_GET['id'] ]): ?>
	<!-- [settings:foto_lightbox] -->
	<h1><?=$r['name']?></h1>
	<div id="gallery" style="float:right;">
		<a href="/<?=$arg['modpath']?>:img/<?=$r['id']?>/tn:index/w:600/h:500/null/img.jpg">
			<img src="/<?=$arg['modpath']?>:img/<?=$r['id']?>/tn:index/w:300/h:300/null/img.jpg">
		</a>
	</div>
	<div><?=$r['description']?></div>
	<div><?=$r['text']?></div>
	<div style="margin-top:10px;">
		<? foreach($conf['tpl']['href'] as $k=>$v): ?>
			<div>
				<div><a target=blank href="<?=$v['href']?>"><?=$v['name']?></div>
				<div><?=$v['description']?></div>
			</div>
		<? endforeach; ?>
	</div>
<? else: ?>
	<? foreach($conf['tpl']['index'] as $k=>$v): ?>
		<div style="margin:10px;"><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><?=$v['name']?></a></div>
		<div><?=$v['description']?></div>
	<? endforeach; ?>
<? endif; ?>