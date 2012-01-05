<? echo mpager($conf['tpl']['pcount']); ?>

<div style="padding:10px;">
	<ul>
	<? foreach($conf['tpl']['res'] as $k=>$v): ?>
		<? if (++$c[$v['kid']] <= 3 || $v['kid'] == $_GET['cid']):?>
			<li type="circle"><a href=/pages/pid:<?=$v['id']?> title="<?=$v['date']?>"><?=$v['name']?></a>
				<div>
					<?=substr($v['text'], 0, 150)?>...
				</div>
			</li>
		<? endif; ?>
	<? endforeach; ?>
	</ul>
	<? foreach($conf['tpl']['all'] as $c=>$cat): ?>
		<li><a href="/pages:list/cid:<?=$cat['id']?>"><?=$cat['name']?></a> [<?=$cat['count']?>]<ul>
		<? foreach($conf['tpl']['res'] as $p=>$page): ?>
			<li type=\"circle\"><a href="/pages/pid:<?=$page['id']?>" title="<?=$page['date']?>"><?=$page['name']?></a></li>
		<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</div>

<? echo mpager($conf['tpl']['pcount']); ?>
