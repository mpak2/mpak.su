<!-- [settings:foto_lightbox] -->
<? echo mpager($conf['tpl']['pcount']); ?>

<div style="text-align:center; padding:20px 100px;"><h2><!-- [settings:portfolio_text] --></h2></div>
<table width=100% border=0 cellspacing=10px>
	<? foreach($conf['tpl']['list'] as $k=>$v): ?>
	<tr valign=middle>
		<td width=30% align=left>
			<? if(!empty($v['url'])): ?>
				<noindex><a target="_blank" href=<?=$v['url']?>>
			<? endif; ?>
				<?=$v['name']?>
			<? if(!empty($v['url'])): ?>
				</a></noindex>
			<? endif; ?>
		</td>
		<td width=30% align=center>
			<div id="gallery" style="padding:5px; border: 1px solid #aaa;">
				<a title="<?=$v['name']?>" alt="<?=$v['name']?>" href=/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:600/h:400/null/img.jpg>
					<img src="/<?=$arg['modpath']?>:img/<?=$v['id']?>/w:200/h:120/c:1/null/img.jpg" border=0>
				</a>
			</div>
		</td>
		<td align=center><?=$v['description']?></td>
	</tr>
	<? endforeach; ?>
</table>

<? echo mpager($conf['tpl']['pcount']); ?>