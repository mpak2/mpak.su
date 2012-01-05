<ul>
	<? $tree = function($tr, $tree) use($conf, $arg) { if(empty($tr[''])) return; ?>
		<li>
			<div><a href="/<?=$arg['modpath']?>/vetki_id:<?=$tr['']['id']?>"><?=$tr['']['name']?></a></div>
			<? if(count($tr) > 1): ?>
				<ul>
					<? foreach($tr as $k=>$v): ?>
						<?=$tree($v, $tree)?>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</li>
	<? }; $tree($conf['tpl']['tree'], $tree); ?>
</ul>
