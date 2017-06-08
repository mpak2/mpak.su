<? # Верхнее

if(!$block = rb("blocks-index", "id", $arg['blocknum'])){ mpre("Ошибка получения информации блока");
}elseif(!$param = ['menu'=>($block['param'] ?: 2)]){ mpre("Ошибочные параметры меню");
}elseif(!inc("themes/{$conf['settings']['theme']}/top.tpl", array('arg'=>$arg, 'param'=>$param, "menu"=>rb("index", "region_id", "id", (get($param, 'menu') ?: 2))))){ mpre("Ошибка подключения шаблона");
}else{ echo aedit("/{$arg['modpath']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_index?&where[region_id]=". get($param, 'menu')); ?>
	<ul class="menu_<?=$arg['blocknum']?>">
		<? foreach(rb("index", "region_id", "id", $param['menu']) as $index): ?>
			<li>
				<? if($index['href']): ?><a href="<?=$index['href']?>"><? endif; ?>
					<?=$index['name']?>
				<? if($index['href']): ?></a><? endif; ?>
			</li>
		<? endforeach; ?>
	</ul>
<? } ?>
