<h1><?=$conf['modules'][ $arg['modpath'] ]['name']?></h1>
<? if($conf['tpl']['count']): ?>
	<? for($i = 0; $i<$conf['tpl']['count']/10; $i++): ?>
		<a href=/news/pid:<?=$i?> style="border: 1px solid rgb(0, 0, 0); margin: 1px; padding: 2px;"><?=($i+1)?></a>
	<? endfor; ?>
<? endif; ?>

<!-- [settings:foto_lightbox] -->
<? foreach($conf['tpl']['news'] as $k=>$news): ?>
	<div style="overflow:hidden;">
		<div class="news_name" style="margin-top: 20px; padding: 5px;">
			<?=aedit("/?m[{$arg['modpath']}]=admin&r={$conf['db']['prefix']}{$arg['modpath']}_post&where[id]={$news['id']}")?>
			<b>
				<?=date('Y.m.d H.i.s', $news['time'])?>
				<? if(!$_GET['id']): ?><h6><a href="/news/<?=$news['id']?>"><? endif; ?><?=$news['name']?><? if(!$_GET['id']): ?></a></h6><? endif; ?>
			</b>
		</div>
		<div class="news_img" style="padding: 5px;">
			<?// if(!empty($news['img']) && !$_GET['id']):?>
				<div id="gallery" style="float:right;">
					<a title="<?=$news['name']?>" alt="<?=$news['name']?>" href="/<?=$arg['modname']?>:img/<?=$news['id']?>/tn:post/fn:img/w:600/h:500/null/img.jpg" style="margin-left:10px;">
						<img src="/<?=$arg['modname']?>:img/<?=$news['id']?>/tn:post/fn:img/w:150/h:150/null/img.jpg" />
					</a>
				</div>
			<?// endif; ?>
			<?=((int)$_GET['id'] ? $news['text'] : strip_tags($news['txt']))?>
			<? if(!$_GET['id']): ?>
				<a href="/news/<?=$news['id']?><?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="news_more">Подробно</a>
			<? endif; ?>
		</div>
		<div style="padding: 5px;">
			Категория: <a href="/<?=$arg['modname']?>/kat_id:<?=$news['kat_id']?>"><?=$news['kname']?></a>; Просмотров: <?=$news['count']?>
		</div>
	</div>
		<div>
			<? if($_GET['id']): ?>
				<a href="/news<?=($_GET['p'] ? "/p:{$_GET['p']}" : '')?>" class="new_smore">К списку новостей</a>
				<div><!-- [settings:comments] --></div>
			<? endif; ?>
		</div>
<? endforeach; ?>

<? if(!$_GET['id']): ?>
	<div align=center><?=$conf['tpl']['mpager']?></div>
<? endif; ?>