<div style="overflow:hidden;">
	<? foreach($conf['tpl']['glass'] as $k=>$v): ?>
		<div style="float: left; padding: 3px; margin: 3px; border: <?=($_GET['id'] == $v['id'] ? '1' : '0')?>px solid #999;">
			<? if ($conf['tpl']['count'][ $v['id'] ]): ?><a href="/<?=$arg['modpath']?>/<?=$v['id']?>"><? endif; ?>
			<?=$v['name']?>
			<? if ($conf['tpl']['count'][ $v['id'] ]): ?></a><? endif; ?>
		</div>
	<? endforeach; ?>
</div>

<? foreach($conf['tpl']['desc'] as $k=>$v): ?>
	<? if ($_GET['did'] == $v['id']) $conf['settings']['title'] = $v['name']. ' : '. $conf['settings']['title']; ?>
	<div style="padding: 5px;"><a href=/<?=$arg['modpath']?>/<?=$_GET['id']?>/did:<?=$v['id']?>><?=$v['name']?></a></div>
	<div style="padding: 5px;"><?=$v['description']?></div>
<? endforeach; ?>

<? if($_GET['id']): ?>
	<?=$conf['settings']['comments']?>
<? endif; ?>
<? echo mpager($conf['tpl']['pcount']); ?>