<!-- [settings:foto_lightbox] -->
<? if($n = $tpl['index'][ $_GET['id'] ]): ?>
		<div>
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]={$n['id']}")?>
				<?=date('d.m.Y H:i', $n['time'])?>
				<h1><?=$n['name']?></h1>
			</div>
			<? if($n['img']): ?>
				<div class="news_img" style="padding: 5px;">
					<div id="gallery" style="float:right;">
						<a title="<?=$n['name']?>" alt="<?=$n['name']?>" href="/news:img/<?=$n['id']?>/tn:index/fn:img/w:600/h:500/null/img.png" style="margin-left:10px;">
							<img src="/news:img/<?=$n['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
						</a>
					</div>
				</div>
			<? endif; ?>
			<div><?=$n['text']?></div>
			<div style="padding: 5px;">
				<span style="float:right;"><a href="/news<?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="new_smore">К списку новостей</a></span>
				<? if($cat = $tpl['cat'][ $n['cat_id'] ]): ?>
					Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$cat['id']?>"><?=$cat['name']?></a>
				<? endif; ?>
				<? if($n['count']): ?>
					Просмотров: <?=$n['count']?>
				<? endif; ?>
			</div>
		</div>
		<div><!-- [settings:comments] --></div>
<? else: ?>
	<? foreach($conf['tpl']['index'] as $n): ?>
		<div style="overflow:hidden;">
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=date('d.m.Y H:i', $n['time'])?>
				<h6><a href="/news/<?=$n['id']?>"><?=$n['name']?></a></h6>
			</div>
			<? if($n['img']): ?>
				<div class="news_img" style="padding: 5px;">
					<div id="gallery" style="float:right;">
						<a title="<?=$n['name']?>" alt="<?=$n['name']?>" href="/<?=$arg['modname']?>:img/<?=$n['id']?>/tn:index/fn:img/w:600/h:500/null/img.jpg" style="margin-left:10px;">
							<img src="/news:img/<?=$n['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
						</a>
					</div>
				</div>
			<? endif; ?>
					<?=mb_substr(strip_tags($n['text']), 0, 500)?>
					<div><a href="/news/<?=$n['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="news_more">Подробнее</a></div>
			<div style="padding: 5px;">
				<? if($cat = $tpl['cat'][ $n['cat_id'] ]): ?>
					Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$cat['id']?>"><?=$cat['name']?></a>
				<? endif; ?>
				<? if($n['count']): ?>
					Просмотров: <?=$n['count']?>
				<? endif; ?>
			</div>
		</div>
	<? endforeach; ?>
<? endif; ?>