<h1><?=$conf['tpl']['cat']['name']?></h1>

<div>
	<? foreach($conf['tpl']['index'] as $n=>$cat): ?>
		<h3><?=$conf['tpl']['cat'][ $n ]['name']?></h3>
		<ul style="padding-left:20px;">
			<? foreach($cat as $k=>$v): ?>
				<li style="border:0;"><a href="/<?=$arg['modname']?>/<?=$v['id']?>/<?=mpue($v['name'])?>"><?=$v['name']?></a></li>
			<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</div>

<!--<? echo mpager($conf['tpl']['pcount']); ?>
<div style="padding:10px;">
	<ul>
	<? foreach($conf['tpl']['res'] as $k=>$v): ?>
		<? if (++$c[$v['kid']] <= 3 || $v['kid'] == $_GET['cid']):?>
			<li type="circle"><a href=/pages/<?=$v['id']?> title="<?=$v['date']?>"><?=$v['name']?></a>
		<? endif; ?>
	<? endforeach; ?>
	</ul>
	<? foreach($conf['tpl']['all'] as $c=>$cat): ?>
		<li><a href="/pages:list/cid:<?=$cat['id']?>"><?=$cat['name']?></a> [<?=$cat['count']?>]<ul>
		<? foreach($conf['tpl']['res'] as $p=>$page): ?>
			<li type=\"circle\"><a href="/pages/<?=$page['id']?>" title="<?=$page['date']?>"><?=$page['name']?></a>
		<? endforeach; ?>
		</ul>
	<? endforeach; ?>
</div>
<?=$conf['tpl']['mpager']?>-->