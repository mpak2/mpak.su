<? if($n = $tpl['index'][ $_GET['id'] ]): ?>
		<div style="overflow:hidden;">
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_index&where[id]={$n['id']}")?>
				<?=date('d.m.Y H.i', $n['time'])?>
				<h6><?=$n['name']?></h6>
			</div>
			<div class="news_img" style="padding: 5px;">
				<div id="gallery" style="float:right;">
					<a title="<?=$n['name']?>" alt="<?=$n['name']?>" href="/news:img/<?=$n['id']?>/tn:index/fn:img/w:600/h:500/null/img.png" style="margin-left:10px;">
						<img src="/news:img/<?=$n['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
					</a>
				</div>
				<div><?=$n['text']?></div>
			</div>
			<div style="padding: 5px;">
				<span style="float:right;"><a href="/news<?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="new_smore">К списку новостей</a></span>
				Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$n['cat_id']?>"><?=$tpl['cat'][ $n['cat_id'] ]['name']?></a>; Просмотров: <?=$n['count']?>
			</div>
		</div>
		<div><!-- [settings:comments] --></div>
<? else: ?>
	<? foreach($conf['tpl']['index'] as $n): ?>
		<div style="overflow:hidden;">
			<div class="news_name" style="margin-top: 20px; padding: 5px;">
				<?=date('Y.m.d H.i.s', $n['time'])?>
				<h6><a href="/news/<?=$n['id']?>"><?=$n['name']?></a></h6>
			</div>
			<div class="news_img" style="padding: 5px;">
				<div id="gallery" style="float:right;">
					<a title="<?=$n['name']?>" alt="<?=$n['name']?>" href="/<?=$arg['modname']?>:img/<?=$n['id']?>/tn:index/fn:img/w:600/h:500/null/img.jpg" style="margin-left:10px;">
						<img src="/news:img/<?=$n['id']?>/tn:index/fn:img/w:150/h:120/null/img.png" />
					</a>
				</div>
				<?=mb_substr(strip_tags($n['text']), 0, 500)?>
				<div><a href="/news/<?=$n['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="news_more">Подробнее</a></div>
			</div>
			<div style="padding: 5px;">
				Категория: <a href="/<?=$arg['modname']?>/cat_id:<?=$n['cat_id']?>"><?=$tpl['cat'][ $n['cat_id'] ]['name']?></a>; Просмотров: <?=$n['count']?>
			</div>
		</div>
	<? endforeach; ?>
<? endif; ?>