<div>
	<? if($default = rb($arg['fn'], "id", get($_GET, 'id'))): ?>
		<?=aedit("/{$arg['modpath']}:admin/r:{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}?where[id]={$default['id']}")?>
		<a href="/<?=$arg['modpath']?>:<?=$arg['fn']?>"><?=(get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: $arg['modname'])?></a> &raquo;
		<? if(get($default, "{$arg['fn']}_id") && ($parent = rb($arg['fn'], "id", $default["{$arg['fn']}_id"]))): ?>
			<a href="<?=seo("/{$arg['modpath']}:{$arg['fn']}/{$parent['id']}")?>"><?=$parent['name']?></a>
		<? endif; ?>
		<h1><?=$default['name']?></h1>
		<? if($img = get($default, 'img')): ?>
			<img src="/<?=$arg['modpath']?>:img/<?=$default['id']?>/tn:<?=$arg['fn']?>/fn:img/w:500/h:500/null/img.png" style="float:right;">
		<? endif; ?>
		<p><?=get($default, 'description')?></p>
		<p><?=get($default, 'text')?></p>
		<? inc("modules/{$arg['modpath']}/default/{$arg['fn']}.tpl", array($arg['fn']=>$default)) ?>
	<? else: ?>
		<h1><?=(get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: $arg['modname'])?></h1>
		<? inc("modules/{$arg['modpath']}/default/{$arg['fn']}.tpl", array($arg['fn']=>array())) ?>
	<? endif; ?>
</div>
