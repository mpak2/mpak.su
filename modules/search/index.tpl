<!-- [settings:foto_lightbox] -->
<meta name="robots" content="noindex" />
<div style="margin:10px;">
	<? if($_SERVER['HTTP_REFERER']): ?>
		<span style="float:right;"><a href="<?=$_SERVER['HTTP_REFERER']?>">Вернуться</a></span>
	<? endif; ?>
</div>
<div style="text-align:center; margin-top:20px;">Страница <?=($_GET['p']+1)?> из <?=$conf['tpl']['pages']?> найденных</div>
<form style="text-align:center;" method="GET" action="/<?=$arg['modname']?>">
	<input type="hidden" name="search_block_num" value="<?=$conf['tpl']['search']['num']?>">
	<input type="text" name="search" style="width:50%;" value="<?=$conf['tpl']['search']['search']?>">
	<input type="submit" value="Искать">
</form>
<div style="text-align:center; margin-bottom:20px;">
	<!-- [settings:<?=$arg['modname']?>_title] -->
</div>
<span><?=$conf['tpl']['mpager']?></span>
<? if($conf['tpl']['result']): ?>
	<? foreach($conf['tpl']['result'] as $k=>$v): ?>
		<div style="margin: 15px 3px 3px 3px; overflow:hidden;">
			<? if($v['uid']): ?>
				<a href="/<?=$conf['modules']['users']['modname']?>/<?=$v['uid']['id']?>">
					<div style="float:right; text-align:center;">
						<img src="/<?=$conf['modules']['users']['name']?>:img/<?=$v['uid']['id']?>/tn:index/w:50/h:50/c:1/null/img.jpg">
						<div><?=$v['uid']['name']?></div>
					</div>
				</a>
			<? endif; ?>
			<? if($v['img']): ?>
				<div id="gallery" style="float:left; margin:0 10px; width:110px;">
					<a href="<?=$v['img']?>">
						<img src="<?=$v['logo']?>">
					</a>
				</div>
			<? endif; ?>
			<div style="margin-left:<?=($v['img'] ? 120 : 0)?>;">
<!--				<div style="float:right;">(<?=$v['title']?>)</div>-->
				<div style="overflow:hidden; white-space:nowrap;"><a href="<?=$v['link']?>" style="font-size:160%;"><?=mb_substr($v['name'], 0, 60)?></a></div>
				<div style="font-style:italic; margin-top:5px;"><?=$v['text']?></div>
				<div><a href="<?=$v['link']?>" style="color:green;"><?=$v['link']?></a></div>
			</div>
		</div>
	<? endforeach; ?>
<? else: ?>
	<div style="text-align:center; margin:100px;">По заданному запросу ничего не найдено</div>
<? endif; ?>

<div style="margin:10px;">
	<?=$conf['tpl']['mpager']?>
</div>