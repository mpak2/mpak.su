<? # Верхнее

echo aedit("/{$arg['modpath']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_index?&where[region_id]=2");

if(!inc("/themes/{$conf['settings']['theme']}/top.tpl", array('arg'=>$arg, "menu"=>rb("index", "region_id", "id", 2)))): ?>
	<ul class="menu_<?=$arg['blocknum']?>">
		<? foreach(rb("index", "region_id", "id", 2) as $index): ?>
			<li>
				<? if($index['href']): ?><a href="<?=$index['href']?>"><? endif; ?>
					<?=$index['name']?>
				<? if($index['href']): ?></a><? endif; ?>
			</li>
		<? endforeach; ?>
	</ul>
<? endif; ?>
