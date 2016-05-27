<!-- [settings:foto_lightbox] -->
<? if($index = rb("index", "id", get($_GET, 'id'))): ?>
		<div>
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]={$index['id']}")?>
				<?=date('d.m.Y H:i', $index['time'])?>
				<h1><?=$index['name']?></h1>
			</div>
			<? if($index['img']): ?>
				<div class="news_img" style="padding: 5px;">
					<div id="gallery" style="float:right;">
						<a title="<?=$index['name']?>" alt="<?=$index['name']?>" href="/news:img/<?=$index['id']?>/tn:index/fn:img/w:600/h:500/null/img.png" style="margin-left:10px;">
							<img src="/news:img/<?=$index['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
						</a>
					</div>
				</div>
			<? endif; ?>
			<div><?=$index['text']?></div>
			<div style="padding: 5px;">
				<span style="float:right;"><a href="/news<?=(get($_GET, 'p') ? "/p:{$_GET['p']}" : '')?>" class="new_smore">К списку новостей</a></span>
				<? if($cat = $tpl['cat'][ $index['cat_id'] ]): ?>
					Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$cat['id']?>"><?=$cat['name']?></a>
				<? endif; ?>
				<? if($index['count']): ?>
					Просмотров: <?=$index['count']?>
				<? endif; ?>
			</div>
		</div>
		<div><!-- [settings:comments] --></div>
<? else: ?>
	<? foreach(rb("index", 10) as $index): ?>
		<div style="overflow:hidden;">
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=date('d.m.Y H:i', $index['time'])?>
				<h2><a href="/news/<?=$index['id']?>"><?=$index['name']?></a></h2>
			</div>
			<? if($index['img']): ?>
				<div class="news_img" style="padding: 5px;">
					<div id="gallery" style="float:right;">
						<a title="<?=$index['name']?>" alt="<?=$index['name']?>" href="/<?=$arg['modname']?>:img/<?=$index['id']?>/tn:index/fn:img/w:600/h:500/null/img.jpg" style="margin-left:10px;">
							<img src="/news:img/<?=$index['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
						</a>
					</div>
				</div>
			<? endif; ?>
					<?=mb_substr(strip_tags($index['text']), 0, 500)?>
					<div><a href="/news/<?=$index['id']?><?=(get($_GET, 'p') ? "/p:{$_GET['p']}" : '')?>" class="news_more">Подробнее</a></div>
			<div style="padding: 5px;">
				<? if($cat = rb('cat', "id", $index['cat_id'])): ?>
					Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$cat['id']?>"><?=$cat['name']?></a>
				<? endif; ?>
				<? if($index['count']): ?>
					Просмотров: <?=$index['count']?>
				<? endif; ?>
			</div>
		</div>
	<? endforeach; ?>
<? endif; ?>
